<?php 
//module_display_filters_1.php
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

//Shared file, used by both filter_display modules.
if (geoPC::is_print() && $this->get_site_setting('disableAllBrowsing')) {
	//browsing disabled, do not show module contents
	return;
}
if ($page->configuration_data['use_filters'] && geoPC::is_ent()) {
	//setup dropdown labels
	$dropdown_labels = array();
	$dropdown_labels[1] = $page->messages[1482];
	$dropdown_labels[2] = $page->messages[1483];
	$dropdown_labels[3] = $page->messages[1484];
	$dropdown_labels[4] = $page->messages[1485];
	$dropdown_labels[5] = $page->messages[1486];
	$dropdown_labels[6] = $page->messages[1487];
	$dropdown_labels[7] = $page->messages[1488];
	$dropdown_labels[8] = $page->messages[1489];
	$dropdown_labels[9] = $page->messages[1490];
	$dropdown_labels[10] = $page->messages[1491];
	$select_previous[1] = $page->messages[1531];
	$select_previous[2] = $page->messages[1532];
	$select_previous[3] = $page->messages[1533];
	$select_previous[4] = $page->messages[1534];
	$select_previous[5] = $page->messages[1535];
	$select_previous[6] = $page->messages[1536];
	$select_previous[7] = $page->messages[1537];
	$select_previous[8] = $page->messages[1538];
	$select_previous[9] = $page->messages[1539];
	$select_previous[10] = $page->messages[1540];
	$clear_labels[1] = $page->messages[1492];
	$clear_labels[2] = $page->messages[1493];
	$clear_labels[3] = $page->messages[1494];
	$clear_labels[4] = $page->messages[1495];
	$clear_labels[5] = $page->messages[1496];
	$clear_labels[6] = $page->messages[1497];
	$clear_labels[7] = $page->messages[1498];
	$clear_labels[8] = $page->messages[1499];
	$clear_labels[9] = $page->messages[1500];
	$clear_labels[10] = $page->messages[1501];
	$last_one = 0;
	
	
	if(!$page->filter_id && $_COOKIE['filter_id'] ) {
		$page->filter_id = $_COOKIE['filter_id'];
	}

	if ($page->filter_id) {
		//get the current filter settings
		$page->sql_query = "select * from geodesic_classifieds_filters where filter_id = ".$page->filter_id;
		$current_filter_result =  $this->Execute($page->sql_query);
		if (!$current_filter_result) {
			return false;
		} elseif ($current_filter_result->RecordCount() == 1) {
			$current_filter = $current_filter_result->FetchRow();
		} else {
			return false;
		}

		//get parents of current filter_id
		if ($current_filter["filter_level"] != 1) {
			$i = $current_filter["filter_level"];
			$filter_next = $current_filter["filter_id"];
			$filter_level_array = array();
			
			while ($filter_next != 0) {
				$page->sql_query = "select * from geodesic_classifieds_filters where filter_id = ".$filter_next;
				$filter_result =  $this->Execute($page->sql_query);
				if (!$filter_result) {
					$page->error_message = $page->messages[3501];
					return false;
				} elseif ($filter_result->RecordCount() == 1) {
					$show_filter = $filter_result->FetchRow();
					$filter_level_array[$i]["parent_id"]  = $show_filter["parent_id"];
					$filter_level_array[$i]["filter_name"] = $show_filter["filter_name"];
					$filter_level_array[$i]["filter_id"]   = $show_filter["filter_id"];
					$i--;
					$filter_next = $show_filter["parent_id"];
				} else {
					return false;
				}
			}

			//display dropdowns above current filter's level
			$parents_level = 1;
			$parents_id = 0 ;
			$parent_in_statement = "";

			$parents = array();
			$p = 0; //parents index
			do {
				$page->sql_query = "select
					geodesic_classifieds_filters.filter_id,
					geodesic_classifieds_filters.filter_level,
					geodesic_classifieds_filters.parent_id,
					geodesic_classifieds_filters.in_statement,
					geodesic_classifieds_filters_languages.filter_name
					from geodesic_classifieds_filters,geodesic_classifieds_filters_languages
					where geodesic_classifieds_filters.filter_id = geodesic_classifieds_filters_languages.filter_id
					and geodesic_classifieds_filters.filter_level = ".$parents_level;
				if ($parent_in_statement)
					$page->sql_query .= " and geodesic_classifieds_filters.filter_id ".$parent_in_statement;
				$page->sql_query .= " and geodesic_classifieds_filters_languages.language_id = ".$page->language_id." order by filter_level asc, display_order asc, geodesic_classifieds_filters_languages.filter_name";

				$level_filter_result = $this->Execute($page->sql_query);
				if (!$level_filter_result) {
					$page->error_message = $page->messages[5501];
					return false;
				} elseif ($level_filter_result->RecordCount() > 0) {
					$parents[$p]['zeroLabel'] = $dropdown_labels[$parents_level];
					$parents[$p]['selectName'] = 'set_filter_id['.$parents_level.']';

					$o = 0; //options index
					while ($show_filter = $level_filter_result->FetchRow())
					{
						if ($show_filter["parent_id"] == $filter_level_array[$show_filter["filter_level"]]["parent_id"]) {
							if ($show_filter["filter_id"] == $current_filter["filter_id"]) {
								$parents[$p]['options'][$o]['value'] = "";
								$parents[$p]['options'][$o]['selected'] = true;
								$show_all_selection = 1;
							} elseif ($show_filter["filter_id"] == $filter_level_array[$parents_level]["filter_id"]) {
								$parents[$p]['options'][$o]['value'] = "";
								$parents[$p]['options'][$o]['selected'] = true;
							} else {
								$parents[$p]['options'][$o]['value'] = $show_filter["filter_id"];
							}
							$parents[$p]['options'][$o]['label'] = $show_filter["filter_name"];
							if ($show_filter["filter_id"] == $current_filter["parent_id"]) {
								$parent_in_statement = $show_filter["in_statement"];
							}
							$o++;
						}
					}
					if ($show_all_selection) {
						$parents[$p]['options'][$o]['value'] = $current_filter["parent_id"];
					} elseif ($filter_level_array[$parents_level]["parent_id"] == 0) {
						$parents[$p]['options'][$o]['value'] = "clear";
					} else {
						$parents[$p]['options'][$o]['value'] = $filter_level_array[$parents_level]["parent_id"]; 
					}
					$parents[$p]['options'][$o]['label'] = $clear_labels[$parents_level];
				} else {
					return false;
				}
				$parents_level++;
				$parents_id = $current_filter["parent_id"];
				$p++;
			} while ($parents_level <= $current_filter["filter_level"]);

			$page->sql_query = "select geodesic_classifieds_filters.filter_id,geodesic_classifieds_filters.filter_level, geodesic_classifieds_filters.parent_id, geodesic_classifieds_filters_languages.filter_name
				from geodesic_classifieds_filters,geodesic_classifieds_filters_languages
				where geodesic_classifieds_filters.filter_id = geodesic_classifieds_filters_languages.filter_id
				and geodesic_classifieds_filters.filter_id ".$current_filter["in_statement"]."
				and geodesic_classifieds_filters.filter_level > ".$current_filter["filter_level"]."
				and geodesic_classifieds_filters_languages.language_id = ".$page->language_id."
				and geodesic_classifieds_filters.parent_id = ".$current_filter["filter_id"]."
				order by filter_level asc, display_order asc, geodesic_classifieds_filters_languages.filter_name";
		} else {
			$page->sql_query = "select f.filter_id, f.filter_level, f.parent_id, l.filter_name
				from geodesic_classifieds_filters as f,geodesic_classifieds_filters_languages as l
				where f.filter_id = l.filter_id
				and (f.filter_id ".$current_filter["in_statement"]." or f.filter_level = 1 )
				and l.language_id = ".$page->language_id."
				order by filter_level asc, display_order asc , l.filter_name";
		}
		
		$filter_result = $this->Execute($page->sql_query);
		if (!$filter_result) {
			$page->error_message = $page->messages[5501];
			return false;
		} elseif ($filter_result->RecordCount() > 0) {

			//find level of currently-active (deepest) filter
			//NOTE: this is different than level of deepest SHOWN filter, because
			//the user may have not selected anything on last dropdown yet
			//if that is the case, we don't want to show the "clear" link for that dropdown
			$sql = "select `filter_level` from `geodesic_classifieds_filters` where filter_id = ?";
			$activeLevel = $this->GetOne($sql, array($page->filter_id));
			
			//organize data by filter level
			$levels = array();
			while ($show_filter = $filter_result->FetchRow()) {
				$levels[$show_filter['filter_level']][] = $show_filter;
			}


			//each "$children" is a separate dropdown
			$children = array();

			foreach($levels as $level => $levelData) {
				$c++; // start with level 1 and go up each time
				$children[$level]['selectName'] = "set_filter_id[".$level."]";	

				$children[$level]['options'] = array();
				$o = 0; //options index

				//first option of this dropdown is just a label
				if (($show_module['display_unselected_subfilters']) && ($current_filter["filter_level"] <= $level)) {
					$children[$level]['options'][$o]['value'] = "";
					$children[$level]['options'][$o]['label'] = $dropdown_labels[$level];
					$o++;
				} else {
					$children[$level]['options'][$o]['value'] = "";
					$children[$level]['options'][$o]['label'] = $select_previous[$level];
					$o++;
				}

				foreach($levelData as $row) {
					//build options for current dropdown
					$children[$level]['options'][$o]['value'] = $row['filter_id'];
					$children[$level]['options'][$o]['label'] = $row['filter_name'];
					if ($row["filter_id"] == $page->filter_id) {
						$children[$level]['options'][$o]['selected'] = true;
					}

					$o++;
				}

				//last is the clear option
				//remember to not show it if this level isn't set by the user yet
				if($level <= $activeLevel) {
					$children[$level]['options'][$o]['value'] = 'clear';
					$children[$level]['options'][$o]['label'] = $clear_labels[$level];
				}

			}
		}
	} else {
		//cookie isn't set, so user hasn't made a filter selection yet
		//show only the level 1 dropdown
		$page->sql_query = "select geodesic_classifieds_filters.filter_id,geodesic_classifieds_filters.filter_level,
			geodesic_classifieds_filters_languages.filter_name
			from geodesic_classifieds_filters,geodesic_classifieds_filters_languages
			where geodesic_classifieds_filters.filter_id = geodesic_classifieds_filters_languages.filter_id
			and geodesic_classifieds_filters_languages.language_id = ".$page->language_id."
			order by filter_level asc, display_order asc, geodesic_classifieds_filters_languages.filter_name";

		$filter_result = $this->Execute($page->sql_query);
		if (!$filter_result) {
			$page->error_message = $page->messages[5501];
			return false;
		} elseif ($filter_result->RecordCount() > 0) {
			$page->sql_query = "select count(distinct(filter_level)) as level_count from ".$page->filters_table;
			$level_count_result = $this->Execute($page->sql_query);
			if (!$level_count_result)
			{
				$page->error_message = $page->messages[5501];
				return false;
			} elseif ($level_count_result->RecordCount() ==1) {
				$show_count = $level_count_result->FetchRow();
				$total_levels = $show_count["level_count"];
			}

			//display only the first level dropdown
			$level = 1;
			$parents = array();
			//only using first element of parents array here, but leave it in php so it lines up with the tpl used above
			$parents[0]['options'] = array();
			$o = 0; //options index
			
			$parents[0]['selectName'] = "set_filter_id[".$level."]";
			
			$parents[0]['zeroLabel'] = $dropdown_labels[$level];
			
			for($o = 0; $show_filter = $filter_result->FetchRow(); $o++) {

				if ($show_filter["filter_level"] == 1) {
					$parents[0]['options'][$o]['value'] = $show_filter["filter_id"];
					$parents[0]['options'][$o]['label'] = $show_filter["filter_name"];
				}
			}
		}
	}
	
	$tpl_vars['module'] = $show_module;
	$tpl_vars['parents'] = $parents;
	$tpl_vars['children'] = $children;
	$view->setModuleTpl($show_module['module_replace_tag'],'index')
		->setModuleVar($show_module['module_replace_tag'],$tpl_vars);
	
}