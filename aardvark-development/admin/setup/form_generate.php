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
 
 // Generates a simple HTML form based from active subscriber criteria
/**********************************
	INITIALIZATION METHODS
*********************************/
require ('../../bootstrap.php');
Pommo::requireOnce($pommo->_baseDir.'inc/helpers/fields.php');

$pommo->init();
$logger = & $pommo->_logger;
$dbo = & $pommo->_dbo;
 
// URL which processes the form input + adds (or warns) subscriber to pending table.
$signup_url = "http://" . $_SERVER['HTTP_HOST'] . $pommo->_baseUrl . "user/process.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>Sample form</title>
</head>

<?php
$form_name = "signup";
?>
<body>

<pre><?php echo Pommo::_T('VIEW THE SOURCE TO COPY, PASTE, EDIT, AND SAVE THE FORM TO AN APPROPRIATE LOCATION ON YOUR WEBSITE'); ?></pre>

<hr />

<h1><?php echo $pommo->_config['list_name']; ?> Subscriber Form</h1>

<!-- 	Set "ACTION" to the URL of poMMo's process.php
	process.php located in the "user" directory of your poMMo installation.
	** poMMo attempted to detect this location, and it may not need to be changed. ** -->
		
<form method="post" action="<?php echo $signup_url; ?>" name="<?php echo $form_name; ?>">
<fieldset>
<legend>Subscribe</legend>

<p><?php echo sprintf(Pommo::_T('%sFields%s are required'),'<strong>','</strong>'); ?></p>

<!--	Email field must be named "Email" -->
<div>
<label for="email"><strong><?php echo Pommo::_T('Your Email:'); ?></strong></label>
<input type="text" name="Email" id="email" maxlength="60" />
</div>

<?php
$fields = & PommoField::get(array('active' => TRUE));
foreach (array_keys($fields) as $field_id) {
	$field = & $fields[$field_id];

	if ($field['required'] == 'on')
		echo "<!--	BEGIN INPUT FOR REQUIRED FIELD \"".$field['name']."\" -->\r\n<div>\r\n<label for=\"field".$field_id."\"><strong>".$field['prompt'].":</strong></label>\r\n";
	else
		echo "<!--	BEGIN INPUT FOR FIELD \"".$field['name']."\" -->\r\n<div>\r\n<label for=\"field".$field_id."\">".$field['prompt'].":</label>\r\n";

	switch ($field['type']) {
		case "checkbox": // checkbox	
			if (empty($field['normally']))
				echo "\r\n<input type=\"checkbox\" name=\"d[".$field_id."]\" id=\"field".$field_id."\" />";
			else
				echo "\r\n<input type=\"checkbox\" name=\"d[".$field_id."]\" id=\"field".$field_id."\" checked=\"checked\" />";
			break;

		case "multiple": // select

			echo "\r\n<select name=\"d[".$field_id."]\" id=\"field".$field_id."\">\r\n";

			echo "<option>Please choose...</option>\r\n";

			foreach ($field['array'] as $option) {

				if (!empty($field['normally']) && $option == $field['normally'])
					echo "<option value=\"".htmlspecialchars($option)."\" selected=\"selected\"> ".$option."</option>\r\n";
				else
					echo "<option value=\"".htmlspecialchars($option)."\"> ".$option."</option>\r\n";
			}			
			echo "</select>\r\n";

			break;

		case "text": // select
		case "number": // select
		case "date": // select

			if (empty($field['normally']))
				echo "<input type=\"text\" name=\"d[".$field_id."]\" id=\"field".$field_id."\" maxlength=\"60\" />\r\n";
			else
				echo "<input type=\"text\" name=\"d[".$field_id."]\" maxlength=\"60\" value=\"".htmlspecialchars($field['normally'])."\" />\r\n";
			break;

		default:
			break;
	}

	echo "</div>\r\n\r\n";
}
?>
</fieldset>

<div id="buttons">

<!--  *** DO NOT CHANGE name="pommo_signup" ! ***
	  If you'd like to change the button text change the "value=" text. -->
	  
<input type="hidden" name="pommo_signup" value="true" />
<input type="submit" value="<?php echo Pommo::_T('Subscribe'); ?>" />

</div>

</form>

</body>
</html>