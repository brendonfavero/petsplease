//----- ToolTips ------//
Text[1] = ["BCC admin on user communication", "<strong>Affects:</strong> \"BCC:\" field<br /><strong>Used:</strong> (If not left blank): All \"notify friend\" and \"notify seller\" e-mails sent between sellers and users.<br /><br /><strong>More Info:</strong> Enter an email address here to have a blind copy of all notify friend and notify seller email sent.  If this address is left empty no email will be sent.  This does not affect communications sent from within communication section."]
Text[2] = ["Standard SendMail Connection","This is the standard connection, which uses your Linux host's built in sendmail function.  Use this connection type for most standard Linux servers.<br /><strong>Windows Server:</strong> This setting does not work on Windows servers.  If your host uses a Windows server, select SMTP Connection, and for the host use the detected PHP SMTP host setting."]
Text[3] = ["SMTP Connection","Requires E-Mail \"SMTP\" connection to send e-mail (Usually provided through your Host or E-Mail service provider).  Use this connection to directly connect to a SMTP server.  <br /><br /><strong>Windows Servers:</strong> For Window's Servers, use SMTP Connection, and for the SMTP Host use the php.ini Detected Setting."]
Text[4] = ["Admin Reply-to Address","<strong>Affects:</strong> \"From:\" and \"Reply To:\" fields<br /><strong>Used:</strong> On all admin communication e-mails sent to users.<br /><br /><strong>More Info:</strong> This is the e-mail address that acts as the Reply-To or From address, when sending communication e-mails to users from the admin account."]
Text[5] = ["Client Side Admin E-Mail","When you log into the client side as the admin user, this is the e-mail address used for that user."]
Text[6] = ["Registration Notify Address","<strong>Affects:</strong>  To: field<br /><strong>Used:</strong> In E-Mails sent to notify the Admin User of new registrations and as the from email address in all registration emails sent to new registrants.<br /><br /><strong>More Info:</strong> This is the e-mail address that a notification e-mail will be sent to whenever someone registers, or attempts to register on the site, as long as you have that setting turned on under E-Mail Setup > Notification E-Mail Config.  This also used as the from email address used in all emails sent to new registrants so if the client respond their responses will go to this email address.  So make sure that email address actually exists or has a forward before setting.<br><strong>Note:</strong> This setting appears in both E-Mail Setup pages, changeing this setting on either page affects the same setting."]
Text[7] = ["Send Text E-Mails as:","<strong>HTML</strong>:  Removes all HTML tags (for security), and converts all \"new lines\" into HTML line breaks (&lt;br> tags), and sends the e-mail in HTML mode."+
"<br /><strong>Convert URL's into HTML links</strong>: When turned on, this converts URLs found into clickable HTML links."+
"<br /><br /><strong>Plain-Text</strong>:  E-mail is sent \"as-is\" in plain-text format, with all HTML tags removed."+
"<br /><br /><strong>Exceptions:</strong> This setting only applies to plain text e-mails, it does not apply to e-mail sent through admin messaging system, if using content type of HTML."]
Text[8] = ["SMTP Settings","Typical settings:<br /><strong>SMTP Host:</strong> localhost<br /><strong>SMTP Port:</strong> 25<br /><strong>Connection Security:</strong> None<br />You may also try using the settings detected in php.ini, or ask your hosting provider."]
Text[9] = ["Native mail() Connection (Compatibility Mode)","This uses the built in PHP function mail().  Only use this when you know that the mail() function works, but neither Sendmail or SMTP will work on your host.  This is not recommended unless neither SendMail nor SMTP connection will work on your host, as using mail() can significantly slow down the site when sending more than 1 e-mail at once."]
Text[10] = ["BCC E-mail address for All E-mails", "<strong>Affects:</strong> \"BCC:\" field<br /><strong>Used:</strong> (If not left blank): <em>All e-mails</em> sent from the software, including e-mails sent to the admin.<br /><br /><strong>More Info:</strong> Enter an email address here to have a blind copy of all e-mails sent by the software, even e-mail sent to admin user, and e-mails sent using admin messaging system.  If this address is left empty no BCC email will be set."]
Text[11] = ["Site-Wide Email Header", "<strong>Affects:</strong> <em>All e-mails</em> sent from the software, including e-mails sent to the admin.<br /><strong>Used:</strong> (If not left blank): <em>All e-mails</em> sent from the software, including e-mails sent to the admin.<br /><br /><strong>More Info:</strong> Enter the text you wish to appear at the TOP of all the emails sent by your site."]
Text[12] = ["Site-Wide Email Footer", "<strong>Affects:</strong> <em>All e-mails</em> sent from the software, including e-mails sent to the admin.<br /><strong>Used:</strong> (If not left blank): <em>All e-mails</em> sent from the software, including e-mails sent to the admin.<br /><br /><strong>More Info:</strong> Enter the text you wish to appear at the BOTTOM of all the emails sent by your site."]

