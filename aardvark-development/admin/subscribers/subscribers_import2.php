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

// TODO -> Rewrite import process.... 

/**********************************
	INITIALIZATION METHODS
*********************************/


require ('../../bootstrap.php');
require_once ($pommo->_baseDir.'/inc/lib.import.php');
require_once ($pommo->_baseDir.'/inc/db_fields.php');

$pommo = & fireup('secure','keep');
$logger = & $pommo->_logger;
$dbo = & $pommo->_dbo;

/**********************************
	SETUP TEMPLATE, PAGE
 *********************************/
Pommo::requireOnce($pommo->_baseDir.'inc/classes/template.php');
$smarty = new PommoTemplate();
$smarty->assign('returnStr', Pommo::_T('Subscribers Page'));


// load data from session
$sessionArray = & $pommo->get();
$csvArray = & $sessionArray['csvArray'];
$numFields = count($csvArray['csvFile'][$csvArray['lineWithMostFields']]);
$fields = dbGetFields($dbo);


$smarty->assign('numFields',$numFields);
$smarty->assign('csvArray',$csvArray);
$smarty->assign('fields',$fields);


if (!empty($_GET['import'])) { // check to see if we should import

	$importArray =& $sessionArray['importArray'];
	
	require_once ($pommo->_baseDir.'/inc/db_subscribers.php');
	
	foreach($importArray['valid'] as $subscriber)
		dbSubscriberAdd($dbo,$subscriber);
		

	$flagArray = array();
	foreach($importArray['invalid'] as $subscriber) {
		dbSubscriberAdd($dbo,$subscriber);
		$flagArray[] = $subscriber['email'];
	}

	if (!empty($flagArray)) { // flag subscribers needing to update their reocrds
		$flagSubscribers =& dbGetSubscriber($dbo,$flagArray,'id');
		foreach ($flagSubscribers as $subscriber_id) {
			if (isset($valStr))
			$valStr .= ',('.$subscriber_id.',\'update\')';
			else
			$valStr = '('.$subscriber_id.',\'update\')';
		}
		$sql = 'INSERT INTO '.$dbo->table['subscribers_flagged'].' (subscribers_id,flagged_type) VALUES '.$valStr;
		$dbo->query($sql);
	}
	
	$smarty->assign('returnStr', Pommo::_T('Subscribers Page'));
	$smarty->assign('page','import');
	
}
elseif (!empty($_POST['preview'])) { // check to see if a preview has been requested
	
	// prepare csvArray for import
	$importArray = csvPrepareImport($fields,$csvArray['csvFile'],$_POST['field']);
	
	// get count of subscribers to be imported
	$totalImported = count($importArray['valid'])+count($importArray['invalid']);
	$totalInvalid = count($importArray['invalid']);
	
	$totalDuplicate = count($importArray['duplicate']);
	if ($totalDuplicate)
		$logger->addMsg($importArray['duplicate']);
		
	// save Array to session
	$sessionArray['importArray'] = & $importArray;
	$pommo->set($sessionArray);
		

	$confirm = array('nourl' => 'subscribers_import2.php','yesurl' => 'subscribers_import2.php?import=TRUE');
	$smarty->assign('confirm',$confirm);
	$smarty->assign('embeddedConfirm',TRUE);
	

	$smarty->assign('totalImported',$totalImported);
	$smarty->assign('totalInvalid',$totalInvalid);
	$smarty->assign('totalDuplicate',$totalDuplicate);
	$smarty->assign('page','preview');
}

else  {
	// Display page for assigning fields
	
	$smarty->assign('entry',$csvArray['csvFile'][$csvArray['lineWithMostFields']]);
	$smarty->assign('emailField',$csvArray['emailField']);
	$smarty->assign('page','assign');
}

$smarty->display('admin/subscribers/subscribers_import2.tpl');
Pommo::kill();
?>