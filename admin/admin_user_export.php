<?php

class user_export
{
	var $db;
	var $admin_site;
	var $menu_loader;
	function user_export()
	{ 	
		$db = true;
		include (GEO_BASE_DIR."get_common_vars.php");
		
		$this->menu_loader = geoAdmin::getInstance();
		$this->admin_site = Singleton::getInstance('Admin_site');
		$this->db = $db;
	}
	function display_user_export()
	{
		if (!geoPC::is_ent()) {geoAdmin::getInstance()->v()->addBody('User export is in Enterprise editions only.');
		return;}
		$sql = "SELECT name, group_id FROM ".$this->db->geoTables->groups_table;
		$group_rs = $this->db->Execute($sql);
		if( !$group_rs )
		{
			echo $sql;
			return false;
		}
			
		
		$sql = "SELECT * FROM ".$this->db->geoTables->registration_configuration_table;
		$reg_opt_rs = $this->db->Execute($sql);
		if (!$reg_opt_rs)
		{
			echo $sql;
			return false;
		}
		
		$registration_configuration = $reg_opt_rs->fetchRow();
				
		$body = "";
		$body .= $this->menu_loader->getUserMessages();
		if (!$this->admin_site->admin_demo())
			$body .= "<form action=\"\" method=\"post\">";
		
		$body .= "<fieldset><legend>User Email Exporter</legend>
				 <table class=\"user_table\" >
					<tr>
						<th colspan=\"2\" class=\"col_hdr\">Export Group</td>
					</tr>";
		
					
				if (geoPC::is_ent() || geoPC::is_premier() || geoPC::is_basic())
				{
				$body .="<tr class=\"row_color2\">
						<td class=\"medium_font\" width=\"66%\">Select a user group</td>
						<td class=\"medium_font\" width=\"33%\">
						<select name=\"email_group\">
										<option value=\"all\">All Groups</option>";
							while( $row = $group_rs->fetchRow() )
							{
								$body .= "
										<option value='".$row['group_id']."'>".$row['name']."</option>";
							}
							$body .="
									</select>";
							$body .= "
						</td>
					</tr><tr>";
				}
				else
				{
					$body .= "<input type='hidden' name='email_group' value='1'>";
				}
				
					$body .= "
						<th class=\"col_hdr\" colspan=\"2\">Please select the columns you would like exported</td>
					</tr><tr class=\"row_color1\">
						<td class=\"medium_font\">User Name</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_username\" checked /></td>
					</tr><tr class=\"row_color2\">
						<td class=\"medium_font\">First Name</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_firstname\" checked /></td>
					</tr><tr class=\"row_color1\">
						<td class=\"medium_font\">Last Name</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_lastname\" checked /></td>
					</tr><tr class=\"row_color2\">
						<td class=\"medium_font\">Company Name</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_company_name\" /></td>
					</tr><tr class=\"row_color1\">
						<td class=\"medium_font\">Primary Email</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_email\" checked /></td>
					</tr><tr class=\"row_color2\">
						<td class=\"medium_font\">Secondary Email</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_email2\" /></td>
					</tr><tr class=\"row_color1\">
						<td class=\"medium_font\">Address</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_address\" checked /></td>
					</tr><tr class=\"row_color2\">
						<td class=\"medium_font\">Address 2</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_address_2\" /></td>
					</tr><tr class=\"row_color1\">
						<td class=\"medium_font\">Zip</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_zip\" checked /></td>
					</tr><tr class=\"row_color2\">
						<td class=\"medium_font\">City</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_city\" checked /></td>
					</tr><tr class=\"row_color1\">
						<td class=\"medium_font\">State</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_state\" checked /></td>
					</tr><tr class=\"row_color2\">
						<td class=\"medium_font\">Country</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_country\" checked /></td>
					</tr><tr class=\"row_color1\">
						<td class=\"medium_font\">Phone</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_phone\" /></td>
					</tr><tr class=\"row_color2\">
						<td class=\"medium_font\">Secondary Phone</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_phone2\" /></td>
					</tr><tr class=\"row_color1\">
						<td class=\"medium_font\">Fax</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_fax\" /></td>
					</tr><tr class=\"row_color2\">
						<td class=\"medium_font\">Url</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"email_url\" /></td>
					</tr>";
													
					for( $x=1; $x<=10; $x++ )
					{
						$body .="<tr class=\"row_color".((($x-1)%2)+1)."\">
						<td class=\"medium_font\" width=\"66%\">".$registration_configuration['registration_optional_'.$x.'_field_name']."</td>
						<td class=\"medium_font\" width=\"33%\"><input type=\"checkbox\" name=\"email_optional_field_{$x}\" /></td>
					</tr>";
					}
					$body .= "<tr class=\"row_color1\">
						<td class=\"medium_font\">Subscription Expiration</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"subscription_expire\" value=\"1\"/></td>
					</tr>
					<tr class=\"row_color2\">
						<td class=\"medium_font\">Encapsulate fields using double quote</td>
						<td class=\"medium_font\"><input type=\"checkbox\" name=\"encapsulate\" value=\"1\"/></td>
					</tr>
					</table>
				<br />";
				$body .= '<div style="text-align: center; margin-top: 5px;">';
				if (!$this->admin_site->admin_demo())
					$body .= "<input type=\"submit\" value=\"Export Data\" name=\"auto_save\">";
				else
					$body .= "<input type=\"button\" value=\"Export Data\">";
				$body .= "</div></fieldset></form>";
				
				
		$this->admin_site->body = $body;
		$this->admin_site->display_page();
	}
	
