<?

/*

File: smppexample.php
Implements: exmaple for smppclass.php::SMPPCLass()
License: GNU Lesser Genercal Public License: http://www.gnu.org/licenses/lgpl.html
Commercial advertisement: Contact info@chimit.nl for SMS connectivity and more elaborate SMPP libraries in PHP and other languages.

*/

/*

Caution: Replace the following values with your own parameters.
Leaving the values like this is not going to work!

*/
include 'smppclass.php';

$smpp = new SMPPClass();
/* bind to smpp server */
$smpp->Start();
/* send enquire link PDU to smpp server */
//$smpp->TestLink();
/* send single message; large messages are automatically split */
$smpp->Send_msg("1064802060031", "This is my PHP message");
/* unbind from smpp server */
$smpp->End();

?>
