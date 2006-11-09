<?php

/** [BEGIN HEADER] **
 * COPYRIGHT: (c) 2005 Brice Burgess / All Rights Reserved    
 * LICENSE: http://www.gnu.org/copyleft.html GNU/GPL 
 * AUTHOR: Brice Burgess <bhb@iceburg.net>
 * SOURCE: http://pommo.sourceforge.net/
 *
 *  :: RESTRICTIONS ::
 *  1. This header must accompany all portions of code contained within.
 *  2. You must notify the above author of modifications to contents within.
 * 
 ** [END HEADER]**/

/**********************************
	INITIALIZATION METHODS
 *********************************/


require ('../bootstrap.php');
require_once ($pommo->_baseDir . '/inc/db_subscribers.php');

$pommo = & fireup();
$logger = & $pommo->_logger;
$dbo = & $pommo->_dbo;

/**********************************
	SETUP TEMPLATE, PAGE
 *********************************/
Pommo::requireOnce($pommo->_baseDir.'inc/classes/template.php');
$smarty = new PommoTemplate();
$smarty->assign('title', $pommo->_config['site_name'] . ' - ' . Pommo::_T('subscriber logon'));

$smarty->prepareForForm();

if (!SmartyValidate :: is_registered_form() || empty($_POST)) {
	// ___ USER HAS NOT SENT FORM ___
	SmartyValidate :: connect($smarty, true);
	SmartyValidate :: register_validator('email', 'Email', 'isEmail', false, false, 'trim');

	$formError = array ();
	$formError['email'] = Pommo::_T('Invalid email address');
	$smarty->assign('formError', $formError);
	
	// Assign email to form if pre-provided
	if (isset($_REQUEST['Email']))
		$smarty->assign('Email',$_REQUEST['Email']);
	elseif (isset($_REQUEST['email']))
		$smarty->assign('Email',$_REQUEST['email']);
		
} else {
	// ___ USER HAS SENT FORM ___
	SmartyValidate :: connect($smarty);
	if (SmartyValidate :: is_valid($_POST)) {
		// __ FORM IS VALID __
		
		if (isDupeEmail($dbo, $_POST['Email'], 'pending')) {
			// __EMAIL IN PENDING TABLE, REDIRECT
			$input = urlencode(serialize(array('email' => $_POST['Email'])));
			SmartyValidate :: disconnect();
			Pommo::redirect('user_pending.php?input='.$input);
		}
		elseif (isDupeEmail($dbo, $_POST['Email'], 'subscribers')) {
			// __ EMAIL IN SUBSCRIBERS TABLE, REDIRECT
			$input = urlencode(serialize(array('bm_email' => $_POST['Email'])));
			SmartyValidate :: disconnect();
			Pommo::redirect('user_update.php?input='.$input);
		} else {
			// __ REPORT STATUS
			$logger->addMsg(Pommo::_T('That email address was not found in our system. Please try again.'));
		}
	}
	$smarty->assign($_POST);
}
$smarty->display('user/login.tpl');
Pommo::kill();
?>