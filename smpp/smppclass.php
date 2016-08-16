<?

/*

File		:	smppclass.php
Implements	:	SMPPClass()
Description	:	This class can send messages via the SMPP protocol. Also supports unicode and multi-part messages.
License		:	GNU Lesser Genercal Public License: http://www.gnu.org/licenses/lgpl.html
Commercial advertisement: Contact info@chimit.nl for SMS connectivity and more elaborate SMPP libraries in PHP and other languages.

*/

/*

The following are the SMPP PDU types that we are using in this class.
Apart from the following 5 PDU types, there are a lot of SMPP directives
that are not implemented in this version.

*/

define(CM_BIND_TRANSMITTER, 0x00000001);
define(CM_SUBMIT_SM, 0x00000004);
define(CM_UNBIND, 0x00000002);

define(HOST, "183.230.96.94");
define(PORT, 17890);
define(USER, "089964");
define(PASSWORD, "089964");

class SMPPClass {
// public members:
	/*
	Constructor.
	Parameters:
		none.
	Example:
		$smpp = new SMPPClass();
	*/
	function SMPPClass()
	{
		/* seed random generator */
		list($usec, $sec) = explode(' ', microtime());
		$seed = (float) $sec + ((float) $usec * 100000);
		srand($seed);

		/* initialize member variables */
		$this->_debug = true; /* set this to false if you want to suppress debug output. */
		$this->_socket = NULL;
		$this->_command_status = 0;
		$this->_sequence_number = 1;
		$this->_source_address = "";
		$this->_message_sequence = rand(1,255);
	}

	/*
	For SMS gateways that support sender-ID branding, the method
	can be used to set the originating address.
	Parameters:
		$from	:	Originating address
	Example:
		$smpp->SetSender("31495595392");
	*/
	function SetSender($from)
	{
		if (strlen($from) > 20) {
			$this->debug("Error: sender id too long.\n");
			return;
		}
		$this->_source_address = $from;
	}

	/*
	This method initiates an SMPP session.
	It is to be called BEFORE using the Send() method.
	Parameters:
		$host		: SMPP ip to connect to.
		$port		: port # to connect to.
		$username	: SMPP system ID
		$password	: SMPP passord.
		$system_type	: SMPP System type
	Returns:
		true if successful, otherwise false
	Example:
		$smpp->Start("smpp.chimit.nl", 2345, "chimit", "my_password", "client01");
	*/
	function Start()
	{
/*
		$testarr = stream_get_transports();
		$have_tcp = false;
		reset($testarr);
		while (list(, $transport) = each($testarr)) {
			if ($transport == "tcpp") {
				$have_tcp = true;
			}
		}
		if (!$have_tcp) {
			$this->debug("No TCP support in this version of PHP.\n");
			return false;
		}
*/
		$this->_socket = fsockopen(HOST, PORT, $errno, $errstr, 20);
		// todo: sanity check on input parameters
		if (!$this->_socket) {
			$this->debug("Error opening SMPP session.\n");
			$this->debug("Error was: $errstr.\n");
			return;
		}
		socket_set_timeout($this->_socket, 1200);
		$status = $this->SendBindTransmitter(USER, PASSWORD);
		if ($status != 0) {
			$this->debug("Error binding to SMPP server. Invalid credentials?\n");
		}
		return ($status == 0);
	}

	/*
	this method send one sms message.
	*/
	function Send_msg($to, $text)
	{
		if(strlen($to) > 20) {
			$this->debug("to-address too long.\n");
			return;
		}
		if(!$this->_socket) {
			$this->debug("Not connected, while trying to send SUBMIT_SM.\n");
			return;
		}
		$zero_bytes = 0x00;
		$msg_id = 0x01;
		$pk_total = 0x01;
		$pk_number = 0x01;
		$register_delivery = 0x01;
		$msg_level = 0x00;
		$service_id = "ysznsh";
		$fee_usertype = 0x00;
		$fee_terminal_id = chr(0);
		$fee_terminal_type = 0x00;
		$tp_pid = 0x00;
		$tp_udhi = 0x00;
		$msg_fmt = 0x00;
		$msg_src = "089964";
		$fee_type = "01";
		$fee_code = "000000";
		$valid_time = chr(0);
		$at_time = chr(0);
		$src_id = "1064899089964";
		$destusr_tl = 0x01;
		$dest_terminal_id = $to;
		$dest_terminal_type = 0x00;
		$msg_length = strlen($text);
		$msg_content = $text;
		$link_id = chr(0);
		
		$spec = "LLCCCCa10Ca32CCCCa6a2a6a17a17a21Ca32CCa{$msg_length}a20";
		$pdu = pack($spec, $zero_bytes, $msg_id, $pk_total, $pk_number, $register_delivery, $msg_level, $service_id, $fee_usertype
				, $fee_terminal_id, $fee_terminal_type, $tp_pid, $tp_udhi, $msg_fmt, $msg_src, $fee_type, $fee_code
				, $valid_time, $at_time, $src_id, $destusr_tl, $dest_terminal_id, $dest_terminal_type, $msg_length
				, $msg_content, $link_id);
		for($i = 0; $i < strlen($pdu); $i++) {
			$this->debug(ord($pdu[$i])." ");
		}
		echo "\n";
		$status = $this->sendPDU(CM_SUBMIT_SM, $pdu);
		return $status;
	}
	
