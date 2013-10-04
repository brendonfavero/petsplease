<?php
//addons/email_sendDirect/admin.php
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
## ##    7.1beta1-1198-g409d9c1
## 
##################################

# Email Send Direct Addon (Main e-mail sender)

class addon_email_sendDirect_admin extends addon_email_sendDirect_info {

	public $admin_site;
	public $messages;
	
	/**
	 * Email configuration constructor.  This is responsible for loading the appropriate page, and
	 * then running site->display_page().
	 */
	public function __construct()
	{
		if (Singleton::isInstance('Admin_site')){
			$this->admin_site = Singleton::getInstance('Admin_site');
		}
		else { //if we cant find the admin site object, we cant do squat!
			return false;
		}
		//$this->Admin_site($db, $product_configuration);
		$this->messages['error_no_host']= "Error:  No SMTP host name given.  The host name is required for SMTP connections.  If you are unsure what the SMTP host name is, contact your host provider, or use the \"Standard Connection\". ";
		
		//This is where you would do any special case loaders or whatever, that get run before the display function gets called.
				
	} 
	
	
	//function to initialize pages, to let the page loader know the pages exist.
	//this will only get run if the addon is installed and enabled.
	public function init_pages () {
		//init e-mail config pages.
		//menu_page::addonAddPage($index, $parent, $title, $addon_name, $image, $type);
		//take over the e-mail general pages.
		menu_page::addonAddPage('email_general_config','email_setup','General E-Mail Settings','email_sendDirect',$this->icon_image,'main_page',true);
	}
	/**
	 * Display general settings for e-mail
	 */
	public function display_email_general_config(){
		//get the instance of the db.
		$db = $admin = 1;
		include GEO_BASE_DIR . 'get_common_vars.php';
		//add the tooltips javascript page
		$html = $admin->getUserMessages();
		$row = 'row_color2';
		$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
		$header_html .= "<LINK href=\"css/email_section.css\" rel=\"stylesheet\" type=\"text/css\"></link>";
		//email server settings
		$html .= '<form action="index.php?mc=email_setup&page=email_general_config" method="post">
<fieldset>
<legend>E-Mail Method Used</legend>
<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
<tbody>';
		//e-mail config settings
		
		//if standard email options are disabled
		if ($db->get_site_setting('email_server_type')=='sendmail'){
			$smtp_disabled=' disabled=true';
			$smtp_email_style = 'class="disabled_text"';
			$sendmail_checked = 'checked="checked" ';
			$mail_checked = ' ';
			$smtp_checked = ' ';
		} elseif ($db->get_site_setting('email_server_type')=='mail') {
			$smtp_disabled=' disabled=true';
			$smtp_email_style = 'class="disabled_text"';
			$sendmail_checked = ' ';
			$mail_checked = 'checked="checked" ';
			$smtp_checked = ' ';
		} else {
			$smtp_disabled = '';
			$smtp_email_style = 'class="enabled_text"';
			$sendmail_checked = ' ';
			$mail_checked = ' ';
			$smtp_checked = 'checked="checked" ';
		}
		
		$standard_email = "<tr class=".$row."><td colspan=\"2\" class=\"medium_font\"><label><input type=\"radio\" name=\"email_server_type\" id=\"email_server_type_sendmail\" onClick=\"toggle_email_server_type();\" value=\"sendmail\"$sendmail_checked";
		
		$standard_email .= " /><strong>SendMail Method</strong>".$this->admin_site->show_tooltip(2,1)."</label></td></tr>
";
		$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
		$smtp_email = "<tr class=".$row."><td colspan=\"2\" class=\"medium_font\"><label><input type=\"radio\" name=\"email_server_type\" id=\"email_server_type_smtp\" onClick=\"toggle_email_server_type();\" value=\"smtp_\" $smtp_checked/><strong>SMTP Server Connection Method</strong>".$this->admin_site->show_tooltip(3,1)."</label></td></tr>";
		$smtp_host=$db->get_site_setting('email_SMTP_server');
		$smtp_port=$db->get_site_setting('email_SMTP_port');
		$detected_host = ini_get('SMTP');
		$detected_port = ini_get('smtp_port');
		$detected_host = '<strong>'.$detected_host.'</strong>';
		$detected_port = '<strong>'.$detected_port.'</strong>';
		$smtp_email .= "<tr class=\"row_color3\"><td width=\"50%\" align=\"right\" valign=\"top\" class=\"medium_font\"><strong name=\"smtp_connection_text\" $smtp_email_style>SMTP Host:".$this->admin_site->show_tooltip(8,1)."</strong></td>\n" .
				"\t<td class=\"medium_font\"><input type=\"text\" name=\"smtp_host_name\" value=\"".$smtp_host."\"$smtp_disabled /> <span name=\"smtp_connection_text\" $smtp_email_style style=\"white-space:nowrap;\">php.ini Detected Setting: $detected_host</span>";
		$smtp_email .= "<tr class=\"row_color3\"><td width=\"50%\" align=\"right\" valign=\"top\" class=\"medium_font\"><strong name=\"smtp_connection_text\" $smtp_email_style>SMTP Port (set to 0 to use the default port):</strong></td>\n" .
				"\t<td class=\"medium_font\"><input type=\"text\" name=\"smtp_port\" value=\"".$smtp_port."\"$smtp_disabled /> <span name=\"smtp_connection_text\" $smtp_email_style style=\"white-space:nowrap;\">php.ini Detected Setting: $detected_port</span>";
		//figure out which security setting to check.
		switch ($db->get_site_setting('email_server_type')) {
			case 'smtp_auth_standard':
			case 'smtp_standard':
			case 'sendmail':
				//no encryption
				$none_checked = ' checked=true';
				$tls_checked = '';
				$ssl_checked = '';
				break;
			case 'smtp_auth_tls':
			case 'smtp_tls': 
				//tls encryption
				$none_checked = '';
				$tls_checked = ' checked=true';
				$ssl_checked = '';
				break;
			default:
				//ssl encryption
				$none_checked = '';
				$tls_checked = '';
				$ssl_checked = ' checked=true';
		}
		$smtp_email .= "<tr class=\"row_color3\"><td width=\"50%\" align=\"right\" valign=\"top\" class=\"medium_font\"><strong name=\"smtp_connection_text\" $smtp_email_style>Connection Security: </strong></td>\n" .
				"\t<td class=\"medium_font\"><label name=\"smtp_connection_text\" $smtp_email_style><input type=\"radio\" name=\"email_server_type_security\" id=\"email_server_type_security1\" value=\"standard\"$none_checked $smtp_disabled /> None</label>\n" .
				"\t<label name=\"smtp_connection_text\" $smtp_email_style><input type=\"radio\" name=\"email_server_type_security\" id=\"email_server_type_security2\" value=\"tls\"$tls_checked $smtp_disabled /> TLS</label>\n" .
				"\t<label name=\"smtp_connection_text\" $smtp_email_style><input type=\"radio\" name=\"email_server_type_security\" id=\"email_server_type_security3\" value=\"ssl\"$ssl_checked $smtp_disabled /> SSL</label></td></tr>\n";
		if ($db->get_site_setting('email_server_type')== 'smtp_auth_standard'||$db->get_site_setting('email_server_type')=='smtp_auth_tls'||$db->get_site_setting('email_server_type')=='smtp_auth_ssl'){
			//$smtp_email_checked .= 'checked=true ';
			$user_pass_disabled = '';
			$user_pass_style = 'class="enabled_text"';
			$user_pass_checked = ' checked=true';
		} else{
			$user_pass_disabled = 'disabled=true ';
			$user_pass_style = 'class="disabled_text"';
			$user_pass_checked = '';
		}
		$smtp_email .= "<tr class=\"row_color3\"><td width=\"50%\" align=\"right\" valign=\"top\" class=\"medium_font\"><label name=\"smtp_connection_text\" $smtp_email_style><input type=\"checkbox\" name=\"email_authentication\" id=\"email_authentication\" onClick=\"toggle_email_server_type();\" value=true$user_pass_checked $smtp_disabled ";
		
		$smtp_email .= "/> <strong>Connection requires username and password</strong></label></td>";
		$smtp_email .= "<td class=\"medium_font\"><label name=\"smtp_user_connection_text\" $user_pass_style><strong>SMTP User: <input type=\"text\" name=\"smtp_user\" id=\"smtp_user\" value=\"".geoString::specialChars($db->get_site_setting('email_username'))."\" $user_pass_disabled/></label><br />\n" .
				"<label name=\"smtp_user_connection_text\" $user_pass_style><strong>SMTP Pass: <input type=\"password\" name=\"smtp_pass\" id=\"smtp_pass\" value=\"".geoString::specialChars($db->get_site_setting('email_password'))."\" $user_pass_disabled/></label></td></tr>\n";
		$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
		$mail_email = "<tr class=".$row."><td colspan=\"2\" class=\"medium_font\"><label><input type=\"radio\" name=\"email_server_type\" id=\"email_server_type_mail\" onClick=\"toggle_email_server_type();\" value=\"mail\" $mail_checked";
		
		$mail_email .= " /><strong>Native mail() Method (For Compatibility)</strong>".$this->admin_site->show_tooltip(9,1)."</label></td></tr>
";
		
		$html .= $standard_email;
		$html .= $smtp_email;
		$html .= $mail_email;
		$row = 'row_color2';//reset row color, start on white
		//Main admin e-mail reply address
		$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
		
		$html .= "
</table>
</fieldset>
<fieldset>
	<legend>E-Mail Addresses</legend>
	<div>
	<div class='$row'>
		<div class='leftColumn'>
			Admin Communication Reply-to Address:</b>".$this->admin_site->show_tooltip(4,1)."
		</div>
		<div class='rightColumn'>
			<input type=text name=\"site_email\" size=30 value=\"".$db->get_site_setting("site_email")."\">
		</div>
		<div class='clearColumn'></div>
	</div>";
		
		//Registration from address
		$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
		$html .= "
	<div class='$row'>
		<div class='leftColumn'>
			Registration Notify Address:</b>".$this->admin_site->show_tooltip(6,1)."
		</div>
		<div class='rightColumn'>
			<input type=text name=\"registration_admin_email\" size=30 value=\"".$db->get_site_setting("registration_admin_email")."\">
		</div>
		<div class='clearColumn'></div>
	</div>";
		
		//Admin BCC address
		if(geoPC::is_ent()) {
			$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
			$html .= "
	<div class='$row'>
		<div class='leftColumn'>
			BCC admin on user communication:</b>".$this->admin_site->show_tooltip(1,1)."
		</div>
		<div class='rightColumn'>
			<input type=text name=\"admin_email_bcc\" size=30 value=\"".$db->get_site_setting("admin_email_bcc")."\">
		</div>
		<div class='clearColumn'></div>
	</div>";
		}
		
		//Admin front side address
		$sql = 'SELECT email FROM '.$db->geoTables->userdata_table.' WHERE id=1';
		$result = $db->Execute($sql);
		if (!$result){
			trigger_error('ERROR SQL: Query: '.$sql.' ERROR: '.$db->ErrorMsg());
		} else {
			$user_data = $result->FetchRow();
			$user_email=geoString::specialChars($user_data['email']);
		}
		$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
		$html .= "
	<div class='$row'>
		<div class='leftColumn'>
			Client Side Admin E-Mail:".$this->admin_site->show_tooltip(5,1)."
		</div>
		<div class='rightColumn'>
			<input type=text name=\"admin_user_email\" size=30 value=\"".$user_email."\">
		</div>
		<div class='clearColumn'></div>
	</div>";
		
		$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
		$tooltip = geoHTML::showTooltip('Force From E-Mail','
		<strong>Affects:</strong> From: field and ReplyTo: field<br />
		<strong>Used:</strong> On all e-mails sent by the system.
		<br /><br />
		<strong>Leave blank</strong> to send e-mails normally.
		<br /><br /> 
		<strong>More Info:</strong>
		If used, all e-mails sent by the system will have the From: field set to this e-mail address, and the ReplyTo: field set to what would have normally been the From: e-mail.');
		$html .= "
	<div class='$row'>
		<div class='leftColumn'>
			Force From E-Mail:$tooltip
		</div>
		<div class='rightColumn'>
			<input type=text name=\"force_admin_email_from\" size='30' value=\"".geoString::specialChars($db->get_site_setting('force_admin_email_from'))."\" />
		</div>
		<div class='clearColumn'></div>
	</div>";
		
		//BCC e-mail address for all e-mail sent, for testing
		$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
		$bcc_all_email = $db->get_site_setting('bcc_all_email');
		$html .= "
	<div class='$row'>
		<div class='leftColumn'>
			BCC For ALL e-mail sent:</b>".$this->admin_site->show_tooltip(10,1)."<br />
			(For Testing purposes)
		</div>
		<div class='rightColumn'>
			<input type=text name=\"bcc_all_email\" size=30 value=\"$bcc_all_email\">
		</div>
		<div class='clearColumn'></div>
	</div>";
		//end e-mail address section
		$html .= '</div></fieldset>';
		
		$row = 'row_color2';//reset row color, start on white
		$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
		$salutation = $db->get_site_setting('email_salutation_type');
		$html .= "
<fieldset>
	<legend>Site Wide Email Settings</legend>
	<div>
		<div class='$row'>
			<div class='leftColumn'>
				Site wide email header:</b>".$this->admin_site->show_tooltip(11,1)."
			</div>
			<div class='rightColumn'>
				<textarea name=\"site_email_header\" rows=10 cols=50>".geoString::specialChars($db->get_site_setting("site_email_header",1))."</textarea>
			</div>
			<div class='clearColumn'></div>
		</div>
		<div class='".($row = ($row == 'row_color1')? 'row_color2': 'row_color1')."'>
			<div class='leftColumn'>
				Site wide email footer:</b>".$this->admin_site->show_tooltip(12,1)."
			</div>
			<div class='rightColumn'>
				<textarea name=\"site_email_footer\" rows=10 cols=50>".geoString::specialChars($db->get_site_setting("site_email_footer",1))."</textarea>
			</div>
			<div class='clearColumn'></div>
		</div>
		<div class='".($row = ($row == 'row_color1')? 'row_color2': 'row_color1')."'>
			<div class='leftColumn'>
				User Salutation
			</div>
			<div class='rightColumn'>
				<label><input type='radio' name='salutation' value='1'".((!$salutation||$salutation==1)? ' checked="checked"' : '')." /> Username</label><br />
				<label><input type='radio' name='salutation' value='2'".(($salutation==2)? ' checked="checked"' : '')." /> Firstname</label><br />
				<label><input type='radio' name='salutation' value='3'".(($salutation==3)? ' checked="checked"' : '')." /> Firstname Lastname</label><br />
				<label><input type='radio' name='salutation' value='4'".(($salutation==4)? ' checked="checked"' : '')." /> Lastname Firstname</label><br />
				<label><input type='radio' name='salutation' value='5'".(($salutation==5)? ' checked="checked"' : '')." /> E-Mail</label><br />
				<label><input type='radio' name='salutation' value='6'".(($salutation==6)? ' checked="checked"' : '')." /> Firstname Lastname (Username)</label><br />
			</div>
			<div class='clearColumn'></div>
		</div>
	</div>
	</fieldset>";			
		
		$row = 'row_color2';//reset row color, start on white
		$row = ($row == 'row_color1')? 'row_color2': 'row_color1';
		
		
		//Advanced Settings

/* "Send Text Emails as:" setting removed 9/13/11 because all major emails are sent exclusively as full HTML now.
 * 
 * Might add this back later if we make it send dual-type emails

		$html .= '
<fieldset>
<legend>Advanced E-Mail Settings</legend>
<table align="center" border="0" cellpadding="3" cellspacing="0" width="100%">
<tbody>';
		
		$html .= "<tr class=".$row.">\n\t<td align=right valign=top width=50% class=medium_font>\n\t<b>Send Text E-Mails as:".$this->admin_site->show_tooltip(7,1)."</b></td>\n\t";//".$this->admin_site->show_tooltip(4,1)."
		$convert = $db->get_site_setting('email_convert_plain_to');
		if ($convert != 'plain'){
			$html_c = ' checked="true"';
			$plain_c = '';
			$convert_to_link = '';
			$convert_to_label = ' class="enabled_text"';
		} else {
			$html_c = '';
			$plain_c = ' checked="true"';
			$convert_to_link = ' disabled="disabled"';
			$convert_to_label = ' class="disabled_text"';
		}
		if ($db->get_site_setting('email_convert_url_to_link')){
			$convert_to_link .= ' checked="true"';
		} else {
			$convert_to_link .= '';
		}
		//Send E-Mail as:
		$html .= "<td valign=top class=medium_font>\n\t" .
"<label style=\"white-space:nowrap;\"><input type=\"radio\" id=\"email_send_as_html\" name=\"email_convert_plain_to\"  onClick=\"javascript:toggleEmailConvertUrls()\" value=\"html\"$html_c /> HTML (Recomended, converts newlines to HTML Line Breaks)</label><br />
&nbsp; &nbsp; <label id=\"label_convert_url_to_link\" onClick=\"javascript:toggleEmailConvertUrls()\"$convert_to_label><input type=\"checkbox\" id=\"email_convert_url_to_link\" name=\"email_convert_url_to_link\" value=\"1\"$convert_to_link /> Convert URL's into HTML links (Recomended)<br />
<label><input type=\"radio\" name=\"email_convert_plain_to\" value=\"plain\"$plain_c onClick=\"javascript:toggleEmailConvertUrls()\" /> Plain-Text (Sent as-is)</label></td></tr>";
		$row = ($row == 'row_color1')? 'row_color2': 'row_color1';

		$html .= '
</table>
</fieldset>'; */

		//test settings
		$html .= '
<fieldset>
<legend>Test E-Mail Settings</legend>
<div style="text-align:center;" class="row_color1"><span class="medium_font" style="font-weight: bold;">Send test e-mail to: </span><input type=text name="email_test_from" /><input type="submit" name="auto_save" value="Save & Send Test E-Mail"></div>
</fieldset>
';
		//save button

		$html .= "<div style='text-align:center;'><input type=submit value=\"Save Settings\" name=\"auto_save\"></div>";
			
		$html .= "</form>";
		$html .= "<script type=\"text/javascript\" src='../addons/email_sendDirect/main.js'></script>";
		$admin->v()->addBody($html)->addTop($header_html);
	}
	
	/**
	 * update general settings for e-mail
	 */
	public function update_email_general_config(){
		//get the instance of the db.
		$db = $admin = 1;
		include GEO_BASE_DIR . 'get_common_vars.php';
		//no input verification needed, since it is all done by ado db for us!
		
		//set the email server type.  I guess this part we do need to verify inputs.
		$sql = 'UPDATE '.$this->admin_site->site_configuration_table.' SET ';
		$sql_vars[0] = '';
		if ((!isset($_POST['email_server_type']))||(isset($_POST['email_server_type'])&&$_POST['email_server_type']=='sendmail')){
			//server type is normal sendmail...
			$db->set_site_setting('email_server_type', 'sendmail');
		} elseif ($_POST['email_server_type'] == 'mail'){
			$db->set_site_setting('email_server_type','mail');
		} else if ($_POST['email_server_type']=='smtp_'){
			
			if (isset($_POST['smtp_host_name'])&&$_POST['smtp_host_name']!=''){
				$db->set_site_setting('email_SMTP_server',$_POST['smtp_host_name']);
			} else {
				$admin->userError('SMTP Host field is required for SMTP connections.');
				return false;
			}
			
			//server type is one of the smtp connections.
			$server_type = 'smtp';
			//now figure out which smtp type it is
			if (isset($_POST['email_authentication']) && $_POST['email_authentication']==true){
				//connection needs authentication, so add the auth thingy
				$server_type .= '_auth';
				//while we're at it, remember the entered user and pass
				
				$db->set_site_setting('email_username', $_POST['smtp_user']);
				$db->set_site_setting('email_password', $_POST['smtp_pass']);
			} 
			//now figure out what connection security to use
			if ($_POST['email_server_type_security']=='standard'){
				//standard connection.
				$server_type .= '_standard';
			} else if ($_POST['email_server_type_security']=='tls'){
				$server_type .= '_tls';
			} else if ($_POST['email_server_type_security']=='ssl'){
				$server_type .= '_ssl';
			} else {
				//either someone tampered with the post vars, or (more likely) they did not click any of the radios, 
				//so default to the standard connection
				$server_type .= '_standard';
			}
			//now do the rest of the vars.
			$db->set_site_setting('email_server_type',$server_type);
				
			
			
			if (isset($_POST['smtp_port'])&&$_POST['smtp_port']!=''){
				//we are defining our own port.
				$db->set_site_setting('email_SMTP_port', $_POST['smtp_port']);
			} else {
				//use default, by setting port to 0.
				$db->set_site_setting('email_SMTP_port', 0);
			}
		}
		$db->set_site_setting('site_email', trim($_POST['site_email']));
		if(geoPC::is_ent()) {
			$db->set_site_setting('admin_email_bcc', trim($_POST['admin_email_bcc']));
		}
		$db->set_site_setting('registration_admin_email', trim($_POST['registration_admin_email']));
		$db->set_site_setting('bcc_all_email', trim($_POST['bcc_all_email']));
		$db->set_site_setting('force_admin_email_from', trim($_POST['force_admin_email_from']));
		$db->set_site_setting('site_email_header', $_POST['site_email_header'],1);
		$db->set_site_setting('site_email_footer', $_POST['site_email_footer'],1);
		$salutation = (isset($_POST['salutation']))? (int)$_POST['salutation'] : 1;
		$db->set_site_setting('email_salutation_type', $salutation);
		
		//refactor fix to clear old settings.
		//remove once old configuration_table is completly removed.
		$sql = 'UPDATE '.$db->geoTables->site_configuration_table.' SET email_header_break=0';
		$result = $db->Execute($sql);
		
		//update the client side e-mail address.
		$sql = 'UPDATE '.$db->geoTables->userdata_table.' SET email=? WHERE id=1';
		$client_email = array( (isset($_POST['admin_user_email']) ? trim($_POST['admin_user_email']) : trim($db->get_site_setting('site_email')))  );
		$result = $db->Execute($sql, $client_email);
		
		//save advanced settings
		/* not used for now (9/13/11).
		 * might come back into use if sending dual-type text/html emails is implemented
		 
		 
		if ($_POST['email_convert_plain_to']!='plain'){
			$convert_to = 'html';
			$convert_to_link = (isset($_POST['email_convert_url_to_link']) && $_POST['email_convert_url_to_link'])? true: false;
			$db->set_site_setting('email_convert_url_to_link',$convert_to_link);
		} else {
			$convert_to = 'plain';
		}
		$db->set_site_setting('email_convert_plain_to',$convert_to);
		*/
		
		$admin->userSuccess('E-Mail Settings Saved.');
		if (isset($_POST['email_test_from']) && strlen($_POST['email_test_from'])>0){
			$date=$this->send_test_email($_POST['email_test_from']);
			$admin->userNotice('Just attempted to send e-mail to address: '.$_POST['email_test_from'].'<br />Timestamp in e-mail will be: '.$date.'<br />If you get the test e-mail with matching timestamp, then the settings below worked.');
		}
		return true;
	}
	
	/**
	 * Function to test sending an e-mail.
	 */
	public function send_test_email($to_address){
		$db = DataAccess::getInstance();
		
		$to = $to_address;
		$subject = 'Testing the E-mail Configuration.';
		$message = 'This is a test of the emailing system.  Below are the e-mail settings that were used at the time this e-mail was sent: ';
		$date = date('M d, Y G:i:s');
		$email_settings = array();
		$connection_type = $db->get_site_setting('email_server_type');
		if ($connection_type=='sendmail'){
			$email_settings['Connection Type: ']='Standard SendMail Connection';
		} elseif ($connection_type == 'mail'){
			$email_settings['Connection Type: ']='Native mail() Connection';
		} else {
			$email_settings['Connection Type: ']= 'SMTP Connection';
			$email_settings['SMTP Host: ']= $db->get_site_setting('email_SMTP_server');
			$email_settings['SMTP Port: ']= $db->get_site_setting('email_SMTP_port');
			$connection_type = $db->get_site_setting('email_server_type');
			if (strstr($connection_type, 'ssl')){
				$email_settings['Connection Security: ']= 'SSL';
			} else if (strstr($connection_type, 'tls')){
				$email_settings['Connection Security: ']= 'TLS';
			} else {
				$email_settings['Connection Security: ']= 'NONE';
			}
			
			if (strstr($connection_type, 'auth')){
				$email_settings['Connection Requires username and password: '] = 'ON';
				$email_settings['SMTP User: ']= $db->get_site_setting('email_username');
				$email_settings['SMTP Pass: ']= '[PASSWORD HIDDEN]';
			}else {
				$email_settings['Connection Requires username and password: '] = 'OFF';
			}
		}
		
		$email_settings['Admin Communication Reply-to Address (should be from address in this e-mail): ']= $db->get_site_setting('site_email');
		$email_settings['Send "text/plain" e-mail as:']=$db->get_site_setting('email_convert_plain_to');
		$convert_url = ($db->get_site_setting('email_convert_plain_to')=='html' && $db->get_site_setting('email_convert_url_to_link'));
		$email_settings['Convert URL\'s into HTML links:']=($convert_url)? 'On': 'Off';
		$email_settings['Content-Transfer-Encoding Header: '] = ($db->get_site_setting('email_encoding_type'))? $db->get_site_setting('email_encoding_type'): 'Auto Detect';
		$message .= "

Time E-Mail Sent:  $date

";
		if ($convert_url) {
			$message .= "
Test URL Link in e-mail:
http://geodesicsolutions.com
";
		}
		$message .= "
E-Mail Settings:

";
		foreach ($email_settings as $key => $value){
			$message .= "$key $value 
";
		}
		$this->admin_site->sendMail($to, $subject, $message);
		//return the date string sent with the message.
		return $date;
	}
}