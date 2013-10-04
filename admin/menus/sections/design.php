<?php
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

//make sure loading in admin
defined('IN_ADMIN') or die ('No Access.');

//Set parent key and head key to defaults if not set
$parent_key = (isset($parent_key))? $parent_key : 0;
$head_key = (isset($head_key))? $head_key : 0;

// ----------------- Design
menu_category::addMenuCategory('design',$parent_key,'Design','admin_images/menu_temp.gif','','',$head_key);
	
	menu_page::addPage('design_sets','design','Template Sets','menu_temp.gif','design.php','DesignManage');
		menu_page::addPage('design_sets_copy','design_sets','Copy Set','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_sets_download','design_sets','Download Set','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_sets_upload','design_sets','Upload Set','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_sets_scan','design_sets','Re-Scan Template Attachments','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_sets_export','design_sets','Export pre-5.0 design to template set','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_change_mode','design_sets','Change Design Mode','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_change_editing','design_sets','Change Template Sets Editing','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_sets_create_main','design_sets','Create Main Template Set','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_sets_import_text','design_sets','Import Text','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_sets_delete','design_sets','Delete Template Set','menu_temp.gif','design.php','DesignManage','sub_page');

	menu_page::addPage('design_manage','design','Manager','menu_temp.gif','design.php','DesignManage');
		menu_page::addPage('design_preview_file','design_manage','Preview','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_new_folder','design_manage','New Folder','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_new_file','design_manage','New File','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_upload_file','design_manage','Upload File','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_edit_file','design_manage','Edit','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_rename_file','design_manage','Rename/Move','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_delete_files','design_manage','Delete','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_download_file','design_manage','Download','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('design_copy_files','design_manage','Copy/Paste Files','menu_temp.gif','design.php','DesignManage','sub_page');
	
	menu_page::addPage('page_attachments','design','Page Attachments','menu_temp.gif','design.php','DesignManage');
		menu_page::addPage('page_attachments_edit','page_attachments','Edit','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('page_attachments_apply_defaults','page_attachments','Apply Default Attachment','menu_temp.gif','design.php','DesignManage','sub_page');
		menu_page::addPage('page_attachments_restore_template','page_attachments','Restored Template','menu_temp.gif','design.php','DesignManage','sub_page');
	
	menu_page::addPage('text_search','design','Text Search','menu_temp.gif','search_text.php','SearchText');