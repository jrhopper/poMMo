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


require ('../../bootstrap.php');
require_once ($pommo->_baseDir . 'inc/db_mailing.php');
require_once ($pommo->_baseDir . 'inc/lib.txt.php');
require_once ($pommo->_baseDir . 'inc/db_fields.php');

$pommo = & fireup('secure','keep');
$logger = & $pommo->_logger;
$dbo = & $pommo->_dbo;

/**********************************
	SETUP TEMPLATE, PAGE
 *********************************/
Pommo::requireOnce($pommo->_baseDir.'inc/classes/template.php');
$smarty = new PommoTemplate();
$smarty->prepareForForm();

// check to see if a mailing is taking place (queue not empty)
if (!mailingQueueEmpty($dbo)) {
	Pommo::kill(sprintf(Pommo::_T('A mailing is already taking place. Please allow it to finish before creating another. Return to the %s Mailing Page %s'), '<a href="admin_mailings.php">', '</a>'));
}
	
// check if altBody should be imported from HTML
if (isset($_POST['altGen'])) {
	require_once ($pommo->_baseDir.'inc/lib.html2txt.php');
	$h2t = & new html2text($_POST['body']);
	$_POST['altbody'] = $h2t->get_text();
}

// fetch subscriber fields for use with personaliztion selector
// Get array of fields. Key is ID, value is an array of the demo's info
$fields = dbGetFields($dbo);
if (!empty($fields))
	$smarty->assign('fields', $fields);

// Get MailingData from SESSION.
$mailingData = $pommo->get('mailingData');
if (!$mailingData) {
	$mailingData = array ();
}
@$smarty->assign('ishtml', $mailingData['ishtml']);

// remove normal forms CSS/JAVA if XInha form is to be printed
if ($mailingData['ishtml'] == 'html') {
	$smarty->assign('isForm',FALSE);
}


if (empty ($_POST)) {
	// ___ USER HAS NOT SENT FORM ___
	
	$formError = array ();
	$formError['fromname'] = $formError['body'] = Pommo::_T('Cannot be empty.');
	$smarty->assign('formError', $formError);
	
	// load mailing data from session
	@$_POST['body'] = $mailingData['body'];
	@$_POST['altbody'] = $mailingData['altbody'];
	@$_POST['altInclude'] = $mailingData['altInclude'];
	
	
} elseif(isset($_POST['preview'])) {
	// ___ USER HAS SENT FORM ___
	
		// __ FORM IS VALID
		unset($_POST['preview']);
		$mailingData['body'] = $_POST['body'];
		$mailingData['altbody'] = $_POST['altbody'];
		$mailingData['altInclude'] = $_POST['altInclude'];
		$pommo->set(array('mailingData' => $mailingData));
		
		Pommo::redirect('mailings_send3.php');
}

$smarty->assign($_POST);
$smarty->display('admin/mailings/mailings_send2.tpl');
Pommo::kill();
?>
