<?php
// admin_payment_management_class.php
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

class Payment_management extends Admin_site {
	function currency_designation_form($db)
	{
		$this->sql_query = "select precurrency,postcurrency from ".$this->site_configuration_table;
		$result = $db->Execute($this->sql_query);
		if (!$result)
		{
			//echo $this->sql_query."<br>\n";
			$this->error_message = $this->internal_error_message;
			return false;
		}
		else
		{

			$show=$result->FetchRow();
			if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=payments&page=payments_currency_designation method=post>\n";
			$this->body .= "<fieldset id='CurrencyDesig'><legend>Currency Type You Accept from Sellers</legend><table cellpadding=3 cellspacing=0 border=0 align=center width=100%>\n";
			//$this->title = "Payment Management > Currency Designation";
			$this->description = "Edit the currency symbol that comes before and currency type that comes after any price within your site.";

			$this->body .= "<tr class=row_color1>\n\t<td align=right width=50% class=medium_font>\n\t<b>Symbol \"before\"</b> ($): \n\t
				</td>\n\t";
			$this->body .= "<td width=\"50%\">\n\t<input type=text name=h[precurrency] value=\"".$show["precurrency"]."\"></td>\n\t</tr>\n";

			$this->body .= "<tr class=row_color2>\n\t<td align=right width=50% class=medium_font>\n\t<b>Currency Type \"after\"</b> (USD,DM,...): \n\t
				</td>\n\t";
			$this->body .= "<td width=\"50%\">\n\t<input type=text name=h[postcurrency] value=\"".$show["postcurrency"]."\"></td>\n\t</tr>\n";

			if (!$this->admin_demo()) $this->body .= "<tr>\n\t<td colspan=2 class=medium_font align=center><input type=submit name='auto_save' value=\"Save\"></td>\n</tr>\n";
			$this->body .= "</table></fieldset>\n";
			$this->body .= "<div class='page_note'><strong>Note:</strong> Currency symbols must be specified in their ASCII code format in order to be
			displayed properly.  Please reference the ASCII codes below for your desired currency symbol. There is no special ASCII code to enter for the dollar ($) symbol.\n\t
			<div style='padding-top:20px;'><strong>Common Currency ASCII Codes:</strong>
			<ul>
			<li>&pound; British Pounds - ASCII CODE: <strong>&amp;pound;</strong></li>
			<li>&euro; European Euro - ASCII CODE: <strong>&amp;euro;</strong></li>
			<li>&yen; Japanese Yen - ASCII CODE: <strong>&amp;yen;</strong></li>
			</ul
			</div>
			</div>\n\t";
			return true;
		}

	} //end of function currency_designation_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function update_currency_designation($db,$currency_type_info=0)
	{
		if ($currency_type_info)
		{
			$this->sql_query = "update ".$this->site_configuration_table." set
				precurrency = \"".$currency_type_info["precurrency"]."\",
				postcurrency = \"".$currency_type_info["postcurrency"]."\"";
			//echo $this->sql_query."<br>\n";
			$result = $db->Execute($this->sql_query);
			//clear the settings cache
			geoCacheSetting::expire('configuration_data');
			if (!$result)
			{
				//echo $this->sql_query."<br>\n";
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
	} //end of function update_currency_designation

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function display_payments_currency_designation()
	{
		if (PHP5_DIR) $menu_loader = geoAdmin::getInstance();
		else $menu_loader =& geoAdmin::getInstance();
		$this->body .= $menu_loader->getUserMessages();
		
		if (!$this->currency_designation_form($this->db))
			return false;
		$this->display_page();
	}
	
	function update_payments_currency_designation()
	{
		return $this->update_currency_designation($this->db,$_REQUEST["h"]);
	}
	
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	/**
	 * pulls a report of incoming revenue given a user group and time period
	 *
	 */
	
	function display_payments_revenue_report()
	{
		$tpl_vars = array();
		$db = DataAccess::getInstance();
		
		//get a list of all the user groups
		$groups = array();
		$sql = "SELECT group_id, name FROM `geodesic_groups`";
		$result = $db->Execute($sql);
		while($group = $result->FetchRow()) {
			$groups[$group['group_id']] = $group['name'];
		}
		$tpl_vars['groups'] = $groups;
		
		if(isset($_POST['d']) && $_POST['d']) {
			$data = $_POST['d'];
			
			//convert dates to ticktime
			$startTime = strtotime($data['start_date']);
			$endTime = strtotime($data['end_date']);
			if($startTime === false || $endTime === false) {
				geoAdmin::m('Invalid date range specified! Reporting revenue from the last 30 days.',geoAdmin::NOTICE);
				$endTime = geoUtil::time();
				$startTime = $endTime - (60*60*24*30);
			}
			//Need to make endTime at end of day to cover things happened that day
			$endTime = mktime(23,59,59,date('n',$endTime), date('j',$endTime),date('Y',$endTime));
			
			$tpl_vars['report_start'] = date("Y-m-d",$startTime);
			$tpl_vars['report_end'] = date("Y-m-d",$endTime);
			
			if(count($data['usergroups']) < 1) {
				geoAdmin::m('No user group(s) selected! Reporting revenue from ALL user groups.',geoAdmin::NOTICE);
				$groupsToReport = array();
				foreach($groups as $id => $g) {
					$groupsToReport[] = $id;
				}
			} else {
				$groupsToReport = $data['usergroups'];
			}
			
			$getGroupTransactions = $db->Prepare("SELECT t.amount FROM `geodesic_transaction` as t, `geodesic_user_groups_price_plans` as ugpp 
				WHERE t.gateway <> 'site_fee' AND t.status = 1 AND t.date >= ? AND t.date <= ? AND t.user = ugpp.id AND ugpp.group_id = ?");
			$getGroupListings = $db->Prepare("SELECT COUNT(c.`id`) FROM `geodesic_classifieds` as c, `geodesic_user_groups_price_plans` as ugpp
				WHERE c.date >= ? AND c.date <= ? AND c.seller = ugpp.id AND ugpp.group_id = ?");
			$report = array();
			foreach($groupsToReport as $groupId) {
				
				$result = $db->Execute($getGroupTransactions, array($startTime, $endTime, $groupId));
				
				$total = 0;
				while($transaction = $result->FetchRow()) {
					$total += $transaction['amount'];
				}
				
				$totalListings = (int)$db->GetOne($getGroupListings, array($startTime, $endTime, $groupId));
				
				$report[] = array('name' => $groups[$groupId], 'total' => geoString::displayPrice($total), 'numListings' => $totalListings);
			}
			
			//sum all the other totals to get an overall total, as well
			$total = $totalNumListings = 0;
			foreach($report as $r) {
				$total += geoNumber::deformat($r['total'], true);
				$totalNumListings += $r['numListings'];
			}
			$report[] = array('name' => '<strong>Total from all selected user groups</strong>', 'total' => geoString::displayPrice($total), 'numListings' => $totalNumListings);
			$tpl_vars['report'] = $report;
		} else {
			$tpl_vars['report'] = false;
		}
		
		if($_POST['d']['as_csv'] == 1 && $tpl_vars['report']) {
			//output as CSV instead of onto the page
			header('Content-type: text/csv');
			header('Content-disposition: attachment;filename=groupReport.csv');
			
			//header row
			echo '"User Group", "Revenue", "Number of Listings"'."\n";
			
			//make final (total) row's text a bit more friendly for the CSV
			$tpl_vars['report'][count($tpl_vars['report'])-1]['name'] = "Total for date range {$tpl_vars['report_start']} to {$tpl_vars['report_end']}"; 
			
			foreach($tpl_vars['report'] as $g) {
				echo "\"{$g['name']}\",\"{$g['total']}\",\"{$g['numListings']}\"\n";
			}
			require GEO_BASE_DIR . 'app_bottom.php';
			exit();
		} else {
			$tpl_vars['admin_msgs'] = geoAdmin::m();
			geoView::getInstance()->setBodyTpl('revenue_report.tpl')
				->setBodyVar($tpl_vars)
				->addCssFile('css/calendarview.css')
				->addJScript('../js/calendarview.js');
		}
	}
	
	
} //end of class Payment_management
