<?php
//addons/email_sendDirect/util.php
/**************************************************************************
Addon Created by Geodesic Solutions, LLC
Copyright (c) 2001-2013 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    6.0.7-216-g9eb2443
## 
##################################

# Email Send Direct Addon (Main e-mail sender)

class addon_email_sendDirect_util {
	private $queue; //queue of messages in case some messages attempt to be sent before a connection is made.
	private $swift;
	function connect (){
		
		$db = DataAccess::getInstance();	
		
		//echo 'attempting to connect...<br>';
		//fix to find current directory.
		
		//make sure the main swift libraries are included.
		require_once(CLASSES_DIR.PHP5_DIR.'swift/Swift.php');
		$connect_type = $db->get_site_setting('email_server_type');
		if ($connect_type=='sendmail'){
			require_once(CLASSES_DIR.PHP5_DIR.'swift/Swift/Connection/Sendmail.php');
			$connection = new Swift_Connection_Sendmail();
			//echo 'connecting via sendmail';
		} elseif ($connect_type == 'mail') { 
			//compatibility, use php's mail() function, but wrapped by the swift mailer.
			require_once (CLASSES_DIR.PHP5_DIR.'swift/Swift/Connection/NativeMail.php');
			$connection = new Swift_Connection_NativeMail();
			//Note: there is ability to specify how the 5th parameter is sent, see documentation
			//for the native mail connection on swiftmailer.org
		} else {
			require_once(CLASSES_DIR.PHP5_DIR.'swift/Swift/Connection/SMTP.php');
			//figure out what type of connection we are useing.
			$connection_security = null; //no connection encryption, auto detect?
			if (strpos($db->get_site_setting('email_server_type'),'tls')!==false)
				$connection_security = Swift_Connection_SMTP::ENC_TLS; //tls encryption
			else if (strpos($db->get_site_setting('email_server_type'),'ssl')!==false)
				$connection_security = Swift_Connection_SMTP::ENC_SSL; //ssl encryption
			
			//figure out the port
			$port = null; //use default port
			if ($db->get_site_setting('email_SMTP_port')>0)//use non standard port
				$port = $db->get_site_setting('email_SMTP_port');
			try {
				$connection = new Swift_Connection_SMTP($db->get_site_setting('email_SMTP_server'),$port,$connection_security);
				//if authentication is turned on, then attempt to athenticate.
				if ($db->get_site_setting('email_server_type')=='smtp_auth_standard'||$db->get_site_setting('email_server_type')=='smtp_auth_tls'||$db->get_site_setting('email_server_type')=='smtp_auth_ssl'){
					//attempt to athenticate
					//echo 'attempting to auth:<br />';
					$connection->setUsername($db->get_site_setting('email_username'));
					$connection->setPassword($db->get_site_setting('email_password'));
					//TODO: Find a way to verify connection..
				}
			} catch (Exception $e){
				trigger_error('ERROR SENDMAIL: Exception caught attempting to make connection.  Msg: '.$e->getMessage());
			}
		}
		if (isset($connection) && is_object($connection)){
			$this->swift = new Swift($connection);
		
		} else {
			trigger_error ('ERROR SENDMAIL: Send Error:  Connection was not established.  Check your e-mail connection settings in admin under E-Mail Setup > General E-Mail Settings');
			return false;
		}
		return true;
	}
	function close(){
		if (isset($this->swift)&& is_object($this->swift)){
			try{
				$this->swift->disconnect();
			} catch (Swift_ConnectionException $e){
				trigger_error('ERROR SENDMAIL: Problem Disconnecting.  Msg:'.$e->getMessage());
			}
			$this->swift = null;
		}
	}
	function core_email ($message_data) {
		$db = DataAccess::getInstance();
		trigger_error('DEBUG SENDMAIL: Message data used BEFORE all processing: '.print_r($message_data,1));
		//set default settings.
		//if type is not set, set it to text/plain
		$message_data['type'] = (strlen($message_data['type']) > 1)? $message_data['type'] : 'text/plain';
		//if from is not set, set it to site_email
		$message_data['from'] = (strlen($message_data['from']) > 1)? $message_data['from'] : $db->get_site_setting('site_email');
		//set encoding type, to allow manual encoding
		$message_data['encoding'] = (isset($message_data['encoding']) && strlen($message_data['encoding']) > 1)? $message_data['encoding'] : $db->get_site_setting('email_encoding_type');
		//set charset
		$message_data['charset'] = (isset($message_data['charset']) && strlen($message_data['charset'])>1)? $message_data['charset'] : null;
		
		$site_email_header = $db->get_site_setting('site_email_header',1);
		
		if (strlen(trim($site_email_header)) > 0) {
			if ($message_data['type'] == 'text/html') {
				$sep = "\n<br /><br />\n";
			} else {
				$sep = "\n\n";
			}
			$message_data['content'] = $site_email_header . $sep . $message_data['content'];
		}

		$site_email_footer = $db->get_site_setting('site_email_footer',1);
		
		if (strlen(trim($site_email_footer)) > 0) {
			if ($message_data['type'] == 'text/html') {
				$sep = "\n<br /><br />\n";
			} else {
				$sep = "\n\n";
			}
			$message_data['content'] .= $sep . $site_email_footer;
		}
						
		
		if (!defined('IN_ADMIN')){
			//counter-act user input filters, if on client side.
			//don't do this if in the admin, since input is not filtered if in admin.
			$message_data['subject'] = geoString::specialCharsDecode($message_data['subject']);
			if ($message_data['type']!='text/html'){
				$message_data['content'] = geoString::specialCharsDecode($message_data['content']);
			}
		}
		if ($message_data['type'] == 'text/plain'){
			//if plaintext, convert the content to look good...
			//strip any tags
			$message_data['content'] = geoString::specialCharsDecode(strip_tags($message_data['content']));
			if ($db->get_site_setting('email_convert_plain_to')!='plain'){
				//fix special chars, or anything strip tags misses
				$message_data['content'] = geoString::specialChars($message_data['content']);
				//convert newlines to br's
				$message_data['content'] = nl2br($message_data['content']);
				$message_data['type'] = 'text/html';
				if ($db->get_site_setting('email_convert_url_to_link')){
					//convert any URLs to be links automatically...
					$message_data['content'] = preg_replace('`(https?://[^\s<>\'"]+)`i','<a href="$1">$1</a>', $message_data['content']);
				}
			}
		}
		
		//to cc and bcc
		if (isset($message_data['to']) && !is_array($message_data['to']) && strlen(trim($message_data['to'])) > 0){
			//change to array of to addresses.
			$message_data['to'] = array ($message_data['to']);
		}
		if (isset($message_data['cc']) && !is_array($message_data['cc']) && strlen(trim($message_data['cc'])) > 0){
			//change to array of to addresses.
			$message_data['cc'] = array ($message_data['cc']);
		}
		if (isset($message_data['bcc']) && !is_array($message_data['bcc']) && strlen(trim($message_data['bcc'])) > 0){
			//change to array of to addresses.
			$message_data['bcc'] = array ($message_data['bcc']);
		}
		if (!isset($message_data['to']) || !is_array($message_data['to']) || count($message_data['to']) == 0){
			//should not send e-mail, can't send w/o to field.
			trigger_error('ERROR SENDMAIL: No to address specified, cannot send e-mail.');
			return false;
		}
		//check to addresses, make sure theres at least one good to address
		$to_ok = false;
		foreach ($message_data['to'] as $key => $to){
			if (strlen(trim($to)) > 0){
				$to_ok = true;
			}
		}
		if (!$to_ok){
			//none of the to e-mail addresses were ok
			trigger_error('ERROR SENDMAIL: No valid to address specified, cannot send e-mail.');
			return false;
		}
		
		//get rid of extra white space surrounding subject
		$message_data['subject'] = trim($message_data['subject']);
		
		//also get rid of newlines from subject.
		$message_data['subject'] = str_replace(array("\n","\r"), '', $message_data['subject']);
		
		//see if we should add the bcc all to the list of bcc recipients
		$bcc_all = $db->get_site_setting('bcc_all_email');
		if (strlen(trim($bcc_all)) > 0){
			//add bcc e-mail address.
			$message_data['bcc'][] = $bcc_all;
		}
		
		//see if we need to force the from address
		$force_from = trim($db->get_site_setting('force_admin_email_from'));
		if ($force_from) {
			//set original from as the replyto, and set the force from as the new from.
			$from = $message_data['from'];
			$message_data['from'] = $force_from;
			if (!isset($message_data['replyto']) || !$message_data['replyto']) {
				//no reply to already specified, so use the original from address as reply to address.
				$message_data['replyto'] = $from;
			}
		}
		trigger_error('DEBUG SENDMAIL: Message data used after all processing: '.print_r($message_data,1));
		//add to queue
		$this->queue[] = $message_data;
		//make sure we are connected.
		if (!isset($this->swift) || !is_object($this->swift)){
			//lets attempt to connect.
			try {
				if (!$this->connect()){
					//if we can't connect, we can't send the e-mail
					trigger_error('ERROR SENDMAIL: Not able to start connection!');
					return false;
				}
			} catch (Exception $e) {
				trigger_error('ERROR SENDMAIL: Exception caught, msg: '.$e->getMessage());
			}
		}
		$this->flushMail();
	}
	
	/**
	 * Attempts to send out any e-mails in the local queue.
	 */
	function flushMail(){
		//go through the whole queue and send it all.
		if (is_array($this->queue) && is_object($this->swift)){
			foreach ($this->queue as $messageData){
				if (is_object($this->swift)){
					$message = new Swift_Message($messageData['subject'],$messageData['content'],$messageData['type'],$messageData['encoding'],$messageData['charset']);
					
					if (isset($messageData['replyto']) && $messageData['replyto']) {
						$message->setReplyTo($messageData['replyto']);
					}
					
					$recipients = new Swift_RecipientList();
					
					if (isset($messageData['to']) && is_array($messageData['to'])){
						foreach($messageData['to'] as $address){
							if (strlen($address) > 0){
								$recipients->addTo($address);
							}
						}
					}
					if (isset($messageData['cc']) && is_array($messageData['cc'])){
						foreach($messageData['cc'] as $address){
							if (strlen($address) > 0){
								$recipients->addCc($address);
							}
						}
					}
					if (isset($messageData['bcc']) && is_array($messageData['bcc'])){
						foreach($messageData['bcc'] as $address){
							if (strlen($address) > 0){
								$recipients->addBcc($address);
							}
						}
					}
					
					trigger_error('DEBUG SENDMAIL STATS: Sending new e-mail, to:'.print_r($messageData['to'],1).' from:'.print_r($messageData['from'],1).' subject:'.$messageData['subject'].' body:'.$messageData['content']. 'type: '.$messageData['type']);
					try{
						if(!$this->swift->send($message, $recipients, $messageData['from'])){
							//echo 'Error in sending e-mail.  From: '.$message['from'].' <br>Error: '.print_r($this->swift->getErrors());
							trigger_error('ERROR SENDMAIL: Sending of message failed!');
						}
					} catch (Exception $e){
						trigger_error('ERROR SENDMAIL: Error caught from sending message, error: '.$e->getMessage());
					}
					trigger_error('DEBUG SENDMAIL STATS: Finished sending e-mail.');
				}
				else{
					//no longer connected for some reason...
					break;
				}
			}
		}
		//reset the queue since we just sent them all.
		$this->queue = array();
	}
	
	/**
	 * Core function to run at app_bottom, closes e-mail connection.
	 *
	 * @param String $val Does nothing in app_bottom
	 */
	public function core_app_bottom ($val){
		//close the e-mail connection
		$this->close();
	}
}