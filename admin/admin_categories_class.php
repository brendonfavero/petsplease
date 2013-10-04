<?php
//admin_categories_class.php
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

class Admin_categories extends Admin_site{

	var $current_category;
	var $in_statement;
	var $isUpdated;

	var $messages = array();
	var $debug_categories = 0;

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function __construct ()
	{
		parent::__construct();

		$this->messages[3500] = "Not enough information to display the category";
		$this->messages[3501] = "Internal browse error!";
		$this->messages[3502] = "no category id";
		$this->messages[3503] = "cannot update the main category";
		$this->messages[3504] = "Category Level ";
		$this->messages[3505] = "Subcategories of ";
		$this->messages[3506] = "There are currently no subcategories to display for this Category.";
		$this->messages[3507] = "subcategories exist";
		$this->messages[3509] = "cannot delete the Main category";
		$this->messages[3510] = "There was an error processing your request";
	} //end of function Admin_categories

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function display_category_form ($db,$category=0,$type=0)
	{
		$this->db = DataAccess::getInstance();
		$admin = geoAdmin::getInstance();
		$this->body .= "<SCRIPT type=\"text/javascript\">";
		// Set title and text for tooltip
		$this->body .= "Text[1] = [\"category order\", \"Category order determines the order this category is displayed relative to the other categories at this same level.\"]\n
			Text[2] = [\"url of category icon\", \"This is the url of the image icon this category uses when this category's name is being displayed.  If no image is referenced no image will be displayed.\"]\n
			Text[3] = [\"edit category specific fields\", \"Click here to edit which fields you wish to use and display within this category (and subcategories if you choose to).\"]\n
			Text[4] = [\"edit listing durations\", \"Click here to edit the durations that will appear for this category.  If you do not enter any durations for this category or any of its direct parent categories the site defaults set within  LISTING SETUP > LISTING DURATIONS will be used.\"]\n
			Text[5] = [\"edit category's name, description and templates\", \"Click here to edit the listing used for this category.\"]\n
			Text[6] = [\"category description\", \"This description will appear below the category name while browsing the listings if you choose to display the category discriptions set in the SITE SETUP > BROWSING page.\"]\n
			Text[7] = [\"listing types allowed\", \"These are the allowed types of listings that can be placed in this category.  This setting applies to all this categories subcategories.\"]\n";

		//".$this->show_tooltip(6,1)."

		// Set style for tooltip
		
		$this->body .= "</script>";

		if ($category)
		{
			//edit this category after getting current info
			$show_category_name = $this->get_category_name($db,$category);
			$sql = "select * from ".$this->classified_categories_table." where category_id = ".$category;
			$result = $this->db->Execute($sql);
			 if (!$result)
			 {
				//$this->body .= $sql." is the query<br>\n";
				$this->error_message = $this->messages[3501];
				return false;
			 }
			 elseif ($result->RecordCount() == 1)
			 {
			 	$show_category = $result->FetchRow();
			 }
			 else
			 {
			 	return false;
			 }

			//add
			if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=".$_GET['mc']."&page=categories_add&c=".$category." method=post>\n";
			$this->body .= "<fieldset id='NewCat'>
				<legend>New Category Details</legend><table cellpadding=3 cellspacing=0 border=0 width=100%>\n";
			//$this->title .= "Categories Setup > Add New Category";
			//$this->description .= "	Insert a new Subcategory into the <b>".$show_category_name."</b> Category.";

			//$this->body .= "<tr class=".$this->get_row_color().">\n\t<td align=right valign=top class=medium_font>\n\tcategory name \n\t</td>\n\t";
			//$this->body .= "<td valign=top>\n\t<input type=text name=b[category_name]>\n\t</td>\n</tr>\n";


			//$this->body .= "<tr class=".$this->get_row_color().">\n\t<td align=right valign=top class=medium_font>\n\tcategory description:".$this->show_tooltip(6,1)."</td>\n\t";
			//$this->body .= "<td valign=top>\n\t<textarea name=b[description] cols=30 rows=3></textarea>\n\t</td>\n</tr>\n";

			$this->row_count = 0;
			$this->body .= "<tr class=".$this->get_row_color().">\n\t<td align=right valign=top class=medium_font>\n\t<strong>Category Order:</strong>".$this->show_tooltip(1,1)."</td>\n\t";
			$this->body .= "<td>\n\t<select name=b[display_order]>\n\t\t";
			for ($i=1;$i<500;$i++)
			{
				$this->body .= "<option ";
				if ($i == $show_category["display_order"])
					$this->body .= "selected";
				$this->body .= ">".$i."</option>\n\t\t";
			}
			$this->body .= "</select>\n\t</td>\n</tr>\n";
			$this->row_count++;

			$this->body .= "<tr class=".$this->get_row_color().">\n\t<td align=right valign=top class=medium_font>\n\t<strong>URL of Category Icon:</strong>".$this->show_tooltip(2,1)."</td>\n\t";
			$this->body .= "<td valign=top><label><em>".$admin->geo_templatesDir()."[Template Set]/external/</em><input type=text name=b[category_image] size=30 maxsize=100 /></label>\n\t</td>\n</tr>\n";
			$this->row_count++;

			if(geoMaster::is('classifieds') && geoMaster::is('auctions'))
			{
				$this->body .= "<tr class=".$this->get_row_color().">\n\t<td align=right valign=top class=medium_font>\n\t<strong>Listing Types allowed in this category:</strong>".$this->show_tooltip(7,1)."</td>\n\t";
				$this->body .= "<td valign=top class=medium_font>";
				$this->body .= "<input type=\"radio\" name=\"b[listing_types_allowed]\" value=\"0\" ";
				if(($show_category['listing_types_allowed'] == 0))
					$this->body .= "checked";
				$this->body .= ">Classified Ads and Auctions<Br>";
				$this->body .= "<input type=\"radio\" name=\"b[listing_types_allowed]\" value=\"1\" ";
				if($show_category['listing_types_allowed'] == 1)
					$this->body .= "checked";
				$this->body .= ">Classified Ads only<Br>";
				$this->body .= "<input type=\"radio\" name=\"b[listing_types_allowed]\" value=\"2\" ";
				if($show_category['listing_types_allowed'] == 2)
					$this->body .= "checked";
				$this->body .= ">Auctions only";
				$this->body .= "</td></tr>";
				$this->row_count++;
			}

			$sql = "select distinct(language_id) from ".$this->pages_languages_table." order by language_id asc";
			$language_result = $this->db->Execute($sql);
			//echo $sql." is the query<br>\n";
			if (!$language_result)
			 {
				//echo $sql." is the query<br>\n";
				$this->error_message = $this->messages[3501];
				return false;
			 }
			 elseif ($language_result->RecordCount() > 0)
			 {
			 	while ($show = $language_result->FetchRow())
			 	{
			 		$this->body .= "<tr>\n\t<td colspan=2 class=col_hdr_left>\n\tCategory Name and Description for Language: ".$this->get_language_name($db,$show["language_id"])." \n\t</td>\n\t";
			 		$this->body .= "<tr class=".$this->get_row_color()."><td align=right valign=top class=medium_font><strong>Parent Category:</strong> </td><td class=\"medium_font\">$show_category_name</td></tr>";
					$this->row_count++;
					$this->body .= "<tr class=".$this->get_row_color().">\n\t<td align=right valign=top class=medium_font>\n\t<b>Category Name:</b> \n\t</td>\n\t";
					$this->body .= "<td valign=top>\n\t<input type=text name=b[".$show["language_id"]."][category_name] />\n\t</td>\n</tr>\n";
					$this->row_count++;

					$this->body .= "<tr class=".$this->get_row_color().">\n\t<td align=right valign=top class=medium_font>\n\t<b>Category Description:</b>".$this->show_tooltip(6,1)."</span></td>\n\t";
					$this->body .= "<td valign=top>\n\t<textarea name=b[".$show["language_id"]."][description] cols=30 rows=3></textarea>\n\t</td>\n</tr>\n";
					$this->row_count++;
				}
			}

			if (!$this->admin_demo())
				$this->body .= "<tr>\n\t<td colspan=2 align=center><input type=submit value=\"Save\" name='auto_save'></td>\n</tr>\n";

			$this->body .= "</table></fieldset>\n</form>\n";
		}
		else
		{
			//this is the main category
			//you can only add a category to the main category
			//there is no edit of the main category
			if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=".$_GET['mc']."&page=categories_add&c=0 method=post>\n";
			$this->body .= "<fieldset id='NewCat'>
				<legend>New Category Details</legend><table cellpadding=3 cellspacing=1 border=0 width=100%>\n";
			//$this->title .= "Categories Setup > Add New Category";
			
			$this->row_count = 0;
			$this->body .= "<tr class='row_color1'>\n\t<td align=right valign=top class=medium_font width=50%>\n\t<b>Category Order:</b>".$this->show_tooltip(1,1)."</td>\n\t";
			$this->body .= "<td>\n\t<select name=b[display_order]>\n\t\t";
			for ($i=1;$i<500;$i++)
			{
				$this->body .= "<option ";
				if ($i == $show_category["display_order"])
					$this->body .= "selected";
				$this->body .= ">".$i."</option>\n\t\t";
			}
			$this->body .= "</select>\n\t</td>\n</tr>\n";
			$this->row_count++;

			$this->body .= "<tr class='row_color2'>\n\t<td align=right valign=top class=medium_font>\n\t<b>URL of Category Icon:</b>".$this->show_tooltip(2,1)."</td>\n\t";
			$this->body .= "<td valign=top><label><em>".$admin->geo_templatesDir()."[Template Set]/external/</em><input type=text name=b[category_image] value=\"".$show_category["category_image"]."\" size=30 maxsize=100 /></label>\n\t</td>\n</tr>\n";
			$this->row_count++;

			if(geoMaster::is('classifieds') && geoMaster::is('auctions'))
			{
				$this->body .= "<tr class='row_color1'>\n\t<td align=right valign=top class=medium_font>\n\t<b>Listing Types Allowed:</b>".$this->show_tooltip(7,1)."</td>\n\t";
				$this->body .= "<td valign=top class=medium_font>";
				$this->body .= "<input type=\"radio\" name=\"b[listing_types_allowed]\" value=\"0\" ";
				if(($show_category['listing_types_allowed'] == 0))
					$this->body .= "checked";
				$this->body .= ">Classified Ads and Auctions<Br>";
				$this->body .= "<input type=\"radio\" name=\"b[listing_types_allowed]\" value=\"1\" ";
				if($show_category['listing_types_allowed'] == 1)
					$this->body .= "checked";
				$this->body .= ">Classified Ads only<Br>";
				$this->body .= "<input type=\"radio\" name=\"b[listing_types_allowed]\" value=\"2\" ";
				if($show_category['listing_types_allowed'] == 2)
					$this->body .= "checked";
				$this->body .= ">Auctions only";
				$this->body .= "</td></tr>";
				$this->row_count++;
			}

			$sql = "select distinct(language_id) from ".$this->pages_languages_table." order by language_id asc";
			$language_result = $this->db->Execute($sql);
			//echo $sql." is the query<br>\n";
			 if (!$language_result)
			 {
				//echo $sql." is the query<br>\n";
				$this->error_message = $this->messages[3501];
				return false;
			 }
			 elseif ($language_result->RecordCount() > 0)
			 {
			 	while ($show = $language_result->FetchRow())
			 	{
			 		$this->body .= "<tr>\n\t<td colspan=2 class=col_hdr_left>\n\tCategory Name and Description for Language: <b>".$this->get_language_name($db,$show["language_id"])."</b></td>\n\t";
					$this->body .= "<tr  class='row_color1'>\n\t<td align=right valign=top class=medium_font>\n\t<b>Category Name:</b></td>\n\t";
					$this->body .= "<td valign=top>\n\t<input type=text name=b[".$show["language_id"]."][category_name] value=\"".$show_category_name."\">\n\t</td>\n</tr>\n";
					$this->body .= "<tr class='row_color2'>\n\t<td align=right valign=top class=medium_font>\n\t<b>Category Description:</b>".$this->show_tooltip(6,1)."</td>\n\t";
					$this->body .= "<td valign=top>\n\t<textarea name=b[".$show["language_id"]."][description] cols=30 rows=3></textarea>\n\t</td>\n</tr>\n";
					$this->row_count++;
				}
			}


			if (!$this->admin_demo())
				$this->body .= "<tr>\n\t<td align=center colspan=2><input type=submit value=\"Save\" name='auto_save'></td>\n</tr>\n";

			$this->body .= "</table></fieldset>\n</form>\n";
		}
		return true;

	} //end of function display_category_form
	
	
	
	function insert_category($db,$info,$parent_category=0)
	{
		if (!is_object($this->db)){
			$this->db = DataAccess::getInstance();
		}
		$sql = "select * from ".$this->pages_languages_table;
		$language_result = $this->db->Execute($sql);
		if (!$language_result) {
			trigger_error('ERROR SQL CATEGORY: Sql execute error.  Sql:'.$sql.' db reported:'.$this->db->ErrorMsg());
			$this->error_message = $this->messages[3500];
			return false;
		} elseif ($language_result->RecordCount() > 0) {

			//if only one listing type, this value not present...make sure it defaults to 0 or the query will error
			$info['listing_types_allowed'] = $info['listing_types_allowed'] ? $info['listing_types_allowed'] : 0;
						
			$sql = "insert into ".geoTables::categories_table."
				(`parent_id`,`category_name`,`description`,`display_order`,`category_image`,`listing_types_allowed`)
				values (?, ?, ?, ?, ?, ?)";
			$query_data = array ($parent_category,$info[1]["category_name"].'',$info[1]["description"].'',$info["display_order"],$info["category_image"].'',$info['listing_types_allowed']);
			$result = $this->db->Execute($sql, $query_data);
			if (!$result) {
				$this->error_message = $this->messages[3500];
				echo 'here with error: '.$this->db->ErrorMsg();
				return false;
			}
			$category_id = $this->db->Insert_ID();
			
			//add to languages table
			while ($show = $language_result->FetchRow()) {
				$sql = "insert into ".geoTables::categories_languages_table."
				(category_id,category_name,description,language_id)
				values (?, ?, ?, ?)";
				$query_data = array ($category_id,
						geoString::toDB($info[$show["language_id"]]["category_name"]),geoString::toDB($info[$show["language_id"]]["description"]),(int)$show["language_id"]);
				$result = $this->db->Execute($sql, $query_data);
				if (!$result) {
					$this->error_message = $this->messages[3500];
					return false;
				}
			}
			
			//update the in statement of this and any parent categories
			geoCategory::updateInStatement($category_id);
		}

		return $category_id;

	} // end of function insert_category

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function delete_category_check($db,$category=0)
	{
		if (strlen(PHP5_DIR)>0){
			$this->db = DataAccess::getInstance();
		} else {
			$this->db =& DataAccess::getInstance();
		}
		if ($category)
		{
			$category_name = $this->get_category_name($db,$category);
			$sql = "select * from ".$this->classified_categories_table." where category_id = ".$category;
			$result = $this->db->Execute($sql);
			if (!$result)
			{
				//echo $sql." is the query<br>\n";
				$this->error_message = $this->messages[3500];
				return false;
			}
			elseif ($result->RecordCount() == 1)
			{
				$show = $result->FetchRow();
				if ($show["parent_id"])
				{
					$parent_name = $this->get_category_name($db,$show["parent_id"]);

					$sql = "select * from ".$this->classified_categories_table." where category_id = ".$show["parent_id"];
					$result = $this->db->Execute($sql);
					if (!$result)
					{
						$this->error_message = $this->messages[3500];
						return false;
					}
					elseif ($result->RecordCount() == 1)
					{
						$show_parent = $result->FetchRow();

						$this->body .= "<table cellpadding=2 cellspacing=1 border=0 width=100%>\n";
						//$this->title .= "Categories Setup > Delete Category";
						//$this->description .= "Verify deletion of a category and choose what to do with listings within it.";
						$this->body .= "<tr>\n\t<td class=page_note>Are you sure you want to
							delete the <strong>".$category_name."</strong> Category?  If so, choose whether or not you want
							to move this category's existing listings to it's Parent Category<strong> ( ".
							$parent_name.")</strong> or delete them along with the category.
							 \n\t</td>\n</tr>\n";
							$this->body .= "<tr>\n\t<td align=center><a href='index.php?mc=".$_GET['mc']."&page=categories_delete&b=".$category."&c=move&auto_save=1' class='lightUpLink'>
							<span class=medium_font><br><br><font color=000000>Move all listings to the ".
							$parent_name." Category</font></span></a>\n\t</td>\n</tr>\n";
						$this->body .= "<tr>\n\t<td class=medium_font align=center><b>--OR--</b>\n\t</td>\n</tr>\n";
						$this->body .= "<tr>\n\t<td align=center><a href='index.php?mc=".$_GET['mc']."&page=categories_delete&b=".$category."&c=delete&auto_save=1' class='lightUpLink'>
							<span class=medium_font align=center><font color=000000>Delete all listings and the <b>".$category_name."</b> Category</font>
							</span></a>\n\t</td>\n</tr>\n";
						$this->body .= "<tr>\n\t<td class=medium_error_font align=center>\n\t<b><br><br>Note: All category specific questions will be removed
							from the database.</b></td>\n</tr>\n";
						$this->body .= "</table>\n";
					}
					else
					{
						//echo $sql." is the query<br>\n";
						$this->error_message = $this->messages[3500];
						return false;
					}
				}
				else
				{
					//delete a main category
					$this->body .= "<table cellpadding=2 cellspacing=1 border=0 width=100%>\n";
					//$this->title .= "Categories Setup > Delete a Main Category";
					//$this->description .= "Verify deletion of a category and choose what to do with listings within it. \n\t</td>\n</tr>\n";
					$this->body .= "\n<tr>\n\t<td class=page_note>Are you sure you want to
						delete the <b>".$category_name."</b> category?  Deleting this category will also delete the listings and subcategories currently within it. \n\t</td>\n</tr>\n";
					$this->body .= "<tr>\n\t<td class=medium_error_font align=center>\n\t<div class='page_note_error'>Note: All category specific questions will be removed
						from the database. </div></td>\n</tr>\n";
					$this->body .= "<tr>\n\t<td align=center><a href='index.php?mc=".$_GET['mc']."&page=categories_delete&b=".$category."&c=delete&auto_save=1' class='lightUpLink'><span class=medium_font>
						Delete the <strong>".$category_name."</strong> category?</span></a>\n\t</td>\n</tr>\n";

					$this->body .= "</table>\n";
				}
				return true;
			}
			else
			{
				$this->error_message = $this->messages[3500];
				return false;
			}
		}
		else
		{
			$this->error_message = $this->messages[3509];
			return false;
		}
	} // end of function delete_category_check

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function delete_category($category=0,$type_of_delete=0)
	{
		$this->db = DataAccess::getInstance();
		
		$category = (int)$category;
		if (!$category) {
			geoAdmin::m('No valid category to remove specified!');
			return false;
		}
		
		$row = $this->db->GetRow("SELECT `parent_id` FROM ".geoTables::categories_table." WHERE `category_id`={$category}");
		
		$moveTo = ($type_of_delete == 'move')? true : null;
		if (!geoCategory::remove($category, $moveTo)) {
			geoAdmin::m('There was an error when attempting to remove the category.',geoAdmin::ERROR);
			return false;
		}
		//re-count parent - no longer needed!  done as part of geoCategory::remove()
		
		return true;
	} // end of function delete_category

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function update_category($db,$category,$info2=0)
	{
		$this->db = DataAccess::getInstance();
		
		if (($category) && ($info2)) {
			$sql = "update ".$this->classified_categories_table." set
				display_order = ".$info2["display_order"].",
				category_image = \"".$info2["category_image"]."\",
				listing_types_allowed = \"".$info2['listing_types_allowed']."\"
				where category_id = ".$category;
			$result = $this->db->Execute($sql);
			//echo $sql." is the query<br>\n";
			if (!$result) {
				//echo $sql." is the query<br>\n";
				$this->error_message = $this->messages[3500];
				return false;
			}
			//update in statement
			geoCategory::updateInStatement($category);
			return true;
		} else {
			$this->error_message = $this->messages[3503];
			return false;
		}
	} // end of function update_category
	
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	public function browse ($category=0)
	{
		$this->db = DataAccess::getInstance();
		$category = (int)$category;
		
		$tpl_vars = array();
		
		$tpl_vars['admin_msgs'] = geoAdmin::m();
		$tpl_vars['category_id'] = $category;
		//browse the listings in this category that are open
		
		$sql = "SELECT * FROM ".geoTables::categories_table." WHERE `parent_id` = {$category} ORDER BY `display_order`, `category_name`";
		$result = $this->db->Execute($sql);
		//echo $sql." is the query<br>\n";
		if (!$result) {
			//echo $sql." is the query<br>\n";
			$this->error_message = $this->messages[5501];
			return false;
		}
		$categories = array ();
		foreach ($result as $row) {
			$row['category_name'] = $this->get_category_name($db,$row["category_id"]);
			
			$try = $this->db->GetRow("SELECT * FROM ".geoTables::categories_table." WHERE `parent_id`={$row['category_id']}");
			if ($try) {
				$row['subcats'] = true;
			}
			$categories[$row['category_id']] = $row;
		}
		$tpl_vars['categories'] = $categories;
		
		if ($category) {
			$this->get_category_tree(0, $category);
			$tree = array();
			$this->category_tree_array = (array)$this->category_tree_array;
			while ($this->category_tree_array) {
				$tree[] = array_pop($this->category_tree_array);
			}
			$tpl_vars['cat_tree'] = $tree;
		}
		$tpl_vars['is_class_auctions'] = geoMaster::is('classifieds') && geoMaster::is('auctions');
		
		$language_rows = $this->db->GetAll("SELECT * FROM ".geoTables::pages_languages_table." ORDER BY `language_id`");
		$languages = array();
		foreach ($language_rows as $row) {
			$text = $this->db->GetRow("SELECT `text` FROM ".geoTables::pages_text_languages_table." WHERE `page_id`=3 AND `text_id`=500961 AND `language_id`={$row['language_id']}");
			
			$row['header_html'] = geoString::fromDB($text['text']);
			$languages[$row['language_id']] = $row;
		}
		
		$tpl_vars['languages'] = $languages;
		$tpl_vars['header_html_tooltip'] = geoHTML::showTooltip('Default Contents to add to {header_html} for LANGUAGE',
					"For any categories that have <strong>Add extra to {header_html} from:</strong> set to \"Default\", it will
					use the text here for that language.  Enter the default text you wish to be added to the {header_html} tag contents.
		 			<br /><br />".$this->header_html_tpl_tip());
		
		geoView::getInstance()->setBodyTpl('categories/list_categories.tpl')
			->setBodyVar($tpl_vars);
		
		return true;
	} //end of function browse

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	
	function category_error()
	{
		$this->body .= "<table cellpadding=5 cellspacing=1 border=0 width=\"100%\">\n";
		$this->body .= "<tr>\n\t<td><div class=page_note_error>".$this->messages[3510]."</div></td>\n</tr>\n";
		if ($this->error_message)
			$this->body .= "<tr>\n\t<td>".$this->error_messages."</td>\n</tr>\n";
		$this->body .= "</table>\n";
	} //end of function category_error

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function migrate_languages_categories($db)
	{
		if (strlen(PHP5_DIR)>0){
			$this->db = DataAccess::getInstance();
		} else {
			$this->db =& DataAccess::getInstance();
		}
		if (strlen(PHP5_DIR)>0){
			$this->db = DataAccess::getInstance();
		} else {
			$this->db =& DataAccess::getInstance();
		}
		$sql = "select * from ".$this->pages_languages_table." where language_id != 1";
		$result = $this->db->Execute($sql);
		//echo $sql." is the query<br><br>\n";
		if (!$result)
		{
			//echo $sql." is the query<br>\n";
			$this->error_message = $this->messages[5501];
			return false;
		}
		while ($show_language = $result->FetchRow())
		{
			$sql = "select * from ".$this->classified_categories_table;
			$category_result = $this->db->Execute($sql);
			//echo $sql." is the query<br><br>\n";
			if (!$category_result)
			{
				//echo $sql." is the query<br>\n";
				$this->error_message = $this->messages[5501];
				return false;
			}
			while ($show_category = $category_result->FetchRow())
			{
				$sql = "insert into ".$this->classified_categories_languages_table."
					(category_id,category_name,description,language_id)
					values
					(".$show_category["category_id"].",\"".addslashes(urlencode($show_category["category_name"]))."\",\"".addslashes(urlencode($show_category["description"]))."\",".$show_language["language_id"].")";
				$insert_result = $this->db->Execute($sql);
				//echo $sql." is the query<br>\n";
				if (!$insert_result)
				{
					//echo $sql." is the query<br>\n";
					$this->error_message = $this->messages[5501];
					return false;
				}
			}
		}
	}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function add_category_specific_length($db,$new_length_info=0,$category_id=0)
	{
		if ( !geoPC::is_ent() )
			return false;
		if (strlen(PHP5_DIR)>0){
			$this->db = DataAccess::getInstance();
		} else {
			$this->db =& DataAccess::getInstance();
		}
		if ($this->debug_categories)
		{
			echo "<BR>ADD_CATEGORY_SPECIFIC_LENGTHS<br>\n";
			echo $new_length_info["display_length_of_ad"]." is new_length_info - display_length_of_ad<br>\n";
			echo $new_length_info["length_of_ad"]." is new_length_info - length_of_ad<br>\n";
			echo $category_id." is category_id<br>\n";
		}
		if (($category_id) && ($new_length_info))
		{
			//check length_of_ad to see if int
			//check length_charge to see if double or int
			if (ereg("[0-9]+", $new_length_info["length_of_ad"]))
			{
				$this->sql_query = "select * from  ".$this->classified_price_plan_lengths_table."
					where length_of_ad = ".$new_length_info["length_of_ad"]." and price_plan_id = 0 and category_id = ".$category_id;
				$result = $this->db->Execute($this->sql_query);
				if ($this->debug_categories) echo $this->sql_query."<Br>";
				if (!$result)
				{
					if ($this->debug_categories)
					{
						echo $this->sql_query."<Br>";
						echo $this->db->ErrorMsg()."<br>\n";
					}
					return false;
				}
				elseif ($result->RecordCount() == 0 )
				{
					$this->sql_query = "insert into ".$this->classified_price_plan_lengths_table."
						(price_plan_id,category_id,length_of_ad,display_length_of_ad,length_charge,renewal_charge)
						values
						(0,".$category_id.",".$new_length_info["length_of_ad"].",\"".$new_length_info["display_length_of_ad"]."\",0,0)";
					$insert_result = $this->db->Execute($this->sql_query);
					if ($this->debug_categories) echo $this->sql_query."<Br>";
					if (!$insert_result)
					{
						if ($this->debug_categories)
						{
							echo $this->sql_query."<Br>";
							echo $this->db->ErrorMsg()."<br>\n";
						}
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
				//$this->ad_configuration_message = "Please only enter numbers for number of days";
				return false;
			}
		}
		else
		{
			$this->error_message = $this->internal_error_message;
			return false;
		}
	} //end of function add_category_specific_length

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function category_specific_delete_length($db,$length_id=0)
	{
		
		if (strlen(PHP5_DIR)>0){
			$this->db = DataAccess::getInstance();
		} else {
			$this->db =& DataAccess::getInstance();
		}
		if ($length_id)
		{
			$this->sql_query = "delete from  ".$this->classified_price_plan_lengths_table." where length_id = ".$length_id;
			$result = $this->db->Execute($this->sql_query);
			//echo $this->sql_query."<Br>";
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

	function category_specific_lengths_form($db,$category_id=0)
	{
			if (strlen(PHP5_DIR)>0){
				$menu_loader = geoAdmin::getInstance();
			} else {
				$menu_loader =& geoAdmin::getInstance();
			}
			$this->body .= $menu_loader->getUserMessages();			
		
			
		if ($category_id)
		{
			$category_name = $this->get_category_name($db,$category_id);

			$this->sql_query = "select * from ".$this->classified_price_plan_lengths_table." where price_plan_id = 0 and category_id = ".$category_id." order by length_of_ad asc";
			$length_result = $this->db->Execute($this->sql_query);
			if (!$length_result)
			{
				$this->site_error($this->db->ErrorMsg());
				return false;
			}
			if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=".$_GET['mc']."&page=categories_durations&c=".$category_id." method=post>\n";
			$this->body .= "<fieldset id='CatListingDur'>
				<legend>Category Listing Durations</legend><table cellpadding='3' cellspacing='0' width='60%'>\n";
			//$this->title = "Categories Setup > Edit > Listing Durations - ".$category_name."";
			$this->title = " (".$category_name.")";
			/* $this->description = "Control the choices your users have for the length of days their
				listings are displayed when entering a listing within this category.  The table below ONLY applies to the (".$category_name.") category and
				overrides the site-wide listing durations spedified on the LISTING SETUP > LISTING DURATIONS menu of the admin.";*/
			if(strlen($this->ad_configuration_message)) {
				$this->body .= "
					<tr>
						<td colspan='2' class='medium_error_font'>".$this->ad_configuration_message."</td>
					</tr>";
			}
					
			$this->body .= "
				<tr>
					<td align=center class=col_hdr2>
						<b>Listing Duration</b><br>
						(displayed)
					</td>
					<td align=center class=col_hdr2>
						<b>Listing Duration</b><br>
						(# of days)
					</td>
					<td align=center class=col_hdr2>&nbsp;</td>
				</tr>";
			$this->row_count = 0;
			while ($show_lengths = $length_result->FetchRow())
			{
				$this->body .= "<tr class=".$this->get_row_color().">\n\t<td class=medium_font align=center>".$show_lengths["display_length_of_ad"]."</td>\n\t";
				$this->body .= "<td class=medium_font align=center>".$show_lengths["length_of_ad"]."</td>\n\t";

				$delete_button = geoHTML::addButton('Delete','index.php?page=categories_durations_delete&c='.$category_id.'&d='.$show_lengths["length_id"].'&auto_save=1', false, '', 'lightUpLink mini_cancel');
				$this->body .= "<td width=100>".$delete_button."</td>\n\t";
				$this->body .= "</tr>\n";	

				$this->row_count++;
			}
			$this->body .= "<tr>\n\t<td class=col_ftr align=center>\n\tDisplayed: <input type=text name=b[display_length_of_ad]>\n\t</td>\n\t";
			$this->body .= "<td class=col_ftr align=center>Days: <input type=text name=b[length_of_ad]></td>\n\t";
			if (!$this->admin_demo())
				$this->body .= "<td class=col_ftr align=center width=100>\n\t<input type=submit name='auto_save' value=\"Save\">\n\t</td>\n";
			$this->body .= "</tr>\n";
			$this->body .= "</table></fieldset>\n";
			
			$this->body .= "<table cellpadding='3' cellspacing='0' width='100%'>\n";		
			$this->body .= "<tr>\n\t<td colspan=5>\n\t
			    <div style='padding: 5px;'><a href=index.php?mc=categories&page=categories_setup&b=".$category_id." class='back_to'>
				<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to Categories Menu</a></div>

				<div style='padding: 5px;'><a href=index.php?mc=".$_GET['mc']."&page=categories_edit&cat=".$category_id." class='back_to'>
				<img src='admin_images/design/icon_back.gif' alt='' class='back_to'>Back to Category Settings</a></div>

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

	function reset_all_category_counts($db)
	{
		if (strlen(PHP5_DIR)>0){
			$this->db = DataAccess::getInstance();
		} else {
			$this->db =& DataAccess::getInstance();
		}
		$this->sql_query = "select * from ".$this->classified_categories_table;
		$category_result = $this->db->Execute($this->sql_query);
		//echo $this->sql_query." is the query<br><br>\n";
		if (!$category_result)
		{
			//echo $this->sql_query." is the query<br>\n";
			$this->error_message = $this->messages[5501];
			return false;
		}
		if ($category_result->RecordCount() > 0) {
			while ($show_category = $category_result->FetchRow()) {
				//don't bother updating parents since we are going through ALL categories!
				geoCategory::updateListingCount($show_category["category_id"], false);
			}
		}
		return true;
	} //end of function reset_all_category_counts

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function duplicate_category_structure($start_category,$target_category=0, $manualFrom = 0, $manualTo = 0, $copy_questions_too=0,$recursive=0, $isRecursive = 0)
	{
		$start_category = (int)(($manualFrom)? $manualFrom : $start_category);
		$target_category = (int)(($manualTo)? $manualTo : $target_category);
		
		//target_category CAN be 0, for copying to main.
		if (!$start_category) {
			geoAdmin::m('Invalid from category specified, not able to copy categories.',geoAdmin::ERROR);
			return false;
		}
		
		$sql = "SELECT * FROM ".geoTables::categories_table." WHERE `parent_id` = $start_category";
		$start_result = $this->db->GetAll($sql);
		
		if (!$start_result) {
			if ($isRecursive) {
				//return true, we've reached the end
				return true;
			}
			geoAdmin::m('No sub-categories found for selected FROM category, not able to copy sub-categories.', geoAdmin::NOTICE);
			return false;
		}
		
		//double check the TO category, but only need to check it once, not on every recursive call...
		//skip this step if copying to main category
		if (!$isRecursive && $target_category) {
			$sql = "SELECT * FROM ".geoTables::categories_table." WHERE `category_id` = $target_category";
			$endCheck = $this->db->GetAll($sql);
			
			if (!$endCheck) {
				geoAdmin::m('Category specified for TARGET category does not exist.', geoAdmin::NOTICE);
				return false;
			}
		}
		
		$sql = "select `language_id` from ".geoTables::pages_languages_table;
		$language_rows = $this->db->GetAll($sql);
		$languages = array();
		foreach ($language_rows as $row) {
			$languages[] = $row['language_id'];
		}
		if (!in_array(1, $languages)) $languages[] = 1;
		
		//keep track of specific cats being copied to top level so we can
		//update in statements at end
		if (!$isRecursive && !$target_category) $cats = array();
		
		//there are some categories to copy - do it
		foreach ($start_result as $starting_subcategory) {
			$sql = "INSERT INTO ".geoTables::categories_table."
				(parent_id,category_name,description,display_order,category_image)
				VALUES (?, ?, ?, ?, ?)";
			$query_data = array ($target_category, $starting_subcategory["category_name"].'', $starting_subcategory["description"].'',
				$starting_subcategory["display_order"],$starting_subcategory["category_image"].'');
			$result = $this->db->Execute($sql, $query_data);
			
			if (!$result) {
				geoAdmin::m('DB Error, copying failed.  Debug information: '.__line__.' SQL: "'.$sql.'" Error msg: '.$this->db->ErrorMsg(), geoAdmin::ERROR);
				
				return false;
			}
			$category_id = $this->db->Insert_ID();
			
			if (!$isRecursive && !$target_category && $category_id) $cats[] = $category_id;
			
			if ($copy_questions_too) {
				$this->duplicate_category_questions($starting_subcategory['category_id'],$category_id);
			}

			foreach ($languages as $language) {
				$sql = "SELECT * FROM ".geoTables::categories_languages_table."
					WHERE `category_id` = ".$starting_subcategory["category_id"]."
					AND `language_id` = $language";
				
				$show_category_language = $this->db->GetRow($sql);
				if (!$show_category_language) {
					//problem with language, use default settings
					geoAdmin::m('DB Error, copying failed.  Debug information: '.__line__.' SQL: "'.$sql.'" Error msg: '.$this->db->ErrorMsg(), geoAdmin::ERROR);
					
					return false;
				}
				
				$sql = "INSERT INTO ".geoTables::categories_languages_table."
					(category_id,category_name,description,language_id)
					values (?, ?, ?, ?)";
				$query_data = array ($category_id,
					$show_category_language["category_name"],$show_category_language["description"],$language);
				$insert_category_language_result = $this->db->Execute($sql, $query_data);
				if (!$insert_category_language_result) {
					geoAdmin::m('DB Error, copying failed.  Debug information: '.__line__.' SQL: "'.$sql.'" Error msg: '.$this->db->ErrorMsg(), geoAdmin::ERROR);
					
					return false;
				}
			}

			if ($recursive) {
				//do a recursive call to all subcategories to copy them also
				$result = $this->duplicate_category_structure($starting_subcategory["category_id"],$category_id,0,0,$copy_questions_too,$recursive, 1);
				if (!$result) {
					//one of the sub-categories failed, don't continue
					return false;
				}
			}
		} // end of while

		//reset sql in statement
		if (!$isRecursive) {
			if ($target_category) {
				//easy!  All categories copied were copied to single parent,
				//so just update in statement for that parent, it will do rest...
				geoCategory::updateInStatement($target_category);
			} else if (isset($cats) && count($cats)) {
				foreach ($cats as $parent) {
					//update each of the top categories...
					geoCategory::updateInStatement($parent);
				}
			}
		}
		return true;
	} // end of function duplicate_category_structure

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function duplicate_structure_form() {
		$this->body .= "<SCRIPT language=\"JavaScript1.2\">";
		// Set title and text for tooltip
		$this->body .= "Text[1] = [\"copy immediate subcategories of this category\", \"This is the category whose subcategories you wish to copy to another category.\"]\n
			Text[2] = [\"category you want the subcategories copied to\", \"This is the category you wish to copy the subcategories to.\"]\n
			Text[3] = [\"copy category specific questions also\", \"Checking \\\"yes\\\" will copy the category specific fields/questions of each subcategory over to the new subcategories.\"]\n
			Text[4] = [\"copy all sub categories recursively\", \"Checking \\\"yes\\\" will copy the subcategories as well as all of their subcategories also...recursively.\"]\n";

		//".$this->show_tooltip(1,1)."

		// Set style for tooltip
		//echo "Style[0] = [\"white\",\"\",\"\",\"\",\"\",,\"black\",\"#ffffcc\",\"\",\"\",\"\",,,,2,\"#b22222\",2,24,0.5,0,2,\"gray\",,2,,13]\n";
		$this->body .= "</script>";

		if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=".$_GET['mc']."&page=categories_copy_subcats method=post>\n";
		$this->body .= "<fieldset id='CopySubCat'>
				<legend>Copy Subcategories</legend><table cellpadding=3 cellspacing=0 border=0 width=100%>\n";
		//$this->title = "Categories Setup > Copy Subcategories";
/*		$this->description = "This form will allow you to copy a category's subcategories to another category.  Please carefully select the appropriate settings below
		before saving your selection.";*/
		
		$this->body .= "<tr class=row_color1>\n\t<td align=right valign=top class=medium_font width=\"50%\">\n\t<b>Copy Immediate Subcategories FROM:</b>".$this->show_tooltip(1,1)."</td>\n\t";
		$this->body .= "<td valign=top width=\"50%\">\n\t";
		$this->get_category_dropdown("b",0,1,$this->db->get_site_setting("levels_of_categories_displayed_admin"));
		$this->body .= $this->dropdown_body;
		$this->body .= " OR <label>Category ID: <input type='text' name='fromID' size='5' value='' /></label></td></tr>\n";

		$this->body .= "<tr class=row_color2>\n\t<td align=right valign=top class=medium_font>\n\t<b>Copy Immediate Subcategories TO:</b>".$this->show_tooltip(2,1)."</td>\n\t";
		$this->body .= "<td valign=top>\n\t";
		$this->get_category_dropdown("c",0,0,$this->db->get_site_setting("levels_of_categories_displayed_admin"), 1, 'Main');
		$this->body .= $this->dropdown_body;
		$this->body .= " OR <label>Category ID: <input type='text' name='toID' size='5' value='' /></label></td></tr>\n";

		$this->body .= "<tr class=row_color1>\n\t<td align=right valign=top class=medium_font>\n\t<b>Also Include Category Specific Questions:</b>".$this->show_tooltip(3,1)."</td>\n\t";
		$this->body .= "<td valign=top class=medium_font>\n\tyes<input type=radio name=d value=1 class=medium_font><br>
			no<input type=radio name=d value=0 checked class=medium_font></td></tr>\n";

		$this->body .= "<tr class=row_color2>\n\t<td align=right valign=top class=medium_font>\n\t<b>Also Include ALL Subcategories Recursively:</b>".$this->show_tooltip(4,1)."</td>\n\t";
		$this->body .= "<td valign=top class=medium_font>\n\tyes<input type=radio name=e value=1 class=medium_font><br>
			no<input type=radio name=e value=0 checked class=medium_font><div class=page_note_error>PLEASE NOTE THAT IF THERE ARE HUNDREDS OR THOUSANDS OF
			SUBCATEGORIES TO COPY YOU MAY REACH THE MAXIMUM EXECUTION STATS FOR PHP ON YOUR SERVER...STOPPING THE PROCEDURE
			BEFORE IT IS FINISHED.</div></td></tr>\n";
		if (!$this->admin_demo())
		{
			$this->body .= "<tr>\n\t<td colspan=2 class=medium_font align=center>
				<input type=submit value=\"Save\" name='auto_save'> \n\t</td>\n</tr>\n";
		}
		$this->body .= "</table></fieldset></form>";
		return true;

	} //end of function duplicate_structure_form

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function duplicate_category_questions($start_category,$target_category=0)
	{
		$this->db = DataAccess::getInstance();
		//clean inputs
		$start_category = (int)$start_category;
		$target_category = (int)$target_category;
		if (!$start_category || !$target_category) {
			geoAdmin::m('From and/or TO category required.',geoAdmin::ERROR);
			return false;
		}
		
		//since entering category ID is allowed, check the TO category
		$sql = "SELECT * FROM ".geoTables::categories_table." WHERE `category_id` = $target_category LIMIT 1";
		$row = $this->db->GetRow($sql);
		if (!$row) {
			geoAdmin::m("Invalid TO category specified (category ID $target_category), please verify the category you are copying questions to.".$this->db->ErrorMsg(), geoAdmin::ERROR);
			return false;
		}
		$this->sql_query = "select * from ".$this->sell_questions_table." where category_id = $start_category order by display_order ASC";
		$category_question_result = $this->db->Execute($this->sql_query);
		if ($this->debug_categories) echo $this->sql_query."<br>\n";
		if (!$category_question_result) {
			if ($this->debug_categories) echo $this->sql_query."<br>\n";
			$this->error_message = $this->messages[5501];
			return false;
		}
		if ($category_question_result->RecordCount() > 0) {
			while ($show_category_question = $category_question_result->FetchRow()) {
				$this->sql_query = "insert into ".$this->sell_questions_table."
					(category_id, name, explanation, choices, other_input, display_order)
					values
					($target_category, ?, ?, ?, ?, ? )";
				if ($this->debug_categories) echo $this->sql_query."<br>\n";
				$qData = array ($show_category_question["name"],
					$show_category_question["explanation"],
					$show_category_question["choices"],
					$show_category_question["other_input"],
					$show_category_question["display_order"],
				);
				
				$result = $this->db->Execute($this->sql_query, $qData);
				$insert_id = $this->db->Insert_ID();
				if (!$result) {
					if ($this->debug_categories) echo $this->sql_query."<br>\n";
					$this->error_message = $this->messages[5501];
					return false;
				}
				
				//get language specific portions of category question
				$this->sql_query = "select * from geodesic_classifieds_sell_questions_languages where question_id = ".$show_category_question["question_id"];
				$question_language_result = $this->db->Execute($this->sql_query);					
				
				//insert the language specific 
				while($language_specific = $question_language_result->FetchRow())
				{
					$input = array( $insert_id, $language_specific["language_id"], $language_specific["name"], 
									$language_specific["explanation"], $language_specific["choices"]);						
					$this->sql_query = "insert into geodesic_classifieds_sell_questions_languages
						(question_id, language_id, name, explanation, choices)
						values (?,?,?,?,?)";						
					$insert_result = $this->db->Execute($this->sql_query,$input);
					if ($this->debug_questions) echo $this->sql_query."<br>\n";
					if (!$insert_result)
					{
						//echo $this->sql_query." is the query<br>\n";
						if ($this->debug_questions)
						{
							echo $db->ErrorMsg()." is the error<br>\n";
							echo $insert_id." is \$insert_id<br>\n";
							echo $language_specific["language_id"]." is \$language_id[language_id]<br>\n";
							echo $language_specific["name"]." is \$language_specific[\"name\"]<br>\n";
							echo $language_specific["explanation"]." is \$language_specific[\"explanation\"]<br>\n";
						}
						$this->error_message = $this->messages[5501];
						return false;
					}						
					
				}
			}
		} else {
			//debug: geoAdmin::m('No questions found for the FROM category! '.$start_category,geoAdmin::NOTICE);
		}
		return true;
	} // end of function duplicate_category_questions

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

	function duplicate_questions_form()
	{
		if (strlen(PHP5_DIR)>0){
			$this->db = DataAccess::getInstance();
		} else {
			$this->db =& DataAccess::getInstance();
		}
		$this->body .= "<SCRIPT language=\"JavaScript1.2\">";
		// Set title and text for tooltip
		$this->body .= "Text[1] = [\"Copy Category Questions\", \"This is the category FROM which whose questions you wish to copy to another category.\"]\n
			Text[2] = [\"Copy Category Questions\", \"This is the category you wish to copy the above category's questions TO.\"]\n";

		//".$this->show_tooltip(2,1)."

		// Set style for tooltip
		//$this->body .= "Style[0] = [\"white\",\"\",\"\",\"\",\"\",,\"black\",\"#ffffcc\",\"\",\"\",\"\",,,,2,\"#b22222\",2,24,0.5,0,2,\"gray\",,2,,13]\n";

		$this->body .= "</script>";

		if (!$this->admin_demo())$this->body .= "<form action=index.php?mc=".$_GET['mc']."&page=categories_copy_questions method=post>\n";
		$this->body .= "<fieldset id='CopyCatQuestions'>
				<legend>Copy Category Questions</legend><table cellpadding=3 cellspacing=0 border=0 width=100%>\n";
		//$this->title .= "Categories Setup - Copy Category Questions";
		//$this->description .= "This form will allow you to copy a category's category speicific questions to another category on the site.";

		$this->body .= "<tr class=row_color1>\n\t<td width=50% align=right valign=top class=medium_font>\n\t<b>Copy Questions FROM: </b>".$this->show_tooltip(1,1)."</td>\n\t";
		$this->body .= "<td valign=top>\n\t";
		$this->get_category_dropdown("b",0,1,$this->db->get_site_setting("levels_of_categories_displayed_admin"));
		$this->body .= $this->dropdown_body;
		$this->body .= " OR <label>Category ID: <input type='text' name='fromCAT' size='3' /></label>";
		$this->body .= "</td></tr>\n";

		$this->body .= "<tr class=row_color2>\n\t<td align=right valign=top class=medium_font>\n\t<b>Copy Questions TO: </b>".$this->show_tooltip(2,1)."</td>\n\t";
		$this->body .= "<td valign=top>\n\t";
		$this->get_category_dropdown("c",0,1,$this->db->get_site_setting("levels_of_categories_displayed_admin"));
		$this->body .= $this->dropdown_body;
		$this->body .= " OR <label>Category ID: <input type='text' name='toCAT' size='3' /></label>";
		$this->body .= "</td></tr>\n";
		if (!$this->admin_demo())
		{
			$this->body .= "<tr>\n\t<td colspan=2 align=center><br>
				<input type=submit value=\"Save\" name='auto_save'> \n\t</td>\n</tr>\n";
		}
		$this->body .= "</table></fieldset></form>";
		return true;

	} //end of function duplicate_questions_form

	function getCategoryData($categoryId, $fields=array()) {
		if (strlen(PHP5_DIR)>0){
			$this->db = DataAccess::getInstance();
		} else {
			$this->db =& DataAccess::getInstance();
		}
		if(!count($fields)) {
			// Set defaults
			$fields = array(
				"category_name",
				"description",
				"display_order",
				"listing_types_allowed",
				"category_image",
				"which_header_html",
				"parent_id"
			);
		}
		
		if (strlen(PHP5_DIR)>0){
			$this->db = DataAccess::getInstance();
		} else {
			$this->db =& DataAccess::getInstance();
		}
		$result = $this->db->Execute("select ".implode(", ", $fields)." from geodesic_categories where category_id = '".(int)$categoryId."'");
		if(false === $result) {
			Admin_site::error("Category does not exist", __LINE__, __FILE__);
		}
		return current($result->GetAssoc());
	}
	
	public function header_html_tpl_tip ()
	{
		return "<strong>Tip:</strong> You can <strong>use a sub-template</strong> rather than
		 				entering the text to add directly in the text box.  The template
		 				must be in your <strong>main_page</strong> templates.  To do so, in the
		 				box enter something like this:<br /><br />
		 				<code>template:template_filename.tpl</code>
		 				<br /><br />
		 				You would replace <strong>template_filename.tpl</strong> in that example
		 				with the template filename you wish to use.
		 				<br /><br />
		 				Note that this is unique to {header_html} that is added
		 				on category pages.";
	}

	function basicsForm($categoryId) {
		// Input fields should be part of the data[basics] array
		// eg. name='data[basics[someInputField]]'
		$html = '';
		$menu_loader = $admin = geoAdmin::getInstance();
		
			
		$data = Admin_categories::getCategoryData($categoryId);
		//array_fill is vulnerable to a DoS attack, so some servers disable it.
		//$typesAllowedLookup = array_fill(0, 3, "");
		$typesAllowedLookup = array('','','');
		$typesAllowedLookup[$data["listing_types_allowed"]] = " checked";
		
		$sql = "select distinct(language_id) from ".$this->pages_languages_table." order by language_id asc";
		$language_result = $this->db->Execute($sql);
				
		if (!$language_result)
		 {
		 	trigger_error("ERROR SQL: " . $db->ErrorMsg());
			$menu_loader->userError("Internal error. Please contact <a href='http://www.geodesicsolutions.com/support/index.htm'>support</a>.");
			$this->body .= $menu_loader->getUserMessages();
			return false;
		 }
		 elseif ($language_result->RecordCount() > 0)
		 {
		 	
		 	$language_html = '';
		 	
		 	while ($show = $language_result->FetchRow())
		 	{
		 		$sql = 'select category_name, description, header_html from '.$this->db->geoTables->categories_languages_table.' where' .
		 			' category_id=? and language_id = ? order by language_id asc';
		 		$query_data = array($categoryId, $show['language_id']);
		 		$result = $this->db->Execute($sql, $query_data);
		 		if (false === $result){
		 			trigger_error('ERROR SQL: query:'.$sql.' ERRor:'.$this->db->ErrorMsg());
		 		}
		 		$this_cat = $result->FetchRow();
		 		$tooltip = geoHTML::showTooltip('{header_html} added contents', "Enter the text you wish to be
		 			added to the {header_html} tag contents specific to this category.  Which text
		 			is loaded depends on the <strong>Add extra to {header_html} from:</strong> setting above.
		 			<br /><br />".$this->header_html_tpl_tip());
		 		
		 		$language_html .= "
		 			<div class=\"col_hdr_left\" style='width: 100%; clear:both;'>
		 				Category Name and Description for Language: ".$this->get_language_name(0,$show["language_id"])." 
					</div>
					<div class='row_color1'>
						<div class='leftColumn'>Name:</div>
						<div style='margin-left: 0em;' class='rightColumn'>
							<input type='text' name='data[basics][".$show["language_id"]."][name]' value='".geoString::specialChars(urldecode($this_cat["category_name"]))."' size='30' />
						</div>
						<div class=\"clearColumn\"></div>
					</div>
					<div class='row_color2'>
						<div class='leftColumn'>Description".$this->show_tooltip(6,1).":</div>
						<div class='rightColumn'>
							<textarea name='data[basics][".$show["language_id"]."][description]' cols='30' rows='3'>".geoString::specialChars(urldecode($this_cat["description"]))."</textarea>
						</div>
						<div class=\"clearColumn\"></div>
					</div>
					<div class='row_color1 header_html'".((in_array($data['which_header_html'], array('cat', 'cat+default')))? '':' style="display: none;"').">
						<div class='leftColumn'>
							{header_html} added contents:{$tooltip}
						</div>
						<div class='rightColumn'>
							<textarea name='data[basics][".$show["language_id"]."][header_html]' cols='30' rows='3'>".geoString::specialChars(geoString::fromDB($this_cat["header_html"]))."</textarea>
						</div>
						<div class=\"clearColumn\"></div>
					</div>";
			}
		}
		$html .= "<script type=\"text/javascript\">Text[1] = [\"category order\", \"Category order determines the order that the categories are displayed when this category's parent category is displayed.\"]\n
			Text[2] = [\"url of category icon\", \"This is the url of the image icon this category uses when this category's name is being displayed.  If no image is referenced no image will be displayed.\"]\n
			Text[3] = [\"edit category specific fields\", \"Click here to edit which fields you wish to use and display within this category (and subcategories if you choose to).\"]\n
			Text[4] = [\"edit listing durations\", \"Click here to edit the durations that will appear for this category.  If you do not enter any durations for this category or any of its direct parent categories the site defaults set within  LISTING SETUP > LISTING DURATIONS will be used.\"]\n
			Text[5] = [\"edit category's name, description and templates\", \"Click here to edit the listing used for this category.\"]\n
			Text[6] = [\"category description\", \"This description will appear below the category name while browsing the listings if you choose to display the category discriptions set in the SITE SETUP > BROWSING page.\"]\n
			Text[7] = [\"listing types allowed\", \"These are the allowed types of listings that can be placed in this category\"]\n";

		// Set style for tooltip
		$html .= "
</script>
";
		if (!$categoryId){
			$typesAllowedLookup[0] = ' checked="checked"';
		}
		
		$html .= '<div class="breadcrumbBorder">';
			$html .= '<ul id="breadcrumb">';
			$html .= '<li class="current">Currently Viewing</li>';
			$html .= '<li><a href="index.php?mc=categories&page=categories_setup">Main</a></li>';
			
			if ($categoryId) {
				$category_tree = $this->get_category_tree($db,$categoryId);
				reset($this->category_tree_array);
				if ($category_tree) {					
					if (is_array($this->category_tree_array)) {
						$i = 0;
						$i = count($this->category_tree_array);
						while ($i > 0) {
							//display all the categories
							$i--;
							$html .= '<li>';
							$catName = $this->category_tree_array[$i]["category_name"];
							if ($i != 0) {
								$html .= "<a href=index.php?mc=categories&page=categories_setup&b=".$this->category_tree_array[$i]["category_id"].">".$catName.'</a>';
							} else {
								$html .= $catName;
							}
							$html .= '</li>';
						}
					}
				}
			}
			$html .= '</ul></div><br />';
		
		
		$html .= "
<fieldset id='EditCatSettings'>
				<legend>Edit Category Settings</legend><table width=100% cellpadding=0 cellspacing=0><tr><td>
				<form action=\"\" method='post'>";
		$html .= "
					<div class='row_color1' style='width: 100%;'>
						<div class='leftColumn'>Category URL:</div> 
						<div class='rightColumn'>
							<a href=\"{$this->db->get_site_setting('classifieds_url')}?a=5&b={$categoryId}\">{$this->db->get_site_setting('classifieds_url')}?a=5&b={$categoryId}</a>
						</div>
						<div class=\"clearColumn\"></div>
					</div>";
		
		$html .= "
					<div class='row_color2'>
						<div class='leftColumn'>Order:".$this->show_tooltip(1,1)."</div>
						<div class='rightColumn'>
							<select name='data[basics][displayOrder]'>".Admin_site::buildSelectOptions(range(1, 500), range(1, 500), $data["display_order"])."</select>
						</div>
						<div class=\"clearColumn\"></div>
					</div>";
		if(geoMaster::is('classifieds') && geoMaster::is('auctions')) {
			$html .= "
					<div class='row_color1' style='width: 100%;'>
						<div class='leftColumn'>Listings Allowed:".$this->show_tooltip(7,1)."</div> 
						<div class='rightColumn'>
							<input type='radio' name='data[basics][typesAllowed]' value='2' id='auctionsRadio'{$typesAllowedLookup[2]}/><label for='auctionsRadio'>Auctions</label><br />
							<input type='radio' name='data[basics][typesAllowed]' value='1' id='classifiedsRadio'{$typesAllowedLookup[1]}/><label for='classifiedsRadio'>Classifieds</label><br />
							<input type='radio' name='data[basics][typesAllowed]' value='0' id='bothRadio'{$typesAllowedLookup[0]}/><label for='bothRadio'>Both</label><br />";
				if (file_exists(geoPC::path_translated()."/classes/order_items/reverse_auctions.php"))	
				{
					$html .= "<input type=radio name='data[basics][typesAllowed]' value='4' id='reverseauctionradio' {$typesAllowedLookup[4]}/><label for='reverseauctionradio'>Reverse Auctions</label><br />";
				}			
							
			$html .= "
							</div>
						<div class=\"clearColumn\"></div>
					</div>";
		} else {
			$html .= '<input type="hidden" name="data[basics][typesAllowed]" value="0" />';
		}
		$html .= '
				<div class="row_color2">
					<div class="leftColumn">Category Icon URL:'.$this->show_tooltip(2,1).'</div>
					<div class="rightColumn"><label><em>'.$admin->geo_templatesDir().'[Template Set]/external/</em><input type="text" name="data[basics][category_image]" value="'.$data["category_image"].'" size="30" maxsize="100" /></label></div>
					<div class="clearColumn"></div>
				</div>';
		
		$html .= '
				<div class="row_color1">
					<div class="leftColumn">Add extra to {header_html} from:</div>
					<div class="rightColumn">
						<select name="data[basics][which_header_html]" id="which_header_html">';
		if ($data['parent_id']) {
			$html.= '<option value="parent"'.(($data['which_header_html']=='parent')? ' selected="selected"': '').'>Parent Category</option>';
		}
		$html .= '
							<option value="default"'.(($data['which_header_html']=='default')? ' selected="selected"': '').'>Default Site-Wide</option>
							<option value="cat"'.(($data['which_header_html']=='cat')? ' selected="selected"': '').'>Category-Specific (Set Below)</option>
							<option value="cat+default"'.(($data['which_header_html']=='cat+default')? ' selected="selected"': '').'>Default AND Category-Specific (Set Below)</option>
						</select>
					</div>
					<div class="clearColumn"></div>
				</div>
		
		';
		
		if ($categoryId){
			$html .= "
					<div class='row_color1' style='width: 100%; padding: 5px;'>
						<div class='leftColumn'>Edit this Category's . . . </div> 
						<div class='rightColumn'>";
			if (geoPC::is_ent() )
				$html .= "
							<div><a href='index.php?mc=".$_GET['mc']."&page=fields_to_use&amp;categoryId={$categoryId}' class='mini_button' style='width: 80px; text-align:center; margin:2px;'>Fields</a></div>
							<div><a href='index.php?mc=".$_GET['mc']."&page=categories_durations&amp;c={$categoryId}' class='mini_button' style='width: 80px; text-align:center; margin:2px;'>Durations</a></div>";
			$html .= "
							<div><a href='index.php?page=categories_templates&b={$categoryId}' class='mini_button lightUpLink' style='width: 80px; text-align:center; margin:2px;'>Templates</a></div>
							<div><a href='index.php?mc=".$_GET['mc']."&page=categories_questions&b={$categoryId}' class='mini_button' style='width: 80px; text-align:center; margin:2px;'>Questions</a></div>";
			
			if(geoAddon::getInstance()->isEnabled('core_display')) {
				//TODO: if there's every reason to add too many more of these buttons, could stand to make it a core event
				$html .= "<div><a href='index.php?page=browsing_filter_settings&mc=addon_cat_core_display&category={$categoryId}' class='mini_button' style='width: 80px; text-align:center; margin:2px;'>Browse Filters</a></div>";
			}
			
			$html .= "		</div>
						<div class=\"clearColumn\"></div>
					</div>";
		}
		$html .= "$language_html
					<div style=\"clear:both; text-align:center;\"><input type='submit' value='Save' name='auto_save' style=\"clear:both;\" /></div>
				</form></td></tr></table></fieldset>"; 
		$html = "
			<div id='categoryBasics' style='width: 100%; text-align: left;'>
				$html
			</div>";
		geoView::getInstance()->addJScript('js/category_edit.js');
		return $html;
	}
	
	public function display_categories_templates() {
		if (!geoAjax::isAjax()) {
			//should anything be done?  this only happens if they open link in
			//new window/tab
		}
		
		$view = geoView::getInstance();
		require_once ADMIN_DIR . 'design.php';
		$design = Singleton::getInstance('DesignManage');
		$tpl_vars = array();
		$tpl_vars['categoryId'] = (isset($_GET['b']))? (int)$_GET['b'] : 0;
		$tpl_vars['categoryName'] = $this->getName($tpl_vars['categoryId']);
		
		$pages = $design->getPagesData ('category');
		foreach ($pages as $page_id => $data) {
			$pages[$page_id]['attachments'] = $view->getTemplateAttachments($page_id, false);
		}
		$tpl_vars['pages'] = $pages;
		$sql = "SELECT `language_id`, `language` FROM ".geoTables::pages_languages_table." ORDER BY `language_id` ASC";
		$languages = $this->db->GetAll($sql);
		$tpl_vars['languages'] = array();
		foreach ($languages as $row) {
			$tpl_vars['languages'][$row['language_id']] = $row['language'];
		}
		
		$tpl = new geoTemplate(geoTemplate::ADMIN);
		$tpl->assign($tpl_vars);
		
		echo $tpl->fetch('categories/attachedTemplates.tpl');
		
		//echo $html;
		$view->setRendered(true);
	}

	function updateBasics($categoryId, $data) {
		if (strlen(PHP5_DIR)>0){
			$this->db = DataAccess::getInstance();
		} else {
			$this->db =& DataAccess::getInstance();
		}
		$which_header_html = (in_array($data['which_header_html'], array('parent','default','cat','cat+default')))? $data['which_header_html'] : 'parent';
		if ($categoryId == 0){
			//this is a new one.
			$sql = 'INSERT INTO `geodesic_categories` SET `category_name` = ?, description = ?, 
			`display_order` = ?, 
			`listing_types_allowed` = ?,
			`category_image` = ?,
			`which_header_html` = ?';
			
			$query_data = array ($data[1]['name'].'',$data[1]['description'].'', intval($data['displayOrder']), intval($data['typesAllowed']), $data['category_image'].'', $which_header_html.'');
		
		} else {
			$sql = "update geodesic_categories 
			set category_name = ?, 
			description = ?, 
			display_order = ?, 
			listing_types_allowed = ?,
			category_image = ?, `which_header_html`=? where category_id = ?";
			$query_data = array ($data[1]['name'].'',$data[1]['description'].'', intval($data['displayOrder']), intval($data['typesAllowed']), $data['category_image'].'', $which_header_html, $categoryId);
		
		}
		$result = $this->db->Execute($sql, $query_data);
		if(false === $result)
			return false;
		if ($categoryId==0){
			$categoryId = $this->db->Insert_ID();
			$new_cat = true;
		} else $new_cat = false;
		//now save it in the category languages table.
		foreach ($data as $key => $value){
			if (is_numeric($key) && is_array($value)){
				//key is numeric, so it must be one of the languages.
				$row = $this->db->GetRow("SELECT * FROM ".geoTables::categories_languages_table." WHERE `category_id`='{$categoryId}' AND `language_id`={$key}");
				$new_cat = (!$row);
				if ($new_cat){
					//new cat
					$sql = 'INSERT INTO '.geoTables::categories_languages_table.' SET `category_name` = ?, 
					`description` = ?, `header_html`=?, `category_id` = ?, `language_id` = ?';
				} else {
					$sql = 'UPDATE '.geoTables::categories_languages_table.' 
					SET `category_name` = ?, 
					`description` = ?, `header_html`=? where `category_id` = ? and `language_id` = ?';
				}
				$query_data = array(geoString::toDB($value['name']), geoString::toDB($value['description']), geoString::toDB($value['header_html']), $categoryId, $key);
				$result = $this->db->Execute($sql, $query_data);
				if(false === $result) {
					geoAdmin::m("DB Error: Sql: $sql Error msg: ".$this->db->ErrorMsg(), geoAdmin::ERROR);
					return false;
				}
			}
		}
		return true;
	}
	
	function updateTemplates($categoryId, $info) {
		//echo "top of updateTemplates<br>\n";
		if (strlen(PHP5_DIR)>0){
			$this->db = DataAccess::getInstance();
		} else {
			$this->db =& DataAccess::getInstance();
		}
		//what the heck is this for?  debug isn't a dataaccess var, is it?
		//$this->db->debug = true;	
		if(isset($info) && count($info)) {
			$languages = $info;
			foreach ($languages as $language_id => $language_data)
			{
				if(isset($info[$language_id]["templates"]["subcats"]) && count($info[$language_id]["templates"]["subcats"])) {
					$targets = Admin_categories::getSubcategories($categoryId);
					$fields = $info[$language_id]["templates"]["subcats"];
					$updates = array();
					$update_str = array();
					foreach ($fields as $field => $value) {
						$update_str[] = "{$field} = ?";
						$updates[] = $info[$language_id]["templates"][$field];
					}
					$sql = "update geodesic_classifieds_categories_languages set ".implode(", ", $update_str)." where category_id in ('".implode("', '", $targets)."') and language_id = ".$language_id;
					//echo $sql."<bR>\n";
					if(false === $this->db->Execute($sql, $updates)) {
						//echo $this->db->ErrorMsg()." is the error<br>\n";
						Admin_site::error($this->db->ErrorMsg(), __LINE__, __FILE__);
						return false;
					}
					reset ($updates);
					unset($language_data["templates"]["subcats"]);
				}
				
				//var_dump($info);
				$updates = array();
				$update_str = array();
				foreach($language_data["templates"] as $field => $value) {
					$updates[] = (int)$value;
					$update_str [] = "{$field} = ?";
				}
				$updates[] = $categoryId;
				//var_dump($updates);
				//echo '<br>';var_dump($update_str);echo '<br>';
				//in ('".implode("', '", $targets)."')");
				
				$sql = "update geodesic_classifieds_categories_languages set ".implode(", ", $update_str)." where category_id = ? and language_id = ".$language_id;
				//echo $sql."<bR>\n";
				if(false === $this->db->Execute($sql, $updates)){
					Admin_site::error($this->db->ErrorMsg(), __LINE__, __FILE__, false);
					return false;
				}		
				reset ($updates);
				//foreach ($updates as $label => $value)
				//	echo $label." is the label for: ".$value."<bR>\n";		
			}
		}

		return true;
	}
	
	function getSubcategories($categoryId) {
		if (strlen(PHP5_DIR)>0){
			$this->db = DataAccess::getInstance();
		} else {
			$this->db =& DataAccess::getInstance();
		}
		$subcategories = array();
		$children = array($categoryId);
		
		
		do {
			$children = $this->db->GetCol("select category_id from geodesic_categories where parent_id in ('".implode("', '", $children)."') and category_id not in('".implode("', '", $subcategories)."')");
			if(false === $children)
				Admin_site::error($this->db->ErrorMsg(), __LINE__, __FILE__, false);
			$subcategories = array_merge($subcategories, $children);
		} while (count($children));	
		return $subcategories;
	}
	
	function getName($categoryId) {
		if ($categoryId == 0){
			//this is a new category
			return '';
		}
		if (strlen(PHP5_DIR)>0){
			$this->db = DataAccess::getInstance();
		} else {
			$this->db =& DataAccess::getInstance();
		}
		$name = $this->db->GetOne("select category_name from geodesic_classifieds_categories_languages where category_id = ? order by language_id asc", $categoryId);
		if(false === $name) {
			Admin_site::error($this->db->ErrorMsg(), __LINE__, __FILE__);
		}
		return stripslashes(urldecode($name));
	}
		
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_categories_setup()
	{
		$cat = (isset($_REQUEST['b']) && $_REQUEST['b'])? $_REQUEST['b'] : 0;
		$this->browse($cat);
		$this->display_page();
	}
	
	function update_categories_setup()
	{
		//save the default contents for header_html
		$header_html = $_POST['header_html'];
		$db = DataAccess::getInstance();
		foreach ($header_html as $language_id => $contents) {
			$language_id = (int)$language_id;
			$sql = "UPDATE ".geoTables::pages_text_languages_table." SET `text`=? WHERE `page_id`=3 AND `text_id`=500961 AND `language_id`={$language_id}";
			$db->Execute($sql, array(geoString::toDB($contents).''));
		}
		return true;
	}

//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_categories_delete()
	{
		if (strlen(PHP5_DIR)>0){
			$menu_loader = geoAdmin::getInstance();
		} else {
			$menu_loader =& geoAdmin::getInstance();
		}
		
		
		if (isset($_REQUEST["b"]) && !isset($_REQUEST['c'])) {
			$this->delete_category_check($this->db,$_REQUEST["b"]);
		} else {
			$this->browse();
		}
		$this->display_page();
	}
	function update_categories_delete()
	{
		if (($_REQUEST["b"]) && ($_REQUEST["c"])) {
			return $this->delete_category($_REQUEST["b"],$_REQUEST["c"]);
		}
		return false;
	}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_categories_edit()
	{
		if (PHP5_DIR) 
			$menu_loader = geoAdmin::getInstance();
		else 
			$menu_loader =& geoAdmin::getInstance();
		$this->body .= $menu_loader->getUserMessages();
		
		$categoryId = 0;
		if(isset($_GET["b"])) {
			$categoryId = (int)$_GET["b"];
		} else if(isset($_GET["c"])) {
			$categoryId = (int)$_GET["c"];
		} else if(isset($_GET["cat"])) {
			$categoryId = (int)$_GET["cat"];
		} else {
			Admin_site::inputError("No category specified", __LINE__, __FILE__);
		}
		
		// Update statements

		$categoryName = $this->getName($categoryId);
		// Edit forms
		$area = isset($_GET["edit"]) ? $_GET["edit"] : "basics";
		switch($area) {
			case "basics":
				$this->title = " ({$categoryName})";
				$this->description = "Here you can edit the basic information for this category, or edit more specific information by clicking one of the links at the bottom of the page.";
				//$this->body .= Admin_categories::basicsForm($categoryId);
				$this->body .= $this->basicsForm($categoryId);
				
				break;
	
			case "templates":
				$this->title = " Templates ({$categoryName})";
				$this->body .= $this->templatesForm($categoryId);
				break;
		}
		
		$this->display_page();
	}
	function update_categories_edit()
	{
		//echo "top of update_categories_edit<br>\n";
			$categoryId = 0;
		if(isset($_GET["b"])) {
			$categoryId = (int)$_GET["b"];
		} else if(isset($_GET["c"])) {
			$categoryId = (int)$_GET["c"];
		} else if(isset($_GET["cat"])) {
			$categoryId = (int)$_GET["cat"];
		} else {
			Admin_site::inputError("No category specified", __LINE__, __FILE__);
		}
		//var_dump($_POST["data"]);
			if(isset($_POST["data"]) && is_array($_POST["data"])) {
			if(isset($_POST["data"]["basics"])) {
				// Update basics
				//Admin_categories::updateBasics($categoryId, $_POST["data"]["basics"]);
				//echo "calling updatBasics<bR>\n";
				return $this->updateBasics($categoryId, $_POST["data"]["basics"]);
			}
			//there will always be a language id of 1 so test there
			if(isset($_POST["data"][1]["templates"])) {
				// Update templates
				//Admin_categories::updateTemplates($categoryId, $_POST["data"]["templates"]);
				//echo "calling updateTemplates<br>\n";
				return $this->updateTemplates($categoryId, $_POST["data"]);
				
			}
		}
			
	}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_categories_add()
	{
		if (!$this->isUpdated) {
			$this->display_category_form($this->db,$_REQUEST["b"],1);
		} else {
			//$this->body .= '<span class="">Create another new ad:</span><br />';
			//$this->basicsForm();
			$this->browse($this->isUpdated);
		}
		$this->display_page();
		
	}
	function update_categories_add()
	{
		$menu_loader = geoAdmin::getInstance();
		
		$this->isUpdated = $this->insert_category($this->db,$_REQUEST["b"],$_REQUEST["c"]);
		if( $this->isUpdated )
		{
			//$menu_loader->userSuccess("Settings Saved.");
			return true;
		}
		else 
		{
			$menu_loader->userError("Settings NOT Saved.");
			return false;
		}
		
	}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_categories_durations()
	{
		
		if ($_REQUEST["c"])
		{
			if (!$this->category_specific_lengths_form($db,$_REQUEST["c"]))
				return false;
		}
		elseif (!$this->browse($_REQUEST["c"]))
			return false;
		$this->display_page();
	}
	function display_categories_durations_delete()
	{
		$this->display_categories_durations();
	}
	function update_categories_durations_delete()
	{
		//delete a length
		return $this->category_specific_delete_length($db,$_GET["d"]);
	}
	function update_categories_durations()
	{
		
		if (($_REQUEST["b"]) && ($_REQUEST["c"]))
		{
			return $this->add_category_specific_length($db,$_REQUEST["b"],$_REQUEST["c"]);
		}
		return false;
	}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_categories_copy_subcats()
	{
		$menu_loader = geoAdmin::getInstance();
		$this->body .= $menu_loader->getUserMessages();
		
		$this->duplicate_structure_form();
		$this->display_page();
	}
	function update_categories_copy_subcats()
	{
		return $this->duplicate_category_structure($_REQUEST["b"],$_REQUEST["c"], $_REQUEST['fromID'], $_REQUEST['toID'],$_POST["d"],$_REQUEST["e"]);
	}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_categories_copy_questions()
	{
		if ($_REQUEST["c"])
		{
			$this->browse($_REQUEST["c"]);
		} else {
			$this->duplicate_questions_form();
		}
		
		$this->display_page();
	}
	function update_categories_copy_questions()
	{
		$from = (int)((isset($_REQUEST['fromCAT']) && (int)$_REQUEST['fromCAT'])? $_REQUEST['fromCAT'] : $_REQUEST['b']);
		$to = (int)((isset($_REQUEST['toCAT']) && (int)$_REQUEST['toCAT'])? $_REQUEST['toCAT'] : $_REQUEST['c']);
		
		return $this->duplicate_category_questions($from,$to);
	}
//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	function display_categories_reset_count()
	{
		if (!$this->reset_all_category_counts($this->db))
			return false;
		elseif (!$this->browse())
			return false;
		$this->display_page();
	}
	function update_categories_reset_count()
	{
		
	}
	
} // end of class Admin_categories