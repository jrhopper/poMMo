This is no class yet!<br>

<?php
/** [BEGIN HEADER] **
 * COPYRIGHT: (c) 2005 Brice Burgess / All Rights Reserved    
 * LICENSE: http://www.gnu.org/copyleft.html GNU/GPL 
 * AUTHOR: Brice Burgess <bhb@iceburg.net>
 * Created: Corinna Thoeni <corinn at gmx dot net> - 16.11.2006
 * SOURCE: http://pommo.sourceforge.net/
 *
 *  :: RESTRICTIONS ::
 *  1. This header must accompany all portions of code contained within.
 *  2. You must notify the above author of modifications to contents within.
 * 
 ** [END HEADER]**/


// +-----------------------------------------------------------------------+
// | Copyright (c) 2002, Richard Heyes                                     |
// | All rights reserved.                                                  |
// |                                                                       |
// | Redistribution and use in source and binary forms, with or without    |
// | modification, are permitted provided that the following conditions    |
// | are met:                                                              |
// |                                                                       |
// | o Redistributions of source code must retain the above copyright      |
// |   notice, this list of conditions and the following disclaimer.       |
// | o Redistributions in binary form must reproduce the above copyright   |
// |   notice, this list of conditions and the following disclaimer in the |
// |   documentation and/or other materials provided with the distribution.|
// | o The names of the authors may not be used to endorse or promote      |
// |   products derived from this software without specific prior written  |
// |   permission.                                                         |
// |                                                                       |
// | THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   |
// | "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     |
// | LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR |
// | A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT  |
// | OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, |
// | SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT      |
// | LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, |
// | DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY |
// | THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT   |
// | (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE |
// | OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.  |
// |                                                                       |
// +-----------------------------------------------------------------------+
// | Author: Richard Heyes <richard@phpguru.org>                           |
// | Co-Author: Damian Fernandez Sosa <damlists@cnba.uba.ar>               |
// +-----------------------------------------------------------------------+
//
// $Id: Net_POP3_example.php,v 1.2 2004/12/05 16:34:39 damian Exp $
?>
<html>
<body>
<?php

include('Net_POP3-1.3.6/POP3.php');



// STORE AND GET FROM DB
$user='corinna-pommo@gmx.net';
$pass='A6Q00VAAS';
$host='pop.gmx.net';
$port="110";

// you can create a file called passwords.php and store your $user,$pass,$host and $port values in it
// or you can modify this script
//@include_once("./passwords.php");



// Create the class

$pop3 =& new Net_POP3();



//$pop3->setDebug();

// Connect to localhost on usual port
// If not given, defaults are localhost:110

if(PEAR::isError( $ret= $pop3->connect($host , $port ) )){
    echo "ERROR: " . $ret->getMessage() . "\n";
    exit();
}


// Login using username/password. APOP will
// be tried first if supported, then basic.

//$pop3->login($user , $pass , 'APOP');
//$pop3->login($user , $pass , 'CRAM-MD5');

if(PEAR::isError( $ret= $pop3->login($user , $pass,'USER' ) )){
    echo "ERROR: " . $ret->getMessage() . "\n";
    exit();
}

/*
if(PEAR::isError( $ret= $pop3->login($user , $pass ) )){
    echo "ERROR: " . $ret->getMessage() . "\n";
    exit();
}
*/
/*
if(PEAR::isError( $ret= $pop3->login($user , $pass , 'CRAM-MD5') )){
    echo "ERROR: " . $ret->getMessage() . "\n";
    exit();
}
*/

$best = $pop3->_getBestAuthMethod();

echo $best;


?>
<table>
	<tr>
		<td>Mail ID</td>
		<td>Mail Size</td>
		<td>Mail Body</td>
	</tr>
<?php


// LISTING

$a=$pop3->getListing();
echo "\n";
print_r($a);
//exit();


foreach ($a as $mailkey => $mail) {//$key => $value) {
	
	echo "<tr>";
		foreach ($mail as $key=>$value) {
			echo "<td valign='top'>{$value}</td>";
			
		}
		
		$bod = $pop3->getBody($mailkey);
		echo "<td style='background-color:#eeeeee'>$bod";
		
	echo "</tr>";

}

echo "</table>";















// Get the raw headers of message 1

echo "<h2>getRawHeaders()</h2>\n";
echo "<pre>" . htmlspecialchars($pop3->getRawHeaders(1)) . "</pre>\n";


// Get structured headers of message 1


echo "<h2>getParsedHeaders()</h2> <pre>\n";
print_r($pop3->getParsedHeaders(1));
echo "</pre>\n";


// Get body of message 1

echo "<h2>getBody()</h2>\n";
echo "<pre>" . htmlspecialchars($pop3->getBody(1)) . "</pre>\n";


// Get number of messages in maildrop

echo "<h2>getNumMsg</h2>\n";
echo "<pre>" . $pop3->numMsg() . "</pre>\n";


// Get entire message

echo "<h2>getMsg()</h2>\n";
for ($i=1; $i <= $pop3->numMsg(); $i++) {
	
	echo "<div style='background-color:#d0d0d0;'>";
	echo "<pre>" . htmlspecialchars($pop3->getMsg($i)) . "</pre>\n";
	echo "</div><br>";
	//echo "<hr>";
}



// Get listing details of the maildrop

echo "<h2>getListing()</h2>\n";
echo "<pre>\n";
print_r($pop3->getListing());
echo "</pre>\n";


// Get size of maildrop

echo "<h2>getSize()</h2>\n";
echo "<pre>" . $pop3->getSize() . "</pre>\n";


// Disconnect

$pop3->disconnect();



?>