	function update_user_export()
	{
		$export_array = array();
		$filename = "";
		$data = "";
		$next_line = "";
		$use_optionals = false;
		$void_array = array('group','optionals');
		$sub_expire = $_POST['subscription_expire'];
		$encapsulate = $_POST['encapsulate'];
		
		$sql = "SELECT ".$this->db->geoTables->userdata_table.".`id`";
		// Compile the sql
		

		foreach ( $_POST as $key => $value ){
			if ( substr($key, 0, 6) == "email_" ){
				$sub = substr($key, 6);
				if ( !in_array($sub,$void_array) && $value != "0")	{
					$export_array[] = $sub;
					$sql .= ",".$this->db->geoTables->userdata_table.".`".$sub."`";
				}
			}
		}
		$sql .= " FROM ".$this->db->geoTables->userdata_table;
		if ( isset($_POST['email_group']) && $_POST['email_group'] != "all" )
		{
			$sql .= ", ".$this->db->geoTables->user_groups_price_plans_table." WHERE ".$this->db->geoTables->userdata_table.".`id` = ".$this->db->geoTables->user_groups_price_plans_table.".`id` AND ".$this->db->geoTables->user_groups_price_plans_table.".`group_id` = ".$_POST['email_group'];
			$filename .= urlencode($this->admin_site->get_group_name($this->db, $_POST['email_group'] ))."_";
		}
		
		$rs = $this->db->Execute( $sql );
		if ( !$rs ){ $this->menu_loader->userFailure('Database Error'); return false; }
		else
		{
			$filename .= "email_export.csv";
			if ($encapsulate)
			{
				$next_line = "\"";
				$next_line .= implode( "\",\"", $export_array );
				$next_line .= "\"";
				if($sub_expire) 
					$next_line .= ',\"subscription_expire\"';
			}
			else
			{
				$next_line = implode( ",", $export_array );
				if($sub_expire) 
					$next_line .= ',subscription_expire';
			}	
			
			$data .= $next_line."\n";
			
			while ( $row = $rs->fetchRow() )
			{	
				$next_line = "";			
				$first = array_shift( $export_array );
				if ($encapsulate)
					$next_line = "\"".$row[$first]."\"";
				else
					$next_line = $row[$first];
				foreach( $export_array as $value ) 
				{
					if ($encapsulate)
					{
						$next_line .= ",\"".$row[$value]."\"";
					}
					else
					{
						$next_line .= ",".$row[$value];
					}
				}
				if($sub_expire) {
					//subscription expiration is in a different table -- query it separately
					$sql = "SELECT `subscription_expire` FROM ".geoTables::user_subscriptions_table." WHERE `user_id` = ?";
					$expire = $this->db->GetOne($sql, array($row['id']));
					if(!$expire || $expire < geoUtil::time()) {
						//subscription not present or expired
						$expire = 0;
					}
					$next_line .= ','.$expire;
				}
				
				
				array_unshift($export_array, $first);	
				$data .= $next_line."\n";
			}
			header("Content-Disposition: attachment; filename={$filename}");
			die($data);
			
		}
	}
	
}