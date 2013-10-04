<?php 
//authenticate_class.php
/**************************************************************************
Geodesic Classifieds & Auctions Platform 7.1
Copyright (c) 2001-2013 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    7.2beta1-143-gc684d71
## 
##################################

class Admin_auth extends Admin_site {

	   var $secret;
	   var $error;
	   var $login_cookie_time;
	   var $classified_user_id;
	   var $username;
	   var $classified_level;
	   var $auth_messages;
	   var $error_messages;
	   var $error_found;

	   //email that all administration messages will be sent to
	   var $admin_email = "";

	   var $messages = array();

	   var $notify_data;

	   var $debug = 0;
	   var $debug_auth = 0;


//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	public function __construct()
	{
		//constuctor
		//echo "hello from admin auth<br>\n";
		parent::__construct();
		$this->secret = "somethingverylong";

		$this->messages[800] = "Login Form";
		$this->messages[801] = "login instructions";
		$this->messages[802] = "<b>Admin Username:</b>";
		$this->messages[803] = "<b>Admin Password:</b>";
		$this->messages[804] = "Login";
		$this->messages[805] = "Please enter your username";
		$this->messages[806] = "Please enter your password";
		$this->messages[807] = "Please re-enter your username";
		$this->messages[808] = "Please re-enter your password";
		$this->messages[809] = "Your login information is incorrect. <a href=\"http://geodesicsolutions.com/support/geocore-wiki/doku.php/id,tutorials;admin;reset_admin_pass/\">Help?</a>";
		$this->messages[810] = "No account exists by that username";
		$this->messages[811] = "Edit Userdata Form";
		$this->messages[812] = "Edit userdata form instructions";
		$this->messages[813] = "username";
		$this->messages[814] = "Email address";
		$this->messages[815] = "Company name";
		$this->messages[816] = "Business type";
		$this->messages[817] = "Firstname";
		$this->messages[818] = "Lastname";
		$this->messages[819] = "Address";
		$this->messages[820] = "Address line 2";
		$this->messages[821] = "City";
		$this->messages[822] = "State";
		$this->messages[823] = "Zip Code";
		$this->messages[824] = "Country";
		$this->messages[825] = "Phone";
		$this->messages[826] = "Phone 2";
		$this->messages[827] = "Fax";
		$this->messages[828] = "Url";
		$this->messages[829] = "lost password email subject";
		$this->messages[830] = "Here is the missplaced login information";
		$this->messages[831] = "From: ";
		$this->messages[832] = "There was an error in processing your request";
		$this->messages[833] = "Change Password Form";
		$this->messages[834] = "Passwords did not match";
		$this->messages[835] = "password verification";
		$this->messages[836] = "Please retry changing your password.";
		$this->messages[837] = "You must login before you can bid on an auction.";
		$this->messages[838] = "submit your changes";
		$this->messages[839] = "You are already logged in.";
		$this->messages[840] = "click here to logout";
		$this->messages[841] = "Admin Tools & Settings > Change Password";
		$this->messages[842] = "Edit admin login form instructions";
		$this->messages[843] = "No account exists by that email address ";
		$this->messages[844] = "Your friends name";
		$this->messages[845] = "Your friends email address";
		$this->messages[846] = "Your name";
		$this->messages[847] = "Your email address";
		$this->messages[848] = "Comments you wish to give your friend";
		$this->messages[849] = "Notify a Friend Form";
		$this->messages[850] = "Enter your friends name and email address as well as your own name and email address if you are not logged in.  Leave comments for your friend if you like and press the submit button when through.";
		$this->messages[851] = "Your friends email address is missing";
		$this->messages[852] = "Your email address is missing";
		$this->messages[853] = "Your friends email address is invalid";
		$this->messages[854] = "Your email address is invalid";
		$this->messages[855] = "Your friends name is missing";
		$this->messages[856] = "Your name is missing";
		$this->messages[857] = "Message from ";
		$this->messages[858] = "Your friend, ";
		$this->messages[859] = "thought you would be interested in this item in the Geodesic Classifieds: ";
		$this->messages[860] = "Click on the above link or cut and paste it into your browser\n\n\rThis classifieds program was created by Geodesic Solutions\n\rhttp://www.geodesicsolutions.com/products/index.htm for product information";
		$this->messages[861] = "From: ";
		$this->messages[862] = "Reply-To: ";
		$this->messages[863] = "With the following comments: ";
		$this->messages[864] = "Hello ";
		$this->messages[865] = "please choose a state";
		$this->messages[866] = "<b>Admin Login Form</b><br>";
		if(defined('DEMO_MODE'))
			$this->messages[867] = "Enter the administration username and password and click the login button.<br><br>username: admin<br>password: geodesic<br><br>";
		else
			$this->messages[867] = "Enter the administration username and password and click the login button.<br><br>";
		$this->messages[868] = "<b>New Admin Password:</b>";
		$this->messages[869] = "<b>Confirm New Password:</b>";
		
		//new cookie messages:
		$this->messages[870] = "<span class='error_msg'>Error: Cookies appear to be disabled in your browser.</span> Cookies are required to log in to this site. Please check to make sure that browser cookies are enabled and that they are not being blocked by a \"firewall\" such as \"Norton Firewall\".<br /><br />
		If you continue to experience problems, make sure you are <strong>not using http://localhost</strong> to access the software.  Also check the <strong>settings in your config.php</strong> for <strong>COOKIE_DOMAIN</strong>, usually this can be left commented out to be automatically detected, but some servers the auto detection does not work so the setting needs to be specified..";
		$this->messages[871] = "<span class='error_msg'>Error: Login browser cookie could not be updated.</span>  To fix this problem, please clear all browser cookies and then close all browser windows (including this one).  Then, open a new browser window and try again.<br /><br />
		If you continue to experience problems, make sure you are <strong>not using http://localhost</strong> to access the software.  Also check the <strong>settings in your config.php</strong> for <strong>COOKIE_DOMAIN</strong>, usually this can be left commented out to be automatically detected, but some servers the auto detection does not work so the setting needs to be specified..";
	} //end of function Auth

//#############################################################################

	function login($db,$username,$password,$license_key=0,$sessionId=0)
	{
		if (!$sessionId)
		{
			if ($this->debug)
				echo "there is no session value so returning false<br>\n";
			return false;
		}
		$session = geoSession::getInstance();
		$rSessionId = $session->getSessionId();
		if (!isset($this->product_configuration) || !is_object($this->product_configuration)) {
			if (strlen(PHP5_DIR) > 0){
				$this->product_configuration = geoPC::getInstance();
			} else {
				$this->product_configuration =& geoPC::getInstance();
			}
		}
		$this->error_found = 0;
		$this->auth_messages["login"] = 0;
		$this->error_messages["username"] =0;
		$this->error_messages["password"] = 0;
		$this->error_messages['cookie'] = 0;
		
		$cookie_status = $session->getStatus();
		if ($cookie_status != 'confirmed' || strlen(trim($rSessionId)) == 0){
			//something wrong with cookie??
			if ($cookie_status == 'new'){
				$this->error_messages['cookie'] = $this->messages[870]; //seems to be no cookies
			} else {
				//must be that cookie could not be updated...
				$this->error_messages['cookie'] = $this->messages[871]; //error updating message
			}
			$this->error_found ++;
			return false;
		}
		
		if (strlen(trim($username)) == 0)
		{
			$this->error_messages["username"] = $this->messages[805];
			$this->error_found++;
		}

		if (strlen(trim($password)) == 0 )
		{
			$this->error_messages["password"] = $this->messages[806];
			$this->error_found++;
		}
		//no longer need to strip out weird chars, the new query is safe enough
		//to handle anything.
		
		//check cookies
		if ($this->error_found > 0)
		{
			$this->auth_messages["login"] = $this->messages[809];
			return false;
		}

		if ($this->debug)
		{
			echo $this->error_found." is the error count<br>\n";
			reset($this->error_messages);
			foreach ($this->error_messages as $key => $value)
				echo $key." is the key to ".$value."<br>\n";
		}
		$license = $license_key;
		$login_data = $this->product_configuration->verify_credentials($username, $password, $license_key);
		
		if ($login_data === false || !$this->product_configuration->discover_type()) {
			//see if it is the license that is not valid.
			if ($login_data) {
				$errors = $this->product_configuration->errors();
				
				$extra_details = '';
				if ($errors){
					$extra_details = "<br />
<div style=\"text-align:left; margin-left:5px; border:thin red solid; padding:3px;\">License Validation Results: $errors</div>";
				}
				if (!$license_key){
					if ($this->db->get_site_setting('license')){
						//validation failed.
						$this->auth_messages['license_key'] = 'Current license has failed validation.  Contact Geo Support if you need to update your license installation location.'.$extra_details;
					} else if (isset($_POST['b']['license_key'])) {
						$this->auth_messages["license_key"] = "Please enter your license key.";
					}
				} else {
					$this->auth_messages["license_key"] = "License key provided seems to be invalid.".$extra_details;
				}
				$this->error_found ++;
				return false;
			}
			$this->error_found ++;
			$this->auth_messages["login"] = $this->messages[809];
			return false;
		}
		//login was good.
		
		//make sure not going over seats
		$maxSeats = geoPC::maxSeats();
		if ($maxSeats >=0 && $maxSeats <= geoSession::currentAdminSeats()) {
			//maxSeats is not -1 and is less or equal to number of admin users logged in,
			//so don't allow login (but do show message as to why)
			
			$this->auth_messages["login"] = "The maximum number of simultaneous admin users are currently logged in.";
			
			//show number of minutes until next session expires, if possible.
			$since = time() - (60*20);
			$sql = "SELECT `last_time` FROM ".geoTables::session_table." WHERE `user_id`>0 AND `admin_session`='Yes' AND `last_time`>$since ORDER BY `last_time`";
			$time = (int)$this->db->GetOne($sql);
			if ($time) {
				$time = time() - $time;
				$minutes = 20-(int)(($time+1)/60);
				$this->auth_messages["login"] .= "  The next session expires in $minutes minutes.";
			}
			return false;
		}
		$sql = "select level,email,firstname,lastname from ".$this->db->geoTables->userdata_table." where id = ?";
		$level_result = $this->db->Execute($sql, array($login_data["id"]));
		if ($this->debug) echo $sql." is the query<br>\n";
		if (!$level_result)
		{
			if ($this->debug)
			{
				echo $sql." contains an error<br>\n";
				echo $this->db->ErrorMsg()."<br>\n";
			}
			$this->auth_messages["login"] = $this->messages[809];
			return false;
		}
		elseif (($level_result->RecordCount() == 0) || ($level_result->RecordCount() > 1))
		{
			if ($this->debug) echo $sql." is the query returned the wrong result count<br>\n";
			$this->auth_messages["login"] = $this->messages[809];
			return false;
		}
		$show_level = $level_result->FetchRow();
		$this->classified_user_id = $login_data["id"];
		$this->level = $show_level["level"];
		$this->email_address = $show_level["email"];
		$this->firstname = $show_level["firstname"];
		$this->lastname = $show_level["lastname"];

		$sql = "update geodesic_sessions set
			user_id = ?,
			level = ?
			where classified_session = ? AND `admin_session`='Yes'";
		//var_dump($login_data);
		$session_result = $this->db->Execute($sql, array($login_data['id'], $show_level['level'], $sessionId));
		if ($this->debug) echo $sql." is the query<bR>\n";
		if (!$session_result)
		{
			//echo $sql." is the query<br>\n";
			$this->auth_messages["login"] = $this->messages[132];
			return false;
		}
		//make sure login credentials aren't used for input in admin pages...
		if (isset($_REQUEST['b']) && !isset($_REQUEST['page'])){
			unset($_REQUEST['b']);
		}
		$session->initSession(true); //get it to update the session id.
		return true;
	} //end of function login

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function auth_error()
	{
		$this->body .= "<table cellpadding=5 cellspacing=1 border=0 align=center width=600>\n";
		$this->body .= "<tr>\n\t<td class=medium_error_font>".$this->messages[832]."</td>\n</tr>\n";
		if ($this->error_message)
			$this->body .= "<tr>\n\t<td class=medium_font>\n\t".$this->error_message."</td>\n</tr>\n";
		$this->body .= "</table>\n";
	} //end of function auth_error

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function validate_login($info){
		$session = true;
		include (GEO_BASE_DIR.'get_common_vars.php');
		
		$tpl = new geoTemplate(geoTemplate::ADMIN);
		
		$tpl->assign($info);
		$tpl->assign('session_id', $session->getSessionId());
		$tpl->assign('agreed', (isset($_POST['agreed']) && $_POST['agreed']));
		
		echo $tpl->fetch('validating_login.tpl');
		return true;
	}
	function admin_login_form($db=0, $username=0,$password=0,$license_key=0)
	{
		$show_login = true;
		//make sure we have access to product..
		if (!isset($this->product_configuration) || !is_object($this->product_configuration)){
			$this->product_configuration = geoPC::getInstance();
		}
		if (defined('TURN_ON_RESET_PASSWORD_TOOL') && TURN_ON_RESET_PASSWORD_TOOL){
			//only show them the link.
			$show_login = false;
		}
		$on_license_page = false;
		$input_type = 'text';
		$input_type_password = 'password';
		$product_name = "";
		$tpl = new geoTemplate('admin');
		$tpl_file = 'login.tpl';
		
		$product_name = "GeoCore";
		$software_type = 'Auctions / Classifieds Management Software';
		if (defined('DISCOVERED')) {
			$license_only = geoPC::license_only();
			if ($license_only) {
				$product_name .= ' '.ucfirst($license_only);
				$software_type = ucfirst($license_only).' Management Software';
			} else {
				$product_name .= ' MAX';
			}
		}
		
		$tpl->assign('login_logo','admin_images/login/logo_login_geocore.jpg');
		$tpl->assign('software_type',$software_type);
		
		if (!defined('DISCOVERED')) {
			//license key is not set yet. check login credentials, then show license form.
			$credits = $this->product_configuration->verify_credentials($username, $password);
			//$tpl->assign('login_logo','admin_images/login/logo_license_screen.jpg');
			//$product_name = "Geo Product";
			if ($credits && $show_login){
				//they logged in ok, so verify login then show license details
				//or lack thereof
				$on_license_page = true;
				$input_type = 'hidden';
				$input_type_password = 'hidden';
				
				$submit_text = "Enter";
				$tpl->assign('software_type','License Information');
				$tpl->assign('must_agree', $this->product_configuration->mustAgree());
				$install_info = $this->product_configuration->get_installation_info();
			} else {
				//echo $username.' pass '.$password;
				$skip_add = true;
				
				$tpl->assign('software_type','&nbsp;');
			}
		}
		if (geoPC::is_leased()) {
			$product_name .= ' Leased';
		}
		
		
		$tpl->assign('product_name',$product_name);
		$tpl->assign('on_license_page', $on_license_page);
		$username_label .= $this->messages[802];
		if ($this->auth_messages["login"]){
			if(defined('DEMO_MODE')) {
				$error = "Incorrect Username/Password. Please use username: admin and password: geodesic1 to log into this demo.";	
			} else {
				$error .= $this->auth_messages["login"];
			}
		}
		if ($this->auth_messages["javascript"]) {
			$error .= $this->auth_messages["javascript"];
		}
		
		if ($this->error_messages["cookie"]) {
			$tpl->assign('cookie_error',$this->error_messages['cookie']);
		} else {
			$tpl->assign('cookie_error','');
		}
				
		
		$username_field = '<input type="'.$input_type.'" name="b[username]" id="admin_username" ';
		if (defined('DEMO_MODE'))
		{
			$username_field .= "value=\"admin\"";
		}
		elseif ($username)
		{
			$username_field .= "value=\"".geoString::specialChars($username)."\"";
		}
		$username_field .= " />\n\t";

		$password_label = $this->messages[803];
		$password_field .= '<input type="'.$input_type_password.'" name="b[password]" ';
		if (defined('DEMO_MODE'))
		{
			$password_field .= "value=\"geodesic1\"";
		} elseif ($on_license_page){
			//pre fill out the password, if on the license page.
			$password_field .= 'value="'.geoString::specialChars($password).'"';
		}
		$password_field .= " />\n\t";
		if (!$license_key){
			$license_key = $this->db->get_site_setting('license');
		}
		$license_label = "License Key:";
		$license_field = "<input type='text' id='license_key_field' name='b[license_key]' value='$license_key' />";
		
		if ($this->auth_messages["license_key"])
			$license_error .= $this->auth_messages["license_key"];

		$submit = (!$submit_text) ? "Login" : $submit_text;

		$sql = "select * from ".$this->db->geoTables->version_table;
		$result = $this->db->Execute($sql);
		if (!$result)
		{
			if ($this->debug_auth)
			{
				echo $sql."<br>\n";
				echo $this->db->ErrorMsg()."<br>\n";
			}
			$show ['db_version'] = 'unknown';
		} else {
			$show = $result->FetchRow();
		}
		if ($this->debug_auth)
		{
			echo $show["db_version"]." is version<br>\n";
		}
		if (!$show_login){
			//login is disabled because the password reset tool is turned on.
			$error = "<p><a href='../reset_admin_password.php?reset_password=".sha1('reset_the_pass_now')."'>Reset Admin Password</a></p>";
			$error .= "<p>Admin disabled until <strong>\"reset_admin_password.php\"</strong> file is turned off by following the instructions found in that file.</p>";
			$username_label = '';
			$password_label = '';
			$password_field = '';
			$username_field = '';
			$submit='';
		}
		if (isset($install_info)){
			//make sure admin folder is good.
			$path='';
			if ($_SERVER['PATH_TRANSLATED'])
			{
				$path= @substr($_SERVER['PATH_TRANSLATED'], 0, @strrpos($_SERVER['PATH_TRANSLATED'], "/"));
			}
		
			elseif ($_SERVER['SCRIPT_FILENAME'])
			{
				$path= @substr($_SERVER['SCRIPT_FILENAME'], 0, @strrpos($_SERVER['SCRIPT_FILENAME'], "/"));
			}
			else {
				$path= @substr($_SERVER['ORIG_PATH_TRANSLATED'], 0, @strrpos($_SERVER['ORIG_PATH_TRANSLATED'], "\\"));
			}
			//remove admin if in admin.
			$path = @realpath($path);
			if (substr( php_uname( ), 0, 7 ) == "Windows"){
				$path = str_replace('\\', '/', $path);
			}
			$folder = @substr($path, @strrpos($path,'/'), (strlen($path) - @strrpos($path,'/')));
			$folder = substr($folder,1,strlen($folder)-1).'/';
			if (ADMIN_LOCAL_DIR != $folder){
				$error .= 'FIX THIS:  The setting ADMIN_LOCAL_DIR in the file config.php is wrong.<br>
ADMIN_LOCAL_DIR set to: '.ADMIN_LOCAL_DIR.'<br>
<strong>Correct Setting: '.$folder.'</strong><br>
If you enter the license when your admin folder is not set correctly, the license will be locked to the wrong directory, and you will need to get it reset by Geodesic Support.';
			}
			$tpl->assign('install_domain_name',$install_info['domain']);
			$tpl->assign('install_folder',$install_info['path']);
		} 
		$vars = array(
			'version'=> "Ver. ".geoPC::getVersion(),
			'username_label'=>$username_label,
			'username_field'=>$username_field,
			'password_label'=>$password_label,
			'password_field'=>$password_field,
			'error'=>$error,
			'license_error'=>$license_error,
			'license_label'=>$license_label,
			'license_field'=>$license_field,
			'submit'=>$submit,
		);
		$tpl->assign($vars);
		$tpl->display($tpl_file);
		
		$this->auth_messages["login"] = 0;
		$this->error_messages["username"] = 0;
		$this->error_messages["password"] = 0;

	} //end of function admin_login_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function edit_admin_login_form($db)
	{
	
		$sql = "select * from ".$this->logins_table." where id = 1";
		$result = $this->db->Execute($sql);
		if (!$result)
		{
			$this->auth_messages["login"] = $this->messages[809];
			return false;
		}
		elseif ($result->RecordCount() == 1)
		{
			$show = $result->FetchRow();
			//get this users info and show the form
			$this->body .= "<form action=index.php?mc=admin_tools_settings&page=admin_tools_password method=post onsubmit='pass = document.getElementsByName(\"b[password]\")[0].value; pass_verify = document.getElementsByName(\"b[password_verify]\")[0].value; if( pass == pass_verify ) { return true; } else { alert(\"Passwords do not match\"); return false; }'>\n";
			$this->body .= "<fieldset id='ChangePassword'><legend>Change Password Form</legend><table cellpadding=2 cellspacing=1 border=0>\n";
			//$this->title = $this->messages[841];
			$this->description = $this->messages[842]." The email field below will
				only affect the ads that the admin places (not recommended) not any site administration or registration
				communications.  It is recommended that if the site owner wishes to places ads that he create a separate
				user account for that purpose.\n\t</td>\n</tr>\n";
			if (defined('DEMO_MODE'))
			{
				$this->body .= "<tr>\n\t<td align=center class=medium_font>\n\t<b>Fields Turned Off</b><Br>\n\t</td>\n\t<td>&nbsp</td>\n\t</tr>\n";
			} else {
				if (strlen(trim($this->auth_messages['results'])) > 0)
					$this->body .= "<tr>\n\t<td colspan=3 class=medium_error_font align=center>\n\t".$this->auth_messages["results"]."</font>\n\t</td>\n</tr>\n";
				if (strlen(trim($this->auth_messages["username"])) > 0)
					$this->body .= "<tr>\n\t<td colspan=3 align=center>\n\t".$this->auth_messages["username"]."</font>\n\t</td>\n</tr>\n";
				$this->body .= "<tr>\n\t<td align=right class=medium_font>\n\t".$this->messages[802]."\n\t</td>\n\t";
				//put username and password in htmlentities, or else having a quote in the user or pass
				//will break this form.
				$this->body .= "<td><input type=text name=b[username] value=\"".geoString::specialChars($show["username"])."\">\n\t</td>\n</tr>\n";
				$this->body .= "<tr>\n\t<td align=right class=medium_font>\n\t".$this->messages[868]."\n\t</td>\n\t";
				$this->body .= "<td><input type=password name=b[password]>\n\t</td>\n";
				if ($this->auth_messages["password"])
					$this->body .= "<td align=left class=medium_error_font>\n\t".$this->auth_messages["password"]."\n\t</td>";
				$this->body .= "</tr>
					<tr>\n\t<td align=right class=medium_font>\n\t".$this->messages[869]."\n\t</td>\n\t";
				$this->body .= "<td><input type=password name=b[password_verify]>\n\t</td>\n";
				$this->body .= "</tr>\n";
				//do not change e-mail here.  It is too confusing for the user to know what the e-mail
				//is used for, since Brian removed all page descriptions.
				/*
				$this->body .= "<tr>\n\t<td align=right class=medium_font>\n\t<b>Admin Email:</b><Br>\n\t</td>\n\t";
				$this->body .= "<td><input type=text name=b[email] value=\"".$show_data["email"]."\">\n\t</td>\n</tr>\n";
				*/
				$this->body .= "<tr>\n\t<td>\n\t</td>\n\t<td align=left>\n\t<input type=submit name='auto_save' value=\"Save\">\n\t</td>\n</tr>\n";
			}
			
			//link to edit admin data
			$this->body .= "<tr>\n\t<td colspan=2 style=\"text-align: center;\">\n\t\t<a class=\"medium_font\" style=\"font-weight: bold;\" href=\"index.php?mc=users&page=users_edit&b=1\">Edit Admin's Personal Data</a>\n\t</tr>\n\t</td>\n";
			
			$this->body .= "</table></fieldset>\n";
			$this->body .= "</form>\n";
			return true;
			
		}
		else
		{
			$this->auth_messages["login"] = $this->messages[809];
			return false;
		}
	} //end of function edit_admin_login_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function update_admin_login($db,$info=0)
	{
		if (is_array($info))
		{
			if( trim($info['password']) != trim($info['password_verify'])) {
				// Passwords did not match
				$this->auth_messages['password'] = urldecode($this->messages[834]);
				return false;
			}
			$info['username'] = trim($info['username']);
			
			//see if username already exists
			$sql = "select id from ".$this->logins_table." where username = ? and id != 1";
			$result = $this->db->Execute($sql, array($info['username']));
			if (!$result)
			{
				$this->error["registration"] = urldecode($this->messages[230]);
				return false;
			}
			elseif ($result->RecordCount() > 0)
			{
				$this->auth_messages["username"] = "That username already exists...try another";
				$this->error_found++;
				return false;
			}
			
			$sql = "update ".$this->logins_table." set
				username = ?";
			$query_data = array();
			$query_data [] = $info['username'];
			if (strlen($info['password']) > 0){
				//only update the password if it is not blank.
				//see which hash type to use.
				$hash_type = $this->db->get_site_setting('admin_pass_hash');
				$salt = '';
				$password_hashed = geoPC::get_hashed_password($info['username'], $info['password'], $hash_type);
				if (is_array($password_hashed)) {
					$salt=$password_hashed['salt'];
					$password_hashed=$password_hashed['password'];
				}
				$sql .=', password = ?, salt = ?';
				$query_data[] = $password_hashed;
				$query_data[] = $salt;
			} else {
				//password not present
				return false;
			}
			$sql .= ' where id = 1';
			$result = $this->db->Execute($sql, $query_data);
			//echo $sql." is the query<br>\n";

			if (!$result)
			{
				return false;
			}
			
			$sql = "update `geodesic_userdata` set `username` = ? where id = 1";
			$result = $this->db->Execute($sql, array($info['username']));
			if(!$result) {
				return false;
			}
			//updated successfully!
			$this->auth_messages['results'] = 'Admin username and password changed.';
			return true;		
		}
		else
		{
			return false;
		}

	} //end of function update_admin_login

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function verify_license()
	{
		return $this->product_configuration->discover_type();
	}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_admin_tools_password()
	{
		if (!$this->edit_admin_login_form($this->db))
			return false;
		$this->display_page();	
	}
	
	function update_admin_tools_password()
	{
		return $this->update_admin_login($this->db,$_REQUEST["b"]);
	}
} //end of class Auth
