<?php
// admin_price_plan_management_class.php
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
## ##    7.2beta1-153-gd6a581e
## 
##################################

class Price_plan_management extends Admin_site {

	var $debug_price_plan = 0;
	var $debug_cat_price_plan = 0;
	var $last_high_variable = 0;
	var $brackets_set = 0;
	var $recurring_charge_ability = 0;
	
	var $discount_form_input;
	var $error;
	var $error_found;
	
	function display_price_plan_list($db=0)
	{
		$sql = "SELECT price_plan_id FROM ".$this->price_plan_table." WHERE applies_to=2 ORDER BY price_plan_id ASC";
		$count_result = $this->db->Execute($sql);
		if (!$count_result)
		{
			$this->error_message = $this->internal_error_message;
			return false;
		}
		if ($count_result->RecordCount() > 0)
			$primary_auc = $count_result->FetchRow();
		$primary_auc_id = ($count_result->RecordCount() > 0) ? $primary_auc["price_plan_id"] : 0;

		$sql = "select * from ".$this->price_plan_table;
	
		$conditions = array();
		$applies = array();	
		if(geoMaster::is('auctions')){
			$applies[] = "applies_to = 2";
		}
		if(geoMaster::is('classifieds')) {
			$applies[] = "applies_to = 1";
		}
		$conditions[] = '('.implode(' OR ', $applies).')';
		
		if(!(geoAddon::getInstance()->isEnabled('subscription_pricing'))) {
			$conditions[] = "type_of_billing = 1";
		}
		if(count($conditions) > 0) {
			$sql .= " WHERE ".implode(' AND ', $conditions);
		}
		
		$sql .= " order by applies_to, name";
				
		$result = $this->db->Execute($sql);
		if (!$result) {
			return false;
		} else {
			$this->body .= "
				<table cellpadding=3 cellspacing=0 border=0 align=center width=100%>\n";
			//$this->title = "Pricing > Price Plans Home";
			$this->description = "Create and delete price plans through this
				admin tool.  You can organize your price plans on a grouping basis.  Each group within your site will have a price plan
				attached to them.  This will be the pricing plan they use to list items on your site.  Pricing plans can be very inventive
				so explore all your options.";
			$this->body .= "
					<tr>
						<td align=center>
							<fieldset id='CurPricePlans'>
				<legend>Current Price Plans</legend><table cellpadding=3 cellspacing=1 border=0 width=\"100%\">
								<tr align=center>
									<td align=left class=col_hdr_left width=\"40%\">Price Plan Name</td>
									<td nowrap class=col_hdr># of Users</td>";

			if(geoMaster::is('classifieds') && geoMaster::is('auctions'))
				$this->body .= "
									<td class=col_hdr>Applies to</td>";
			$this->body .= "
									<td class=col_hdr>Type</td>
									<td colspan=2 class=col_hdr width=\"30%\">Actions</td>
								</tr>";

			$this->row_count = 0;
			$error=0;
			while ($show = $result->FetchRow())
			{
				$sql = "SELECT * FROM ".$this->classified_subscription_choices_table." WHERE price_plan_id = ".$show["price_plan_id"]."";
				$sub_choice_result = $this->db->Execute($sql);
				if ($plan_count_result===false)
					return false;
				if ($show["type_of_billing"]==2 && $sub_choice_result->RecordCount()==0)
				{
					$error++;
					$medium_font = "medium_error_font";
					if ($error==1)
						$this->description .= "<br><strong>You currently have one or more subscription price plan(s) (<span class=medium_error_font>listed in
							red</span>) that needs subscription periods in order to be available to the groups.</strong>";
				}
				else
					$medium_font = "medium_font";

				if((geoMaster::is('classifieds')) && $show["applies_to"]==1)
				{
					$sql = "select count(*) as price_plan_total from ".$this->user_groups_price_plans_table." where price_plan_id = ".$show["price_plan_id"]." and id!=1";
					$plan_count_result = $this->db->Execute($sql);
					if (!$plan_count_result)
					{
						//echo $sql."<br>\n";
						return false;
					}
					elseif ($plan_count_result->RecordCount() == 1)
						$show_plan_count = $plan_count_result->FetchRow();
				}
				if((geoMaster::is('auctions')) && $show["applies_to"]==2)
				{
					$sql = "select count(*) as price_plan_total from ".$this->user_groups_price_plans_table." where auction_price_plan_id = ".$show["price_plan_id"]." and id!=1";
					$plan_count_result = $this->db->Execute($sql);
					if (!$plan_count_result)
					{
						//echo $sql."<br>\n";
						return false;
					}
					elseif ($plan_count_result->RecordCount() == 1)
						$show_plan_count = $plan_count_result->FetchRow();
				}

				$this->body .= "<tr align=center class=".$this->get_row_color().">
									<td align=left class=$medium_font><strong>".$show["name"]."</strong><br>".$show["description"]."</td>";

				$this->body .= "<td class=medium_font>".$show_plan_count["price_plan_total"]." </td>";

				if(geoMaster::is('classifieds') && geoMaster::is('auctions'))
					$this->body .= "
									<td class=medium_font>".(($show["applies_to"] == 1) ? "Classifieds" : "Auctions")."</td>";
				$this->body .= "
									<td class=medium_font>".($show["type_of_billing"] == 1 ? "Fee-based" : "Subscription")."</td>
									<td class=medium_font width=\"10%\">".geoHTML::addButton('edit', "index.php?mc=pricing&page=pricing_edit_plans&g=".$show["price_plan_id"])."</td>";
				if ($show["price_plan_id"]!=1 && $show["price_plan_id"]!=$primary_auc_id)
					$this->body .= "
									<td width=\"10%\">".geoHTML::addButton('delete', "index.php?mc=pricing&page=pricing_delete_plans&c=".$show["price_plan_id"], false, '', 'mini_cancel')."</span></td>";
				else
					$this->body .= "
									<td class=medium_font width=\"10%\">&nbsp; </td>";
				$this->body .= "
								</tr>";
				$this->row_count++;
			}
			$this->body .= "
							</table></fieldset>
						</td>
					</tr>";
			
			
			if(geoAddon::getInstance()->isEnabled('enterprise_pricing')) {
				$this->body .= "
					<tr>
						<td align=center><a href=index.php?mc=pricing&page=pricing_new_price_plan class='mini_button'>\n\tAdd New Price Plan</a></td>
					</tr>";
			}
					
			$this->body .= "
				</table>
				</form>";
			return true;
		}

	} //end of function display_price_plan_list

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	var $insert_price_plan_id;
	function insert_price_plan($db,$price_plan_info=0)
	{
		if(!(geoPC::is_ent() || geoPC::is_premier())) {
			return false;
		}
		if(!$price_plan_info) {
			geoAdmin::m('Error: Price plan info not set. Please try again.', geoAdmin::ERROR);
			return false;
		}
		if((strlen(trim($price_plan_info["name"])) == 0)) {
			geoAdmin::m("Name is a required field, please enter a name for this price plan.", geoAdmin::ERROR);
			return false;
		}
		if(!$price_plan_info['type_of_billing'] || ($price_plan_info['type_of_billing'] == 2 && !geoAddon::getInstance()->isEnabled('subscription_pricing'))) {
			geoAdmin::m("Invalid billing type.", geoAdmin::ERROR);
			return false;
		}
		
			
		$sql = "INSERT INTO ".geoTables::price_plans_table." (name,description,type_of_billing, max_ads_allowed, applies_to) VALUES (?,?,?,?,?)";
		$result = $this->db->Execute($sql, array($price_plan_info['name'], $price_plan_info['description'], $price_plan_info['type_of_billing'], 1000, $price_plan_info['applies_to']));
		if (!$result) {
			geoAdmin::m('Database error adding new price plan',geoAdmin::ERROR);
			return false;
		} 
		geoAdmin::m('New price plan created.  You can edit the new price plan below.', geoAdmin::SUCCESS);
		$this->insert_price_plan_id = $this->db->Insert_Id();
		return true;	
	}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function delete_price_plan($db,$price_plan_id=0)
	{
		$sql = "SELECT price_plan_id FROM ".$this->price_plan_table." WHERE applies_to=2 ORDER BY price_plan_id ASC";
		$count_result = $this->db->Execute($sql);
		if (!$count_result)
		{
			$this->error_message = $this->internal_error_message;
			return false;
		}
		if ($count_result->RecordCount() > 0)
			$primary_auc = $count_result->FetchRow();
		$primary_auc_id = ($count_result->RecordCount() > 0) ? $primary_auc["price_plan_id"] : 0;
		if ($price_plan_id && $price_plan_id!=1 && $price_plan_id!=$primary_auc_id)
		{
			$sql = "delete from ".$this->price_plan_table."
				where price_plan_id = ".$price_plan_id;
			//echo $sql."<br>\n";
			$result = $this->db->Execute($sql);
			if (!$result)
			{
				$this->error_message = $this->internal_error_message;
				return false;
			}
			else
			{
				$sql = "delete from ".$this->classified_price_plans_categories_table."
					where price_plan_id = ".$price_plan_id;
				//echo $sql."<br>\n";
				$result = $this->db->Execute($sql);
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}

				$sql = "delete from ".$this->price_plans_increments_table."
					where price_plan_id = ".$price_plan_id;
				//echo $sql."<br>\n";
				$result = $this->db->Execute($sql);
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}

				$sql = "delete from ".$this->price_plans_extras_table."
					where price_plan_id = ".$price_plan_id;
				//echo $sql."<br>\n";
				$result = $this->db->Execute($sql);
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}

				$sql = "delete from ".$this->classified_price_plan_lengths_table."
					where price_plan_id = ".$price_plan_id;
				//echo $sql."<br>\n";
				$result = $this->db->Execute($sql);
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}

				$sql = "delete from ".$this->attached_price_plans."
					where price_plan_id = ".$price_plan_id;
				//echo $sql."<br>\n";
				$result = $this->db->Execute($sql);
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}

				return true;
			}
		}
		elseif ($price_plan_id == 1)
		{
			return true;
		}
		else
		{
			$this->error_message = $this->internal_error_message;
			return false;
		}
	} //end of function delete_price_plan

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function move_to_price_plan($db,$price_plan_from=0,$price_plan_to=0)
	{
		if (($price_plan_from) && ($price_plan_from != $price_plan_to))
		{
			if ($price_plan_to)
			{
				$sql = "select * from ".$this->price_plan_table." where price_plan_id = ".$price_plan_to;
				//echo $sql."<br>\n";
				$result = $this->db->Execute($sql);
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}
				elseif ($result->RecordCount() == 1)
				{
					$sql = "update ".$this->user_groups_price_plans_table." set
						price_plan_id = ".$price_plan_to."
						where price_plan_id = ".$price_plan_from;
					$result = $this->db->Execute($sql);
					//echo $sql."<br>\n";
					if (!$result)
					{
						$this->error_message = $this->internal_error_message;
						return false;
					}

					$sql = "update ".$this->classified_groups_table." set
						price_plan_id = ".$price_plan_to."
						where price_plan_id = ".$price_plan_from;
					$result = $this->db->Execute($sql);
					//echo $sql."<br>\n";
					if (!$result)
					{
						$this->error_message = $this->internal_error_message;
						return false;
					}
				}
			}
			return true;
		}
		else
		{
			$this->error_message = $this->internal_error_message;
			return false;
		}
	} //end of function move_to_price_plan

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function price_plan_home($db,$price_plan_id=0)
	{
		if ($price_plan_id)
		{
			$price_plan_name = $this->get_price_plan_name($db,$price_plan_id);
			$price_plan = $this->get_price_plan($db,$price_plan_id);
			$addons = geoAddon::getInstance();
			if ($price_plan_name)
			{
				$this->body .= "<table cellpadding=3 cellspacing=0 border=0 align=center width=100%><tr>\n\t<td colspan=5 class=group_price_hdr align=center>\n\t<strong>Price Plan: ".$price_plan_name."</strong>\n\t</td>\n</tr>\n</table>\n";
				
				$this->body .= "<fieldset id='EditPP'>
				<legend>Edit Price Plan</legend><table cellpadding=3 cellspacing=0 border=0 align=center width=100%>\n";
				//$this->title = "Pricing > Price Plans > Edit";
				$this->description = "Edit the <strong>".$price_plan_name."</strong> price plan details through this
					admin tool.  Click on the aspect of this price plan you wish to edit by clicking on the appropriate link below.";

				// Name and description
				$row_color = geoHTML::adminGetRowColor();
				$this->body .= "<tr class='$row_color'>\n\t<td width=\"15%\" align=\"right\"><a href=index.php?mc=pricing&page=pricing_edit_plans&f=5&g=".$price_plan_id."><img alt=\"\" src=\"admin_images/design/icon_write.gif\"/></a></td>\n\t
				<td>\n\t<div><a href=index.php?mc=pricing&page=pricing_edit_plans&f=5&g=".$price_plan_id." class=large_font>\n\t
				Name and Description</a></div><div class=small_font>Edit this Price Plan's Name and Description.</div>\n\t</td>\n</tr>\n";

				if($addons->isEnabled('enterprise_pricing')) {
					// Expiration of plan
					$row_color = geoHTML::adminGetRowColor();
					$this->body .= "<tr class='$row_color'>\n\t<td width=\"15%\" align=\"right\"><a href=index.php?mc=pricing&page=pricing_edit_plans&f=1&g=".$price_plan_id."><img alt=\"\" src=\"admin_images/design/icon_hourglass.gif\"/></a></td>\n\t
					<td>\n\t<div><a href=index.php?mc=pricing&page=pricing_edit_plans&f=1&g=".$price_plan_id." class=large_font>\n\t
					Expiration of Plan</a></div><div class=small_font>Edit this Price Plan's Expiration Details.</div>\n\t</td>\n</tr>\n";
				}
				// Cost specifics
				$row_color = geoHTML::adminGetRowColor();
				$this->body .= "<tr class='$row_color'>\n\t<td width=\"15%\" align=\"right\"><a href=index.php?mc=pricing&page=pricing_edit_plans&f=3&g=".$price_plan_id."><img alt=\"\" src=\"admin_images/design/icon_cost.gif\"/></a></td>\n\t
					<td>\n\t<div><a href=index.php?mc=pricing&page=pricing_edit_plans&f=3&g=".$price_plan_id." class=large_font>\n\t
					Cost Specifics</a></div><div class=small_font>Edit this Price Plan's Pricing Specifics and Associated Fees.</div>\n\t</td>\n</tr>\n";
				
				if(!geoMaster::is('classifieds') && geoMaster::is('auctions') && ($addons->isEnabled('subscription_pricing') || $addons->isEnabled('enterprise_pricing'))) {
					// Registration specifics
					// In ClassAuctions, this feature is administered in group management, not price plan management.
					$row_color = geoHTML::adminGetRowColor();
					$this->body .= "<tr class='$row_color'>\n\t<td width=\"15%\" align=\"right\"><a href=index.php?mc=pricing&page=pricing_edit_plans&f=4&g=".$price_plan_id."><img alt=\"\" src=\"admin_images/design/icon_registration.gif\"/></a></td>\n\t
					<td>\n\t<div><a href=index.php?mc=pricing&page=pricing_edit_plans&f=4&g=".$price_plan_id." class=large_font>\n\t
					Registration Specifics</a></div><div class=small_font>Enter special attributes to users who register into this User Group.</div>\n\t</td>\n</tr>\n";
				}
				

				if ($addons->isEnabled('subscription_pricing') && $price_plan["type_of_billing"] == 2) {
					$row_color = geoHTML::adminGetRowColor();
					$this->body .= "<tr class='$row_color'>\n\t<td width=\"15%\" align=\"right\"><a href=index.php?mc=pricing&page=pricing_edit_plans&f=8&g=".$price_plan_id."><img alt=\"\" src=\"admin_images/design/icon_calendar.gif\"/></a></td>\n\t
					<td>\n\t<div><a href=index.php?mc=pricing&page=pricing_edit_plans&f=8&g=".$price_plan_id." class=large_font>\n\t
					Subscription Periods</a></div><div class=small_font>Enter Subscription Periods and Fees associated with this Price Plan.</div>\n\t</td>\n</tr>\n";
				} elseif ($addons->isEnabled('enterprise_pricing')) {
					//Category specific Costs
					//Note: category-specific costs cannot be used on a subscription price plan
					$row_color = geoHTML::adminGetRowColor();
					$this->body .= "<tr class='$row_color'>\n\t<td width=\"15%\" align=\"right\"><a href=index.php?mc=pricing&page=pricing_category_costs&x=".$price_plan_id."><img alt=\"\" src=\"admin_images/design/icon_cat.gif\"/></a></td>\n\t
					<td>\n\t<div><a href=index.php?mc=pricing&page=pricing_category_costs&x=".$price_plan_id." class=large_font>\n\t
					Category Specific Costs</a></div><div class=small_font>Enter Category Specific Fees that will override this Plan's 'Cost Specifics' for specified categories.</div>\n\t</td>\n</tr>\n";
				}

				$this->body .= "</table></fieldset>\n";
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	} //end of function price_plan_home

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function price_plan_expiration_form($db,$price_plan_id=0)
	{
		if(!(geoPC::is_ent() || geoPC::is_premier())) {
			return false;
		}
		if ($price_plan_id)
		{
			$price_plan_name = $this->get_price_plan_name($db,$price_plan_id);
			if ($price_plan_name)
			{
				$sql = "select * from ".$this->price_plan_table." where price_plan_id = ".$price_plan_id;
				$result = $this->db->Execute($sql);
				//echo $sql."<br>\n";
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}
				elseif ($result->RecordCount() == 1)
				{
					$show_price_plan = $result->FetchRow();
				}
				else
				{
					return false;
				}
				//get expire into first, so we know if there are expire into available.
				$select_html = '';
				$where = '';
				if (geoMaster::is('classifieds') && geoMaster::is('auctions') && is_numeric($show_price_plan['applies_to']))
					$where = ' WHERE `applies_to`='.$show_price_plan['applies_to'];
				elseif (geoMaster::is('auctions'))
					$where = ' WHERE `applies_to`=2';
				elseif (geoMaster::is('classifieds'))
					$where = ' WHERE `applies_to`=1';
					
				$sql = "select name,price_plan_id from ".$this->price_plan_table."$where order by name";
				$result = $this->db->Execute($sql);
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}
				elseif ($result->RecordCount() > 0)
				{
					while ($show = $result->FetchRow())
					{
						if ($show["price_plan_id"] != $show_price_plan["price_plan_id"])
						{
							$select_html .= "<option value=".$show["price_plan_id"];
							if ($show_price_plan["price_plan_expires_into"] == $show["price_plan_id"])
								$select_html .= " selected";
							$select_html .= ">".$show["name"]."</option>\n\t\t";
						}
					}
				}
				$this->body .= "<table cellpadding=3 cellspacing=0 border=0 align=center width=100%><tr>\n\t<td colspan=5 class=group_price_hdr align=center>\n\t<strong>Price Plan: ".$price_plan_name."</strong>\n\t</td>\n</tr>\n</table>\n";
				
				$fixed_price_plan_expiration = $this->get_price_plan_expiration($db,$price_plan_id);
				if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=pricing&page=pricing_edit_plans&f=1&g=".$price_plan_id." method=post>\n";
				$this->body .= "<fieldset id='EditPPExpir'>
				<legend>Expiration Settings</legend><table cellpadding=3 cellspacing=0 border=0 align=center width=100%>\n";
				//$this->title = "Pricing > Price Plans > Edit Expiration";
				$this->description = "Edit a price plan details through this
					admin tool.  Make your changes then click the \"save\" button at the bottom. <br>";

				//expire price plan in to new price plan
				$this->body .= "<tr><td colspan=2><table cellpadding=3 cellspacing=0 border=0 align=center width=100%><tr>
					<td valign=center align=center class=row_color2><span class=large_font>\n\t
					When \"".$price_plan_name."\" expires, <br>replace with this Price Plan:</span><br>\n\t
					<br>";
				
				if (strlen($select_html) > 0){
					//show select box for expire to go into
					$this->body .=  "<select name=d[price_plan_expires_into]>\n\t\t{$select_html}</select>";
				} else {
					//no price planes to expire into, show hidden field and text saying none available.
					$this->body .= "<div style='margin: 5px auto;'><span class=page_note_error>No other price plans available. Must create another price plan for listings to expire into, otherwise this price plan can never expire.</span></div>";
				}
				$this->body .= "</td>\n</tr>\n</table></td></tr>\n";

				
				//expire on fixed date
				$this->body .= "<tr class=row_color1>\n\t<td align=right valign=top>\n\t<input type=radio name=d[expiration_type] ";
				if (strlen($select_html) == 0){
					$this->body .= "disabled=\"disabled\"";
				} elseif ($show_price_plan["expiration_type"] == 1)
					$this->body .= "checked=\"checked\"";
				$this->body .= " value=1>\n\t</td>\n\t";
				$this->body .= "<td class=medium_font><strong>\n\tExpire by: Fixed Date</strong><br>This option allows you to designate a specific date for the Price Plan
					to expire. For instance, you may be running a special promotion pricing until a certain date. If you choose this option, you must specify a new price plan that 
					will automatically be applied when this one expires. Available price plans will be displayed in a dropdown box to the right. This setting affects all users that currently 
					fall under the price plan, regardless of which user group they are in.<br>";

				$this->date_dropdown($fixed_price_plan_expiration,"d[fixed_expire_date]");

				$this->body .= " \n\t</td>\n\t";
				
				$this->body .= "</tr>\n";

				//expire on length of time from registration
				$this->body .= "<tr class=row_color2>\n\t<td align=right valign=top>\n\t<input type=radio name=d[expiration_type] ";
				if (strlen($select_html) == 0){
					$this->body .= "disabled=\"disabled\"";
				} elseif ($show_price_plan["expiration_type"] == 2)
					$this->body .= "checked";
				$this->body .= " value=2>\n\t</td>\n\t";
				$this->body .= "<td class=medium_font><strong>\n\tExpire by: Time Period from Registration Date</strong><br>This option allows you to expire the 
					price plan a fixed number of days from the date that the user registers. The user may take advantage of the price plan's fees during that period.
					period of time from the date of registration.  If you choose this option, you must specify a new price plan that will automatically be applied when
					this one expires. Available price plans will be displayed in a dropdown box to the right. This setting affects all users that currently fall under 
					the price plan, regardless of which user group they are in. This means that users will be moved from this price plan to the new one at different times 
					because they registered at different times.  Using this option may leave various users within the same group on different price plans. Changing the
					date in this option will not affect the current expirations already set for users currently in this price plan.  This
					will only affect future users joining this price plan. <br>";
				$this->subscription_period_dropdown($db,$show_price_plan["expiration_from_registration"],"d[expiration_from_registration]");
				$this->body .= " \n\t</td>\n\t</tr>\n";

				//never expire price plan
				$this->body .= "<tr class=row_color1>\n\t<td align=right valign=top>\n\t<input type=radio name=d[expiration_type] ";
				if (strlen($select_html) == 0 || $show_price_plan["expiration_type"] == 0)
					$this->body .= "checked";
				$this->body .= " value=0>\n\t</td>\n\t";
				$this->body .= "<td class=medium_font><strong>\n\tNever Expire</strong><br>This option allows you to run this price plan indefinitely. If you have previously set an expiration
					for this price plan and you are now changing it to <strong>Never Expire</strong> all expirations currently associated with this price plan will be removed.  This
					will apply regardless of which expiration choice was previously set. In other words, all <strong>Fixed Date</strong> expirations and <strong>Time Period from Registration Date</strong>
					expirations will be removed from the system. However, this will not affect those users who were previously on this price plan and have since expired into another
					price plan. \n\t</td>\n\t</tr>\n\t";
				$this->body .= "</td>\n</tr>\n";
				if (!$this->admin_demo()) $this->body .= "<tr>\n\t<td colspan=2 class=medium_font align=center>\n\t<input type=submit name='auto_save' value=\"Save\"> \n\t</td>\n</tr>\n";
				$this->body .= "</table></fieldset>\n";
				$this->body .= "<table><tr>\n\t<td colspan=2>
				
				<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_edit_plans&g=".$price_plan_id." class='back_to'>
				<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to ".$price_plan_name." Details</a></div>

				<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_price_plans class='back_to'>
				<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to Price Plans Home</a></div>
				
				</td>\n</tr>\n</table>\n";
				
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	} //end of function price_plan_home

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function update_price_plan_expiration($db,$price_plan_id=0,$price_plan_info=0)
	{
		if(!(geoPC::is_ent() || geoPC::is_premier())) {
			return false;
		}
		if (($price_plan_info) && ($price_plan_id))
		{
			//echo $price_plan_info["expiration_type"]." is expiration type in update<br>\n";
			switch ($price_plan_info["expiration_type"])
			{
				case 0:
					//remove expirations
					$sql = "update ".$this->price_plan_table." set
						expiration_type = 0,
						expiration_from_registration = 0,
						price_plan_expires_into = 0
						where price_plan_id = ".$price_plan_id;
					$result = $this->db->Execute($sql);
					//echo $sql."<br>\n";
					if (!$result)
					{
						$this->error_message = $this->internal_error_message;
						return false;
					}
					$sql = "delete from ".$this->classified_expirations_table."
						where type_id = ".$price_plan_id." and type = 2";
					$result = $this->db->Execute($sql);
					//echo $sql."<br>\n";
					if (!$result)
					{
						$this->error_message = $this->internal_error_message;
						return false;
					}
					else
					{
						return true;
					}
					break;

				case 1:
					//fixed date expiration
					//remove current expirations
					$expiration_date = mktime(0,0,0,$price_plan_info["fixed_expire_date"]["month"],$price_plan_info["fixed_expire_date"]["day"],$price_plan_info["fixed_expire_date"]["year"]);
					$sql = "update ".$this->price_plan_table." set
						expiration_type = 1,
						expiration_from_registration = 0,
						price_plan_expires_into = ".$price_plan_info["price_plan_expires_into"]."
						where price_plan_id = ".$price_plan_id;
					$result = $this->db->Execute($sql);
					//echo $sql."<br>\n";
					if (!$result)
					{
						$this->error_message = $this->internal_error_message;
						return false;
					}
					$sql = "select * from ".$this->classified_expirations_table." where type_id = ".$price_plan_id." and type = 2";
					$current_result = $this->db->Execute($sql);
					//echo $sql."<br>\n";
					if (!$current_result)
					{
						$this->error_message = $this->internal_error_message;
						return false;
					}
					elseif ($current_result->RecordCount() > 0)
					{
						//there is either no or one present expiration
						$sql = "update ".$this->classified_expirations_table." set
							expires = ".$expiration_date." 
							where type_id = ".$price_plan_id." and type = 2";
						$result = $this->db->Execute($sql);
						//echo $sql."<br>\n";
						if (!$result)
						{
							//echo $db->ErrorMsg()."<Br>\n";
							$this->error_message = $this->internal_error_message;
							return false;
						}
						else
						{
							$expiration_date = mktime(0,0,0,$price_plan_info["fixed_expire_date"]["month"],$price_plan_info["fixed_expire_date"]["day"],$price_plan_info["fixed_expire_date"]["year"]);
							$sql = "insert into ".$this->classified_expirations_table."
								(type,expires,type_id)
								values
								(2,".$expiration_date.",".$price_plan_id.")";
							$insert_result = $this->db->Execute($sql);
							//echo $sql."<br>\n";
							if (!$insert_result)
							{
								//oops
								//put the expiration returned in the current result back (if there was one)
								//since a new one could not be added
								if ($current_result->RecordCount() == 1)
								{
									$show_current = $current_result->FetchRow();
									$sql = "insert into ".$this->classified_expirations_table."
										(type,expires,type_id)
										values
										(2,".$show_current["expires"].",".$price_plan_id.")";
									$insert_result = $this->db->Execute($sql);
									//echo $sql."<br>\n";
								}
								return false;
							}
							else
							{
								return true;
							}
						}
					}
					else
					{
						$sql = "insert into ".$this->classified_expirations_table."
							(type,expires,type_id)
							values
							(2,".$expiration_date.",".$price_plan_id.")";
						$insert_result = $db->Execute($sql);
						//echo $sql."<br>\n";
						if (!$insert_result)
						{
							//oops
							//put the expiration returned in the current result back (if there was one)
							//since a new one could not be added
							if ($current_result->RecordCount() == 1)
							{
								$show_current = $current_result->FetchRow();
								$sql = "insert into ".$this->classified_expirations_table."
									(type,expires,type_id)
									values
									(2,".$show_current["expires"].",".$price_plan_id.")";
								$insert_result = $db->Execute($sql);
								//echo $sql."<br>\n";
							}
							return false;
						}
						else
						{
							return true;
						}
						return true;
					}
					break;

				case 2:
					//dynamic expiration from registration
					//first remove the fixed expiration
					$sql = "delete from ".$this->classified_expirations_table."
						where type_id = ".$price_plan_id." and type = 2 and user_id = 0";
					$result = $this->db->Execute($sql);
					//echo $sql."<br>\n";
					if (!$result)
					{
						$this->error_message = $this->internal_error_message;
						return false;
					}
					$sql = "update ".$this->price_plan_table." set
						expiration_type = 2,
						expiration_from_registration = ".$price_plan_info["expiration_from_registration"].",
						price_plan_expires_into = ".$price_plan_info["price_plan_expires_into"]."
						where price_plan_id = ".$price_plan_id;
					$result = $this->db->Execute($sql);
					//echo $sql."<br>\n";
					if (!$result)
					{
						$this->error_message = $this->internal_error_message;
						return false;
					}
					else
					{
						return true;
					}
					break;
			} // end of switch

		}
		else
		{
			$this->error_message = $this->internal_error_message;
			return false;
		}
	} //end of function update_price_plan_expiration

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function price_plan_form($db,$price_plan_id=0)
	{
		if ($price_plan_id)
		{
			
			
			
			//edit this price plan form
			if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=pricing&page=pricing_edit_plans&f=5&g=".$price_plan_id." method=post>\n";
			$this->body .= "<fieldset id='EditPPName'>
				<legend>General Settings</legend><table cellpadding=3 cellspacing=0 border=0 align=center width=100%>\n";
			//$this->title = "Pricing > Price Plans > Edit Name and Description";
			
			$this->description = "Edit a price plan details through this admin tool.  Make your changes then click the \"save\" button at the bottom.";
			$sql = "select * from ".$this->price_plan_table." where price_plan_id = ".$price_plan_id;
			$result = $this->db->Execute($sql);
			if (!$result)
			{
				$this->error_message = $this->internal_error_message;
				return false;
			}
			elseif ($result->RecordCount() == 1)
			{
				$show_price_plan = $result->FetchRow();
			}
			else
			{
				return false;
			}
		}
		else
		{
			if(!(geoPC::is_ent() || geoPC::is_premier()))
			{
				$this->body .= "
						<span class=medium_font>
							The ability to create additional Price Plans is not a feature included in this edition of 
							the software. If you determine that you are in need of additional Price Plans, please 
							consider <a target=\"blank\" href=\"http://www.geodesicsolutions.com/products/index.htm\">upgrading</a> your software package.
						</span>";
					
				return true;
			}
			//insert new price plan form
			if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=pricing&page=pricing_new_price_plan method=post>\n";
			$this->body .= "
				<fieldset id='NewPP'>
				<legend>General Settings</legend><table cellpadding=3 cellspacing=0 border=0 align=center width=100%>\n";
			
			
			if (isset($_REQUEST['d']) && is_array($_REQUEST['d'])){
				$show_price_plan = $_REQUEST['d'];
			}
		}

		$this->body .= "<tr class=row_color1>\n\t<td align=right width=45% class=medium_font>\n\t<strong>Price Plan Name: </strong>\n\t</td>\n\t";
		$this->body .= "<td width=\"55%\"><input type=text name=d[name] size=30 maxsize=30 value=\"".$show_price_plan["name"]."\">\n\t</td>\n</tr>\n";
		if(geoMaster::is('classifieds') && geoMaster::is('auctions')) {
			$this->body .= "
				<tr class=row_color2>
					<td align=right width=45% class=medium_font><strong>Applies to: </strong></td>
					<td width=55% class=medium_font>
						<input type=radio name=d[applies_to] value=1".(($show_price_plan["applies_to"] == 1) ? " checked" : "")." class=medium_font checked> Classifieds<br>
						<input type=radio name=d[applies_to] value=2".(($show_price_plan["applies_to"] == 2) ? " checked" : "")." class=medium_font> Auctions
					</td>
				</tr>";
		} elseif(geoMaster::is('auctions')) {
			$this->body .= "<input type=\"hidden\" name=\"d[applies_to]\" value=2>";
		} elseif(geoMaster::is('classifieds')) {
			$this->body .= "<input type=\"hidden\" name=\"d[applies_to]\" value=1>";
		}
		$this->body .= "<tr class=row_color1>\n\t<td align=right valign=top class=medium_font>\n\t<strong>Plan Description: </strong>\n\t</td>\n\t";
		$this->body .= "<td><textarea name=d[description] rows=3 cols=30>".geoString::specialChars($show_price_plan["description"])."</textarea>\n\t</td>\n</tr>\n";

		if (!$price_plan_id) {
			
			$this->body .= "<tr>\n\t<td colspan=2 class=col_hdr>\n\tPrice Plan Type \n\t</td>\n</tr>\n";
			$this->body .= "<tr>\n\t<td colspan=2>\n\t<table width=\"100%\">\n\t";
			$this->body .= "<tr>\n\t<td width=5% align=right valign=top class=medium_font>\n\t<input type=radio name=d[type_of_billing] value=1 checked='checked'>\n\t</td>\n\t";
			$this->body .= "<td width=95% class=medium_font>\n\t<strong>Fee Based</strong><br>This type of price plan charges the user for each listing they
				place on your site. Additionally, you can charge for \"Listing Extras (Featured, Bolding, etc.)\" on a per listing basis. \n\t</td>\n</tr>\n";
			
			$canSubscription = geoAddon::getInstance()->isEnabled('subscription_pricing');
			$this->body .= "<tr><td width=5% align=right valign=top class=medium_font><input type=radio name=d[type_of_billing] value=2 ".(!$canSubscription?" disabled='disabled'":"")."></td>";
			$this->body .= "<td class=medium_font>\n\t<strong>Subscription Based".(!$canSubscription?" - Requires the Subscription Pricing addon":"")."</strong><br>This type of price plan charges the user for a time
				period to place listings.  They can place as many as they want up to the limit you set.  You can set the length of the time period and
				price per time period to charge. You may also charge for \"Listing Extras (Featured, Bolding, etc.)\" on a per listing basis.\n\t</td>\n</tr>\n";
			
			$this->body .= "</table></td>\n</tr>\n";
		}

		if (!$this->admin_demo()) $this->body .= "<tr>\n\t<td colspan=2 align=center>\n\t<input type=submit name='auto_save' value=\"Save\">\n\t</td>\n</tr>\n";
		$this->body .= "</table></fieldset>\n";
		$this->body .= "</form>\n";
		$this->body .= "<table><tr>\n\t<td colspan=2>\n\t
		
		<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_edit_plans&g=".$price_plan_id." class='back_to'>
		<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to Price Plan Details</a></div>	

		</td>\n</tr>\n</table>\n";
		return true;
	} //end of function price_plan_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function delete_price_plan_form($db,$price_plan_id)
	{
		$delete_price_plan_name = $this->get_price_plan_name($db,$price_plan_id);
		if (!$price_plan_id || $price_plan_id == 1 || !$delete_price_plan_name)
			return false;

		$current_price_plan = $this->get_price_plan($db,$price_plan_id);
		$this->title = " ({$delete_price_plan_name})";

		$sql = "SELECT name,price_plan_id,applies_to,type_of_billing FROM ".$this->price_plan_table." WHERE
			price_plan_id != ".$price_plan_id." AND
			applies_to = ".$current_price_plan["applies_to"]." AND
			type_of_billing = ".$current_price_plan["type_of_billing"]." ORDER BY name";
		$price_plan_result = $this->db->Execute($sql);
		//echo $sql."<br>\n";
		if ($price_plan_result === false)
			return false;


		if (!$this->admin_demo())
			$this->body .= "
				<form action=index.php?mc=pricing&page=pricing_delete_plans&c=".$price_plan_id." method=post>\n";
		$this->body .= "
			<fieldset id='DeletePP'><legend>Delete Price Plan</legend><table cellpadding=3 cellspacing=0 border=0 align=center width=100%>\n";

		$sql = "select * from ".$this->user_groups_price_plans_table." where price_plan_id = ".$price_plan_id;
		$user_price_plan_result = $this->db->Execute($sql);
		//echo $sql."<br>\n";
		if ($user_price_plan_result === false)
			return false;
		elseif ($user_price_plan_result->RecordCount() == 0)
		{
			$this->body .= "
				<tr>
					<td>
						<div class='page_note_error'>There are currently no users attached to this price plan.</div>
					</td>
				</tr>";
		}

		$sql = "select * from ".$this->classified_groups_table." where price_plan_id = $price_plan_id OR `auction_price_plan_id` = $price_plan_id";
		
		$group_result = $this->db->Execute($sql);
		//echo $sql."<br>\n";
		if ($group_result === false)
			return false;
		elseif ($group_result->RecordCount() == 0)
		{
			$this->body .= "
				<tr>
					<td>
						<div class='page_note_error'>There are currently no groups attached to this price plan.</div>
					</td>
				</tr>";
		}
		$delete_ok=1;
		if ($group_result->RecordCount()>0 || $user_price_plan_result->RecordCount()>0)
		{
			if ($group_result->RecordCount()>0)
			{
				$this->body .= "
				<tr>
					<td>
						<div class='page_note_error'>There are currently groups attached to this price plan.</div>
					</td>
				</tr>";
			}

			if ($user_price_plan_result->RecordCount()>0)
			{
				$this->body .= "
				<tr>
					<td>
						<div class='page_note_error'>There are currently users attached to this price plan.</div>
					</td>
				</tr>";
			}
			if ($current_price_plan["type_of_billing"]==2)
			{
				//PREVENT DELETION of subsription with users/groups attached.
				$this->body .= "
					<tr>
						<td>
							<div class='page_note_error'>Subscription price plans cannot be deleted with users and/or groups attached.
							To delete, remove all attached groups and/or users.</div>
						</td>
					</tr>";
				$delete_ok=0;
			}
			else
			{
				$this->body .= "
					<tr>
						<td>
							<div class='page_note'>This price plan is currently attached to a group or user(s).
							To delete this price plan you must choose which price plan to replace this one with.
							Every group/user must be have a price plan.  In the dropdown list of price plans below
							choose the price plans you will replace	this one with. Once you have made a choice the
							changes will be made by clicking the \"delete\" button at the bottom.</div>
						</td>
					</tr>
					<tr>
						<td class=medium_font align=center>
							<strong>Choose the price plan you wish to replace the <strong>".$delete_price_plan_name."</strong> price plan with:</strong>
						</td>
					</tr>
					<tr>
						<td align=center class=medium_font>
							Replace <strong>".$delete_price_plan_name."</strong> with:
							<select name=d>";
				while ($show = $price_plan_result->FetchRow())
				{
					if ($show["price_plan_id"] != $price_plan_id)
						$this->body .= "
								<option value=".$show["price_plan_id"].">".$show["name"]."</option>";
				}
				$this->body .= "
							</select>
						</td>
					</tr>";
			}
		}
		if (!$this->admin_demo() && $delete_ok)
		{
			$this->body .= "
				<tr>
					<td class=medium_font_light align=center>
						<input type=submit name='auto_save' value=\"Delete\">
						<input type=hidden name=f value='delete'>
					</td>
				</tr>";
		}
		$this->body .= "
			</table></fieldset>
			</form>";		
		$this->body .= "
			<table>
				<tr>
					<td>
					<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_price_plans class='back_to'>
					<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to Price Plans Home</a></div>	
					</td>
				</tr>
			</table>";

		return true;
	} //end of function delete_price_plan_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function update_price_plan_name_and_description($db,$price_plan_id=0,$price_plan_info=0)
	{
		if (($price_plan_info) && ($price_plan_id))
		{
			if (strlen(trim($price_plan_info["name"])) > 0) 
			{
				$sql = "update ".$this->price_plan_table." set
					name = \"".$price_plan_info["name"]."\",
					description = \"".$price_plan_info["description"]."\",
					applies_to = \"".$price_plan_info["applies_to"]."\"
					where price_plan_id = ".$price_plan_id;
				$result = $this->db->Execute($sql);
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return true;
			}
		}
		else
		{
			$this->error_message = $this->internal_error_message;
			return false;
		}
	} //end of function update_price_plan_name_and_description

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	

	function update_price_plan_specifics($db,$price_plan_id=0,$price_plan_info=0)
	{
		if ($this->debug_price_plan)
		{
			echo "TOP OF UPDATE_PRICE_PLAN_SPECIFICS<BR>\n";
		}		
		if ($price_plan_id && $price_plan_info)
		{
			$sql = "select * from ".$this->price_plan_table." where price_plan_id = ".$price_plan_id;
			$type_result = $this->db->Execute($sql);
			if (!$type_result)
			{
				$this->error_message = $this->internal_error_message;
				return false;
			}
			elseif ($type_result->RecordCount() == 1)
			{
				$show_type = $type_result->FetchRow();
				$sql = "update ".$this->price_plan_table." set ";
				if ($show_type["type_of_billing"] == 1)
				{
					$sql .= "
						charge_per_ad_type = '".$price_plan_info["charge_per_ad_type"]."',
						charge_per_ad = '".$price_plan_info["charge_per_ad"][0].".".sprintf("%02d",$price_plan_info["charge_per_ad"][1])."',
						instant_cash_renewals = '".$price_plan_info["instant_cash_renewals"]."',
						instant_check_renewals = '".$price_plan_info["instant_check_renewals"]."',
						instant_money_order_renewals = '".$price_plan_info["instant_money_order_renewals"]."',
						allow_credits_for_renewals = '".$price_plan_info["allow_credits_for_renewals"]."',
						ad_renewal_cost = '".$price_plan_info["ad_renewal_cost"][0].".".sprintf("%02d",$price_plan_info["ad_renewal_cost"][1])."', ";
				}
				elseif ($show_type["type_of_billing"] == 2)
				{
					$sql .= "
						subscription_billing_period = \"".$price_plan_info["subscription_billing_period"]."\",
						subscription_billing_charge_per_period = \"".$price_plan_info["subscription_billing_charge_per_period"][0].".".sprintf("%02d",$price_plan_info["subscription_billing_charge_per_period"][1])."\", 
						ad_and_subscription_expiration = \"".$price_plan_info["ad_and_subscription_expiration"]."\", ";
					if( is_numeric($price_plan_info["free_subscription_period_upon_registration"]) ) {
						$sql .= "free_subscription_period_upon_registration = \"".$price_plan_info["free_subscription_period_upon_registration"]."\", ";
					}
				}
				else
				{
					return false;
				}

				//build price from form data
				if (geoPC::is_ent()){
					if(strlen(trim($price_plan_info["use_featured_ads"])) >0)
					{
						if(ereg("^[0-9]*$", $price_plan_info["featured_ad_price"][0]) == 0)
							$price_plan_info["featured_ad_price"][0] = 0;
						$sql .= "use_featured_ads = ".$price_plan_info["use_featured_ads"].",
											featured_ad_price = ".$price_plan_info["featured_ad_price"][0].".".sprintf("%02d",$price_plan_info["featured_ad_price"][1]).", ";
					}
					if(strlen(trim($price_plan_info["use_featured_ads_level_2"])) >0)
					{
						if(ereg("^[0-9]*$", $price_plan_info["featured_ad_price_2"][0]) == 0)
							$price_plan_info["featured_ad_price_2"][0] = 0;
						$sql .= "use_featured_ads_level_2 = ".$price_plan_info["use_featured_ads_level_2"].",
											featured_ad_price_2 = ".$price_plan_info["featured_ad_price_2"][0].".".sprintf("%02d",$price_plan_info["featured_ad_price_2"][1]).", ";
					}
					if(strlen(trim($price_plan_info["use_featured_ads_level_3"])) >0)
					{
						if(ereg("^[0-9]*$", $price_plan_info["featured_ad_price_3"][0]) == 0)
							$price_plan_info["featured_ad_price_3"][0] = 0;
						$sql .= "use_featured_ads_level_3 = ".$price_plan_info["use_featured_ads_level_3"].",
											featured_ad_price_3 = ".$price_plan_info["featured_ad_price_3"][0].".".sprintf("%02d",$price_plan_info["featured_ad_price_3"][1]).", ";
					}
					if(strlen(trim($price_plan_info["use_featured_ads_level_4"])) >0)
					{
						if(ereg("^[0-9]*$", $price_plan_info["featured_ad_price_4"][0]) == 0)
							$price_plan_info["featured_ad_price_4"][0] = 0;
						$sql .= "use_featured_ads_level_4 = ".$price_plan_info["use_featured_ads_level_4"].",
											featured_ad_price_4 = ".$price_plan_info["featured_ad_price_4"][0].".".sprintf("%02d",$price_plan_info["featured_ad_price_4"][1]).", ";
					}
					if(strlen(trim($price_plan_info["use_featured_ads_level_5"])) >0)
					{
						if(ereg("^[0-9]*$", $price_plan_info["featured_ad_price_5"][0]) == 0)
							$price_plan_info["featured_ad_price_5"][0] = 0;
						$sql .= "use_featured_ads_level_5 = ".$price_plan_info["use_featured_ads_level_5"].",
											featured_ad_price_5 = ".$price_plan_info["featured_ad_price_5"][0].".".sprintf("%02d",$price_plan_info["featured_ad_price_5"][1]).", ";
					}
				}
				if(strlen(trim($price_plan_info["use_attention_getters"])) >0)
				{
					if(ereg("^[0-9]*$", $price_plan_info["attention_getter_price"][0]) == 0)
						$price_plan_info["attention_getter_price"][0] = 0;
					$sql .= "use_attention_getters = ".$price_plan_info["use_attention_getters"].",
										attention_getter_price = ".$price_plan_info["attention_getter_price"][0].".".sprintf("%02d",$price_plan_info["attention_getter_price"][1]).", ";
				}
				if (geoPC::is_ent()){
					if(strlen(trim($price_plan_info["use_better_placement"])) >0)
					{
						if(ereg("^[0-9]*$", $price_plan_info["better_placement_charge"][0]) == 0)
							$price_plan_info["better_placement_charge"][0] = 0;
						$sql .= "use_better_placement = ".$price_plan_info["use_better_placement"].",
											better_placement_charge = ".$price_plan_info["better_placement_charge"][0].".".sprintf("%02d",$price_plan_info["better_placement_charge"][1]).", ";
					}
					if(strlen(trim($price_plan_info["use_bolding"])) >0)
					{
						if(ereg("^[0-9]*$", $price_plan_info["bolding_price"][0]) == 0)
							$price_plan_info["bolding_price"][0] = 0;
						$sql .= "use_bolding = ".$price_plan_info["use_bolding"].",
											bolding_price = ".$price_plan_info["bolding_price"][0].".".sprintf("%02d",$price_plan_info["bolding_price"][1]).", ";
					}
				}

				if(ereg("^[0-9]*$", $price_plan_info["charge_per_picture"][0]) == 0)
						$price_plan_info["charge_per_picture"][0] = 0;
				
				$sql .=	"charge_per_picture = ".$price_plan_info["charge_per_picture"][0].".".sprintf("%02d",$price_plan_info["charge_per_picture"][1]).",
					max_ads_allowed = ".$price_plan_info["max_ads_allowed"];
				if (geoPC::is_ent()){
					$sql .= ",
					num_free_pics = ".$price_plan_info["num_free_pics"];
				}
				$sql .= ",
					invoice_max = ".$price_plan_info["invoice_max"][0].".".sprintf("%02d",$price_plan_info["invoice_max"][1])."
					where price_plan_id = ".$price_plan_id;

				//echo $sql."<br>";
				$result = $this->db->Execute($sql);
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			$this->error_message = $this->internal_error_message;
			return false;
		}
	} //end of function update_price_plan_specifics

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function get_price_plan_expiration($db,$price_plan_id=0)
	{
		if ($price_plan_id) {
			$sql = "SELECT `expires` FROM ".geoTables::expirations_table." WHERE `type_id` = ? AND `type` = 2";
			$result = $db->GetOne($sql, array($price_plan_id));
			return ($result) ? $result : false;
		}
	}


	function browse_categories($db,$category=0,$price_plan_id=0)
	{
		if(!(geoPC::is_ent() || geoPC::is_premier())) {
			return false;
		}
		if (!$price_plan_id) {
			return false;
		}
			
		$this->body .= '<div class="breadcrumbBorder">';
		$this->body .= '<ul id="breadcrumb">';
		$this->body .= '<li class="current">Currently Viewing</li>';
		$this->body .= '<li><a href="index.php?mc=pricing&page=pricing_category_costs&x='.$price_plan_id.'">Main</a></li>';

		if ($category) {
			$category_tree = $this->get_category_tree($db,$category);
			reset ($this->category_tree_array);
			if ($category_tree) {
				if (is_array($this->category_tree_array)) {
					$i = 0;
					$i = count($this->category_tree_array);
					while ($i > 0) {
						//display all the categories
						$i--;
						$this->body .= '<li>';
						$catName = $this->category_tree_array[$i]["category_name"];
						if ($i != 0) {
							$this->body .= '<a href="index.php?mc=pricing&page=pricing_category_costs&d='.$this->category_tree_array[$i]["category_id"].'&x='.$price_plan_id.'">'.$catName.'</a>';
						} else {
							$this->body .= $catName;
						}
						$this->body .= '</li>';
					}
				}
			}
		}
		$this->body .= '</ul></div><br />';


		$price_plan_name = $this->get_price_plan_name($db,$price_plan_id);

		$this->body .= "<table cellpadding=2 cellspacing=1 border=0 width=100%>\n";
		$this->body .= "<tr>\n\t<td colspan=3 class=group_price_hdr>Price Plan: <strong>".$price_plan_name."</strong></td>\n</tr>\n";
		$this->body .= "</table>\n";
		$this->body .= "<fieldset id='CatSpecPricing'><legend>Category Specific Pricing</legend><table cellpadding=2 cellspacing=1 border=0 width=100%>\n";
		//$this->title = "Pricing > Price Plans > Category Specific Pricing";
		$this->description = "Change, add and remove Category Specific Pricing for this Price Plan.
			A category that does not have Category Specific pricing attached to it will display \"Base Price Plan\".
			To delete Category Specific Pricing simply click the \"delete\" button if it exists. When Category Specific
			Pricing is initiated for a category, that pricing will be inherited to all of that category's subcategories
			as well. Therefore, the \"category pricing status\" column will always display either \"Base Price Plan\"
			or the Parent Category's specific pricing.";


		if ($category)
		{
			$sql_query = "select * from ".$this->classified_categories_table." where category_id = ".$category;
			$result = $this->db->Execute($sql_query);
			//echo $sql_query." is the query<br>\n";
			if (!$result)
			{
				//echo $sql_query." is the query<br>\n";
				$this->error_message = $this->messages[5501];
				return false;
			}
			elseif ($result->RecordCount() == 1)
			{
				$show = $result->FetchRow();
				$parent_id = $show["parent_id"];
				$category_name = $this->get_category_name($db,$category);
				$description = $this->get_category_description($db,$category);
			}
			else
			{
				//category does not exist
				$this->error_message = $this->messages["5500"];
				return false;
			}
		}
		else
		{
			$parent_id = 0;
			$category_name = "Main";
			$description = "home of all main categories";
			$category = 0;
		}

		$sql_query = "select * from ".$this->classified_categories_table." where parent_id = ".$category." order by display_order";
		$result = $this->db->Execute($sql_query);
		//echo $sql_query." is the query<br>\n";
		if (!$result)
		{
			//echo $sql_query." is the query<br>\n";
			$this->error_message = $this->messages[5501];
			return false;
		}
		else
		{

			if ($result->RecordCount() > 0)
			{
				//echo $result->RecordCount()." is the record count<br>\n";
				//display the sub categories of this category
				//					$this->body .= "<tr class=row_color_black>\n\t<td colspan=5 class=medium_font_light>\n\t".$this->messages[3505]." Subcategories of <strong>".$category_name."</strong> \n\t</td>\n</tr>\n";
				$this->body .= "<tr>\n\t<td align=center class=col_hdr_left>\n\t<strong>Category Name</strong>\n\t</td>\n\t";
				$this->body .= "<td align=center class=col_hdr>\n\t<strong>Edit Category Pricing</strong>\n\t</td>\n\t";
				$this->body .= "<td align=center class=col_hdr>\n\t<strong>Category Pricing Status</strong>\n\t</td>\n</tr>";
				$this->row_count = 0;
				while ($show_sub_categories = $result->FetchRow())
				{
					//check for subcategories to the current category
					$subcategory_name = $this->get_category_name($db,$show_sub_categories["category_id"]);
					$sql_query = "select * from ".$this->classified_categories_table." where parent_id = ".$show_sub_categories["category_id"]." order by display_order";
					$test_sub_result = $this->db->Execute($sql_query);
					//echo $sql_query." is the query<br>\n";
					if (!$test_sub_result)
					{
						//echo $sql_query." is the query<br>\n";
						$this->error_message = $this->messages[5501];
						return false;
					}
					if ($test_sub_result->RecordCount() > 0)
					$this->body .= "<tr class=".$this->get_row_color().">\n\t<td>\n\t<a href=index.php?mc=pricing&page=pricing_category_costs&d=".$show_sub_categories["category_id"]."&x=".$price_plan_id."><span class=medium_font>\n\t".$subcategory_name."</span></a></td>\n\t";
					else
					$this->body .= "<tr class=".$this->get_row_color().">\n\t<td class=medium_font>\n\t".
					$subcategory_name."</td>\n\t";

					//see if there is a price plan attached
					$sql_query = "select * from ".$this->classified_price_plans_categories_table."
							where category_id = ".$show_sub_categories["category_id"]." and price_plan_id = ".$price_plan_id;

					$test_current_result = $this->db->Execute($sql_query);
					//echo $sql_query." is the query<br>\n";
					if (!$test_current_result)
					{
						//echo $sql_query." is the query<br>\n";
						$this->error_message = $this->messages[5501];
						return false;
					}
					if ($test_current_result->RecordCount() == 1)
					{
						$current_sub_category = $test_current_result->FetchRow();
						$this->body .= "<td align=center>\n\t<a href=index.php?mc=pricing&page=pricing_category_costs&d=".$show_sub_categories["category_id"]."&e=1&x=".$price_plan_id."&y=".$current_sub_category["category_price_plan_id"]." class='mini_button'>edit</a></td>";
						$this->body .= "<td align=center><a href=index.php?mc=pricing&page=pricing_category_costs&e=2&d=".$show_sub_categories["category_id"]."&x=".$price_plan_id."&y=".$current_sub_category["category_price_plan_id"]."&auto_save=1 class='mini_cancel lightUpLink'>delete</a></td>\n\t";
					}
					else
					{
						$this->body .= "<td align=center><a href=index.php?mc=pricing&page=pricing_category_costs&d=".$show_sub_categories["category_id"]."&e=3&x=".$price_plan_id." class='mini_button'>click to add</a></td>";
						$this->body .= "<td align=center class=medium_font>\n\t";
						//check for parent price plan
						$parent_category_price_plan_id = $this->get_parent_price_plan($db,$show_sub_categories["category_id"],$price_plan_id);
						if ($parent_category_price_plan_id)
						{
							//show category name or delete if it is the current
							$category_name = $this->get_category_name($db,$parent_category_price_plan_id);
							$this->body .= $category_name." Price Plan";
						}
						else
						{
							$this->body .= "Base Price Plan";
						}

						$this->body .= " </td>\n\t";
					}
					$this->body .= "</tr>\n";
					$this->row_count++;
				}
			}
		}

		$this->body .= "</table></fieldset>\n";

		$this->body .= "<table cellpadding=2 cellspacing=1 border=0 width=100%>\n";
		$this->body .= "<tr>\n\t<td colspan=5>\n\t
		
		<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_edit_plans&g=".$price_plan_id." class='back_to'>
		<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to ".$price_plan_name." Details</a></div>
		</td>\n</tr>\n";
		$this->body .= "</table>\n";
		return true;

	} //end of function browse_categories

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function get_parent_price_plan($db,$category_id=0,$price_plan_id=0)
	{
		if (($category_id) && ($price_plan_id))
		{
			$i = 0;
			$category_next = $category_id;
			do
			{
				$sql = "select category_id,parent_id from ".$this->classified_categories_table."
					where category_id = ".$category_next;
				$category_result =  $this->db->Execute($sql);

				//$category = array();

				//echo $sql." is the query<br>\n";
				if (!$category_result)
				{
					//echo $sql." is the query<br>\n";
					$this->error_message = $this->messages[3501];
					return false;
				}
				elseif ($category_result->RecordCount() == 1)
				{
					$show_category = $category_result->FetchRow();
					$sql = "select * from ".$this->classified_price_plans_categories_table."
						where category_id = ".$show_category["category_id"]." and price_plan_id = ".$price_plan_id;
					$price_plan_result =  $this->db->Execute($sql);
					if ($price_plan_result->RecordCount() == 1)
					{
						return $show_category["category_id"];
					}
					$category_next = $show_category["parent_id"];
				}
				else
				{
					//echo "wrong return<Br>\n";
					return false;
				}

			} while ( $show_category["parent_id"] != 0 );
			return 0;
		}
		else
			return false;
	} //end of function get_parent_price_plan

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function category_specific_price_plan_form($db,$category_price_plan_id=0,$category_id=0,$price_plan_id=0)
	{
		if(!(geoPC::is_ent() || geoPC::is_premier()) && $category_price_plan_id != 0 && $category_id != 0) {
			return false;
		}
		trigger_error("DEBUG PRICE_PLAN: TOP OF CATEGORY_SPECIFIC_PRICE_PLAN_FORM");
		trigger_error("DEBUG PRICE_PLAN: $category_price_plan_id is category_price_plan_id");
		trigger_error("DEBUG PRICE_PLAN: $category_id is category_id");
		trigger_error("DEBUG PRICE_PLAN: $price_plan_id is price_plan_id");

		$this->body .= "<SCRIPT language=\"JavaScript1.2\">";
		// Set title and text for tooltip
		$this->body .= "Text[1] = [\"fee options\", \"<strong>Fee-Based Price Plans.</strong> You will have three options: Flat Fee for each Listing; Fee based on the Price the seller enters into the listing's price field; or a fee based upon the duration of the listing. If you choose either the \\\"Price\\\" or \\\"Duration\\\" method be sure to create a bracketing system that will produce the appropriate fees. <br><br><strong>Subscription-Based Price Plans.</strong> There are no base fees associated; only extra fees are available.\"]\n
			Text[2] = [\"instant cash renewals\", \"Choosing \\\"yes\\\" will renew listings instantly when paying for renewals with cash. If \\\"no\\\" the renewals will be placed on hold until the administrator approves them.\"]\n
			Text[3] = [\"instant check renewals\", \"Choosing \\\"yes\\\" will renew listings instantly when paying for renewals with check. If \\\"no\\\" the renewals will be placed on hold until the administrator approves them.\"]\n
			Text[4] = [\"instant money order renewals\", \"Choosing \\\"yes\\\" will renew listings instantly when paying for renewals with money order. If \\\"no\\\" the renewals will be placed on hold until the administrator approves them.\"]\n
			Text[5] = [\"allow credits to be used for listing renewals\", \"Choosing \\\"yes\\\" will allow the user to use credits to renew their listings. The rate will be one credit used per renewal.\"]\n
			";
		if (geoPC::is_ent()){
			$this->body .= "Text[6] = [\"number of free photos\", \"This is the number of pictures (or uploaded files) a seller can post for free before they get charged for each additional picture (or file) at the price indicated in the previous field.\"]\n";
		} else {
			$this->body .= "Text[6] = [\"number of free photos -- Enterprise Only\", \"This is the number of pictures a seller can post for free before they get charged for each additional picture at the price indicated in the previous field.<br /><br /><strong>(Enterprise Only)</strong>\"]\n";
		}
		$this->body .= "Text[7] = [\"all auctions placed are buy now auctions only\", \"All users in this price plan will be restricted to the buy now auctions only which will prevent bidding for any of this user's auctions.\"]\n
			Text[8] = [\"roll final fee charges into future auction placement\", \"Authorize.net (AIM method only) is the only currently supported processing company that allows \\\"backend\\\" credit card processing. This means that from the admin you can charge credit card accounts for the final fees that are accrued at the end of an auction if you choose to charge a final fee. If you charge a final fee and allow the final fees to accrue until the next time the user places an auction you can collect the final fee at that time \\\"before\\\" the seller can place another auction. If you roll the final fees into future transactions it will not matter what payment method you prefer as the final fee will be charged the next time the user tries to place an auction.\"]\n
			Text[9] = [\"charge per listing\", \"Enter a flat listing rate to charge users of this Price Plan for each listing they enter. Or, leave the setting at '0' if you do not want to charge a flat rate for listing placements.<br /><br /><strong>Note:</strong>If you also charge using setting below \\\"<em>charge a percentage of final bid at end of auction</em>\\\", those charges will be on top of the flat charge set here.\"]\n
			Text[10] = [\"Intabill Recurring Price ID\", \"Enter the recurring price id from the Intabill merchant admin tool for the recurring period and price you wish to charge for any specific listing placed by this price plan.\"]\n
			Text[11] = [\"Delayed Start Auctions\", \"Checking yes for delayed auctions will force all auctions under this price plan to not start until the first bid as been received.  Once the first bid has been received the start time is set at the time of the first bid and the end time is set at the proper time from the start time using the duration chosen by the seller.\"]\n
";
		//".$this->show_tooltip(1,1)."

		// Set style for tooltip
		//echo "Style[0] = [\"white\",\"\",\"\",\"\",\"\",,\"black\",\"#ffffcc\",\"\",\"\",\"\",,,,2,\"#b22222\",2,24,0.5,0,2,\"gray\",,2,,13]\n";

		$this->body .= "</script>";
		
		if (!$price_plan_id){
			return false;
		}
		$admin = geoAdmin::getInstance();
		$base_price_plan = $this->get_price_plan($db,$price_plan_id);
		$price_plan_name = $this->get_price_plan_name($db,$price_plan_id);
		$skip_table = 0;
		
		if($category_id) {
			// set category specific variables
			$category_name = $this->get_category_name($db,$category_id);

			$sql = "select * from {$this->classified_categories_table} where category_id = ".$category_id;
			$result = $this->db->Execute($sql);
			if (!$result) {
				$this->error_message = $this->internal_error_message;
				return false;
			} else if ($result->RecordCount() == 1){
				$show_parent_id = $result->FetchRow();
			}

			$parent_name = $this->get_category_name($db,$show_parent_id["parent_id"]);
			if($category_price_plan_id) {
				$sql = "select * from ".$this->classified_price_plans_categories_table." where category_price_plan_id = ".$category_price_plan_id;
				$result = $this->db->Execute($sql);
				if (!$result) {
					$this->error_message = $this->internal_error_message;
					return false;
				} elseif ($result->RecordCount() == 1) {
					$show_price_plan = $result->FetchRow();
					//echo "using category specific<br>\n";
				} else {
					return false;
				}
				if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=pricing&page=pricing_category_costs&e=1&d=".$show_parent_id["parent_id"]."&x=".$price_plan_id."&y=".$category_price_plan_id." id='main_pp_specifics_form' method=post>\n";

				$this->body .= "<table valign=center cellspacing=0 cellpadding=3 width=\"100%\">\n";
				$this->body .= "<tr>\n\t<td class=group_price_hdr>\n\tPrice Plan: ".$price_plan_name."\n\t</td>\n</tr>\n";
				$this->body .= "</table>\n";
				$this->body .= "<table valign=center cellspacing=0 cellpadding=3 width=\"100%\">\n";
				$this->body .= "<tr>\n\t<td colspan=4 class=col_hdr_top align=center>\n\t
					Category: ".$category_name."\n\t</td>\n</tr>\n";
				$this->body .= "</table>\n";					
				$this->body .= "<fieldset id='PPChargeType'><legend>Options</legend><table cellpadding=3 cellspacing=1 border=0 align=center width=100%>\n";
				//$this->title = "Pricing > Price Plans > Category Specific Pricing > Edit";
				

			} else {
				//add new one
				$skip_table = 1;
				//$this->title = "Pricing > Price Plans > Category Specific Pricing";
				$this->description .= "Use the form below to add category specific pricing
					under the <strong>".$price_plan_name."</strong> price plan and attached to the <strong>".$category_name."</strong>.
					The charges set below are used for the current category and its subcategories unless a subcategory overrides these charges
					with its own pricing.<br><strong>Be mindful of the effects certain
					choices could have on people currently on this pricing plan";
				if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=pricing&page=pricing_category_costs&e=3&d=".$category_id."&x=".$price_plan_id." method=post id='main_pp_specifics_form'>\n";

				$this->body .= "<table valign=center cellspacing=0 cellpadding=3 width=\"100%\">\n";
				$this->body .= "<tr>\n\t<td class=group_price_hdr>\n\tPrice Plan: ".$price_plan_name."\n\t</td>\n</tr>\n";
				$this->body .= "</table>\n";
				$this->body .= "<table valign=center cellspacing=0 cellpadding=3 width=\"100%\">\n";
				$this->body .= "<tr>\n\t<td colspan=4 class=col_hdr_top align=center>\n\t
					Category: ".$category_name."\n\t</td>\n</tr>\n";
				$this->body .= "</table>\n";
				$this->body .= "<fieldset id='PPChargeType'><legend>Options</legend><table cellpadding=3 cellspacing=0 border=0 align=center width=100%>\n";

			}
		} else {
			// set global price plan variables
			$sql = "select * from ".$this->price_plan_table." where price_plan_id = ".$price_plan_id;
			$result = $this->db->Execute($sql);
			if (!$result)
			{
				$this->error_message = $this->internal_error_message;
				return false;
			}
			elseif ($result->RecordCount() == 1)
			{
				$show_price_plan = $result->FetchRow();
				//echo "using category specific<br>\n";
			}
			else
				return false;
			if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=pricing&page=pricing_edit_plans&f=3&g=".$price_plan_id." id='main_pp_specifics_form' method=post>\n";
			
			$this->body .= "<table valign=center cellspacing=0 cellpadding=3 width=\"100%\">\n";
			$this->body .= "<tr>\n\t<td class=group_price_hdr>\n\tPrice Plan: ".$price_plan_name."\n\t</td>\n</tr>\n";
			$this->body .= "</table>\n";				
			$this->body .= "<fieldset id='PPChargeType'><legend>Options</legend><table cellpadding=3 cellspacing=0 border=0 align=center width=\"100%\">\n";
			//$this->title .= "Pricing > Price Plans > Edit Cost Specifics";
			//$this->description = "Edit <strong>".$price_plan_name."</strong> price plan detailed charges through this form.  Make your changes then click the \"save\" button at the bottom.
				//<br><strong>Be mindful of the effects certain choices could have on people currently on this Price Plan.";


			$sql = "select * from ".$this->price_plan_table." where price_plan_id = ".$price_plan_id;
		}

		//echo $sql."<br>\n";
		if (!$price_plan_name) {
			return false;
		}
		
		//charge for ----

		if ($base_price_plan["type_of_billing"] == 1)
		{
			//charge for ----
			//charge per listing
			$this->body .= "<tr bgcolor=#D2D2D2>";
			if($base_price_plan["applies_to"] == 1 && geoMaster::is('classifieds')) {

				$this->body .= "<td width=10% valign=top align=center class=medium_font>\n\t";
				$checked = ($show_price_plan["charge_per_ad_type"] == 0)? 'checked="checked"':"";
				$this->body .= "<strong>OPTION 1:</strong><br><input type=radio name=h[charge_per_ad_type] value=0 $checked /></td>";//<td width=90% valign=top class=medium_font>\n\t<strong><br>Charge Same Fee for Each Listing</strong> <br>";
				if ($this->debug_cat_price_plan)
					ECHO '<BR><span class=medium_error_font>$show_price_plan</span>[<font color=green>"charge_per_ad"</font>] = <font color=red>'.$show_price_plan["charge_per_ad"].'</font><br>';
				
				$this->body .= "
				<td class='medium_font'>
					<strong>Charge Same Fee for Each Listing</strong><br />
					<table cellpadding=3 cellspacing=1 border=0 width=100%>
						<tr>
							<td class='col_hdr'>Listing Fee Assessed:</td>
							<td class='col_hdr'>Renewal Fee Assessed:</td>
						</tr>
						<tr>
							<td class='row_color1' style='text-align:center;'>";
				$this->charge_select_box($show_price_plan["charge_per_ad"],"h[charge_per_ad]");
				$this->body .= "
							</td>
							<td class='row_color1' style='text-align:center;'>";
				//listing renewal cost
				$this->charge_select_box($show_price_plan["ad_renewal_cost"],"h[ad_renewal_cost]");
				$this->body .= "
							</td>
						</tr>
					</table><br />";
				
				$this->body .= "<br><br></td>\n\t</tr>\n";
		
				//$this->charge_select_box($show_price_plan["charge_per_ad"],"h[charge_per_ad]");
				//$this->body .= "<br><br></td>\n\t</tr>\n";

				//charge based on price field
				$this->body .= "<tr bgcolor=#FFFFFF><td valign=top align=center class=medium_font>";
				$checked = ($show_price_plan["charge_per_ad_type"] == 1)? 'checked="checked"': '';
				$this->body .= "<strong>OPTION 2:</strong><br><input type=radio name=h[charge_per_ad_type] value=1 $checked /></td><td width=90% valign=top class=medium_font>\n\t<strong><br>Charge Fee Based on \"Price Field\" Seller Enters for Each Listing</strong> <br>";

				if (!$skip_table) {
					$sql = "select * from ".$this->price_plans_increments_table." where price_plan_id = ".$price_plan_id." and category_id = ".$category_id." order by low asc";
					$result = $this->db->Execute($sql);
					if ($this->debug_cat_price_plan)
						echo $sql." is the bracket display query<br>\n";
					if (!$result) {
						return false;
					}
					if ($result->RecordCount() > 0) {
						$this->body .= "<table cellpadding=3 cellspacing=1 border=0 width=100%>\n";
						$this->body .= "<tr>\n\t<td class=col_hdr_left>\n\t<strong>Price Field Low:</strong></td>\n\t
							<td class=col_hdr>&nbsp;</td>\n\t
						<td class=col_hdr_left>\n\t<strong>Price Field High:</strong></td>\n\t
						<td class=col_hdr align=center>\n\t<strong>Listing Fee Assessed:</strong></td>\n
						<td class=col_hdr align=center>\n\t<strong>Renewal Fee Assessed:</strong></td>\n";
						
						$this->body .= "</tr>\n";
						$this->row_count = 0;
						while ($show = $result->FetchRow())
						{
							//highlight_string(print_r($show,1));
							$this->body .= "<tr class=".$this->get_row_color().">\n\t
								<td class=medium_font>\n\t".$show["low"]." ";
							$this->body .= "\n\t</td>\n\t
								<td class=medium_font>\n\tto \n\t</td>\n\t
								<td class=medium_font>\n\t";
							if ($show["high"] == 100000000)
								$this->body .= "and up";
							else
								$this->body .= $show["high"]."\n\t</td>\n\t";
							$this->body .= "<td class=medium_font align=center>\n\t".$show["charge"]."\n\t</td>\n";
							$this->body .= "<td class=medium_font align=center>\n\t".$show["renewal_charge"]."\n\t</td>\n";
							
							$this->body .= "</tr>\n";							
							$this->row_count++;
						} //end of while
						$this->body .= "</table>\n";
					} else {
						$this->body .= "<div align=center><span class=medium_font><br>There are currently no \"Price Field\" increments entered.<br>Please enter at least one if this \"option\" is selected.</span></div><br>";
					}
					$this->body .= "<div align=center><a href=index.php?mc=pricing&page=pricing_increments&e=".$price_plan_id."&f=".$category_id."><span class=medium_font>\n\t<strong>Edit \"Price Field\" Range Increments</strong><br><br></span></a></div>";
				} else {
					$this->body .= "<div align=center><span class=medium_font><br>There are currently no \"Price Field\" increments entered.<br> This capabiity will be available after you \"Save\" this form.</span></div>";
				}
				$this->body .= "</td>\n\t</tr>\n";

				//charge based on length of listing
				$this->body .= "<tr bgcolor=#D2D2D2><td valign=top align=center class=medium_font>";
				$checked = ($show_price_plan["charge_per_ad_type"] == 2)? 'checked="checked"': "";
				$this->body .= "<strong>OPTION 3:</strong><br><input type=radio name=h[charge_per_ad_type] value=2 $checked /></td><td width=90% valign=top class=medium_font>\n\t<strong><br>Charge Fee Based on \"Duration\" Seller Chooses for Each Listing</strong> <br>";

				if (!$skip_table) {
					$sql = "select * from ".$this->classified_price_plan_lengths_table." where price_plan_id = ".$price_plan_id." and category_id = ".$category_id." order by length_of_ad asc";
					$result = $this->db->Execute($sql);
					//echo $sql." is the bracket display query<br>\n";
					if (!$result)
						return false;
					elseif ($result->RecordCount() > 0)
					{
						$this->body .= "<table cellpadding=3 cellspacing=1 border=0 width=100%>\n";
						$this->body .= "<tr>\n\t<td class=col_hdr2 align=center>\n\t<strong>Duration of Listing<br>(displayed)</strong></td>\n\t
							<td class=col_hdr2 align=center>\n\t<strong>Duration of Listing<br>(days)</strong></td>\n\t
								<td class=col_hdr2 align=center>\n\t<strong>Listing Fee Assessed:</strong></td>\n\t
							<td class=col_hdr2 align=center>\n\t<strong>Renewal Fee Assessed:</strong></td>\n";
						
						$this->body .= "</tr>\n";
						$this->row_count = 0;
						while ($show = $result->FetchRow())
						{
							$this->body .= "<tr class=".$this->get_row_color().">\n\t
								<td class=medium_font align=center>\n\t".$show["display_length_of_ad"]."\n\t</td>\n\t
								<td class=medium_font align=center>\n\t".$show["length_of_ad"]."\n\t</td>\n\t
								<td class=medium_font class=medium_font align=center>\n\t".geoString::displayPrice($show["length_charge"])."\n\t</td>
								<td class=medium_font class=medium_font align=center>\n\t".geoString::displayPrice($show["renewal_charge"])."\n\t</td>\n";
							
							$this->body .= "</tr>\n";
							$this->row_count++;
						} //end of while
						$this->body .= "</table>\n";
					}
					else
						$this->body .= "<div align=center><span class=medium_font><br>There are currently no \"Durations\" entered.<br>Please enter at least one if this \"option\" is selected.</span></div><br>";
					$this->body .= "<div align=center><a href=index.php?mc=pricing&page=pricing_lengths&price_plan_id=".$price_plan_id."&category_id=".$category_id."><span class=medium_font>\n\t<strong>Edit Listing \"Duration\" Choices</strong><br><br></span></a></div>";
				}else {
					$this->body .= "<div align=center><span class=medium_font><br>There are currently no \"Duration\" increments entered.<br> This capabiity will be available after you \"Save\" this form.</span></div>";
				}
				$this->body .= "</td>";
			} elseif ($base_price_plan["applies_to"] != 1 && geoMaster::is('auctions')) {

				$this->body .= "<td width=10% valign=top align=center class=medium_font>\n\t";
				$checked=($show_price_plan["charge_per_ad_type"] != 2)? "checked=\"checked\" ": '';
				$this->body .= "<strong>OPTION 1:</strong><br><input type=\"radio\" name=\"h[charge_per_ad_type]\" value=\"0\" $checked /></td>";
				$this->body .= "
				<td class='medium_font'>
					<strong>Charge Same Fee for Each Listing</strong><br />
					<table>
						<tr>
							<td class='col_hdr'>Listing Fee Assessed:</td>
							<td class='col_hdr'>Renewal Fee Assesed:</td>
						</tr>
						<tr>
							<td class='row_color1' style='text-align:center;'>";
				$this->charge_select_box($show_price_plan["charge_per_ad"],"h[charge_per_ad]");
				$this->body .= "
							</td>
							<td class='row_color1' style='text-align:center;'>";
				//listing renewal cost
				$this->charge_select_box($show_price_plan["ad_renewal_cost"],"h[ad_renewal_cost]");
				$this->body .= "
							</td>
						</tr>
					</table><br />
				</td>\n\t</tr>\n";
				
				
				//charge based on length of listing
				$this->body .= "<tr><td valign=top align=center class=\"medium_font\">";
				$this->body .= "\n\t";
				$checked = ($show_price_plan["charge_per_ad_type"] == 2)? "checked=\"checked\" ": "";
				$this->body .= "<strong>OPTION 2:</strong><br><input type=radio name=h[charge_per_ad_type] value=2 $checked /></td><td class=\"medium_font\"><br><strong>Charge Fee Based on \"Duration\" Seller Chooses</strong><br />";

				if (!$skip_table)
				{
					$sql = "select * from ".$this->classified_price_plan_lengths_table." where price_plan_id = ".$price_plan_id." and category_id = ".$category_id." order by length_of_ad asc";
					$result = $this->db->Execute($sql);
					//echo $sql." is the bracket display query<br>\n";
					if (!$result)
						return false;
					elseif ($result->RecordCount() > 0)
					{
						$this->body .= "<table cellpadding=3 cellspacing=1 border=0 width=100%>\n";
						$this->body .= "<tr>\n\t<td class=col_hdr2 align=center>\n\t<strong>Duration of Listing<br>(displayed)</strong></td>\n\t
							<td class=col_hdr2 align=center>\n\t<strong>Duration of Listing<br>(days)</strong></td>\n\t
								<td class=col_hdr2 align=center>\n\t<strong>Listing Fee Assessed:</strong></td>\n\t
							<td class=col_hdr2 align=center>\n\t<strong>Renewal Fee Assessed:</strong></td>\n</tr>\n";
						$this->row_count = 0;
						while ($show = $result->FetchRow())
						{
							$this->body .= "<tr class=".$this->get_row_color().">\n\t
								<td class=medium_font align=center>\n\t".$show["display_length_of_ad"]."\n\t</td>\n\t
								<td class=medium_font align=center>\n\t".$show["length_of_ad"]."\n\t</td>\n\t
								<td class=medium_font class=medium_font align=center>\n\t".geoString::displayPrice($show["length_charge"])."\n\t</td>
								<td class=medium_font class=medium_font align=center>\n\t".geoString::displayPrice($show["renewal_charge"])."\n\t</td>\n</tr>\n";
							$this->row_count++;
						} //end of while
						$this->body .= "</table>\n";
					}
					else
						$this->body .= "<div align=center><span class=medium_font><br>There are currently no \"Duration\" increments entered.<br>Please enter at least one if this \"option\" is selected.</span><br><br></div>";
					$this->body .= "<div align=center><a href=index.php?mc=pricing&page=pricing_lengths&price_plan_id=".$price_plan_id."&category_id=".$category_id."><span class=medium_font>\n\t<strong>Edit Listing \"Duration\" Choices</strong></span></a></div><br>";
				}else {
					$this->body .= "<div align=center><span class=medium_font><br>There are currently no \"Duration\" increments entered.<br> This capabiity will be available after you \"Save\" this form.</span></div>";
				}
				$this->body .= "</td>";
				
				
			} else {
				$this->body = "Error:  Invalid Price Plan";
				return true;
			}
		} elseif ($base_price_plan["type_of_billing"] == 2) {
			//expire ads when subscription expires
			$this->body .= "
					<tr class=".$this->get_row_color().">
						<td colspan=2 align=right valign=top class=medium_font width=\"50%\">
							<strong>Expire Listings when Subscription Expires:</strong>
						</td>
						<td class=medium_font>
							<input type=radio name=h[ad_and_subscription_expiration] value=1
							".(($show_price_plan['ad_and_subscription_expiration']==1) ? " checked" : "").">
							yes<Br>
							<input type=radio name=h[ad_and_subscription_expiration] value=0
							".(($show_price_plan['ad_and_subscription_expiration']==0) ? " checked" : "").">
							no
						</td>
					</tr>";
			$this->row_count++;
		}
		
		$this->body .= "</tr>
				</table>
			</fieldset>";
		
		//show options for buyer-seller transactions
		if ($base_price_plan["applies_to"] == 2 && geoPC::is_ent()){
			$sb_html = geoSellerBuyer::callDisplay('adminDisplayPricePlanSettings',array('price_plan_id'=>$price_plan_id,'category'=>$category_id));
			
			if (strlen($sb_html) > 0){
				//if one of the seller buyer handlers wants to display messages
				$this->body .= "
			<fieldset>
				<legend>Seller Buyer Transactions</legend>
				$sb_html
			</fieldset>";
			}
		}
		
		$this->body .= "<fieldset id='PPFees2'><legend>General Fees and Settings</legend><table valign=center cellspacing=0 cellpadding=3 width=\"100%\">\n";
		
		// max # of listings
		if(!$category_id)
		{
			$this->body .= "
				<tr class=".$this->get_row_color().">
					<td width=50% align=right class=medium_font colspan=2><strong>Max Listings Allowed (per user) at One Time:</strong></td>
					<td colspan=2 class=medium_font>
						<input type='text' size='5' name='h[max_ads_allowed]' value='{$show_price_plan["max_ads_allowed"]}' />
					</td>
				</tr>";$this->row_count++;

			if($base_price_plan["applies_to"] == 2) { 
				if(geoPC::is_ent()) {
					// buy now only
					$this->body .= "
					<tr class=".$this->get_row_color().">
						<td class='medium_font' align='right' colspan=2>
							<strong>Limit All Auctions to \"Buy Now\" Only</strong>:".$this->show_tooltip(7,1)."<br>
							NOTE: ENABLING THIS OPTION WILL DISABLE DUTCH AUCTIONS.
						</td>
						<td colspan='2' class='medium_font'>";
					if($base_price_plan["buy_now_only"])
						$this->body .= "
							<input name='h[buy_now_only]' value='1' type='radio' checked> yes<br>
							<input name='h[buy_now_only]' value='0' type='radio'> no";
					else
						$this->body .= "
							<input name='h[buy_now_only]' value='1' type='radio'> yes<br>
							<input name='h[buy_now_only]' value='0' type='radio' checked> no";
					$this->body .= "
						</td>
					</tr>";
					$this->row_count++;
				}
				$this->body .= "
            <tr class=".$this->get_row_color().">
              <td valign=top align=right class=medium_font colspan=2>
                <strong>Final Fee (charged to seller at auction end) </strong><br>Percentage of Winning Bid and/or Fixed Fee<br/>
					   <div style='float:right;text-align:left'>";
					   //<div style='text-align:left; position:absolute;right:-5px;";
				if ($show_price_plan["charge_percentage_at_auction_end"] == 1)
					$this->body .= "
						<input type=radio name=h[charge_percentage_at_auction_end] value=1 checked> Yes<br>
						<input type=radio name=h[charge_percentage_at_auction_end] value=0> No";
				else
					$this->body .= "
						<input type=radio name=h[charge_percentage_at_auction_end] value=1> Yes<br>
						<input type=radio name=h[charge_percentage_at_auction_end] value=0 checked> No";

				$this->body .= "
				    </div>
					</td>
					<td width=60% valign=top class=medium_font>";

				$sql = "select * from geodesic_auctions_final_fee_price_increments where price_plan_id = ".$price_plan_id." order by low asc";
				$result = $this->db->Execute($sql);
				if (!$result)
					return false;
				elseif ($result->RecordCount() > 0)
				{
					$this->body .= "
						<table cellpadding=3 cellspacing=1 border=0 width=100%>
							<tr>
								<td class=col_hdr align=center>Winning Bid<br>Low</td>
								<td class=col_hdr>&nbsp;</td>
								<td class=col_hdr align=center>Winning Bid<br>High</td>
								<td class=col_hdr align=center>Final Fee<br>Percentage Charge</td>
								<td class=col_hdr align=center>Final Fee<br>Fixed Charge</td>
							</tr>";
					$this->row_count = 0;
					while ($show = $result->FetchRow())
					{
						$this->body .= "
							<tr class=".$this->get_row_color().">
								<td class=medium_font align=center>".$show["low"]."</td>
								<td class=medium_font align=center>to</td>
								<td class=medium_font align=center>";
						if ($show["high"] == 100000000)
							$this->body .= "and up";
						else
							$this->body .= $show["high"];
						$this->body .= "</td>
								<td class=medium_font align=center>".$show["charge"]." %</td>
								<td class=medium_font align=center>".$show["charge_fixed"]."</td>
							</tr>";
						$this->row_count++;
					}
					$this->body .= "
						</table>";
				}
				else
					$this->body .= "
						<div class=page_note_error>There are currently no Final Fee charges entered. If set to \"Yes\" you must specify at least one final fee increment.<br /><br />YOU MAY EXPERIENCE ERRORS IN YOUR SELL PROCESS IF ONE IS NOT ENTERED.</strong></div>";
				if(!$category_id)
					$this->body .= "
						<div align=center>".geoHTML::addButton('edit', "index.php?mc=pricing&page=pricing_final_fees&e=".$price_plan_id."&price_plan=".$price_plan_id)."</div>";
				$this->body .= "
						</td>
					</tr>";
				$this->row_count++;
			}
		}
		$this->body .= "<tr class=".$this->get_row_color().">
					<td align=right colspan=2 class=medium_font><strong>Charge Per Photo (uploaded file): </strong></td>
					<td>";
		$this->charge_select_box($show_price_plan["charge_per_picture"],"h[charge_per_picture]");
		$this->body .= "</td>
				</tr>";
		$this->row_count++;
		
		if (geoPC::is_ent()){
			$this->body .= "<tr class=".$this->get_row_color().">\n\t<td align=\"right\" colspan=\"2\" class=\"medium_font\">\n\t<strong>Number of Free Photos:</strong>".$this->show_tooltip(6,1)."</td>\n\t";
			$this->body .= "<td colspan=2 class=medium_font>\n\t<select name=h[num_free_pics]>\n\t\t";
			$maxImg = geoPlanItem::getPlanItem('images',$price_plan_id,$category_id, true)->get('max_uploads',8);
			for ($i=0;$i <= $maxImg;$i++) {
				$this->body .= "<option ";
				if ($show_price_plan["num_free_pics"] == $i)
					$this->body .= "selected";
				$this->body .= ">".$i."</option>\n\t\t";
			}
			$this->body .= "</select></font></td>\n\t</tr>\n";
			
			$this->row_count++;
		}
		$this->body .= "</table></fieldset>\n";			
		$this->body .= "<fieldset id='PPExtras'><legend>Listing Extras - Fees and Settings</legend><div>
		<div class=page_note>Relies on settings in <a href='?page=listing_extras'>Listing Setup > Listing Extras</a></div>
		<table valign=center cellspacing=0 cellpadding=3 width=\"100%\">\n";
		
		// Get site configuration data
		$sql = "select * from ".$this->site_configuration_table;
		$result = $this->db->Execute($sql);

		if($result)
			$site_config = $result->FetchRow();
		
		for($i = 1; $i < 6; $i++)
		{
			$suffix = ($i == 1) ? "" : "_$i";
			$level_suffix = ($i == 1) ? "" : "_level_$i";
			if (!geoPC::is_ent() && strlen($suffix)>0){
				break;
			}
			elseif($this->db->get_site_setting("use_featured_feature$suffix"))
			{
				// featured listings
				$this->body .= "<tr>\n\t<td colspan=4 class=col_hdr>\n\tFeatured Listings Level $i</td></tr>\n";
				if (geoPC::is_ent()){$this->body .= "<tr class=row_color1>\n\t<td width=50% align=right colspan=2 valign=top class=medium_font>\n\t<strong>Use: </strong></td>\n\t";
				$this->body .= "<td colspan=4 valign=top class=medium_font>\n\t<input type=radio name=h[use_featured_ads$level_suffix] value=1 ";
				if ($show_price_plan["use_featured_ads$level_suffix"] == 1)
					$this->body .= "checked";
				$this->body .= " /> yes<br><input type=radio name=h[use_featured_ads$level_suffix] value=0 ";
				if ($show_price_plan["use_featured_ads$level_suffix"] == 0)
					$this->body .= "checked";
				$this->body .= " /> no\n\t</td>\n</tr>\n";}

				$this->body .= "
					<tr class=row_color2>
						<td align=right colspan=2 class=medium_font><strong>Fee: </strong></td>
						<td>";
				$this->charge_select_box($show_price_plan["featured_ad_price$suffix"],"h[featured_ad_price$suffix]");
				$this->body .= "</td>
					</tr>";
			}
		}

		if($this->db->get_site_setting("use_bolding_feature"))
		{
			// bolding
			$this->body .= "<tr>\n\t<td colspan=4 class=col_hdr>\n\tBolding</td></tr>\n";
			if (geoPC::is_ent()){$this->body .= "<tr class=row_color1>\n\t<td align=right colspan=2 valign=top class=medium_font width=\"50%\">\n\t<strong>Use: </strong></td>\n\t";
			$this->body .= "<td valign=top class=medium_font>\n\t<input type=radio name=h[use_bolding] value=1 ";
			if ($show_price_plan["use_bolding"] == 1)
				$this->body .= "checked";
			$this->body .= "> yes<br><input type=radio name=h[use_bolding] value=0 ";
			if ($show_price_plan["use_bolding"] == 0)
				$this->body .= "checked";
			$this->body .= "> no\n\t</td>\n</tr>\n";}

			$this->body .= "
				<tr class=row_color2>
					<td align=right colspan=2 class=medium_font><strong>Fee: </strong></td>
					<td>";
			$this->charge_select_box($show_price_plan["bolding_price"],"h[bolding_price]");
			$this->body .= "</td>
				</tr>";
		}

		if($this->db->get_site_setting("use_better_placement_feature"))
		{
			// better placement
			$this->body .= "<tr>\n\t<td colspan=4 class=col_hdr>\n\t<strong>Better Placement</strong></td></tr>\n";
			if (geoPC::is_ent()){$this->body .= "<tr class=row_color1>\n\t<td align=right colspan=2 valign=top class=medium_font>\n\t<strong>Use: </strong></td>\n\t";
			$this->body .= "<td valign=top class=medium_font>\n\t<input type=radio name=h[use_better_placement] value=1 ";
			if ($show_price_plan["use_better_placement"] == 1)
				$this->body .= "checked";
			$this->body .= "> yes<br><input type=radio name=h[use_better_placement] value=0 ";
			if ($show_price_plan["use_better_placement"] == 0)
				$this->body .= "checked";
			$this->body .= "> no\n\t</td>\n</tr>\n";}

			$this->body .= "
				<tr class=row_color2>
					<td align=right colspan=2 class=medium_font><strong>Fee: </strong></td>
					<td>";
			$this->charge_select_box($show_price_plan["better_placement_charge"],"h[better_placement_charge]");
			$this->body .= "</td>
				</tr>";
		}
	
		
		if(geoAddon::getUtil('attention_getters') && $this->db->get_site_setting("use_attention_getters"))
		{
			// attention getters
			$this->body .= "<tr>\n\t<td colspan=4 class=col_hdr>\n\t<strong>Attention Getters</strong></td></tr>\n";
			if (geoPC::is_ent()){$this->body .= "<tr class=row_color1>\n\t<td align=right colspan=2 valign=top class=medium_font>\n\t<strong>Use: </strong></td>\n\t";
			$this->body .= "<td valign=top class=medium_font>\n\t<input type=radio name=h[use_attention_getters] value=1 ";
			if ($show_price_plan["use_attention_getters"] == 1)
				$this->body .= "checked";
			$this->body .= "> yes<br><input type=radio name=h[use_attention_getters] value=0 ";
			if ($show_price_plan["use_attention_getters"] == 0)
				$this->body .= "checked";
			$this->body .= "> no\n\t</td>\n</tr>\n";}

			$this->body .= "
				<tr class=row_color2>
					<td align=right colspan=2 class=medium_font><strong>Fee: </strong></td>
					<td>";
			$this->charge_select_box($show_price_plan["attention_getter_price"],"h[attention_getter_price]");
			$this->body .= "</td>
				</tr>";
		}

		$this->body .= '<input type="hidden" name="auto_save" value="yes" />';
		$this->body .= "</table></div></fieldset></form>";
			
			
		//add everything so far to the body, because price plan items function does the same
		$admin->v()->addBody($this->body);
		$this->body = '';
		if ($admin->isAllowed('pricing_items')) {
			//display items as a sub-page of this one
			include_once ADMIN_DIR . 'price_plan_items.php';
			$plan_items_admin = Singleton::getInstance('PricePlanItemManage');
			$plan_items_admin->display_pricing_items($price_plan_id, $category_id);
		}
			
		$footerLinks = "<table valign=center cellspacing=0 cellpadding=3 width=\"100%\">\n";

		if (!$this->admin_demo())
		{
			//the save button for this form has to be outside the form:
			//the actual <form> ends ABOVE the price plan items (because they use their own <forms>
			//but we want the Save button to be at the bottom of the page, so using a button that submits the form via javascript
			$footerLinks .= "
						<tr>
							<td colspan=4 align=center><input type='button' onclick=\"if(configuresOpen>0){ alert('One or more Price Plan Items have unsaved changes. You must Save or Cancel those configurations before submitting this form!'); return false; }else{ $('main_pp_specifics_form').submit(); }\" value=\"Save\"></td>
						</tr>";
		}
		
		if ($category_id)
		{
			$footerLinks .= "
					<tr>
						<td colspan=4>
						<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_category_costs&x=".$price_plan_id."&d=".$show_parent_id["parent_id"]." class='back_to'>
						<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to ".$price_plan_name." &gt; Category Specific Pricing</a></div>
						</td>
					</tr>";
		}
		elseif ($show_price_plan["type_of_billing"] == 1)
		{
			$footerLinks .= "
					<tr>
						<td colspan=2>
							<a href=index.php?mc=pricing&page=pricing_category_costs&x=".$price_plan_id.">
								<span class=medium_font></span>
							</a>
						</td>
					</tr>";
		}

		$footerLinks .= "
					<tr>
						<td colspan=4>
						<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_edit_plans&g=".$price_plan_id." class='back_to'>
						<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to ".$price_plan_name." Details</a></div>
						</td>
					</tr>
					<tr>
						<td colspan=4>
						<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_price_plans class='back_to'>
						<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to Price Plans Home</a></div>
						</td>
					</tr>
				</table>
			";
		
		$admin->v()->addBody($footerLinks);
		
		return true;
	} //end of function category_specific_price_plan_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function delete_increments($db, $price_plan_id=0, $increment_number=0)
	{
		$this->function_name = "delete_increments";

		if(!($price_plan_id) && !($increment_number))
			return false;

		$sql_query = "select * from geodesic_auctions_final_fee_price_increments where price_plan_id = ".$price_plan_id." order by low asc";
		$result = $this->db->Execute($sql_query);
		if (!$result)
			return false;
		elseif($result->RecordCount() < $increment_number)
			return false;
		else
		{
			// Find the final fee element to delete
			// since it goes by indices it finds it from the record number
			$i = 0;
			do
			{
				$i++;
				$show = $result->FetchRow();
			} while($i != $increment_number);

			// Since we found it, delete the correct increment
			$sql = "delete from geodesic_auctions_final_fee_price_increments where price_plan_id = ".$price_plan_id." and low = ".$show->LOW."
				 and high = ".$show->HIGH." and charge = ".$show->CHARGE." and charge_fixed = ".$show->CHARGE_FIXED;
			$result = $this->db->Execute($sql);
			if(!$result)
				return false;
		}

		return true;
	} //end of function delete_increments

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function insert_category_specific_price_plan($db,$category_id=0,$price_plan_id=0,$info=0)
	{
		if ($this->debug_cat_price_plan) {
			ECHO '<BR>$category_id = '.$category_id;
			ECHO '<BR>$price_plan_id = '.$price_plan_id;
			ECHO '<BR>$info = '.highlight_string(print_r($info,1));
		}
		if (($category_id) && ($price_plan_id) && ($info))
		{
			//make sure one does not already exist
			$sql = "select * from ".$this->classified_price_plans_categories_table." where price_plan_id = ".$price_plan_id." and category_id = ".$category_id;
			$result = $this->db->Execute($sql);
			if (!$result)
			{
				$this->error_message = $this->internal_error_message;
				return false;
			}
			elseif ($result->RecordCount() == 0)
			{
				//none there...insert
				$this->first_sql_query = "insert into ".$this->classified_price_plans_categories_table."
					(category_id,price_plan_id,charge_per_ad_type,charge_per_ad,charge_per_picture,ad_renewal_cost";
				$this->second_sql_query = ") values	(
					".$category_id.",
					".$price_plan_id.",
					".$info["charge_per_ad_type"].",
					".$info["charge_per_ad"][0].".".sprintf("%02d",$info["charge_per_ad"][1]).",
					".$info["charge_per_picture"][0].".".sprintf("%02d",$info["charge_per_picture"][1]).",
					".$info["ad_renewal_cost"][0].".".sprintf("%02d",$info["ad_renewal_cost"][1]);
				if (!geoPC::is_ent() || strlen(trim($info["use_featured_ads"])) > 0)
				{
					if (geoPC::is_ent()){$this->first_sql_query .= ",use_featured_ads";
					$this->second_sql_query .= ",".$info["use_featured_ads"];}
					$this->first_sql_query .= ",featured_ad_price";
					$this->second_sql_query .= ",".$info["featured_ad_price"][0].".".sprintf("%02d",$info["featured_ad_price"][1]);
				}
				if (geoPC::is_ent()){ if (strlen(trim($info["use_featured_ads_level_2"])) > 0)
				{
					$this->first_sql_query .= ",use_featured_ads_level_2";
					$this->second_sql_query .= ",".$info["use_featured_ads_level_2"];
					$this->first_sql_query .= ",featured_ad_price_2";
					$this->second_sql_query .= ",".$info["featured_ad_price_level_2"][0].".".sprintf("%02d",$info["featured_ad_price_level_2"][1]);
				}

				if (strlen(trim($info["use_featured_ads_level_3"])) > 0)
				{
					$this->first_sql_query .= ",use_featured_ads_level_3";
					$this->second_sql_query .= ",".$info["use_featured_ads_level_3"];
					$this->first_sql_query .= ",featured_ad_price_3";
					$this->second_sql_query .= ",".$info["featured_ad_price_level_3"][0].".".sprintf("%02d",$info["featured_ad_price_level_3"][1]);
				}

				if (strlen(trim($info["use_featured_ads_level_4"])) > 0)
				{
					$this->first_sql_query .= ",use_featured_ads_level_4";
					$this->second_sql_query .= ",".$info["use_featured_ads_level_4"];
					$this->first_sql_query .= ",featured_ad_price_4";
					$this->second_sql_query .= ",".$info["featured_ad_price_level_4"][0].".".sprintf("%02d",$info["featured_ad_price_level_4"][1]);
				}

				if (strlen(trim($info["use_featured_ads_level_5"])) > 0)
				{
					$this->first_sql_query .= ",use_featured_ads_level_5";
					$this->second_sql_query .= ",".$info["use_featured_ads_level_5"];
					$this->first_sql_query .= ",featured_ad_price_5";
					$this->second_sql_query .= ",".$info["featured_ad_price_level_5"][0].".".sprintf("%02d",$info["featured_ad_price_level_5"][1]);
				}}
				if (!geoPC::is_ent() || strlen(trim($info["use_bolding"])) > 0)
				{
					if (geoPC::is_ent()){$this->first_sql_query .= ",use_bolding";
					$this->second_sql_query .= ",".$info["use_bolding"];}
					$this->first_sql_query .= ",bolding_price";
					$this->second_sql_query .= ",".$info["bolding_price"][0].".".sprintf("%02d",$info["bolding_price"][1]);
				}

				if (!geoPC::is_ent() || strlen(trim($info["use_better_placement"])) > 0)
				{
					if (geoPC::is_ent()){$this->first_sql_query .= ",use_better_placement";
					$this->second_sql_query .= ",".$info["use_better_placement"];}
					$this->first_sql_query .= ",better_placement_charge";
					$this->second_sql_query .= ",".$info["better_placement_charge"][0].".".sprintf("%02d",$info["better_placement_charge"][1]);
				}
				

				if (!geoPC::is_ent() || strlen(trim($info["use_attention_getters"])) > 0)
				{
					if (geoPC::is_ent()){$this->first_sql_query .= ",use_attention_getters";
					$this->second_sql_query .= ",".$info["use_attention_getters"];}
					$this->first_sql_query .= ",attention_getter_price";
					$this->second_sql_query .= ",".$info["attention_getter_price"][0].".".sprintf("%02d",$info["attention_getter_price"][1]);
				}
				
				if (geoPC::is_ent()){$this->first_sql_query .= ",num_free_pics";
				$this->second_sql_query .= ",".$info["num_free_pics"];}
				
				$sql = $this->first_sql_query.$this->second_sql_query.")";
				if ($this->debug_cat_price_plan)
					ECHO '<br><br>'.$sql."<br><br>\n";
				$result = $this->db->Execute($sql);

				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	} //end of function insert_category_specific_price_plan

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function update_category_specific_price_plan($db,$category_price_plan_id=0,$info=0, $category_specific = 1)
	{
		if ($category_specific && !(geoPC::is_ent() || geoPC::is_premier())) {
			return false;
		}
		if ($this->debug_price_plan)
		{
			echo "TOP OF UPDATE_CATEGORY_SPECIFIC_PRICE_PLAN<BR>\n";
			echo $category_price_plan_id." is \$category_price_plan_id<br>\n";
			echo $info." is \$info<br>\n";
		}
		
		if ($category_price_plan_id && $info)
		{
			if($category_specific)
			{
				$table = "geodesic_classifieds_price_plans_categories";
				$sql = "select * from geodesic_classifieds_price_plans_categories as cat,{$this->price_plan_table} as gen where cat.category_price_plan_id = $category_price_plan_id and cat.price_plan_id = gen.price_plan_id";
			}
			else
			{
				$table = $this->price_plan_table;
				$sql = "select * from ".$this->price_plan_table." where price_plan_id = $category_price_plan_id";				
			}
			$type_result = $this->db->Execute($sql);
			if (!$type_result) {
				$this->error_message = $this->internal_error_message;
				IF ($this->debug_cat_price_plan)
					ECHO "<BR>ERROR LINE - ".__LINE__;
				return false;
			}
			if($type_result->RecordCount() == 1)
				$show_type = $type_result->FetchRow();
			else {
				IF ($this->debug_cat_price_plan)
					ECHO "<BR>ERROR LINE - ".__LINE__;
				return false;
			}

			if(geoPC::is_ent()) {
				//seller/buyer stuff
				$price_id = $show_type['price_plan_id'];
				geoSellerBuyer::callUpdate('adminUpdatePricePlanSettings',array('price_plan_id'=>$price_id, 'category'=>$show_type['category_id']));
			}

			$sql = "update $table set ";
			if(isset($show_type["type_of_billing"]) && $show_type["type_of_billing"] == 1)
			{
				

				$sql .= "
					charge_per_ad_type = ".$info["charge_per_ad_type"].",
					charge_per_ad = ".$info["charge_per_ad"][0].".".sprintf("%02d",$info["charge_per_ad"][1]).",
					ad_renewal_cost = ".$info["ad_renewal_cost"][0].".".sprintf("%02d",$info["ad_renewal_cost"][1]).", ";
			}
			else
			{
				$sql .= "
					subscription_billing_period = \"".$info["subscription_billing_period"]."\",
					subscription_billing_charge_per_period = \"".$info["subscription_billing_charge_per_period"][0].".".sprintf("%02d",$info["subscription_billing_charge_per_period"][1])."\", 
					ad_and_subscription_expiration = \"".$info["ad_and_subscription_expiration"]."\", ";
				if( is_numeric($info["free_subscription_period_upon_registration"]) ) {
					$sql .= "free_subscription_period_upon_registration = \"".$info["free_subscription_period_upon_registration"]."\", ";
				}
			}
			
			
			if(!$category_specific)
			{
				$sql .= "
					max_ads_allowed = '".intval($info["max_ads_allowed"])."',
					charge_percentage_at_auction_end = '".$info["charge_percentage_at_auction_end"]."', ";
			}

			if (geoPC::is_ent()){
					$sql .= "
					num_free_pics = '".$info["num_free_pics"]."',";
				}
			
			$sql .= "
					charge_per_picture = \"".$info["charge_per_picture"][0].".".sprintf("%02d",$info["charge_per_picture"][1])."\", ";

			if($show_type["applies_to"] == 2 && !$category_specific)
				$sql .= "buy_now_only = ".((isset($info["buy_now_only"])&&$info['buy_now_only'])? $info["buy_now_only"] : 0).", ";

			// featured listings
			$m_lev = (geoPC::is_ent())? 6 : 2;
			for($i = 1; $i < $m_lev; $i++)
			{
				$suffix = ($i == 1) ? "" : "_$i";
				$level_suffix = ($i == 1) ? "" : "_level_$i";
				
				if ($this->debug_price_plan)
				{
					echo $info["use_featured_ads$level_suffix"]." is \$info[use_featured_ads".$level_suffix."]<Br>\n";
					echo $info["use_featured_ads$level"]." is \$info[use_featured_ads".$level."]<Br>\n";
				}
				if (strlen(trim($info["use_featured_ads$level_suffix"]) != 0))
					$featured_value = $info["use_featured_ads$level_suffix"];
				elseif (strlen(trim($info["use_featured_ads$level"])) != 0)
					$featured_value = $info["use_featured_ads$level"];
				else 	$featured_value = 0;

				$sql .=	"use_featured_ads$level_suffix = ".(($info["use_featured_ads$level_suffix"]) ? $info["use_featured_ads$level_suffix"] : "0").", ";
				$sql .= "featured_ad_price$suffix = ".$info["featured_ad_price$suffix"][0].".".sprintf("%02d",$info["featured_ad_price$suffix"][1]).", ";
			}
			
			$sql .= "use_bolding = ".(($info["use_bolding"]) ? $info["use_bolding"] : "0").", ";
			$sql .= "bolding_price = ".$info["bolding_price"][0].".".sprintf("%02d",$info["bolding_price"][1]).", ";
			
			$sql .= "use_better_placement = ".(($info["use_better_placement"]) ? $info["use_better_placement"] : "0").", ";
			$sql .= "better_placement_charge = ".$info["better_placement_charge"][0].".".sprintf("%02d",$info["better_placement_charge"][1]).", ";			
			
			$sql .=	"use_attention_getters = ".(($info["use_attention_getters"]) ? $info["use_attention_getters"] : "0").", ";
			$sql .= "attention_getter_price = ".$info["attention_getter_price"][0].".".sprintf("%02d",$info["attention_getter_price"][1]).", ";

			$sql = rtrim($sql, ", ");
     		$sql .= " where ".(($category_specific) ? "category_price_plan_id" : "price_plan_id")." = ".$category_price_plan_id;

     		$result = $this->db->Execute($sql);
			IF ($this->debug_cat_price_plan) {
					ECHO '<BR>Query - '.$sql;
				}
			if (!$result)
			{
				$this->error_message = $this->internal_error_message;
				IF ($this->debug_cat_price_plan) {
					ECHO "<BR>ERROR LINE - ".__LINE__;
					ECHO '<BR>Query - <pre>'.$sql.'<br />Error: '.$this->db->ErrorMsg().'</pre><br />';
				}
				return false;
			}
			else
			{
				if(!$category_specific && geoPC::is_ent() && ($show_type["applies_to"] == 2))
					if(!$this->set_final_fees_to_attached($category_price_plan_id,$info["charge_percentage_at_auction_end"],0))
						return false;
				return true;
			}
		}
		else {
			IF ($this->debug_cat_price_plan)
				ECHO "<BR>ERROR LINE - ".__LINE__;
			return false;
		}

	} //end of function update_category_specific_price_plan

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function set_final_fees_to_attached($price_plan_id,$charge_percentage_at_auction_end,$roll_final_fee_into_future)
	/*
	 * This function removes conflicts of charge_percentage_at_auction_end and roll_final_fee_into_future
	 * when multiple priceplans are attached to the same usergroup 
	 */
	{
		//$roll_final_fees_into_future will always be off now since it is not used in system
		$roll_final_fee_into_future = 0;
		//first, find out what usergroup this plan is attached to, if any
		$sql = "select group_id from ".$this->db->geoTables->attached_price_plans." where price_plan_id = ".$price_plan_id;
		$result = $this->db->Execute($sql);
		if(!$result)
		{
			//echo "error in query: ".$sql."<br />";
			//echo "mysql says: ".$this->db->ErrorMsg()."<br />";
			return false;
		}
		$line = $result->FetchRow();
		if($result->RecordCount() < 1)
		{
			//no result, so exit this
			return true;
		}
		$group_id = $line["group_id"];
				
		//now get all other priceplans attached to that usergroup
		$sql = "select * from ".$this->db->geoTables->attached_price_plans." where group_id = ".$group_id;
		$result = $this->db->Execute($sql);
		
		if(!$result)
		{
			//echo "error in query: ".$sql."<br />";
			//echo "mysql says: ".$this->db->ErrorMsg()."<br />";
			return false;
		}
		if($result->RecordCount() < 1)
		{
			//no other attached priceplans, so exit this
			return true;
		}
		
		
		$plans = array();
		while($line = $result->FetchRow())
			$plans[] = $line['price_plan_id'];
			
		//update other price plans with new CPAAE and RFFIF values
		foreach($plans as $plan)
		{
			$sql = "update ".$this->price_plan_table." set charge_percentage_at_auction_end = ".$charge_percentage_at_auction_end.
			", roll_final_fee_into_future = ".$roll_final_fee_into_future." where price_plan_id = ".$plan;
			$result = $this->db->Execute($sql);
			if(!$result)
			{
				//echo "error in query: ".$sql."<br />";
				//echo "mysql says: ".$this->db->ErrorMsg()."<br />";
				return false;
			}
		}
		//update complete, so return
		return true;
	}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function delete_category_specific_price_plan($db,$category_price_plan_id=0,$price_plan_id=0,$category_id=0)
	{
		if ($this->debug_cat_price_plan) {
			ECHO '<BR><BR>$category_price_plan_id = '.$category_price_plan_id;
			ECHO '<BR>$price_plan_id = '.$price_plan_id;
			ECHO '<BR>$category_id = '.$category_id;
		}

		if (!$category_price_plan_id)
			return false;

		$sql = "DELETE FROM ".$this->classified_price_plans_categories_table."
			WHERE category_price_plan_id = ".$category_price_plan_id;
		$result = $this->db->Execute($sql);
		if (!$result) {
			$this->error_message = $this->internal_error_message;
			return false;
		}
		$sql = "DELETE FROM ".$this->price_plans_increments_table."
			WHERE price_plan_id = ".$price_plan_id."
			AND category_id = ".$category_id;
		if ($this->debug_cat_price_plan)
			echo $sql."<br>\n";
		$result = $this->db->Execute($sql);
		if (!$result) {
			$this->error_message = $this->internal_error_message;
			return false;
		}
		$sql = "DELETE FROM ".$this->classified_price_plan_lengths_table."
			WHERE price_plan_id = ".$price_plan_id."
			AND category_id = ".$category_id;
		if ($this->debug_cat_price_plan)
			echo $sql."<br>\n";
		$result = $this->db->Execute($sql);
		if (!$result) {
			$this->error_message = $this->internal_error_message;
			return false;
		}
		
		//delete plan items for this category and price plan ID
		geoPlanItem::deletePlanItems($price_plan_id,$category_id);
		return true;
	} //end of function delete_category_specific_price_plan

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function display_subscription_periods($db,$price_plan_id=0)
	{
		if(!(geoPC::is_ent() || geoPC::is_premier())) {
			return false;
		}
		//echo $price_plan_id." is price plan id<br>";
		$price_plan_name = $this->get_price_plan_name($db,$price_plan_id);
		//echo $price_plan_name." is price plan name<br>";
		if (!$price_plan_id || !$price_plan_name)
			return false;
		$this->body .= "<table cellpadding=3 cellspacing=1 border=0 align=center width=100%>\n
		<tr>\n\t<td colspan=5 class=group_price_hdr align=center>\n\t<strong>Price Plan: ".$price_plan_name."</strong>\n\t</td>\n</tr>\n</table>\n";
		$this->body .= "<fieldset id='PPSubPeriods'><legend>Subscription Periods</legend><table cellpadding=3 cellspacing=1 border=0 align=center width=100%>\n";
		//$this->title = "Pricing > Price Plans > Edit Subscription Periods";
		$this->description = "Below are the current
			subscription choices offered to users on this price plan.  Delete choices by clicking the delete link next to the
			appropriate choice.  Add new choices by clicking the add new choice link at the bottom.";
		$sql = "select * from ".$this->classified_subscription_choices_table." where price_plan_id = ".$price_plan_id." order by value";
		//echo $sql." is query1<br>";
		$result = $this->db->Execute($sql);
		if (!$result)
		{
			$this->error_message = $this->internal_error_message;
			return false;
		}
		elseif ($result->RecordCount() > 0)
		{
			$this->body .= "<tr class=row_color_black>\n\t
				<td class=col_hdr>\n\t<strong>Length of Period</strong> \n\t</td>\n\t
				<td class=col_hdr>\n\t<strong>Length in Days</strong>\n\t</td>\n\t
				<td class=col_hdr>\n\t<strong>Cost</strong> \n\t</td>\n\t
				<td class=col_hdr>\n\t&nbsp; \n\t</td>\n\t
				</tr>\n";
			$this->row_count = 0;
			while ($show_subscriptions = $result->FetchRow())
			{
				$delete_button = geoHTML::addButton('Delete','index.php?mc=pricing&page=pricing_edit_plans&f=6&g='.$price_plan_id.'&h='.$show_subscriptions["period_id"].'&auto_save=1', false, '', 'lightUpLink mini_cancel');
				$this->body .= "<tr class=".$this->get_row_color().">\n\t
					<td class=medium_font align=center>\n\t".$show_subscriptions["display_value"]." \n\t</td>\n\t
					<td class=medium_font align=center>\n\t".$show_subscriptions["value"]." days\n\t</td>\n\t
					<td class=medium_font align=center>\n\t".sprintf("%0.2f",$show_subscriptions["amount"])." \n\t</td>\n\t
					<td align=center width=100>".$delete_button."</td>\n\t
					</tr>\n";
				$this->row_count++;
			}
		}
		else
		{
			//none...allow to add
			$this->body .= "<tr>\n\t<td colspan=3>\n\t<div class='page_note_error'>There are currently no Subscription Periods for this Price Plan.</div> </td>\n</tr>\n";
		}
		$this->body .= "</table></fieldset\n";
		$this->body .= "</form>\n";
		$this->body .= "
			<div style='text-align:center;'><a href=index.php?mc=pricing&page=pricing_edit_plans&f=7&g={$price_plan_id} class='mini_button'>
				Add New Subscription Period Choice</a></div><br />

				<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_edit_plans&f=3&g={$price_plan_id} class='back_to'>
				<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to Cost Specifics</a></div>
			";
		return true;
	} //end of function display_subscription_periods

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function subscription_period_form($db,$price_plan_id=0)
	{
		if(!(geoPC::is_ent() || geoPC::is_premier()))
		{
			return false;
		}
		$price_plan_name = $this->get_price_plan_name($db,$price_plan_id);
		if (($price_plan_id) && ($price_plan_name))
		{
			if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=pricing&page=pricing_edit_plans&f=7&g=".$price_plan_id." method=post>\n";
			$this->body .= "<table cellpadding=3 cellspacing=0 border=0 align=center width=100%>\n<tr>\n\t<td colspan=5 class=group_price_hdr align=center>\n\t<strong>Price Plan: ".$price_plan_name."</strong>\n\t</td>\n</tr>\n</table>\n";
			$this->body .= "<fieldset id='PPNewSubPer'><legend>Add New Subscription Period</legend><table cellpadding=3 cellspacing=0 border=0 align=center width=100%>\n";
			//$this->title = "Pricing > Price Plans > Edit Subscription Periods > New Subscription Choice";
			$this->description = "Use this form to enter a new Subscription Period for this Price Plan.
				Enter the specifics below and then click the \"enter choice\" button at the bottom.";
			$this->body .= "<tr class=row_color1>
				<td align=right width=50% class=medium_font>\n\t<strong>Display Value: </strong>\n\t</td>\n\t
				<td class=medium_font>\n\t<input type=text name=d[display_value]> ie 30 days \n\t</td>\n\t
				</tr>\n";
			$this->body .= "
			<tr class=row_color2>
				<td align='right' width='50%' class='medium_font'>
					<strong>Length of Subscription Period: </strong>
				</td>
				<td class=medium_font>
					<label><input name='d[value]' type='text' size='5'> Days</label>
				</td>
			</tr>
			<tr class='row_color1'>
				<td align='right' width='50%' class='medium_font'>
					<strong>Amount to Charge for Period: </strong>
				</td>
				<td class='medium_font'>
					<label>".$this->db->get_site_setting('precurrency')."<input name='d[period_cost]' type='text' value='1.00' size='10' />".$this->db->get_site_setting('postcurrency')."</label>
				</td>
			</tr>";
				
			
			$this->body .= "</td>\n</tr>\n";
			if (!$this->admin_demo()) $this->body .= "<tr>\n\t<td colspan=2 align=center><input type=submit name='auto_save' value=\"Save\">\n\t</td>\n</tr>\n";
			$this->body .= "</table></fieldset>\n";
			$this->body .= "</form>\n";
			$this->body .= "
			<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_edit_plans&f=8&g={$price_plan_id} class='back_to'>
			<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to Subscription Periods</a></div>";
			return true;
		}
		else
		{
			return false;
		}
	} //end of subscription_period_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function insert_subscription_period($db,$price_plan_id=0,$subscription_info=0)
	{
		if(!(geoPC::is_ent() || geoPC::is_premier())) {
			return false;
		}
		if (($price_plan_id) && ($subscription_info))
		{
			$price = floatval($subscription_info['period_cost']);
			$days = intval($subscription_info['value']);
			$sql = "insert into ".$this->classified_subscription_choices_table."
				(price_plan_id,display_value,value,amount)
				values
				(".$price_plan_id.",\"".$subscription_info["display_value"]."\",$days,$price)";
			$insert_result = $this->db->Execute($sql);
			//echo $sql."<br>\n";
			if (!$insert_result)
			{
				$this->error_message = $this->internal_error_message;
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	} //end of function insert_subscription_period

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function delete_subscription_period($db,$subscription_period_id=0)
	{
		if ($subscription_period_id)
		{
			$sql = "delete from ".$this->classified_subscription_choices_table." where period_id = ".$subscription_period_id;
			$delete_result = $this->db->Execute($sql);
			//echo $sql."<br>\n";
			if (!$delete_result)
			{
				$this->error_message = $this->internal_error_message;
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	} //end of function insert_subscription_period

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function display_credit_periods($db,$price_plan_id=0)
	{
		//echo "hello from display<br>\n";
		$price_plan_name = $this->get_price_plan_name($db,$price_plan_id);
		if (($price_plan_id) && ($price_plan_name))
		{
			$this->body .= "<table cellpadding=3 cellspacing=0 border=0 align=center width=100%>\n";
			//$this->title = "Price Plan Credit Choices Management";
			$this->description = "Below are the current
				choice choices offered to users on this price plan.  Delete choices by clicking the delete link next to the
				appropriate choice.  Add new choices by clicking the add new choice link at the bottom.";

			$sql = "select * from ".$this->credit_choices." where price_plan_id = ".$price_plan_id." order by value";
			$result = $this->db->Execute($sql);

			if (!$result)
			{
				$this->error_message = $this->internal_error_message;
				return false;
			}
			elseif ($result->RecordCount() > 0)
			{
				$this->body .= "<tr class=row_color_black>\n\t
					<td class=medium_font_light>\n\tnumber of credits \n\t</td>\n\t
					<td class=medium_font_light>\n\tcost \n\t</td>\n\t
					<td class=medium_font_light>\n\t&nbsp; \n\t</td>\n\t
					</tr>\n";
				$this->row_count = 0;
				while ($show_credits = $result->FetchRow())
				{
					$this->body .= "<tr class=".$this->get_row_color().">\n\t
						<td class=medium_font>\n\t".$show_credits["display_value"]." \n\t</td>\n\t
						<td class=medium_font>\n\t".sprintf("%0.2f",$show_credits["amount"])." \n\t</td>\n\t
						<td>\n\t<a href=index.php?mc=pricing&page=pricing_edit_plans&f=9&g=".$price_plan_id."&h=".$show_credits["credit_id"]."><span class=medium_font>\n\tdelete</span></a>\n\t</td>\n\t
						</tr>\n";
					$this->row_count++;
				}
			}
			else
			{
				//none...allow to add
				$this->body .= "<tr>\n\t<td colspan=3 class=medium_font>\n\tthere are no credit plans to choose from </td>\n</tr>\n";
			}
			$this->body .= "<tr>\n\t<td align=center><a href=index.php?mc=pricing&page=pricing_edit_plans&f=10&g=".$price_plan_id."><span class=medium_font>\n\tadd a new credit choice</span></a></td>\n</tr>\n";
			$this->body .= "</table>\n";
			$this->body .= "</form>\n";
			return true;
		}
		else
		{
			return false;
		}

	} //end of function display_credit_periods

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function credit_period_form($db,$price_plan_id=0)
	{
		$price_plan_name = $this->get_price_plan_name($db,$price_plan_id);
		if (($price_plan_id) && ($price_plan_name))
		{
			if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=pricing&page=pricing_edit_plans&f=10&g=".$price_plan_id." method=post>\n";
			$this->body .= "<table cellpadding=3 cellspacing=0 border=0 align=center width=100%>\n";
			//$this->title = "Pricing > Price Plans > Add New Credit Choice";
			$this->description = "Use this form to enter a new credit choice.
				Enter the specifics below and then click the \"enter choice\" button at the bottom.";

			$this->body .= "<tr class=row_color1>
				<td align=right width=50% class=medium_font>\n\tdisplay value: \n\t</td>\n\t
				<td class=medium_font>\n\t<input type=text name=d[display_value]> ie 30 days \n\t</td>\n\t
				</tr>\n";
			$this->body .= "<tr class=row_color2>
				<td align=right width=50% class=medium_font>\n\tnumber of credits: \n\t</td>\n\t
				<td class=medium_font>\n\t<select name=d[value]>";
			for ($i=1;$i < 1826;$i++)
			{
				$this->body .= "<option>".$i."</option>\n\t";
			}
			$this->body .= "</select> \n\t</td>\n</tr>\n";

			$this->body .= "<tr class=row_color1>
				<td align=right width=50% class=medium_font>\n\tamount to charge for above credits: \n\t</td>\n\t
				<td class=medium_font>\n\t<select name=d[credit_dollars]>";
			for ($i=1;$i < 1001;$i++)
			{
				$this->body .= "<option>".$i."</option>\n\t";
			}
			$this->body .= "</select><select name=d[credit_cents]>";
			for ($i=0;$i < 100;$i++)
			{
				$this->body .= "<option>".sprintf("%02d",$i)."</option>\n\t";
			}
			$this->body .= "</select> \n\t</td>\n</tr>\n";
			if (!$this->admin_demo()) $this->body .= "<tr>\n\t<td colspan=2><input type=submit name=submit value=\"Save\">\n\t</td>\n</tr>\n";
			$this->body .= "</table>\n";
			$this->body .= "</form>\n";
			return true;
		}
		else
		{
			return false;
		}
	} //end of credit_period_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function insert_credit_period($db,$price_plan_id=0,$credit_info=0)
	{
		if (($price_plan_id) && ($credit_info))
		{
			$sql = "insert into ".$this->credit_choices."
				(price_plan_id,display_value,value,amount)
				values
				(".$price_plan_id.",\"".$credit_info["display_value"]."\",".$credit_info["value"].",".$credit_info["credit_dollars"].".".$credit_info["credit_cents"].")";
			$insert_result = $this->db->Execute($sql);
			//echo $sql."<br>\n";
			if (!$insert_result)
			{
				$this->error_message = $this->internal_error_message;
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	} //end of function insert_subscription_period

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function delete_credit_period($db,$credit_period_id=0)
	{
		if ($credit_period_id)
		{
			$sql = "delete from ".$this->credit_choices." where credit_id = ".$credit_period_id;
			$delete_result = $this->db->Execute($sql);
			//echo $sql."<br>\n";
			if (!$delete_result)
			{
				$this->error_message = $this->internal_error_message;
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	} //end of function delete_credit_period

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function delete_length($db,$length_id=0)
	{
		if ($length_id)
		{
			$sql = "delete from  ".$this->classified_price_plan_lengths_table." where length_id = ".$length_id;
			$result = $this->db->Execute($sql);
			//echo $sql."<Br>";
			if (!$result)
			{
				$this->site_error($this->db->ErrorMsg());
				return false;
			}
			return true;
		}
		else
		{
			$this->error_message = $this->internal_error_message;
			return false;
		}
	} //end of function delete_length

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function increments_form($db,$price_plan_id=0,$category_id=0,$item_type=0)
	{
		//item_type is 1 by default for classifieds
		if (!$item_type) $item_type = 1;
		if ($price_plan_id)
		{
			$price_plan_name = $this->get_price_plan_name($db,$price_plan_id);
			$current_price_plan = $this->get_price_plan($db,$price_plan_id);
			if ($category_id)
				$category_name = $this->get_category_name($db,$category_id);
			else
				$category_name = "all categories (default)";
			if (!$category_id)
				$category_id = 0;
			$sql_query = "select * from ".$this->price_plans_increments_table." where price_plan_id = ".$price_plan_id." and category_id = ".$category_id." and item_type = ".$item_type." order by low asc";
			$result = $this->db->Execute($sql_query);
			//echo $sql_query." is the bracket display query<br>\n";
			if (!$result)
			{
				//die ('here'.$this->db->ErrorMsg());
				return false;
			}
			elseif ($result->RecordCount() > 0)
			{
				$price_plan_name = $this->get_price_plan_name($db,$price_plan_id);
				$this->body .= "<table valign=center cellspacing=0 cellpadding=3 width=\"100%\">\n";
				$this->body .= "<tr>\n\t<td class=group_price_hdr>\n\tPrice Plan: ".$price_plan_name."\n\t</td>\n</tr>\n";
				$this->body .= "</table>\n";
				$this->body .= "<fieldset id='PPIncrements'><legend>Price Field Increments</legend><table cellpadding=3 cellspacing=1 border=0 width=100%>\n";
				//$this->title = "Pricing > Price Plans > Category Specific Pricing > Price Range Increments";
				$this->description = "If you have decided to charge users of this Price Plan based upon their listing's \"price field\", you may use
				the table below to edit the fees that will be assessed per listing. Starting with 0.01 create a price field bracketing system and charge
				a fee based upon that field. Each price bracket must have a \"Price field low\" and \"Price field high\" (or be checked 'and up').";
				$this->body .= "<tr>\n\t<td colspan=5 class=col_hdr_top align=center>\n\t
						Category: ".$category_name."\n\t</td>\n</tr>\n";

				$this->body .= "<tr>\n\t<td class=col_hdr_left>\n\t<strong>Price Field Low:</strong></td>\n\t
					<td class=col_hdr_left>&nbsp;</td>\n\t
					<td class=col_hdr_left>\n\t<strong>Price Field High:</strong></td>\n\t
					<td class=col_hdr align=center>\n\t<strong>Listing Fee Assessed:</strong></td>
					<td class=col_hdr align=center>\n\t<strong>Renewal Fee Assessed:</strong></td>\n</tr>\n";
				$this->row_count = 0;
				while ($show = $result->FetchRow())
				{
					$this->body .= "<tr class=".$this->get_row_color().">\n\t
						<td class=medium_font>\n\t".$show["low"]." ";
					$this->body .= "\n\t</td>\n\t
						<td class=medium_font>\n\tto \n\t</td>\n\t
						<td class=medium_font>\n\t";
					if ($show["high"] == 100000000)
						$this->body .= "and up";
					else
						$this->body .= $show["high"]."\n\t</td>\n\t";
					$this->body .= "<td class=medium_font align=center>\n\t".$show["charge"]."\n\t</td>\n";
					$this->body .= "<td class=medium_font align=center>\n\t".$show["renewal_charge"]."\n\t</td>\n</tr>\n";
					$this->row_count++;
				} //end of while
				$this->body .= "</table>\n";
			}
			else
			{
				//there are no brackets to display
					$this->body .= "<table valign=center cellspacing=0 cellpadding=3 width=\"100%\">\n";
					$this->body .= "<tr>\n\t<td colspan=4 class=col_hdr_top align=center>\n\t
						Category: ".$category_name."\n\t</td>\n</tr>\n";
					$this->body .= "</table>\n";	
				
//				$this->body .= "<table cellpadding=3 cellspacing=1 border=0>\n";
//				$this->body .= "<table cellpadding=3 cellspacing=0 border=0 width=100% class=row_color1>\n";
//				$this->body .= "<tr class=row_color_red>\n\t<td colspan=2 class=very_large_font_light>\n\t<strong>Price Based Increments Brackets</strong> \n\t</td>\n</tr>\n";
//				$this->body .= "<tr class=row_color_red>\n\t<td colspan=4 class=very_large_font_light>\n\t<strong>Price Plan: ".$price_plan_name."<br>Category: ";
//				if ($category_id)
//					$this->body .= $category_name." and subcategories";
//				else
//					$this->body .= $category_name;
//				$this->body .= "</strong> \n\t</td>\n</tr>\n";
//				$this->body .= "<tr class=row_color_red>\n\t<td colspan=2 class=medium_font_light>\n\t
//					You can charge different amounts to place listings using the price field value through this admin tool.  Starting with 0
//					create a bracketing system and charge differently for each bracket you create.  Once you start a new bracket you must
//					finish it or there will be \"holes\" where a price could not be charged to place a listing. \n\t</td>\n</tr>\n";
//				$this->body .= "</table>\n";
			}

			if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=pricing&page=pricing_increments&e=".$price_plan_id."&f=".$category_id." method=post>\n";

			$this->body .= "<table cellpadding=3 cellspacing=1 border=0 width=70%>\n";
			$this->body .= "<tr>\n\t<td class=col_hdr_left>\n\t<strong>Price Field Low:</strong></td>\n\t
				<td class=col_hdr>\n\t&nbsp; \n\t</td>\n\t
				<td class=col_hdr_left>\n\t<strong>Price Field High:</strong></td>\n\t
				<td class=col_hdr align=center>\n\t<strong>Listing Fee Assessed:</strong></td>\n
				<td class=col_hdr align=center>\n\t<strong>Renewal Fee Assessed:</strong></td>\n</tr>\n";
			$this->body .= "<tr>\n\t<td class=medium_font align=center>\n\t";
			if ($this->debug_price_plan)
			{
				echo $this->last_high_variable." is last_high_variable<bR>\n";
			}
			if ($this->last_high_variable == 0)
				$last_high_variable_to_use = 0;
			else
				$last_high_variable_to_use = $this->last_high_variable + .01;
			$this->body .= $last_high_variable_to_use."<input type=hidden name=d[new_low] value=\"".$last_high_variable_to_use."\"></td>\n\t";
			$this->body .= "<td class=medium_font align=center>\n\tto</td>\n\t";
			$this->body .= "<td class=medium_font align=center>\n\t<select name=d[new_high]>\n\t\t";
			$this->body .= "<option value=100000000>and up</option>\n\t\t";
		
			for ($i = $this->last_high_variable + .25; $i <= 100;$i = $i + .25)
			{
				$this->body .= "<option>".$i."</option>\n\t\t";
			}
			for ($i = max(101, $this->last_high_variable+1); $i < 499;$i++)
			{
				$this->body .= "<option>".$i."</option>\n\t\t";
			}
			for ($i = max(500, $this->last_high_variable+5); $i < 10000;$i = $i + 5)
			{
				$this->body .= "<option>".$i."</option>\n\t\t";
			}
			for ($i = max(10001, $this->last_high_variable+1000); $i < 100000;$i = $i + 1000)
			{
				$this->body .= "<option>".$i."</option>\n\t\t";
			}
			for ($i = max(100001, $this->last_high_variable+10000); $i < 1000000; $i = $i + 10000)
			{
				$this->body .= "<option>".$i."</option>\n\t\t";
			}
			$this->body .= "</select>\n\t</td>\n\t";
			$this->body .= "<td align=center>\n\t<input type=text name=d[new_increment] value=\"0\">\n\t</td>\n";
			$this->body .= "<td align=center>\n\t<input type=text name=d[new_renewal_charge] value=\"0\">
				<input type=hidden name=g value=\"".$item_type."\">{$this->hidden_bracket_variables} \n\t</td>\n</tr>\n";

			if (!$this->admin_demo()) $this->body .= "<tr>\n\t<td colspan=5 align=center>\n\t<input type=submit name='auto_save' value=\"Save Increment\">\n\t</td>\n</tr>\n";
			
			$this->body .= "</table>\t";
			
			$this->body .= "</form></fieldset>\n";
			
			$this->body .= "<table valign=center cellspacing=0 cellpadding=3 width=\"100%\">";
			
			
	
			if ($category_id)
			{
				$sql = "select * from ".$this->classified_price_plans_categories_table." where price_plan_id = ".$price_plan_id." and category_id = ".$category_id." and item_type = ".$item_type;
				$result = $this->db->Execute($sql);
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}
				elseif ($result->RecordCount() == 1)
				{
					$show_this = $result->FetchRow();
					$this->body .= "<tr>\n\t<td colspan=5>\n\t
					<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_category_costs&e=1&x=".$price_plan_id."&d=".$category_id."&y=".$show_this["category_price_plan_id"]." class='back_to'>
					<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to ".$price_plan_name." &gt; ".$category_name." Category Pricing</a></div>
									</td>\n</tr>\n";
				}
			}
			if (geoPC::is_ent() || geoPC::is_premier())
				$this->body .= "<tr>\n\t<td colspan=5>\n\t
				<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_category_costs&x=".$price_plan_id."&d=".$show_parent_id["parent_id"]." class='back_to'>
				<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to ".$price_plan_name." &gt; Category Specific Pricing</a></div>";			
				
			$this->body .= "<tr>\n\t<td colspan=5>\n\t
				<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_edit_plans&g=".$price_plan_id." class='back_to'>
				<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to ".$price_plan_name." Details</a></div>";			

			$this->body .= "<tr>\n\t<td colspan=5>\n\t
				<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_price_plans class='back_to'>
				<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to Price Plans Home</a></div>";			
			
			$this->body .= "</td>\n</tr>\n</table>\n";
			
	
			$this->hidden_bracket_variables = "";
			return true;
		}
		else
			return false;
	} //end of function increments_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function final_fees_increments_form($db,$price_plan_id=0)
	{
		$this->function_name = "increments_form";
		$this->body .= "<fieldset id='FinFeeSetup'><legend>Final Fee Settings for this Price Plan</legend><table valign=center cellspacing=0 cellpadding=3 width=\"100%\"><tr><td>";
		$sql_query = "select * from geodesic_auctions_final_fee_price_increments where price_plan_id = ".$price_plan_id." order by low asc";
		$result = $this->db->Execute($sql_query);
		if($this->configuration_data->DEBUG_ADMIN)
		{
			$this->debug_display($db, $this->filename, $this->function_name, "final_fee_table", "delete final fee data");
		}
		if (!$result)
		{
			return false;
		}
		elseif ($result->RecordCount() > 0)
		{

			//new

			//new
			$this->body .= "<table cellpadding=3 cellspacing=1 border=0 width=100%>\n";
			//$this->title .= "Final Fee % Brackets";
			$this->description .= "You can charge different amounts at the end of your auctions based upon the final price of the items sold.  Set the
					brackets that will determine the final fee percentage charged.  Each bracket will have its own percentage to charge for
					an auction based upon the final selling price.  Starting with 0
					create a bracketing system and charge differently for each bracket you create.  Once you start a new bracket you must
					finish it or there will be \"holes\" where a final fee could not be charged at the end of the auction.";
			$this->body .= "<tr>\n<td class=group_price_hdr colspan=5 align=center>\n\tCurrent Final Fee % Brackets\n\t</td>\n</tr>";
			$this->body .= "<tr>\n\t<td class=col_hdr2 width=\"25%\"><strong>Winning Bid Low</strong></td>\n\t
				<td class=col_hdr2 width=\"10%\"><strong>&nbsp;</strong></td>\n\t
				<td class=col_hdr2 width=\"25%\"><strong>Winning Bid High</strong></td>\n\t
				<td class=col_hdr2 align=center width=\"20%\"><strong>Final Fee<br>Percentage Charge</strong></td>\n
				<td class=col_hdr2 align=center width=\"20%\"><strong>Final Fee<br>Fixed Charge</strong></td>\n";			
			//$this->body .= "<td align=center class=medium_font_light>\n\tDelete?</td>\n";
			$this->body .= "</tr>\n";
			$this->row_count = 0;
			while ($show = $result->FetchNextObject())
			{
				$this->body .= "<tr class=".$this->get_row_color().">\n\t
					<td class=medium_font align=center>\n\t".$show->LOW." ";
				$this->body .= "\n\t</td>\n\t
					<td class=medium_font align=center>\n\tto</font>\n\t</td>\n\t
					<td class=medium_font align=center>\n\t";
				if ($show->HIGH == 100000000)
					$this->body .= "and up";
				else
					$this->body .= $show->HIGH."\n\t</td>\n\t";
				if ($show->HIGH != 100000000 && $show->HIGH > $this->last_high_variable)
					$this->last_high_variable = $show->HIGH;
				$this->body .= "<td class=medium_font align=center>\n\t".$show->CHARGE." %\n\t</td>\n";
				$this->body .= "<td class=medium_font align=center>\n\t".$show->CHARGE_FIXED."\n\t</td>\n";
				//$this->body .= "<td align=center>\n\t".geoHTML::addButton('delete', "index.php?mc=pricing&page=pricing_final_fees&e=".$price_plan_id."&f=".($this->row_count+1)."&auto_save=1", false, '', 'lightUpLink mini_cancel');
				$this->body .= "</tr>\n";

				$this->row_count++;
				


			} //end of while
			$this->body .= "</table>\n";
		}
		else
		{
			//there are no brackets to display
			$this->body .= "<table cellpadding=3 cellspacing=1 border=0>\n";
			$this->body .= "<table cellpadding=3 cellspacing=0 border=0 width=100%>\n";
			//$this->title .= "Pricing > Price Plans > Edit Final Fee % Brackets";
			$this->description .= "You can charge different amounts at the end of your auctions based upon the final price of the items sold.  Set the
					brackets that will determine the final fee percentage charged.  Each bracket will have its own percentage to charge for
					an auction based upon the final selling price.  Starting with 0
					create a bracketing system and charge differently for each bracket you create.  Once you start a new bracket you must
					finish it or there will be \"holes\" where a final fee could not be charged at the end of the auction.";
			$this->body .= "<tr>\n\t<td align=center class=page_note_error>\nYou currently have no brackets set up.</td>\n\t</tr>\n";
			$this->body .= "</table>\n";
		}

		if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=pricing&page=pricing_final_fees&e=".$price_plan_id."&price_plan=".$price_plan_id." method=post>\n";

		// Inserting new brackets form
		$this->body .= "<br><table cellpadding=3 cellspacing=1 border=0 width=\"100%\">\n";
		$this->body .= "<tr>\n\t<td colspan=5 class=group_price_hdr>\n\tInsert New Final Fee % Bracket \n\t</td>\n</tr>\n";
		//$this->body .= "<tr  class=row_color_red>\n\t<td colspan=4 class=medium_font_light>Use this form to change the
		//	current price based increment brackets.  Once you start to enter a new set of increment brackets the old ones will be deleted.
		//	You must then complete the complete the new brackets before you quit or there will be no charge for some values of price.\n\t</td>\n</tr>\n";

		$this->body .= "<tr>\n\t
			<td class=col_hdr2 width=\"25%\"><strong>Winning Bid Low</strong></td>\n\t
			<td class=col_hdr2 width=\"10%\">\n\t<strong>&nbsp;</strong></td>\n\t
			<td class=col_hdr2 width=\"25%\"><strong>Winning Bid High</strong></td>\n\t
			<td class=col_hdr2 align=center width=\"20%\"><strong>Final Fee<br>Percentage Charge</strong></td>\n\t
			<td class=col_hdr2 align=center width=\"20%\"><strong>Final Fee<br>Fixed Charge</strong></td>\n</tr>\n";		
		$this->body .= "<tr>\n\t<td class=medium_font align=center>\n\t";
		$this->body .= "<input type=text size=5 name=d[new_low] value=\"".($this->last_high_variable + .01)."\"></td>\n\t";
		$this->body .= "<td class=medium_font align=center>to</td>\n\t";
		$this->body .= "<script type='text/javascript'>
		  function disable_last_bracket(element) {
		    if (element.checked) {
  		    document.getElementById('upperBracket').disabled=true;
  		    document.getElementById('upperBracket').value='';
		    }
  		  else {
  		    document.getElementById('upperBracket').disabled='';
  		  }
		  }
		  </script>";
		$this->body .= "<td class=medium_font align=center><input id='upperBracket' type=text size=5 name=d[new_high]><input onclick='disable_last_bracket(this);' type=checkbox name=d[and_up] value=1> check for \"and up\"";

		$this->body .= "\n\t</td align=center>\n\t";
		$this->body .= "<td align=center>\n\t<input type=text size=4 name=d[new_increment] value=\"0\">%\n\t</td>\n\t";
		$this->body .= "<td align=center>\n\t<input type=text size=4 name=d[new_increment_fixed] value=\"0.00\">\n\t</td>\n</tr>\n";		

		$this->body .= "</table>\n";
		$this->body .= "<table valign=center cellspacing=0 cellpadding=3 width=\"100%\">\n";		
		if (isset($this->error['increments'])) 
			$this->body .= "<tr>\n\t<td class=medium_error_font align=center>Please Fill in All Fields</td>\n</tr>";
		if (!$this->admin_demo()) $this->body .= "<tr>\n\t<td align=center>\n\t<input type=submit name='auto_save' value=\"Save Increment\">\n\t</td>\n</tr>\n";

		$this->body .= "<tr><td align=center><a class=medium_font href=index.php?mc=pricing&page=pricing_edit_plans&f=3&g=$price_plan_id><strong>Return to Price Plan Cost Specifics</strong></a></td></tr>\n\t";

		$this->body .= "</table>\n";
		$this->body .= $this->hidden_bracket_variables;
		$this->body .= "</form>\n";
		//new
		$this->body .= "</td></tr></table></fieldset>\n";
		//new
		$this->hidden_bracket_variables = "";
		return true;
	} //end of function increments_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function update_final_fee_increments($db,$new_info=0,$info=0,$price_plan_id=0)
	{
		//echo $info." is the entered variables<br>\n";
		//echo count($info)." is the count of entered variable<Br>\n";
	
		$this->error_found = 0;
		
		if($new_info)
		{
			if (!(isset($new_info['new_low']) && $new_info['new_low'] != ""))
			{
				$this->error['increments'] = 1;
				$this->error_found++;
			}
			if (!((isset($new_info['new_high']) && $new_info['new_high'] != "") || isset($new_info['and_up'])))
			{
				$this->error['increments'] = 1;
				$this->error_found++;
			}
			if (!(isset($new_info['new_increment']) && $new_info['new_increment'] != ""))
			{
				$this->error['increments'] = 1;
				$this->error_found++;
			}
			if (!(isset($new_info['new_increment_fixed']) && $new_info['new_increment_fixed'] != ""))
			{
				$this->error['increments'] = 1;
				$this->error_found++;
			}
		}
		else
		{
			$this->error['increments'] = 1;
			$this->error_found++;
		}
		
		if ( $this->error_found )
		{
				if( is_array($info) )
				{
					foreach($info as $key => $value)
					{
						$this->hidden_bracket_variables .= "<input type=hidden name=c[".$key."][lower] value=".$info[$key]["lower"].">\n\t";
						$this->hidden_bracket_variables .= "<input type=hidden name=c[".$key."][higher] value=".$info[$key]["higher"].">\n\t";
						$this->hidden_bracket_variables .= "<input type=hidden name=c[".$key."][increment] value=".$info[$key]["increment"].">\n\t";
						$this->hidden_bracket_variables .= "<input type=hidden name=c[".$key."][increment_fixed] value=".$info[$key]["increment_fixed"].">\n\t";
					}
				}
			return false;
		}
		
		
		if ($price_plan_id)
		{
			$this->function_name = "update_increments";

			$sql_query = "delete from geodesic_auctions_final_fee_price_increments where price_plan_id = ".$price_plan_id;
			$result = $this->db->Execute($sql_query);
			if($this->configuration_data->DEBUG_ADMIN)
			{
				$this->debug_display($db, $this->filename, $this->function_name, "final_fee_table", "delete final fee data");
			}
			if (!$result)
			{
				$this->database_error($this->db->ErrorMsg(),$sql_query);
				return false;
			}

			$this->hidden_bracket_variables = "";

			if ((is_array($info)) && ($info != 0))
			{
				foreach($info as $key => $value)
				{
					$sql_query = "insert into geodesic_auctions_final_fee_price_increments
						(price_plan_id,low,high,charge,charge_fixed)
						values
						(".$price_plan_id.",".$info[$key]["lower"].",".$info[$key]["higher"].",".$info[$key]["increment"].",".$info[$key]["increment_fixed"].")";
					if ($new_info["and_up"] != 1)
					{
						$this->hidden_bracket_variables .= "<input type=hidden name=c[".$key."][lower] value=".$info[$key]["lower"].">\n\t";
						$this->hidden_bracket_variables .= "<input type=hidden name=c[".$key."][higher] value=".$info[$key]["higher"].">\n\t";
						$this->hidden_bracket_variables .= "<input type=hidden name=c[".$key."][increment] value=".$info[$key]["increment"].">\n\t";
						$this->hidden_bracket_variables .= "<input type=hidden name=c[".$key."][increment_fixed] value=".$info[$key]["increment_fixed"].">\n\t";
						$this->brackets_set = 1;
					}
					$result = $this->db->Execute($sql_query);
					if($this->configuration_data->DEBUG_ADMIN)
					{
						$this->debug_display($db, $this->filename, $this->function_name, "final_fee_table", "insert new final fee data");
					}
					if (!$result)
					{
						$this->site_error($this->db->ErrorMsg(),$sql_query);
						return false;
					}
				} //end of while
			}
			//echo $new_info["and_up"]." is and_up<br>\n";
			if ((is_array($new_info)) && ($new_info["and_up"] != 1))
			{
				$sql_query = "insert into geodesic_auctions_final_fee_price_increments
					(price_plan_id,low,high,charge,charge_fixed)
					values
					(".$price_plan_id.",".$new_info["new_low"].",".$new_info["new_high"].",".$new_info["new_increment"].",".$new_info["new_increment_fixed"].")";
				$result = $this->db->Execute($sql_query);
				if($this->configuration_data->DEBUG_ADMIN)
				{
					$this->debug_display($db, $this->filename, $this->function_name, "final_fee_table", "insert new final fee data");
				}
				if (!$result)
				{
					return false;
				}
				if ($new_info["and_up"] != 1)
				{
					$this->last_high_variable = $new_info["new_high"];
					if ($info == 0)
						$new_key = 0;
					else
						$new_key = count($info);
					$this->hidden_bracket_variables .= "<input type=hidden name=c[".$new_key."][lower] value=".$new_info["new_low"].">\n\t";
					$this->hidden_bracket_variables .= "<input type=hidden name=c[".$new_key."][higher] value=".$new_info["new_high"].">\n\t";
					$this->hidden_bracket_variables .= "<input type=hidden name=c[".$new_key."][increment] value=".$new_info["new_increment"].">\n\t";
					$this->hidden_bracket_variables .= "<input type=hidden name=c[".$new_key."][increment_fixed] value=".$new_info["new_increment_fixed"].">\n\t";					
				}
				else
					$this->last_high_variable = 0;

			}
			elseif ($new_info["and_up"] == 1)
			{
				$sql_query = "insert into geodesic_auctions_final_fee_price_increments
					(price_plan_id,low,high,charge,charge_fixed)
					values
					(".$price_plan_id.",".$new_info["new_low"].",100000000,".$new_info["new_increment"].",".$new_info["new_increment_fixed"].")";
				$result = $this->db->Execute($sql_query);
				if($this->configuration_data->DEBUG_ADMIN)
				{
					$this->debug_display($db, $this->filename, $this->function_name, "final_fee_table", "insert new final fee data");
				}
				if (!$result)
				{
					return false;
				}
				$this->last_high_variable = 0;
			}
			return true;
		}
		else
			return false;

	} //end of function update_increments

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function update_increments($db,$new_info=0,$info=0,$price_plan_id=0,$category_id=0,$item_type=0)
	{
		//echo $info." is the entered variables<br>\n";
		//echo count($info)." is the count of entered variable<Br>\n";
		if (!$item_type) $item_type = 1;
		if ($price_plan_id)
		{
			$sql_query = "delete from ".$this->price_plans_increments_table." where price_plan_id = ".$price_plan_id." and category_id=".$category_id;
			$result = $this->db->Execute($sql_query);
			//echo $sql_query." is the fonts_and_colors query<br>\n";
			if (!$result)
			{
				$this->database_error($this->db->ErrorMsg(),$sql_query);
				return false;
			}

			$this->hidden_bracket_variables = "";

			if ((is_array($info)) && ($info != 0))
			{
				foreach ($info as $key => $value) {
					$sql_query = "insert into ".$this->price_plans_increments_table."
						(price_plan_id,category_id,low,high,charge,renewal_charge,item_type)
						values
						(".$price_plan_id.",".$category_id.",".$info[$key]["lower"].",".$info[$key]["higher"].",".$info[$key]["increment"].",".$info[$key]["renewal_charge"].",".$item_type.")";
					if ($new_info["new_high"] != 100000000)
					{
						$this->hidden_bracket_variables .= "<input type=\"hidden\" name=\"c[".$key."][lower]\" value=\"".$info[$key]["lower"]."\">\n\t";
						$this->hidden_bracket_variables .= "<input type=\"hidden\" name=\"c[".$key."][higher]\" value=\"".$info[$key]["higher"]."\">\n\t";
						$this->hidden_bracket_variables .= "<input type=\"hidden\" name=\"c[".$key."][increment]\" value=\"".$info[$key]["increment"]."\">\n\t";
						$this->hidden_bracket_variables .= "<input type=\"hidden\" name=\"c[".$key."][renewal_charge]\" value=\"".$info[$key]["renewal_charge"]."\">\n\t";
					}
					$result = $this->db->Execute($sql_query);
					//echo $sql_query." is the entered variables query<br>\n";
					if (!$result)
					{
						//echo $sql_query." is the entered variables query<br>\n";
						return false;
					}
				} //end of while
			}

			if (is_array($new_info))
			{
				$sql_query = "insert into ".$this->price_plans_increments_table."
					(price_plan_id,category_id,low,high,charge,renewal_charge,item_type)
					values
					(".$price_plan_id.",".$category_id.",".$new_info["new_low"].",".$new_info["new_high"].",".$new_info["new_increment"].",".$new_info["new_renewal_charge"].",".$item_type.")";
				$result = $this->db->Execute($sql_query);
				//echo $sql_query." is the new entries query<br>\n";
				if (!$result)
				{
					return false;
				}
				if ($new_info["new_high"] != 100000000)
				{
					$this->last_high_variable = $new_info["new_high"];
					if ($info == 0)
						$new_key = 0;
					else
						$new_key = count($info);
					$this->hidden_bracket_variables .= "<input type=\"hidden\" name=\"c[".$new_key."][lower]\" value=\"".$new_info["new_low"]."\">\n\t";
					$this->hidden_bracket_variables .= "<input type=\"hidden\" name=\"c[".$new_key."][higher]\" value=\"".$new_info["new_high"]."\">\n\t";
					$this->hidden_bracket_variables .= "<input type=\"hidden\" name=\"c[".$new_key."][increment]\" value=\"".$new_info["new_increment"]."\">\n\t";
					$this->hidden_bracket_variables .= "<input type=\"hidden\" name=\"c[".$new_key."][renewal_charge]\" value=\"".$new_info["new_renewal_charge"]."\">\n\t";
				}
				else
					$this->last_high_variable = 0;

			}
			return true;
		}
		else
			return false;

	} //end of function update_increments

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function lengths_form($price_plan_id=0,$category_id=0)
	{
		if ($price_plan_id)
		{
			if (!$category_id)
			{
				$category_id = 0;
				$category_name = "all categories (default)";
			}
			else
				$category_name = $this->get_category_name($db,$category_id);

			$name = $this->get_price_plan_name($db,$price_plan_id);
			$sql_query = "select * from ".$this->classified_price_plan_lengths_table." where price_plan_id = ".$price_plan_id." and category_id = ".$category_id." order by length_of_ad asc";
			$length_result = $this->db->Execute($sql_query);
			if (!$length_result)
			{
				$this->site_error($this->db->ErrorMsg());
				return false;
			}
			if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=pricing&page=pricing_lengths_add&price_plan_id=".$price_plan_id."&category_id=".$category_id." method=post>\n";
			$this->body .= "<fieldset id='PPDurations'><legend>Listing Durations Setup</legend><table cellpadding=3 cellspacing=1 border=0 align=center>\n";
			//$this->title = "Pricing > Price Plans > Category Specific Pricing > Listing Length Choices";
			//$this->body .= "<tr class=row_color_red>\n\t<td colspan=5 class=large_font_light>\n\t<strong>Price Plan: ".$name." <br>Category: ".$category_name."</strong> \n\t</td>\n</tr>\n";
			$this->description = "Control the choices your users have for the length of days their
				listings are displayed in this form.  This only affects users within this price plan.  Delete the values you do not want by clicking the delete link next to them.
				Add a value by using the short form at the bottom and clicking \"add value\".  The values will always appear in numerical order.";
				$this->body .= "<tr>\n\t<td colspan=5 class=group_price_hdr align=center>\n\t<strong>Price Plan: ".$name."</strong>\n\t</td>\n</tr>\n";
				$this->body .= "<tr>\n\t<td colspan=5 class=col_hdr_top align=center>\n\t
				Category: ".$category_name."\n\t</td>\n</tr>\n";
			$this->body .= "<tr>\n\t<td align=center class=col_hdr2>\n\t<strong>Duration of Listing<br>(Displayed)</strong>\n\t</td>\n\t";
			$this->body .= "<td align=center class=col_hdr2>\n\t<strong>Duration of Listing<br>(# of Days)</strong>\n\t</td>\n\t";
			$this->body .= "<td align=center class=col_hdr2>\n\t<strong>Listing Fee Assessed</strong>\n\t</td>\n\t";
			$this->body .= "<td align=center class=col_hdr2>\n\t<strong>Renewal Fee Assessed</strong>\n\t</td>\n\t";
			
			$this->body .= "<td align=center class=col_hdr2>\n\t&nbsp; \n\t</td>\n</tr>\n";
			$this->row_count = 0;
			while ($show_lengths = $length_result->FetchRow())
			{
				$this->body .= "<tr class=".$this->get_row_color().">\n\t<td class=medium_font align=center>\n\t".$show_lengths["display_length_of_ad"]." \n\t</td>\n\t";
				$this->body .= "<td class=medium_font align=center>\n\t".$show_lengths["length_of_ad"]." \n\t</td>\n\t";
				$this->body .= "<td class=medium_font align=center>\n\t".geoString::displayPrice($show_lengths["length_charge"])." \n\t</td>\n\t";
				$this->body .= "<td class=medium_font align=center>\n\t".geoString::displayPrice($show_lengths["renewal_charge"])." \n\t</td>\n\t";
				
				$delete = geoHTML::addButton('Delete',"index.php?mc=pricing&page=pricing_lengths_delete&category_id=$category_id&price_plan_id=".$price_plan_id."&x=".$show_lengths["length_id"]."&auto_save=1", false, '', 'lightUpLink mini_cancel');
				$this->body .= "<td align=center align=center>$delete</td>\n</tr>\n";
				$this->row_count++;
			}
			
			$pre = $this->db->get_site_setting('precurrency');
			$post = $this->db->get_site_setting('postcurrency');
			
			$this->body .= "<tr>\n\t<td class=col_ftr align=center>\n\t<input type=text name=d[display_length_of_ad]>\n\t</td>\n\t";
			$this->body .= "<td class=col_ftr align=center>\n\t<input type=text name=d[length_of_ad]> \n\t</td>\n\t";
			$this->body .= "<td class=col_ftr align=center>\n\t$pre<input type='text' name='d[length_charge]' value='0.00' size='4' />$post";
			$this->body .= "</td>\n\t";
			$this->body .= "<td class=col_ftr align=center>\n\t$pre<input type='text' name='d[renewal_charge]' value='0.00' size='4' />$post";
			$this->body .= "</td>\n\t";
			
			if (!$this->admin_demo()) $this->body .= "<td class=col_ftr align=center>\n\t<input type=submit name=auto_save value=\"Save\">\n\t</td>\n";
			$this->body .= "</tr>\n";

			$this->body .= "</table></fieldset>";
			$this->body .= "<table valign=center cellspacing=0 cellpadding=3 width=\"100%\">";
			
			if(geoPC::is_ent() || geoPC::is_premier())
			{
				$this->body .= "<tr>\n\t<td colspan=5>\n\t
				<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_category_costs&x=".$price_plan_id."&d=".$show_parent_id["parent_id"]." class='back_to'>
				<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to ".$name." &gt; Category Specific Pricing</a></div>\n
				</td>\n</tr>\n";
			}
			$this->body .= "<tr>\n\t<td colspan=5>\n\t
			<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_edit_plans&g=".$price_plan_id." class='back_to'>
			<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to ".$name." Details</a></div>\n\t
			</td>\n</tr>\n";
			$this->body .= "<tr>\n\t<td colspan=5>\n\t
			<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_price_plans class='back_to'>
			<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to Price Plan Home</a></div>
			</td>\n</tr>\n";
			$this->body .= "</table>\n";
			$this->body .= "</form>\n";
			return true;
		}
		else
		{
			return false;
		}

	} //end of function lengths_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function add_category_specific_length($db,$price_plan_id=0,$new_length_info=0,$category_id=0)
	{
		//echo $price_plan_id." is price_plan_id<br>\n";
		//echo $new_length_info." is new_length_info<br>\n";
		//echo $category_id." is category_id<br>\n";
		if (!$category_id)
			$category_id = 0;
			
		if (($new_length_info) && ($price_plan_id))
		{
			//check length_of_ad to see if int
			//check length_charge to see if double or int
			if (ereg("[0-9]+", $new_length_info["length_of_ad"]))
			{
				$sql = "select * from  ".$this->classified_price_plan_lengths_table."
					where length_of_ad = ".$new_length_info["length_of_ad"]." and price_plan_id = ".$price_plan_id." and category_id = ".$category_id;
				$result = $this->db->Execute($sql);
				//echo $sql."<Br>";
				if (!$result)
				{
					$this->site_error($this->db->ErrorMsg());
					return false;
				}
				elseif ($result->RecordCount() == 0 )
				{
					$sql = "insert into ".$this->classified_price_plan_lengths_table."
						(price_plan_id,category_id,length_of_ad,display_length_of_ad,length_charge,renewal_charge)
						values
						(".$price_plan_id.",".$category_id.",".$new_length_info["length_of_ad"].",\"".$new_length_info["display_length_of_ad"]."\",".geoNumber::deformat($new_length_info["length_charge"]).",".geoNumber::deformat($new_length_info["renewal_charge"])." )";
					//echo $sql."<Br>";
					$insert_result = $this->db->Execute($sql);
					if (!$insert_result)
					{
						$this->site_error($this->db->ErrorMsg());
						return false;
					}
					else
					{
						return true;
					}
				}
				else
				{
					$this->ad_configuration_message = "That value already exists";
					return true;
				}
			}
			else
			{
				$this->ad_configuration_message = "Please only enter numbers";
				return true;
			}
		}
		else
		{
			$this->error_message = $this->internal_error_message;
			return false;
		}
	} //end of function add_length

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function category_specific_delete_length($db,$length_id=0)
	{
		if ($length_id)
		{
			$sql = "delete from  ".$this->classified_price_plan_lengths_table." where length_id = ".$length_id;
			$result = $this->db->Execute($sql);
			//echo $sql."<Br>";
			if (!$result)
			{
				$this->site_error($this->db->ErrorMsg());
				return false;
			}
			return true;
		}
		else
		{
			$this->error_message = $this->internal_error_message;
			return false;
		}
	} //end of function category_specific_delete_length

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function price_plan_registration_freebies_form($db,$price_plan_id=0)
	{
		if ($price_plan_id && !geoMaster::is('classifieds') && geoMaster::is('auctions'))
		{
			$price_plan_name = $this->get_price_plan_name($db,$price_plan_id);
			$this->get_configuration_data($db);

			if ($price_plan_name)
			{
				if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=pricing&page=pricing_edit_plans&f=4&g=".$price_plan_id." method=post>\n";
				$this->body .= "<table cellpadding=3 cellspacing=0 border=0 align=center width=100%>\n";
				//$this->title .= "Pricing > Price Plans > Edit Registration";
				$this->description = "Edit ".$price_plan_name."
					price plans detailed registration promotions through this form.  The options you see in this form are affected by the
					choice you made for the type of the <strong>".$price_plan_name."</strong> plan you made in the price plan type form (subscription or
					fee based).  Make sure you have chosen the option in that form that you want.  If not create a new price plan
					with the correct choice and discard this price plan (if you don't need it).  Make your changes then click the \"save\" button at the bottom.
					<br><strong>Be mindful of the effects certain
					choices could have on people currently on this pricing plan</strong>
					<br><strong>Changes in expirations below will not affect credits already issued by your site.
					</strong> \n\t</td>\n</tr>\n";
				$sql = "select * from ".$this->price_plan_table." where price_plan_id = ".$price_plan_id;
				$result = $this->db->Execute($sql);
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}
				elseif ($result->RecordCount() == 1)
				{
					$show_price_plan = $result->FetchRow();
				}
				else
				{
					return false;
				}

				if ($show_price_plan["type_of_billing"] == 1)
				{
					//fee based price plans
					
					if(geoPC::is_ent()) {
						$precurrency = $this->db->get_site_setting('precurrency');
						$postcurrency = $this->db->get_site_setting('postcurrency');
						$this->body .= "<tr class=".$this->get_row_color().">\n\t<td colspan=2 class=medium_font>\n\tinitial site balance\n\t
							<label>$precurrency<input name=\"h[initial_site_balance]\" type=\"text\" value=\"{$show_price_plan['initial_site_balance']}\" size=\"6\" /> $postcurrency
							</td>\n\t</tr>\n";
						$this->row_count++;
					}
				}
				elseif ($show_price_plan["type_of_billing"] == 2)
				{
					//subscription based price plans
					//free subscription period from registration
					$this->body .= "<tr>\n\t<td align=right class=medium_font>\n\tfree subscription period upon registration \n\t</td>\n\t";
					$this->body .= "<td>\n\t";
					$this->subscription_period_dropdown($db,$show_price_plan["free_subscription_period_upon_registration"],"h[free_subscription_period_upon_registration]");
					$this->body .= "</td>\n\t</tr>\n";
				}
				if (!$this->admin_demo()) $this->body .= "<tr>\n\t<td colspan=2>\n\t<input type=submit name='auto_save' value=\"Save\">\n\t</td>\n</tr>\n";
				$this->body .= "<tr>\n\t<td colspan=2>\n\t
				<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_edit_plans&g=".$price_plan_id." class='back_to'>
				<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to <strong>".$price_plan_name."</strong> Price Plan Details</a></div>
				</td>\n</tr>\n";
				$this->body .= "<tr>\n\t<td colspan=2>\n\t
				<div style='padding: 5px;'><a href=index.php?mc=pricing&page=pricing_price_plans class='back_to'>
				<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to Price Plan Home</a></div>		
				</td>\n</tr>\n";
				$this->body .= "</table>\n";
				$this->body .= "</form>\n";
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	} //end of function price_plan_registration_freebies_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function update_price_plan_registration_freebies($db,$price_plan_id=0,$price_plan_info=0)
	{
		if (($price_plan_info) && ($price_plan_id) && (!geoMaster::is('classifieds') && geoMaster::is('auctions')))
		{
			$sql = "select type_of_billing from ".$this->price_plan_table." where price_plan_id = ".$price_plan_id;
			$type_result = $this->db->Execute($sql);
			if (!$type_result)
			{
				$this->error_message = $this->internal_error_message;
				return false;
			}
			elseif ($type_result->RecordCount() == 1)
			{
				$show_type = $type_result->FetchRow();
				$sql = "update ".$this->price_plan_table." set ";
				if ($show_type["type_of_billing"] == 1)
				{
					$sql .= "initial_site_balance = \"".geoNumber::deformat($price_plan_info["initial_site_balance"])."\"";
				}
				elseif ($show_type["type_of_billing"] == 2 && is_numeric( $price_plan_info["free_subscription_period_upon_registration"]) ) {
					//get how the credits expire
					$sql .= "free_subscription_period_upon_registration = \"".$price_plan_info["free_subscription_period_upon_registration"]."\"";
				}
				else
				{
					return false;
				}
				$sql .= " where price_plan_id = ".$price_plan_id;

				$result = $this->db->Execute($sql);
				if (!$result)
				{
					$this->error_message = $this->internal_error_message;
					return false;
				}
				else
				{
					return true;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			$this->error_message = $this->internal_error_message;
			return false;
		}
	} //end of function update_price_plan_registration_freebies
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_pricing_price_plans()
	{		
		if (PHP5_DIR) 
			$menu_loader = geoAdmin::getInstance();
		else 
			$menu_loader =& geoAdmin::getInstance();
		
		$this->body .= $menu_loader->getUserMessages();
		$this->display_price_plan_list($this->db);
		
		$this->display_page();
	}
	function update_pricing_price_plans()
	{
		
	}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_pricing_new_price_plan()
	{
		if (PHP5_DIR) 
			$menu_loader = geoAdmin::getInstance();
		else 
			$menu_loader =& geoAdmin::getInstance();
		$this->body .= $menu_loader->getUserMessages();
		if (!isset($this->insert_price_plan_id)){
			$this->insert_price_plan_id = 0;
		}
		$this->price_plan_form($this->db, $this->insert_price_plan_id);
		$this->display_page();
	}
	function update_pricing_new_price_plan()
	{
		if ($_POST["d"])
		{
			return $this->insert_price_plan($this->db,$_POST["d"]);
		}
		return false;
		
	}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_pricing_edit_plans()
	{
		if (PHP5_DIR) $menu_loader = geoAdmin::getInstance();
		else $menu_loader =& geoAdmin::getInstance();
		
		if(!geoMaster::is('site_fees')) {
			geoAdmin::m('NOTICE: The <a href="index.php?page=master_switches&mc=site_setup">Site Fees master switch</a> switch is OFF. You can configure pricing settings, but that switch must be ON to actually charge any fees to site users', geoAdmin::NOTICE);
		}
		
		$this->body .= $menu_loader->getUserMessages();
		
		switch ($_REQUEST["f"])
		{
			case 1:
				//edit and update price plan expiration
				if(geoAddon::getInstance()->isEnabled('enterprise_pricing')) {
					if (($_REQUEST["g"]) && ($_POST["d"])){
						$this->price_plan_home($this->db,$_REQUEST["g"]);
					}elseif ($_REQUEST["g"]){
						$this->price_plan_expiration_form($this->db,$_REQUEST["g"]);
					}else{
						$this->display_price_plan_list($this->db);
					}
				} else {
					$this->price_plan_home($this->db,$_REQUEST["g"]);
				}
				break;
			case 3:
				//edit and update price plan specifics
				if ($_REQUEST["g"]) {
					$this->category_specific_price_plan_form($this->db, 0, 0, $_REQUEST["g"]);
				} else {
					$this->display_price_plan_list($this->db);
				}
				break;
			case 4:
				//edit and update registration freebies
				if (($_REQUEST["g"]) && ($_REQUEST["h"])){
					$this->price_plan_home($this->db,$_REQUEST["g"]);
				}elseif ($_REQUEST["g"]){
					$this->price_plan_registration_freebies_form($this->db,$_REQUEST["g"]);
				}else{
					$this->display_price_plan_list($this->db);
				}
				break;
			case 5:
				//edit and update price plan name and description
				if (($_REQUEST["g"]) && ($_POST["d"])){
					$this->price_plan_home($this->db,$_REQUEST["g"]);
				}elseif ($_REQUEST["g"]){
					$this->price_plan_form($this->db,$_REQUEST["g"]);
				}else{
					$this->display_price_plan_list($this->db);
				}
				break;
			case 6:
				//delete subscription period
				if (($_REQUEST["g"]) && ($_REQUEST["h"])){
					$this->price_plan_home($this->db,$_REQUEST["g"]);
				} elseif ($_REQUEST["g"]){
					$this->display_subscription_periods($this->db,$_REQUEST["g"]);
				}else{
					$this->display_price_plan_list($this->db);
				}
				break;
			case 7:
				//add new subscription period
				if (($_REQUEST["g"]) && ($_POST["d"])){
					$this->subscription_period_form($this->db,$_REQUEST["g"]);
				}elseif ($_REQUEST["g"]){
					$this->subscription_period_form($this->db,$_REQUEST["g"]);
				}else{
					$this->display_price_plan_list($this->db);
				}
				break;
			case 8:
				//subscription period list
				if ($_REQUEST["g"]){
					$this->display_subscription_periods($this->db,$_REQUEST["g"]);
				}else{
					$this->display_price_plan_list($this->db);
				}
				break;
			case 9:
				//delete credit period
				if (($_REQUEST["g"]) && ($_REQUEST["h"])){
					$this->price_plan_home($this->db,$_REQUEST["g"]);
				}elseif ($_REQUEST["g"]){
					$this->display_credit_periods($this->db,$_REQUEST["g"]);
				}else{
					$this->display_price_plan_list($this->db);
				}
				break;
			case 10:
				//add new credit period
				if (($_REQUEST["g"]) && ($_POST["d"])){
					$this->price_plan_home($this->db,$_REQUEST["g"]);
				}elseif ($_REQUEST["g"]){
					$this->credit_period_form($this->db,$_REQUEST["g"]);
				}else{
					$this->display_price_plan_list($this->db);
				}
				break;
			case 11:
				//credit period list
				if ($_REQUEST["g"]){
					$this->display_credit_periods($this->db,$_REQUEST["g"]);
				}else{
					$this->display_price_plan_list($this->db);
				}
				break;

			default:
				//show price plan home
				if ($_REQUEST["g"]){
					$this->price_plan_home($this->db,$_REQUEST["g"]);
				}else{
					$this->display_price_plan_list($this->db);
				}
				break;
		}
		$this->display_page();
	}
	function update_pricing_edit_plans()
	{
		switch ($_REQUEST["f"])
		{
			case 1:
				//edit and update price plan expiration
				if (($_REQUEST["g"]) && ($_POST["d"])){
					return $this->update_price_plan_expiration($this->db,$_REQUEST["g"],$_POST["d"]);
				}
				break;
			case 3:
				//edit and update price plan specifics
				if (($_REQUEST["g"]) && ($_REQUEST["h"])){
					return $this->update_category_specific_price_plan($this->db,$_REQUEST["g"],$_REQUEST["h"], 0);
				}
				break;
			case 4:
				//edit and update registration freebies
				if (($_REQUEST["g"]) && ($_REQUEST["h"])){
					return $this->update_price_plan_registration_freebies($this->db,$_REQUEST["g"],$_REQUEST["h"]);
				}
				break;
			case 5:
				//edit and update price plan name and description
				if (($_REQUEST["g"]) && ($_POST["d"])){
					return $this->update_price_plan_name_and_description($this->db,$_REQUEST["g"],$_POST["d"]);
				}
				break;
			case 6:
				//delete subscription period
				if (($_REQUEST["g"]) && ($_REQUEST["h"])){
					return $this->delete_subscription_period($this->db,$_REQUEST["h"]);
				}
				break;
			case 7:
				//add new subscription period
				if (($_REQUEST["g"]) && ($_POST["d"])){
					return $this->insert_subscription_period($this->db,$_REQUEST["g"],$_POST["d"]);
				}
				break;
			case 9:
				//delete credit period
				if (($_REQUEST["g"]) && ($_REQUEST["h"])){
					return $this->delete_credit_period($this->db,$_REQUEST["h"]);
				}
				break;
			case 10:
				//add new credit period
				if (($_REQUEST["g"]) && ($_POST["d"])){
					return $this->insert_credit_period($this->db,$_REQUEST["g"],$_POST["d"]);
				}
				break;
			
			default:
				return false;
				break;
		}
		
		
	}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_pricing_delete_plans()
	{
		if (PHP5_DIR) $menu_loader = geoAdmin::getInstance();
		else $menu_loader =& geoAdmin::getInstance();
		$this->body .= $menu_loader->getUserMessages();
		
		if ($_REQUEST["c"])
		{
			if (!$this->delete_price_plan_form($this->db,$_REQUEST["c"]))
				if (!$this->display_price_plan_list($db))
					return false;
		}
		else
		{
			if (!$this->display_price_plan_list($db))
				return false;
		}
		$this->display_page();
	}
	function update_pricing_delete_plans()
	{
		if (isset($_REQUEST["c"]) && isset($_POST["f"]) && isset($_POST["d"]))
		{
			//move current users from group
			if ($this->move_to_price_plan($this->db,$_REQUEST["c"],$_POST["d"]))
			{
				//delete price plan
				return $this->delete_price_plan($this->db,$_REQUEST["c"]);				
			}
			else
			{
				return false;
			}
		}
		elseif (isset($_GET['c']) && isset($_POST['f'])) {
			//delete price plan, no users to move.
			//delete price plan
			return $this->delete_price_plan($this->db,$_REQUEST["c"]);
			
		}
		return false;
	}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_pricing_category_costs()
	{
		if (PHP5_DIR) $menu_loader = geoAdmin::getInstance();
		else $menu_loader =& geoAdmin::getInstance();
		
		if(!geoMaster::is('site_fees')) {
			geoAdmin::m('NOTICE: The <a href="index.php?page=master_switches&mc=site_setup">Site Fees master switch</a> switch is OFF. You can configure pricing settings, but that switch must be ON to actually charge any fees to site users', geoAdmin::NOTICE);
		}
		
		$this->body .= $menu_loader->getUserMessages();
		
		switch ($_REQUEST["e"])
		{
			case 1:
				//edit and update price plan for this category
				if ($_REQUEST["h"] && $_REQUEST["y"] && $_REQUEST["x"]){
					if (!$this->browse_categories($this->db,$_GET["d"],$_REQUEST["x"]))
						return false;
				}elseif (($_REQUEST["y"]) && ($_REQUEST["x"])){
					if (!$this->category_specific_price_plan_form($this->db,$_REQUEST["y"],$_GET["d"],$_REQUEST["x"]))
						return false;
				}else{
					if (!$this->browse_categories($this->db,0,$_REQUEST["x"]))
						return false;
				}
				break;
			case 2:
				//delete price plan attached to this category
				if ($_REQUEST["x"] && $_GET["d"] && $_REQUEST["y"]){
					if (!$this->delete_category_specific_price_plan($this->db,$_REQUEST["y"],$_REQUEST["x"],$_REQUEST["d"]))
						return false;
					elseif (!$this->browse_categories($this->db,0,$_REQUEST["x"]))
						return false;
				}elseif (($_REQUEST["x"]) && ($_GET["d"])){
					if (!$this->browse_categories($this->db,$_GET["d"],$_REQUEST["x"]))
						return false;
				}elseif ($_REQUEST["x"]){
					if (!$this->browse_categories($this->db,0,$_REQUEST["x"]))
						return false;
				}elseif (!$this->display_price_plan_list($this->db))
					return false;
				break;
	
			case 3:
				if (($_REQUEST["d"]) && ($_REQUEST["h"]) && ($_REQUEST["x"])){
					if (!$this->browse_categories($this->db,0,$_REQUEST["x"]))
						return false;
				}elseif (($_REQUEST["x"]) && ($_REQUEST["d"])){
					if (!$this->category_specific_price_plan_form($this->db,0,$_GET["d"],$_REQUEST["x"]))
						return false;
				}elseif (!$this->display_price_plan_list($this->db))
					return false;
	
				break;
	
			default:
				//show price plan category home
				if ($_REQUEST["x"]){
					if (!$this->browse_categories($this->db,$_GET["d"],$_REQUEST["x"]))
						return false;
				}elseif (!$this->display_price_plan_list($this->db))
					return false;
				break;
		}
		$this->display_page();
	}
	function update_pricing_category_costs()
	{
		switch ($_REQUEST["e"])
		{
			case 1:
				//edit and update price plan for this category
				if ($_REQUEST["h"] && $_REQUEST["y"] && $_REQUEST["x"]){
					return $this->update_category_specific_price_plan($this->db,$_REQUEST["y"],$_REQUEST["h"]);
				}
				break;
			case 2:
				//delete price plan attached to this category
				if ($_REQUEST["x"] && $_GET["d"] && $_REQUEST["y"]){
					return $this->delete_category_specific_price_plan($this->db,$_REQUEST["y"],$_REQUEST["x"],$_REQUEST["d"]);
				}
				break;

			case 3:
				if (($_REQUEST["d"]) && ($_REQUEST["h"]) && ($_REQUEST["x"])){
					return $this->insert_category_specific_price_plan($this->db,$_GET["d"],$_REQUEST["x"],$_REQUEST["h"]);
				}
				break;

			default:
				return false;
				break;
		}
		return false;
	}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%	
	function display_pricing_increments()
	{
		if (PHP5_DIR) $menu_loader = geoAdmin::getInstance();
		else $menu_loader =& geoAdmin::getInstance();
		$this->body .= $menu_loader->getUserMessages();
		//if (!$_REQUEST["g"]) $_REQUEST["g"] = 1;
		$this->increments_form($this->db,$_REQUEST["e"],$_REQUEST["f"],$_REQUEST["g"]);
		
		$this->display_page();
	}
	function update_pricing_increments()
	{
		return $this->update_increments($this->db,$_POST["d"],$_REQUEST["c"],$_REQUEST["e"],$_REQUEST["f"],$_REQUEST["g"]);
	}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%	
	function display_pricing_lengths()
	{
		$admin = geoAdmin::getInstance();
		$this->body .= $admin->getUserMessages();
		$this->lengths_form($_GET["price_plan_id"],$_GET["category_id"]);
		$this->display_page();
		return;
	}
	
	public function display_pricing_lengths_add()
	{
		$this->display_pricing_lengths();
	}
	public function update_pricing_lengths_add()
	{
		$admin = geoAdmin::getInstance();
		$category_id = (isset($_GET['category_id']))? intval($_GET['category_id']): 0;
		$price_plan_id = (isset($_GET['price_plan_id']))? intval($_GET['price_plan_id']): 0;
		$new_length_info = $_POST['d'];
		if (!$new_length_info || !$price_plan_id) {
			return false;
		}
		if (!is_numeric($new_length_info['length_of_ad'])) {
			$admin->userError('Please only enter numbers for the length.');
			return false;
		}
		$length_of_ad = intval($new_length_info['length_of_ad']);
		$sql = "SELECT * FROM  ".geoTables::price_plan_lengths_table."
			WHERE length_of_ad = $length_of_ad AND price_plan_id = $price_plan_id AND category_id = $category_id";
		$result = $this->db->Execute($sql);
		//echo $sql."<Br>";
		if (!$result)
		{
			trigger_error('ERROR SQL: sql: '.$sql.' Error Msg: '.$this->db->ErrorMsg());
			return false;
		} else if ($result->RecordCount() == 0 ) {
			$sql = "INSERT INTO ".geoTables::price_plan_lengths_table."
				(price_plan_id,category_id,length_of_ad,display_length_of_ad,length_charge,renewal_charge)
				values
				($price_plan_id,$category_id,$length_of_ad, ?,".geoNumber::deformat($new_length_info["length_charge"]).",".geoNumber::deformat($new_length_info["renewal_charge"]).")";
			$insert_result = $this->db->Execute($sql, array(trim($new_length_info["display_length_of_ad"])));
			//echo $sql."<Br>";
			if (!$insert_result)
			{
				trigger_error('ERROR SQL: sql: '.$sql.' Error Msg: '.$this->db->ErrorMsg());
				return false;
			}
			return true;
		} else {
			$admin->userError('That duration of listing already exists.');
			return false;
		}
	}
	public function display_pricing_lengths_delete()
	{
		$this->display_pricing_lengths();
	}
	public function update_pricing_lengths_delete()
	{
		$length_id = intval($_POST['x']);
		if (!$length_id) {
			return false;
		}
		
		$sql = "DELETE FROM  ".geoTables::price_plan_lengths_table." WHERE length_id = $length_id LIMIT 1";
		$result = $this->db->Execute($sql);
		//echo $sql."<Br>";
		if (!$result) {
			trigger_error('ERROR SQL: sql: '.$sql.' Error Msg: '.$this->db->ErrorMsg());
			return false;
		}
		return true;
	}
	function update_pricing_lengths()
	{
		
	}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_pricing_final_fees()//
	{
		if (PHP5_DIR) $menu_loader = geoAdmin::getInstance();
		else $menu_loader =& geoAdmin::getInstance();
		$this->body .= $menu_loader->getUserMessages();
		
		if(($_REQUEST["e"]) && ($_REQUEST["f"]))
		{
			$this->final_fees_increments_form($this->db,$_REQUEST["e"]);	
		}elseif (($_POST["d"]) && ($_REQUEST["e"])){
			$this->final_fees_increments_form($this->db,$_REQUEST["e"] );
		}elseif ($_REQUEST["e"] ){
			$this->final_fees_increments_form($this->db,$_REQUEST["e"] );
		}else
			$this->display_price_plan_list($this->db);
		$this->display_page();
	}
	function update_pricing_final_fees()
	{
		if(($_REQUEST["e"]) && ($_REQUEST["f"]))
		{
			if(!$this->delete_increments($this->db, $_REQUEST["e"], $_REQUEST["f"]))
				return false;
			else
				return true;
		}elseif (($_POST["d"]) && ($_REQUEST["e"])){
			if (!$this->update_final_fee_increments($this->db,$_POST["d"],$_REQUEST["c"],$_REQUEST["e"] ))
				return false;
			else
				return true;			
		}
	}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
} //end of class Price_plan_management