var Style = new Array()
Style[1]=["white","#7BC342","","","",,"3c3c3c","#F5FBF0","","","",,,,2,"#70B339",2,,,,,"",3,,,]

var TipId = "tiplayer"
var FiltersEnabled = 1
mig_clay()

//------End ToolTips-----//

/**
 * Function to switch between standard connection and smtp connection.
 **/
function toggle_email_server_type(){
	//figure out which ones to toggle to what
	
	if (document.getElementById('email_server_type_sendmail').checked){
		//sendmail server currently checked
		var test = document.getElementsByTagName('input');
		//change the disabled fields
		for (var i=0; i < test.length; i++){
			if (test[i].getAttribute('name') == 'email_header_config'){
				test[i].disabled = false;
			} else if (test[i].getAttribute('name') == 'email_server_type_security') {
				test[i].disabled = true;
			} else if (test[i].getAttribute('name') == 'email_authentication'){
				test[i].disabled = true;
			} else if (test[i].getAttribute('name') == 'smtp_user'){
				test[i].disabled = true;
			} else if (test[i].getAttribute('name') == 'smtp_pass'){
				test[i].disabled = true;
			} else if (test[i].getAttribute('name') == 'smtp_host_name' || test[i].getAttribute('name') ==  'smtp_port'){
				test[i].disabled = true;
			}
		}
		
		//change the text color
		var text_color = document.getElementsByTagName("label");
		for (var b=0; b<text_color.length; b++){
			if (text_color[b].getAttribute('name') == "standard_connection_text"){
				text_color[b].className="enabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_connection_text"){
				text_color[b].className="disabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_user_connection_text"){
				text_color[b].className="disabled_text";
			}
		}
		
		text_color = document.getElementsByTagName("strong");
		for (var b=0; b<text_color.length; b++){
			if (text_color[b].getAttribute('name') == "standard_connection_text"){
				text_color[b].className="enabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_connection_text"){
				text_color[b].className="disabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_user_connection_text"){
				text_color[b].className="disabled_text";
			}
		}
		text_color = document.getElementsByTagName("span");
		for (var b=0; b<text_color.length; b++){
			if (text_color[b].getAttribute('name') == "standard_connection_text"){
				text_color[b].className="enabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_connection_text"){
				text_color[b].className="disabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_user_connection_text"){
				text_color[b].className="disabled_text";
			}
		}
		
	} else if (document.getElementById('email_server_type_smtp').checked) {
		var test = document.getElementsByTagName('input');
		//change the disabled fields
		for (var i=0; i < test.length; i++){
			if (test[i].getAttribute('name') == 'email_header_config'){
				test[i].disabled = true;
			} else if (test[i].getAttribute('name') == 'email_server_type_security') {
				test[i].disabled = false;
			} else if (test[i].getAttribute('name') == 'email_authentication'){
				test[i].disabled = false;
			} else if (test[i].getAttribute('name') == 'smtp_user'){
				if (document.getElementById('email_authentication').checked){
					test[i].disabled = false;
				} else {
					test[i].disabled = true;
				}
			} else if (test[i].getAttribute('name') == 'smtp_pass'){
				if (document.getElementById('email_authentication').checked){
					test[i].disabled = false;
				} else {
					test[i].disabled = true;
				}
			} else if (test[i].getAttribute('name') == 'smtp_host_name' || test[i].getAttribute('name') ==  'smtp_port'){
				test[i].disabled = false;
			}
		}
		//change the text color
		var text_color = document.getElementsByTagName("label");
		for (var b=0; b<text_color.length; b++){
			//alert (text_color[b].getAttribute('name'));
			if (text_color[b].getAttribute('name') == "standard_connection_text"){
				text_color[b].className="disabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_connection_text"){
				text_color[b].className="enabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_user_connection_text"){
				if (document.getElementById('email_authentication').checked)
					text_color[b].className="enabled_text";
				else
					text_color[b].className="disabled_text";
			}
		}
		
		text_color = document.getElementsByTagName("strong");
		for (b=0; b<text_color.length; b++){
			if (text_color[b].getAttribute('name') == "standard_connection_text"){
				text_color[b].className="disabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_connection_text"){
				text_color[b].className="enabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_user_connection_text"){
				if (document.getElementById('email_authentication').checked)
					text_color[b].className="enabled_text";
				else
					text_color[b].className="disabled_text";
			}
		}
		
		text_color = document.getElementsByTagName("span");
		for (b=0; b<text_color.length; b++){
			if (text_color[b].getAttribute('name') == "standard_connection_text"){
				text_color[b].className="disabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_connection_text"){
				text_color[b].className="enabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_user_connection_text"){
				if (document.getElementById('email_authentication').checked)
					text_color[b].className="enabled_text";
				else
					text_color[b].className="disabled_text";
			}
		}
	} else {
		//mail server currently checked
		var test = document.getElementsByTagName('input');
		//change the disabled fields
		for (var i=0; i < test.length; i++){
			if (test[i].getAttribute('name') == 'email_header_config'){
				test[i].disabled = true;
			} else if (test[i].getAttribute('name') == 'email_server_type_security') {
				test[i].disabled = true;
			} else if (test[i].getAttribute('name') == 'email_authentication'){
				test[i].disabled = true;
			} else if (test[i].getAttribute('name') == 'smtp_user'){
				test[i].disabled = true;
			} else if (test[i].getAttribute('name') == 'smtp_pass'){
				test[i].disabled = true;
			} else if (test[i].getAttribute('name') == 'smtp_host_name' || test[i].getAttribute('name') ==  'smtp_port'){
				test[i].disabled = true;
			}
		}
		
		//change the text color
		var text_color = document.getElementsByTagName("label");
		for (var b=0; b<text_color.length; b++){
			if (text_color[b].getAttribute('name') == "standard_connection_text"){
				text_color[b].className="disabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_connection_text"){
				text_color[b].className="disabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_user_connection_text"){
				text_color[b].className="disabled_text";
			}
		}
		
		text_color = document.getElementsByTagName("strong");
		for (var b=0; b<text_color.length; b++){
			if (text_color[b].getAttribute('name') == "standard_connection_text"){
				text_color[b].className="disabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_connection_text"){
				text_color[b].className="disabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_user_connection_text"){
				text_color[b].className="disabled_text";
			}
		}
		text_color = document.getElementsByTagName("span");
		for (var b=0; b<text_color.length; b++){
			if (text_color[b].getAttribute('name') == "standard_connection_text"){
				text_color[b].className="disabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_connection_text"){
				text_color[b].className="disabled_text";
				//alert (text_color[b].className);
			} else if (text_color[b].getAttribute('name') == "smtp_user_connection_text"){
				text_color[b].className="disabled_text";
			}
		}
	}
	return true;
}
function toggleEmailCharEncoding(){
	if (document.getElementById("encode_auto_detect").checked){
		document.getElementById("encode_force_val").disabled=true;
		//document.getElementById("encode_force_val").value='';
		document.getElementById("example_utf").className="disabled_text";
	} else {
		document.getElementById("encode_force_val").disabled=false;
		//document.getElementById("encode_force_val").value='';
		document.getElementById("example_utf").className="enabled_text";
	}
}
function toggleEmailConvertUrls(){
	if (document.getElementById("email_send_as_html").checked){
		document.getElementById("email_convert_url_to_link").disabled=false;
		document.getElementById("label_convert_url_to_link").className="enabled_text";
	} else {
		document.getElementById("email_convert_url_to_link").disabled=true;
		document.getElementById("label_convert_url_to_link").className="disabled_text";
	}
}