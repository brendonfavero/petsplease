<?php
//admin_filter_class.php
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

/**
 * DEFACTORED!  This is being replaced by upcoming feature for "leveled fields"...
 * Do not use this.  Note that this is not the new browsing filters.  This is a very old
 * feature that only serves to confuse more than help, it doesn't work like anyone thinks
 * it should work (except for the original client that paid us to add this years ago)
 *
 */
class Admin_filter extends Admin_site{

	var $current_filter;
	var $filter_level_array = array();
	var $in_statement;
	var $subfilter_array = array();

	var $messages = array();
	function display_main_filters($db=0,$filter=0) { return $this->browse( $db, $filter ); }
	
	function update_main_filters()
	{
		$db = DataAccess::getInstance();
		if($_POST['toggle_filters'] == 1) {
			$use_filters = ($db->get_site_setting('use_filters')) ? 0 : 1;
			$db->set_site_setting('use_filters', $use_filters);
		}
		
		return true;
	}
	
	function browse($db=0,$filter=0) {
		if( !defined('INCLUDE_PROTOTYPE') ) define( 'INCLUDE_PROTOTYPE', true );
		if(!geoPC::is_ent())
		{
			$this->body .= '<table width="100%"><tr><td align="center" class="large_font">Filters are only available in the Enterprise version of the software.</td></tr></table>';
			return true;
		}
		
		$query = "select max(filter_level) as levels from " . $this->classified_filters_table;
		$levels = $this->db->GetRow( $query );
		if( false === $levels ) {
			trigger_error('ERROR SQL:' . $this->db->ErrorMsg());
		}
		$levels = $levels[0]['levels'];
		$levels = $levels >= 1 ? $levels : 1;
		$lang = isset($_GET['language']) ? $_GET['language'] : 1;		
		
		if(!defined('IN_ADMIN')) define('IN_ADMIN', true);
		require_once "AJAX.php";
		require_once "AJAXController/Filter.php";
		$this->additional_header_html .= "
			<script type='text/javascript'>
				" . ADMIN_AJAXController_Filter::getJavascript() . "
				geoFilter = new Geo_Filter_Admin( $levels, $lang );
			</script>";
		ob_start();
		ADMIN_AJAXController_Filter::addLevel( array('level'=>1, 'parent'=>0, 'lang' => $lang) );
		
		if( $levels > 1 ) {
			for( $i = 2; $i <= $levels; $i++) {
				ADMIN_AJAXController_Filter::addLevel( array('level'=>$i, 'lang' => $lang) );
			}
		}
		$levelsHTML = ob_get_contents();
		$previewHTML = "";
		for( $i = 1; $i <= 10; $i++) {	
			ob_clean();
			ADMIN_AJAXController_Filter::showPreviewDropdown( array( 'level' => $i, 'lang' => $lang ) );
			$previewHTML .= "<div id='level_{$i}_preview_placeholder' style='float: left;'>" . ob_get_contents() . "</div>";
		}
		ob_end_clean();
		
		if( $this->db->get_site_setting('use_filters') ) {
			$current = 'enabled';
			$toggle = 'Disable Filters';
			$class = 'mini_cancel';
			$changeTo = '1'; 
			$toggle_display = "";
		} else {
			$current = 'disabled';
			$toggle = 'Enable Filters';
			$class = 'mini_button';
			$changeTo = '0';
			$toggle_display = "none";
		}
		
		//$this->body .= geoAdmin::m();
		$this->body .= "
			<fieldset><legend>Filter Status</legend>
				<form action='' method='post'>
					<div style='text-align: center;'>
						<div><span style='font-size: 12pt;'>Filters are <strong>$current</strong></span></div>
						<div style='margin: 5px;'>
							<input type='hidden' value='1' name='toggle_filters' />
							<input type='submit' class='$class' value='$toggle' name='auto_save' />
						</div>
					</div>
				</form>
			</fieldset>
			<div id='filter_container' style='display: {$toggle_display}'>
			
				<fieldset><legend>Language</legend>
					<div class='medium_font' style='text-align: center; margin-top: 0; margin-bottom: 1em; padding: 0.5em 0;'>
						<p class='page_note' style='margin: 0 5px 5px 5px;'>
							<strong>Note:</strong> The language denoted as Base is used to suggest values for other languages. These suggestions will be placed in parentheses at the end of the values for choices. These suggestions will not be visible to the public.
						</p>
					<form action='{$_SERVER['PHP_SELF']}?{$_SERVER['QUERY_STRING']}' method='get'>
						Display choices in " . ADMIN_AJAXController_Filter::getLanguageSelect( $lang ) . " <input type='submit' value='Go' />
						</form>
					</div>
				</fieldset>
	
				<fieldset><legend>Filter Levels</legend>
					<div id='levelsHolder' style='margin-bottom: 1em; border-bottom: 1px solid gray;'>
					{$levelsHTML}
					</div>
				</fieldset>
	
				
				<fieldset><legend>Preview</legend>
					<div class='medium_font' style='text-align: center; margin-top: 0; margin-bottom: 1em;border-bottom: 1px solid gray;'>
						<div class='medium_font row_color2' style='position: relative; margin: 1em;' id='previews' >
						{$previewHTML}
						<br /><br />
						</div>
					</div>
				</fieldset>
			
			</div>";
		ob_end_clean();	
		$this->display_page();
		return true;	
	} //end of function browse
	
} // end of class Admin_filters