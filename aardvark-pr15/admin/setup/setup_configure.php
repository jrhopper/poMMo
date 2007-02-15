<?php
/**
 * Copyright (C) 2005, 2006, 2007  Brice Burgess <bhb@iceburg.net>
 * 
 * This file is part of poMMo (http://www.pommo.org)
 * 
 * poMMo is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published 
 * by the Free Software Foundation; either version 2, or any later version.
 * 
 * poMMo is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty
 * of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See
 * the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with program; see the file docs/LICENSE. If not, write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA.
 */

/**********************************
	INITIALIZATION METHODS
 *********************************/
require ('../../bootstrap.php');
$pommo->init();
$logger = & $pommo->_logger;
$dbo = & $pommo->_dbo;

/**********************************
	SETUP TEMPLATE, PAGE
 *********************************/
Pommo::requireOnce($pommo->_baseDir.'inc/classes/template.php');
$smarty = new PommoTemplate();
$smarty->prepareForForm();

// ADD CUSTOM VALIDATOR FOR CHARSET
function check_charset($value, $empty, & $params, & $formvars) {
	$validCharsets = array (
		'UTF-8',
		'ISO-8859-1',
		'ISO-8859-2',
		'ISO-8859-7',
		'ISO-8859-15',
		'cp1251',
		'KOI8-R',
		'GB2312',
		'EUC-JP',
		'ISO-2022-JP'
	);

	return in_array($value, $validCharsets);
}

if (!SmartyValidate :: is_registered_form() || empty ($_POST)) {
	// ___ USER HAS NOT SENT FORM ___

	SmartyValidate :: connect($smarty, true);

	// register custom criteria
	SmartyValidate::register_criteria('isCharSet','check_charset');

	SmartyValidate :: register_validator('admin_username', 'admin_username', 'notEmpty', false, false, 'trim');
	SmartyValidate :: register_validator('site_name', 'site_name', 'notEmpty', false, false, 'trim');
	SmartyValidate :: register_validator('list_name', 'list_name', 'notEmpty', false, false, 'trim');
	SmartyValidate :: register_validator('list_fromname', 'list_fromname', 'notEmpty', false, false, 'trim');

	SmartyValidate :: register_validator('site_url', 'site_url', 'isURL', false, false, 'trim');
	SmartyValidate :: register_validator('site_success', 'site_success', 'isURL', TRUE);
	SmartyValidate :: register_validator('site_confirm', 'site_confirm', 'isURL', TRUE);

	SmartyValidate :: register_validator('admin_password2', 'admin_password:admin_password2', 'isEqual', TRUE);

	SmartyValidate :: register_validator('admin_email', 'admin_email', 'isEmail');
	SmartyValidate :: register_validator('list_fromemail', 'list_fromemail', 'isEmail');
	SmartyValidate :: register_validator('list_frombounce', 'list_frombounce', 'isEmail');

	SmartyValidate :: register_validator('list_charset', 'list_charset', 'isCharSet', false, false, 'trim');


	$formError = array ();
	$formError['admin_username'] = $formError['sitename'] = $formError['list_name'] = $formError['list_fromname'] = Pommo::_T('Cannot be empty.');

	$formError['admin_email'] = $formError['list_fromemail'] = $formError['list_frombounce'] = Pommo::_T('Invalid email address');

	$formError['admin_password2'] = Pommo::_T('Passwords must match.');

	$formError['site_url'] = $formError['site_success'] = $formError['site_confirm'] = Pommo::_T('Must be a valid URL');

	$formError['list_charset'] = Pommo::_T('Invalid Character Set');

	$smarty->assign('formError', $formError);

	// populate _POST with info from database (fills in form values...)
	$dbVals = PommoAPI::configGet(array (
		'admin_username',
		'site_success',
		'site_confirm',
		'list_fromname',
		'list_fromemail',
		'list_frombounce',
		'list_exchanger',
		'list_confirm',
		'list_charset',
		'public_history'
	));

	$dbVals['demo_mode'] = (!empty ($pommo->_config['demo_mode']) && ($pommo->_config['demo_mode'] == "on")) ? 'on' : 'off';

	$dbVals['site_url'] = $pommo->_config['site_url'];
	$dbVals['site_name'] = $pommo->_config['site_name'];
	$dbVals['admin_email'] = $pommo->_config['admin_email'];
	$dbVals['list_name'] = $pommo->_config['list_name'];

	$smarty->assign($dbVals);
} else {
	// ___ USER HAS SENT FORM ___
	SmartyValidate :: connect($smarty);

	if (SmartyValidate :: is_valid($_POST)) {
		// __ FORM IS VALID

		// convert password to MD5 if given...
		if (!empty ($_POST['admin_password']))
			$_POST['admin_password'] = md5($_POST['admin_password']);

		$oldDemo = $pommo->_config['demo_mode'];

		PommoAPI::configUpdate($_POST);
		
		unset($_POST['admin_password'],$_POST['admin_password2']);

		$pommo->reloadConfig();

		$logger->addMsg(Pommo::_T('Configuration Updated.'));

		// refresh page to reflect demonstration mode changes
		if ($oldDemo != $pommo->_config['demo_mode'])
			Pommo::redirect('setup_configure.php');

	} else {
		// __ FORM NOT VALID
		$logger->addMsg(Pommo::_T('Please review and correct errors with your submission.'));
	}
}

$smarty->assign($_POST);
$smarty->display('admin/setup/setup_configure.tpl');
Pommo::kill();
?>