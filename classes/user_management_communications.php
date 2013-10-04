<?php 
//user_management_communications.php
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
## ##    6.0.7-2-gc953682
## 
##################################

class User_management_communications extends geoSite
{
	var $debug_comm = 0;

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function list_communications()
	{
		if (!$this->userid) {
			$this->error_message = $this->data_missing_error_message;
			return false;
		}
		
		$this->page_id = 24;
		$this->get_text();
		$db = DataAccess::getInstance();
		$tpl_vars = array();
		$tpl_vars['helpLink'] = $this->display_help_link(389);


		$this->sql_query = "select * from ".$this->user_communications_table." where message_to = ".$this->userid." order by
				date_sent desc";
		$result = $db->Execute($this->sql_query);
		if (!$result) {
			$this->error_message = $this->internal_error_message;
			return false;
		} elseif ($result->RecordCount() > 0) {
			$tpl_vars['showCommunications'] = true;
			
			$communications = array();
			for($i = 0; $show = $result->FetchNextObject(); $i++)
			{
				
				if ($show->MESSAGE_FROM) {
					$sender = geoUser::userName($show->MESSAGE_FROM);
				} else {
					$sender = $show->MESSAGE_FROM_NON_USER;
				}
				$sender = ($sender)? $sender: '--';
				$communications[$i]['sender'] = $sender;
				$communications[$i]['sender_id'] = $show->MESSAGE_FROM ? $show->MESSAGE_FROM : false;
				$communications[$i]['read'] = $show->READ;
				$communications[$i]['listingTitle'] = geoListing::getTitle($show->REGARDING_AD);
				$communications[$i]['dateSent'] = date($this->configuration_data['entry_date_configuration'],$show->DATE_SENT);
				$communications[$i]['viewLink'] = $this->configuration_data['classifieds_file_name']."?a=4&amp;b=8&amp;c=1&amp;d=".$show->MESSAGE_ID;
				$communications[$i]['deleteLink'] = $this->configuration_data['classifieds_file_name']."?a=4&amp;b=8&amp;c=2&amp;d=".$show->MESSAGE_ID;
			}
			$tpl_vars['communications'] = $communications;
		} else {
			$tpl_vars['showCommunications'] = false;
		}

		$tpl_vars['commConfigLink'] = $this->configuration_data['classifieds_file_name']."?a=4&amp;b=7";
		$tpl_vars['userManagementHomeLink'] = $this->configuration_data['classifieds_file_name']."?a=4";
		geoView::getInstance()->setBodyTpl('communications/list_communications.tpl','','user_management')
		->setBodyVar($tpl_vars);
		$this->display_page($db);
		return true;
	}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function view_this_communication($db,$communication_id=0)
	{
		trigger_error('DEBUG MESSAGE: Top of view_this_communication()');
		$this->page_id = 25;
		$this->get_text();
		$db = DataAccess::getInstance();
		
		if (!$this->userid) {
			return false;
		}		
		if (!$communication_id) {
			$this->error_message = $this->data_missing_error_message;
			return false;
		}

		$this->sql_query = "select * from ".geoTables::user_communications_table." where message_id = ".$communication_id;
		$result = $db->Execute($this->sql_query);
		if (!$result) {
			$this->error_message = $this->internal_error_message;
			return false;
		} elseif ($result->RecordCount() != 1) {
			//wrong return count
			$this->error_message = $this->internal_error_message;
			return false;
		}

		$show = $result->FetchNextObject();
		
		if ($show->MESSAGE_TO != $this->userid) {
			//what kind of message goes best with cheese? Nacho Message!
			//(not your message, get it? haha...)
			$this->error_message = $this->data_missing_error_message;
			return false;
		}
		if ($show->MESSAGE_FROM) {
			$sender = geoUser::userName($show->MESSAGE_FROM);
		} else {
			$sender = $show->MESSAGE_FROM_NON_USER;
		}
		$sender = ($sender)? $sender: '--';
		
		$tpl_vars['sender'] = $sender;
		$tpl_vars['sender_id'] = $show->MESSAGE_FROM ? $show->MESSAGE_FROM : false;
		$tpl_vars['formTarget'] = $this->configuration_data['classifieds_file_name']."?a=3&amp;b=reply";
		$tpl_vars['dateSent'] = date($this->configuration_data['entry_date_configuration'],$show->DATE_SENT);
		$tpl_vars['listingTitle'] = geoListing::getTitle($show->REGARDING_AD);
		$tpl_vars['message'] = str_replace("\n","<br />",geoString::fromDB($show->MESSAGE));
		$tpl_vars['userManagementHomeLink'] = $this->configuration_data['classifieds_file_name']."?a=4";

		if ($sender != '--') {
			$tpl_vars['comm_id'] = $communication_id;
			$newMessage['to'] = $show->MESSAGE_FROM;
			$newMessage['from'] = $this->userid;
			$newMessage['about'] = $show->REGARDING_AD;
			$tpl_vars['newMessage'] = $newMessage;
			$tpl_vars['isPublicQuestion'] = ($show->PUBLIC_QUESTION == 1) ? true : false;
		}
		
		//mark this message as read
		if($show->READ != 1) {
			$sql = "UPDATE ".geoTables::user_communications_table." SET `read` = '1' WHERE `message_id` = '".$communication_id."'";
			$result = $db->Execute($sql);
		}

		geoView::getInstance()->setBodyTpl('communications/view_communication.tpl','','user_management')
			->setBodyVar($tpl_vars);
		$this->display_page($db);
		return true;
	} //end of function view_this_communication

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function delete_this_communication($db=null,$communication_id)
	{
		if (!$this->userid || !$communication_id) {
			$this->error_message = $this->data_missing_error_message;
			return false;
		}
		
		$is_admin = ($this->userid == 1  || geoAddon::triggerDisplay('auth_listing_delete',null,geoAddon::NOT_NULL)) ? true : false;

		$db = DataAccess::getInstance();
		
		if($_REQUEST['public'] == 1 && $is_admin) {
			//remove this communication from being shown publicly, but not from the system altogether
			$sql = "UPDATE ".geoTables::user_communications_table." SET `public_question` = 0 WHERE `message_id` = ?";
			$result = $db->Execute($sql, array($communication_id));
		} else {
			$sql = "DELETE FROM ".geoTables::user_communications_table." WHERE `message_id` = ? AND `message_to` = ?";
			$result = $db->Execute($sql, array($communication_id, $this->userid));
		}
		
		if (!$result) {
			$this->error_message = $this->internal_error_message;
			return false;
		}
		return true;
		

	} //end of function delete_this_communication

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function communication_success()
	{
		$this->page_id = 45;
		$this->get_text();
		$tpl_vars = array();
		
		$tpl_vars['uid'] = $this->userid;
		$tpl_vars['userManagementHomeLink'] = $this->configuration_data['classifieds_file_name']."?a=4";	
		geoView::getInstance()->setBodyTpl('communications/communication_success.tpl','','user_management')
			->setBodyVar($tpl_vars);
		$this->display_page();
		return true;
	}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function send_communication_form($to=0,$classified_id=0,$affiliate_id=0)
	{
		if (!$to) {
			$this->error_message = $this->data_missing_error_message;
			return false;
		}
		$to_data = $this->get_user_data($to);
		if (!$to_data) {
			$this->error_message = $this->data_missing_error_message;
			return false;
		}
		
		$sender = geoSession::getInstance()->getUserId();
		
		$db = DataAccess::getInstance();
		$this->page_id = 45;
		$this->get_text();
		$tpl_vars = array();
		
		//make sure this contact is between the appropriate users (prevent spam)
		$sql = 'SELECT `message_id` FROM '.geoTables::user_communications_table.' WHERE (`message_to` = ? AND `message_from` = ?)';
		$contact = $db->Execute($sql, array($sender, $to));
		if($contact->RecordCount() < 1 || !$classified_id) {
			//no messages from target to sender -- nothing to reply to!
			$tpl_vars['error'] = $this->messages[500748];
		}
		
		
		
		
		if ($affiliate_id) {
			$tpl_vars['formTarget'] = $this->configuration_data['affiliate_url']."?a=3&amp;b=".$to;
		} else {
			$tpl_vars['formTarget'] = $this->configuration_data['classifieds_url']."?a=3&amp;b=".$to;
		}
		
		//message to
		if ($to_data->COMMUNICATION_TYPE == 1) {
			$tpl_vars['messageTo'] = $to_data->EMAIL;
		} else {
			$tpl_vars['messageTo'] = $to_data->USERNAME;
		}
		if ($this->userid == $to) {
			$tpl_vars['toMe'] = true;
		}

		//message from
		if ($this->userid) {
			$from_data = $this->get_user_data($this->userid);
			if ($from_data) {
				$tpl_vars['fromKnown'] = true;
				if ($from_data->COMMUNICATION_TYPE == 1) {
					$tpl_vars['messageFrom'] = $from_data->EMAIL;
				} else {
					$tpl_vars['messageFrom'] = $from_data->USERNAME;
				}
			}
		}
		
		if ($classified_id) {
			$tpl_vars['listingTitle'] = geoListing::getTitle($classified_id);
			$tpl_vars['classified_id'] = $classified_id;
		}
		
		$tpl_vars['userManagementHomeLink'] = $this->configuration_data['classifieds_file_name']."?a=4";
		geoView::getInstance()->setBodyTpl('communications/send_communication_form.tpl','','user_management')
			->setBodyVar($tpl_vars);
		$this->display_page();
		return true;
	} //end of function send_communication_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function communications_configuration()
	{
		if (!$this->userid) {
			$this->error_message = $this->data_missing_error_message;
			return false;
		}
		
		$db = DataAccess::getInstance();
		$this->page_id = 26;
		$this->get_text();
		$tpl_vars = array();
		
		$sql = "select communication_type from ".$this->userdata_table." where id = ".$this->userid;
		$commType = $db->GetOne($sql);

		$tpl_vars['formTarget'] = $this->configuration_data['classifieds_file_name']."?a=4&amp;b=7&amp;z=1";
		$tpl_vars['helpLink'] = $this->display_help_link(1400);
		$tpl_vars['communicationType'] = $commType;
		$tpl_vars['userManagementHomeLink'] = $this->configuration_data['classifieds_file_name']."?a=4";
			
		geoView::getInstance()->setBodyTpl('communications/configuration_form.tpl','','user_management')
			->setBodyVar($tpl_vars);
		$this->display_page($db);
		return true;
	}
	
	

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function update_communication_configuration($configuration_information=0)
	{
		if ($this->userid)
		{
			//update the communication configuration
			if ($configuration_information)
			{
				$db = DataAccess::getInstance();
				$this->sql_query = "update ".$this->userdata_table." set communication_type = ".
					$configuration_information["communication_type"]." where id = ".$this->userid;
				$result = $db->Execute($this->sql_query);
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}
				return true;
			}
			else
			{
				//no communication information
				$this->error_message = $this->data_missing_error_message;
				return false;
			}
		}
		else
			return false;

	} //end of function update_communication_configuration

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

}