	function End()
	{
		if (!$this->_socket) {
			// not connected
			return;
		}
		$status = $this->SendUnbind();
		if ($status != 0) {
			$this->debug("SMPP Server returned error $status.\n");
		}
		fclose($this->_socket);
		$this->_socket = NULL;
		return ($status == 0);
	}

	/*
	This method sends an enquire_link PDU to the server and waits for a response.
	Parameters:
		none
	Returns:
		true if successfull, otherwise false.
	Example: $smpp->TestLink()
	*/
	function TestLink()
	{
		$pdu = "";
		$status = $this->SendPDU(CM_ENQUIRELINK, $pdu);
		return ($status == 0);
	}

// private members (not documented):

	function ExpectPDU($our_sequence_number)
	{
		do {
			$this->debug("Trying to read PDU.\n");
			if (feof($this->_socket)) {
				$this->debug("Socket was closed.!!\n");
			}
			$elength = fread($this->_socket, 4);
			if (empty($elength)) {
				$this->debug("Connection lost.\n");
				return;
			}
			extract(unpack("Nlength", $elength));
			$this->debug("Reading PDU     : $length bytes.\n");
			$stream = fread($this->_socket, $length - 4);
			$this->debug("Stream len      : " . strlen($stream) . "\n");
			extract(unpack("Ncommand_id/Nsequence_number", $stream));
			$command_id &= 0x0fffffff;
			$this->debug("Command id      : $command_id.\n");
			$this->debug("sequence_number : $sequence_number.\n");
			switch ($command_id) {
			case CM_BIND_TRANSMITTER:
				$pdu = substr($stream, 28);
				$this->debug("Got CM_BIND_TRANSMITTER_RESP.\n");
				$spec = "cversion";
				extract($this->unpack2($spec, $pdu));
				$this->debug("authen:$authen.\n");
				$this->debug("version: $version.\n");
				break;
			case CM_UNBIND:
				$this->debug("Got CM_UNBIND_RESP.\n");
				break;
			case CM_SUBMIT_SM:
				$pdu = substr($stream, 16);
				$this->debug("Got CM_SUBMIT_SM_RESP.\n");
				$spec = "Nresult";
				extract($this->unpack2($spec, $pdu));
				$this->debug("result      : $result.\n");
				break;
			default:
				$this->debug("Got unknown SMPP pdu.\n");
				break;
			}
			$this->debug("\nReceived PDU: ");
			for ($i = 0; $i < strlen($stream); $i++) {
				if (ord($stream[$i]) < 32) $this->debug("(" . ord($stream[$i]) . ")"); else $this->debug($stream[$i]);
			}
			$this->debug("\n");
		} while ($sequence_number != $our_sequence_number);
		return $command_status;
	}
	
	function SendPDU($command_id, $pdu)
	{
		$length = strlen($pdu) + 12;
		$header = pack("NNN", $length, $command_id, $this->_sequence_number);
		$this->debug("Sending PDU, len == $length\n");
		$this->debug("Sending PDU, header-len == " . strlen($header) .  "\n");
		$this->debug("Sending PDU, command_id == " . $command_id  .  "\n");
		fwrite($this->_socket, $header . $pdu, $length);
		$status = $this->ExpectPDU($this->_sequence_number);
		$this->_sequence_number = $this->_sequence_number + 1;
		return $status;
	}

	function SendBindTransmitter($user, $password)
	{
		$user = $user;
		$user_len = strlen($user);
		
		$time = new DateTime();
		$time_stamp = $time->format('mdHis');
		$zero = chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0).chr(0);
		$authenticator_str = $user.$zero.$password.$time_stamp;
		$authenticator_source = md5($authenticator_str, true);
		$authen_len = strlen($authenticator_source);

		$pdu = pack("a{$user_len}a{$authen_len}CN", $user, $authenticator_source, 48, intval($time_stamp));
		$this->debug("Bind Transmitter PDU: ");
		for ($i = 0; $i < strlen($pdu); $i++) {
			$this->debug(ord($pdu[$i]) . " ");
		}
		$this->debug($user." ".$password." ".$time_stamp." ".$authenticator_source);
		$this->debug("\n");
		$status = $this->SendPDU(CM_BIND_TRANSMITTER, $pdu);
		return $status;
	}

	function SendUnbind()
	{
		$pdu = "";
		$status = $this->SendPDU(CM_UNBIND, $pdu);
		return $status;
	}

	function unpack2($spec, $data)
	{
		$res = array();
		$specs = explode("/", $spec);
		$pos = 0;
		reset($specs);
		while (list(, $sp) = each($specs)) {
			$subject = substr($data, $pos);
			$type = substr($sp, 0, 1);
			$var = substr($sp, 1);
			switch ($type) {
			case "N":
				$temp = unpack("Ntemp2", $subject);
				$res[$var] = $temp["temp2"];
				$pos += 4;
				break;
			case "c":
				$temp = unpack("ctemp2", $subject);
				$res[$var] = $temp["temp2"];
				$pos += 1;
				break;
			case "a":
				$pos2 = strpos($subject, chr(0)) + 1;
				$temp = unpack("a{$pos2}temp2", $subject);
				$res[$var] = $temp["temp2"];
				$pos += $pos2;
				break;
			}
		}
		return $res;
	}

	function debug($str)
	{
		if ($this->_debug) {
			echo $str;
		}
	}
};

?>
