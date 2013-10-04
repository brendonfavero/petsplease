<?php

if( !class_exists( 'admin_AJAX' )) {
	trigger_error('Parent class has not been included');
	exit;
}

/**
 * TODO: Add a filtering function that makes sure all data passed from the user's browser is clean and valid
 */
class ADMIN_AJAXController_Filter extends admin_AJAX {	
	function addLevel( $data ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";	
		
		$level = intval($data['level']);
		$lang = isset($data['lang']) ? intval($data['lang']) : 1;
		
		if( $level > 1 ) {
			echo "
				<div style='text-align: left;'><img src='admin_images/downArrow.gif' style='position: absolute; margin: -20px 0 0 7em; background-color: transparent;' /></div>";
		}
		?>
				
			<h3 style='width: 100%; text-align: left; margin: 0; line-height: 180%; font-weight: bold; vertical-align: center;' class='medium_font_light row_color_red'>
				<span id='level_<?php echo $level; ?>_optionals_message' style='text-align: right; color: white; width: 70%; float: right; clear: right; margin: 3px 1em 3px 5em;display:none;' class='medium_font row_color_red'>You must have at least one choice on this level before you can associate fields with it</span>
				<span id='level_<?php echo $level; ?>_optionals_settings' style='text-align: right; color: white; width: 70%; float: right; clear: right; margin: 0px 1em 3px 5em; white-space:nowrap'>
						Uses
						<select onchange='geoFilter.setRegistrationOptional( <?php echo $level; ?> );' id='level_<?php echo $level; ?>_registration_optionals' style='border-width: 0;'>
							<?php ADMIN_AJAXController_Filter::showRegistrationOptionals( array('level'=>$level) ); ?>
						</select>
						 and
						<select onchange='geoFilter.setSiteOptional( <?php echo $level; ?> );' id='level_<?php echo $level; ?>_site_optionals' style='border-width: 0;'>
							<?php ADMIN_AJAXController_Filter::showSiteOptionals( array('level'=>$level) ); ?>
						</select> 
				</span>
				<span style='margin: auto 1em;'>Level <?php echo $level; ?></span>
			</h3>
			<div style='text-align: left; margin: 0;' class='medium_font row_color2'>
				<br />	
<?php 
			if( !isset($data['parent']) ) {
				$settingsStyle = "display: none; ";
				$noneSelectedStyle = "";
				$noChoicesStyle = "display: none; ";
			} else {
				ob_start();
				ADMIN_AJAXController_Filter::getChildren( $data );
				$options = ob_get_contents();
				ob_end_clean();		
					
				if( strlen($options) ) { 
					$settingsStyle = "";
					$noneSelectedStyle = "display: none; ";
					$noChoicesStyle = "display: none; ";
				} else {
					$settingsStyle = "display: none; ";
					$noneSelectedStyle = "display: none; ";
					$noChoicesStyle = "";
				}
			}
			?>	
				<div id='level_<?php echo $level; ?>_choices_none_selected' style='<?php echo $noneSelectedStyle; ?>width: 50%; margin-left: 1em; margin-bottom: 1em; text-align: left;'>
					Please choose a choice for the previous level
				</div>
				<div id='level_<?php echo $level; ?>_choices_no_choices' style='<?php echo $noChoicesStyle; ?>width: 50%; margin-left: 1em; margin-bottom: 1em; text-align: left;'>
					No choices. <a href="#" onclick="javascript: return geoFilter.addChoice( <?php echo $level; ?> );">Add one</a>
				</div>		
				<div id='level_<?php echo $level; ?>_choices_settings' style='<?php echo $settingsStyle; ?>width: 50%; margin-left: 1em; margin-bottom: 0; text-align: left;'>
					Choices for this level are:
						<div style='margin-left: 1em; width: 100%;'>
							<select id="level_<?php echo $level; ?>" size="6" style="width: 60%; float: left; margin-right: 1em;" onclick="geoFilter.loadChildren( <?php echo $level; ?>, this);">
							<?php echo $options; ?>
							</select>
							 
							<ul style='list-style: none; margin: 0 0 0 60%; padding: 0;'>
								<li><a href='#' onclick='javascript: return geoFilter.addChoice( <?php echo $level; ?> );'>Add</a></li>
								<li><a href='#' onclick='javascript: return geoFilter.delChoice( <?php echo $level; ?> );'>Delete</a></li>
								<li><a href='#' onclick='javascript: return geoFilter.editChoice( <?php echo $level; ?> );'>Edit</a></li>
								<li>Move: 
									<ul style='list-style: none; margin: 0 0 0 1em; padding: 0;'>
										<li><a href='#' onclick='javascript: return geoFilter.moveChoiceUp( <?php echo $level; ?> );'>up</a> / <a href='#' onclick='javascript: return geoFilter.moveChoiceDown( <?php echo $level; ?> );'>down</a></li>
										<li><a href='#' onclick='javascript: return geoFilter.moveChoiceTop( <?php echo $level; ?> );'>top</a> / <a href='#' onclick='javascript: return geoFilter.moveChoiceBottom( <?php echo $level; ?> );'>bottom</a></li>
									</ul>
								</li>
							</ul><br /><br />
						</div>
				</div>
				<br />
			</div>
			<?php
	}
	
	function addChoice( $data ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";	
		
		$name = urldecode($data['name']);
		$level = intval($data['level']);
		$parent = intval($data['parent']);
		$lang = isset($data['lang']) ? intval($data['lang']) : 1;
			
		if( $lang != 1 ) {
			trigger_error("Value for base language set to \"{$name}\"", E_USER_NOTICE);
		}	
		// Get maximum value of display_order for this group of choices
		$result = $db->GetAll('select max(display_order) as order_num, count(*) as total from ' . $db->geoTables->filters_table . ' where parent_id = ? limit 1', array( $parent ));
		if( false === $result ) {
			trigger_error('Unable to determine order of choice (' . $db->ErrorMsg() . ')');
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		// Account for display_order of zero
		$total = $result[0]['total'];
		$order = $total == 0 ? 0 : $result[0]['order_num'] + 1;
			
		$result = $db->Execute( 'insert into ' . $db->geoTables->filters_table . ' set 
				parent_id = ?, 
				filter_level = ?, 
				filter_name = ?, 
				display_order = ?', 
			array( $parent, $level, $name, $order)
		);
		if(false === $result) {
			trigger_error('Unable to insert filter choice (' . $db->ErrorMsg() . ')');
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		$id = $db->Insert_Id();
		
		$result = $db->Execute( 'update ' . $db->geoTables->filters_table . ' set 
				in_statement = ? where filter_id = ?', 
			array( "in({$id})", $id )
		);
		if(false === $result) {
			trigger_error('Unable to set in-statement (' . $db->ErrorMsg() . ')');
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
	
		$result = $db->Execute( 'insert into ' . $db->geoTables->filters_languages_table . ' set 
				filter_id = ?,
				filter_name = ?,
				language_id = ?', 
			array( $id, $name, $lang )
		);
		if(false === $result) {
			trigger_error('Unable to insert filter choice (' . $db->ErrorMsg());
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		
		if( $lang != 1 ) {
			$result = $db->Execute( 'insert into ' . $db->geoTables->filters_languages_table . ' set 
					filter_id = ?,
					filter_name = ?,
					language_id = 1', 
				array( $id, $name )
			);
			if(false === $result) {
				trigger_error('Unable to insert filter choice (' . $db->ErrorMsg());
				ADMIN_AJAXController_Filter::getChildren( $data, $id );
				exit;
			}
		}
			
		if( !ADMIN_AJAXController_Filter::setInStatement( $parent, $lang ) ) {
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
	
		ADMIN_AJAXController_Filter::getChildren( $data, $id );
	}
	
	function editChoice( $data ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";	
		
		$id = intval($data['id']);
		$lang = isset($data['lang']) ? intval($data['lang']) : 1;
		$name = urldecode($data['name']);
		
		// Get the parent id
		$parent = $db->GetAll('select parent_id from ' . $db->geoTables->filters_table . ' where filter_id = ?', array( $id ));
		if( false === $parent ) {
			trigger_error('Unable to find filter choice (' . $db->ErrorMsg() . ') on line ' . __LINE__);
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		$data['parent'] = $parent[0]['parent_id'];
		
		// Language 1 is the language used for names in the filters_table
		if( $lang == 1 ) {
			if( false === $db->Execute('update ' . $db->geoTables->filters_table . ' set filter_name = ? where filter_id = ?', array( $name, $id ))) {
				trigger_error('Unable to update filter choice (' . $db->ErrorMsg() . ') on line ' . __LINE__);
				ADMIN_AJAXController_Filter::getChildren( $data, $id );
				exit;
			}
		}
	
		// Make sure the entry exists
		$result = $db->Execute('select * from ' . $db->geoTables->filters_languages_table . ' where filter_id = ? and language_id = ?', array($id, $lang) );
		if(false === $result) {
			trigger_error('Unable to update filter choice (' . $db->ErrorMsg());
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		
		if( $result->RecordCount() ) {
			// Update
			$result = $db->Execute( 'update ' . $db->geoTables->filters_languages_table . ' 
					set filter_name = ? where filter_id = ? and language_id = ?', 
				array( $name, $id, $lang )
			);
		} else {
			// Insert
			$result = $db->Execute( 'insert into ' . $db->geoTables->filters_languages_table . ' 
					set filter_name = ?, filter_id = ?, language_id = ?', 
				array( $name, $id, $lang )
			);
		}
		if(false === $result) {
			trigger_error('Unable to update filter choice (' . $db->ErrorMsg());
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		
		ADMIN_AJAXController_Filter::getChildren( $data, $id );
	}
	
	function moveChoiceUp( $data ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";	
		
		$id = intval($data['id']);
		$lang = intval($data['lang']);
		
		$result = $db->Execute('select display_order, parent_id from ' . $db->geoTables->filters_table . ' where filter_id = ?', array( $id ));
		if( false === $result || !$result->RecordCount() ) {
			trigger_error('Unable to load choice with that ID (' . $db->ErrorMsg() . ') on line ' . __LINE__);
		}
		$result = $result->FetchRow();
		$order = $result['display_order'];
		$parent = $data['parent'] = $result['parent_id'];
		
		// Move previous choice down
		if( false === $db->Execute('update ' . $db->geoTables->filters_table . ' set display_order = display_order + 1 where display_order = ? and parent_id = ? order by display_order limit 1', array( $order - 1, $parent ) ) ) {
			trigger_error('Unable to update order (' . $db->ErrorMsg() . ') on line ' . __LINE__);
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		
		// Move selected choice up
		if( $db->Affected_Rows() ) {
			if( false === $db->Execute('update ' . $db->geoTables->filters_table . ' set display_order = display_order - 1 where filter_id = ? and display_order > 0', array( $id ) ) ) {
				trigger_error('Unable to update order (' . $db->ErrorMsg() . ') on line ' . __LINE__);
				ADMIN_AJAXController_Filter::getChildren( $data, $id );
				exit;
			}
		}
		ADMIN_AJAXController_Filter::getChildren( $data, $id);
	}
	
	function moveChoiceDown( $data ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";	
		
		$id = intval($data['id']);
		$lang = intval($data['lang']);
				
		$result = $db->Execute('select display_order, parent_id from ' . $db->geoTables->filters_table . ' where filter_id = ?', array( $id ));
		if( false === $result || !$result->RecordCount() ) {
			trigger_error('Unable to load choice with that ID (' . $db->ErrorMsg() . ')', E_USER_ERROR);
		}
		$result = $result->FetchRow();
		$order = $result['display_order'];
		$parent = $data['parent'] = $result['parent_id'];

		// Move next choice up
		if( false === $db->Execute('update ' . $db->geoTables->filters_table . ' set display_order = display_order - 1 where display_order > ? and parent_id = ? order by display_order limit 1', array( $order, $parent ) ) ) {
			trigger_error('Unable to update order (' . $db->ErrorMsg() . ')');
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		
		// Move selected choice down
		if( $db->Affected_Rows() ) {
			if( false === $db->Execute('update ' . $db->geoTables->filters_table . ' set display_order = display_order + 1 where filter_id = ? limit 1', array( $id ) ) ) {
				trigger_error('Unable to update order (' . $db->ErrorMsg() . ') on line ' . __LINE__);
				ADMIN_AJAXController_Filter::getChildren( $data, $id );
				exit;
			}
		}
		ADMIN_AJAXController_Filter::getChildren( $data, $id);
	}
	
	function moveChoiceTop( $data ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";	
		
		$id = intval($data['id']);
		$lang = intval($data['lang']);
		
		$result = $db->Execute('select display_order, parent_id from ' . $db->geoTables->filters_table . ' where filter_id = ?', array( $id ));
		if( false === $result || !$result->RecordCount() ) {
			trigger_error('Unable to load choice with that ID (' . $db->ErrorMsg() . ') on line ' . __LINE__);
			exit;
		}
		$result = $result->FetchRow();
		$order = $result['display_order'];
		$parent = $data['parent'] = $result['parent_id'];
		
		// Move choices below selected down one
		if( false === $db->Execute('update ' . $db->geoTables->filters_table . ' set display_order = display_order + 1 where display_order < ? and parent_id = ?', array( $order, $parent ) ) ) {
			trigger_error('Unable to update order (' . $db->ErrorMsg() . ') on line ' . __LINE__);
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		
		// Move selected to top
		if( false === $db->Execute('update ' . $db->geoTables->filters_table . ' set display_order = 0 where filter_id = ?', array( $id ) ) ) {
			trigger_error('Unable to update order (' . $db->ErrorMsg() . ') on line ' . __LINE__);
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		ADMIN_AJAXController_Filter::getChildren( $data, $id);
	}
	
	function moveChoiceBottom( $data ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";	
		
		$id = intval($data['id']);
		$lang = intval($data['lang']);
				
		$result = $db->Execute('select display_order, parent_id from ' . $db->geoTables->filters_table . ' where filter_id = ? limit 1', array( $id ));
		if( false === $result || !$result->RecordCount() ) {
			trigger_error('Unable to load choice with that ID (' . $db->ErrorMsg() . ') on line ' . __LINE__);
			exit;
		}
		$result = $result->FetchRow();
		$order = $result['display_order'];
		$parent = $data['parent'] = $result['parent_id'];
		
		$result = $db->Execute('select max(display_order) as last from ' . $db->geoTables->filters_table . ' where parent_id = ? limit 1', array( $parent ));
		if( false === $result || !$result->RecordCount() ) {
			trigger_error('Unable to load choice with that ID (' . $db->ErrorMsg() . ') on line ' . __LINE__);
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		$result = $result->FetchRow();	
		$last = $result['last'];
			
		// Move choices below selected up one
		if( false === $db->Execute('update ' . $db->geoTables->filters_table . ' set display_order = display_order - 1 where display_order > ? and parent_id = ?', array( $order, $parent ) ) ) {
			trigger_error('Unable to update order (' . $db->ErrorMsg() . ') on line ' . __LINE__);
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		
		// Move selected to bottom
		if( $db->Affected_Rows() ) {
			if( false === $db->Execute('update ' . $db->geoTables->filters_table . ' set display_order = ? where filter_id = ?', array( $last, $id ) ) ) {
				trigger_error('Unable to update order (' . $db->ErrorMsg() . ') on line ' . __LINE__);
				ADMIN_AJAXController_Filter::getChildren( $data, $id );
				exit;
			}
		}
		ADMIN_AJAXController_Filter::getChildren( $data, $id );
	}
	
	function delChoice( $data ) {		
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";	
		
		$id = intval($data['id']);
		$lang = $data['lang'] = 1;
			
		$result = $db->Execute('select display_order, parent_id from ' . $db->geoTables->filters_table . ' where filter_id = ?', array( $id ));
		if( false === $result || !$result->RecordCount() ) {
			trigger_error('Unable to load choice with that ID (' . $db->ErrorMsg() . ') on line ' . __LINE__);
			exit;
		}
		$result = $result->FetchRow();
		$order = $result['display_order'];
		$parent = $data['parent'] = $result['parent_id'];	
		
		// Make sure there aren't any children
		$result = $db->Execute('select count(*) as count from ' . $db->geoTables->filters_table . ' where parent_id = ?', array( $id ));
		if( false === $result ) {
			trigger_error('Unable to determine order of choice (' . $db->ErrorMsg() . ')');
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		$count = $result->FetchRow();
		$count = $count['count'];
				
		if( $count ) {
			trigger_error( "Cannot delete choices that contain subchoices", E_USER_NOTICE );
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}	
			
		if( false === $db->Execute('delete from ' . $db->geoTables->filters_table . ' where filter_id = ?', array( $id ) ) ) {
			trigger_error('Unable to update order (' . $db->ErrorMsg() . ') on line ' . __LINE__);
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
			
		if( false === $db->Execute('delete from ' . $db->geoTables->filters_languages_table . ' where filter_id = ?', array( $id ) ) ) {
			trigger_error('Unable to update order (' . $db->ErrorMsg() . ') on line ' . __LINE__);
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		if( $db->Affected_Rows() ) {
			if( false === $db->Execute('update ' . $db->geoTables->filters_table . ' set display_order = display_order - 1 where display_order > ? and parent_id = ?', array( $order, $parent ) ) ) {
				trigger_error('Unable to update order (' . $db->ErrorMsg() . ') on line ' . __LINE__);
				ADMIN_AJAXController_Filter::getChildren( $data, $id );
				exit;
			}
		}
		if( !ADMIN_AJAXController_Filter::setInStatement( $parent ) ) {
			ADMIN_AJAXController_Filter::getChildren( $data, $id );
			exit;
		}
		ADMIN_AJAXController_Filter::getChildren( $data );
	}
	
	function getChildren( $data, $selectedId = 0 ) {
		$parent = intval($data['parent']);
		$lang = intval($data['lang']);
		
		$choicesBase = ADMIN_AJAXController_Filter::getChildrenAsArray( $parent, 1 );
		$choices = ADMIN_AJAXController_Filter::getChildrenAsArray( $parent, $lang );
	
		if( !count($choicesBase )) {
			return "";
		}
		
		$optionsHTML = "";
		$options = array();
		foreach( $choicesBase as $id => $name ) {
			$hint = $lang == 1 ? "" : " ({$choicesBase[$id]})";
			$name = isset($choices[$id]) ? $choices[$id] : "Unspecified for this language";
			if( $selectedId == $id ) {
				$optionsHTML .= "
					<option value='" . $id . "' selected='selected'>{$name}{$hint}</option>";
			} else {
				$optionsHTML .= "
					<option value='" . $id . "' >{$name}{$hint}</option>";
			}
			$options[] = '"' . $name . $hint .'": ' . $id;
		}
		
		if( defined("AJAX_REQUEST")) {
			$json = '{ "options": $H({ ' . implode(', ', $options) . ' }), "selected": ' . $selectedId . ' }';
			$charset = geoString::getCharsetTo();
			if (!$charset){
				//if not using charsetTo, then use the charsetclean setting.
				$charset = geoString::getCharset();
			}
			
			//work-around for different charsets, so that content is still sent through body, not through headers
			header('Content-Type: application/json; charset='.$charset);
			header("X-JSON: eval(\"(\"+this.transport.responseText+\")\")");
			//header("X-JSON: {$json}");
			echo $json;
		} else {
			echo $optionsHTML;
		}
	}
	
	function getChildrenAsArray( $parent, $lang = 1 ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";
		
		$lang = intval($lang) > 0 ? intval($lang) : 1;
		
		if( !is_array($parent) ) {
			$parent = array( intval($parent) );
		}

		$sql = 'select l.filter_name as filter_name, f.filter_id as filter_id from ' . $db->geoTables->filters_table . ' as f, ' . $db->geoTables->filters_languages_table . ' as l where f.parent_id in(?) and l.filter_id = f.filter_id and l.language_id = ? order by display_order';
		$parent = implode( ", ", $parent );
		$choices = $db->GetAll( $sql, array($parent, $lang) );
		if( false === $choices ) {
			trigger_error('Unable to fetch children.' . $db->ErrorMsg());
			return array();
		}
		$children = array();
		foreach( $choices as $choice ) {
			$children[$choice['filter_id']] = $choice['filter_name'];
		}
		return $children;
	}
	
	function setInStatement( $root, $lang = 1 ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";
		
		$root = intval( $root );
		if( $root == 0 ) {
			// ignore, because there is no filter with id = 0
			return true;
		}
		
		// Fetch everyone that descended from $parent

		
		/*
		 set parents to an array with root in it
		 declare ancestors
		 do {
			add parents to ancestors
			set parents to children of parents
		 } while( parents is not empty );
		*/
		$parents = array( $root );
		$ancestors = array();
		do {
			$parents = array_keys( ADMIN_AJAXController_Filter::getChildrenAsArray( $parents, $lang ) );
			if( count($parents) ) {
				$ancestors = array_merge($ancestors, $parents);
			}
		} while( count($parents) );	
			$ancestors[] = $root;
		$inStatement = "in( " . implode( ', ', $ancestors ) . ")";

		$result = $db->Execute( 'update ' . $db->geoTables->filters_table . ' set 
				in_statement = ? where filter_id = ?', 
			array( $inStatement, $root)
		);
		if(false === $result) {
			trigger_error('Unable to update in-statement (' . $db->ErrorMsg() . ')');
			return false;
		}
		return true;
	}

	function setRegistrationOptional( $data ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";
		
		$level = intval($data['level']);
		$id = intval($data['id']);
		
		$fields = "registration_optional_1_filter_association";
		for($i = 2; $i <= 10; $i++ ) {
			$fields .= ", registration_optional_{$i}_filter_association";
		}
				
		// Make sure setting this level is not associated with an optional field already
		$result = $db->Execute("select {$fields} from " . $db->geoTables->registration_configuration_table);
		if( false === $result ) {
			trigger_error("Cannot save association");
			ADMIN_AJAXController_Filter::showRegistrationOptionals( $data );
			exit;
		}
		$row = $result->FetchRow();
		
		
		
		if( !$id ) {
			// Remove this level's previous association
			$unsetPrevious = "";
			reset($row);
			foreach($row as $fieldName => $levelAssociation ) {
				if( $level == $levelAssociation && !is_numeric($fieldName) ) {
					$unsetPrevious[] = "{$fieldName} = 0";
				}
			}
			$unsetPrevious = implode(", ", $unsetPrevious);
			// Save association
			$result = $db->Execute("update " . $db->geoTables->registration_configuration_table . " set {$unsetPrevious}");
			if( false === $result ) {
				trigger_error("Cannot save association");
				ADMIN_AJAXController_Filter::showRegistrationOptionals( $data );
				exit;
			}
		} else if( isset($row["registration_optional_{$id}_filter_association"]) && $row["registration_optional_{$id}_filter_association"] > 0 ) {
			$previous = $row["registration_optional_{$id}_filter_association"];
			trigger_error('This field is already associated with level ' . $previous . '.  If this is not the case, you may try setting it to first option (blank) to reset this level\'s registration association.', E_USER_NOTICE);
			ADMIN_AJAXController_Filter::showRegistrationOptionals( $data );
			exit;
		} else {
			// Save association
			$query_data = array();
			$parts = array ();
			foreach ($row as $field => $value) {
				if ($field == "registration_optional_{$id}_filter_association") {
					//this is the one we are associating now
					$parts[] = "$field = ?";
					$query_data[] = $level;
				} else if ($level == $value && !is_numeric($field)) {
					//this is the old value for this level, set it to 0
					$parts[] = "$field = ?";
					$query_data[] = 0;
				}
			}
			$sql = "UPDATE " . geoTables::registration_configuration_table . " SET ".implode(', ',$parts);
			$result = $db->Execute($sql, $query_data);
			if( false === $result ) {
				trigger_error("Cannot save association");//, Debug Info: sql: $sql error: ".$db->ErrorMsg());
				ADMIN_AJAXController_Filter::showRegistrationOptionals( $data );
				exit;
			}
		}
		ADMIN_AJAXController_Filter::showRegistrationOptionals( $data );
	}
	
	function showRegistrationOptionals( $data ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";
		
		$level = intval($data['level']);
				
		// Show options
		$sql = "select * from ".$db->geoTables->registration_configuration_table;
		$result = $db->Execute($sql);
		if ( false === $result) {
			trigger_error("ERROR SQL: " . $db->ErrorMsg());
			exit;
		}
		$reg_optionals_row = $result->FetchRow();	
		
		$optionsHTML =  "
			<option value='' style='font-style: italic'>&nbsp;</option>";
		$options = array( '"&nbsp;": ""' );
		$selected = 0;
		for($i = 1; $i <= 10; $i++ ) {
			// Check if this optional field is associated with this level, and select the option if it is.
			if($reg_optionals_row['registration_optional_' . $i . '_filter_association'] == $level) {
				$optionsHTML .= "
					<option value='{$i}' selected='selected'>" . $reg_optionals_row['registration_optional_' . $i . '_field_name'] . "</option>";
				$selected = $i;
			} else {
				$optionsHTML .= "
					<option value='{$i}'>" . $reg_optionals_row['registration_optional_' . $i . '_field_name'] . "</option>";
			}
			$options[] = '"' . $reg_optionals_row['registration_optional_' . $i . '_field_name'] .'": ' . $i;
		}
		
		if( defined("AJAX_REQUEST")) {
			$json = '{ "options": $H({ ' . implode(', ', $options) . ' }), "selected": ' . $selected . ' }';
			header("X-JSON: {$json}");
		}
		echo $optionsHTML;
	}
	
	function setSiteOptional( $data ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";	
		
		$level = intval($data['level']);
		$id = intval($data['id']);
		
		for($i = 1; $i <= 20; $i++ ) {
			$currentField = $db->get_site_setting( 'optional_' . $i . '_filter_association' );
			
			// If current field is $id, and has a value then the user cannot make this association
			if( $i == $id && $currentField > 0 && $currentField != $level ) {
				trigger_error( "This field is already associated with level {$currentField}.", E_USER_NOTICE );
				ADMIN_AJAXController_Filter::showSiteOptionals( array('level' => $level ) );
				exit;
			}
		}
	
		for($i = 1; $i <= 20; $i++ ) {
			if( $i == $id ) {
				$db->set_site_setting( 'optional_' . $i . '_filter_association', $level );
			} else if( $db->get_site_setting( 'optional_' . $i . '_filter_association' ) == $level) {
				$db->set_site_setting( 'optional_' . $i . '_filter_association', '');
			}
		}
		
		ADMIN_AJAXController_Filter::showSiteOptionals( $data );
	}
	
	function showSiteOptionals( $data ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";	
		
		$level = intval($data['level']);

		// Show options
		$optionsHTML =  "
			<option value='' style='font-style: italic'>&nbsp;</option>";
		$options = array( '"&nbsp;": ""' );
		$selected = 0;
		for($i = 1; $i <= 20; $i++ ) {
			$text = $db->get_site_setting('optional_field_'.$i.'_name');
			if( $db->get_site_setting( 'optional_' . $i . '_filter_association' ) == $level ) {
				$optionsHTML .= "
					<option value='{$i}' selected='selected'>{$text}</option>";
				$selected = $i;
			} else {
				$optionsHTML .= "
					<option value='{$i}'>{$text}</option>";
			}
			$options[] = '"' . $text .'": ' . $i;
		}
		
		if( defined("AJAX_REQUEST")) {
			$json = '{ "options": $H({ ' . implode(', ', $options) . ' }), "selected": ' . $selected . ' }';
			header("X-JSON: {$json}");
		}
		echo $optionsHTML;
	}
	
	function toggleFilter( $data ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";	
		
		$state = intval($data['state']);
		
		$db->set_site_setting('use_filters', $state );
		
		echo $state;		
	}
	
	function getLanguageSelect( $selected = 1 ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";
		
		$selected = intval($selected);
		
		$sql = "select language_id,language from ".$db->geoTables->pages_languages_table." order by language_id asc";
		$result = $db->Execute($sql);
		if( false === $result ) {
			trigger_error('Could not get language data');
			exit;
		}
		$options = "";
		while( $language = $result->FetchRow() ) {
			$hint = $language['language_id'] == 1 ?  " (Base)" : "";
			if( $selected == $language['language_id'] ) {
				$options .= "
					<option value='{$language['language_id']}' selected='selected'>{$language['language']}{$hint}</option>";
			} else {
				$options .= "
					<option value='{$language['language_id']}'>{$language['language']}{$hint}</option>";
			}
		}
		return "
				<select name='language'>{$options}</select>";
	}
	
	function showPreviewDropdown( $data ) {
		$db = true;
		include GEO_BASE_DIR . "get_common_vars.php";
		
		$lang = intval($data['lang']);
		$level = intval($data['level']);
		$parent = isset($data['parent']) ? intval($data['parent']) : 0;
		
		if( $level > 1 && $parent == 0 ) {
			return "";
		}
		
		$sql = "select f.filter_id, f.parent_id, l.filter_name
			from ".$db->geoTables->filters_table." as f, ".$db->geoTables->filters_languages_table." as l
			where f.filter_level = ? and f.filter_id = l.filter_id and l.language_id = ? and f.parent_id = ? order by display_order asc";
		$result = $db->Execute($sql, array( $level, $lang, $parent ));
		if ( false === $result ) {
			trigger_error('Could not get preview' . $db->ErrorMsg() );
		}
		if( $result->RecordCount() ) {
			$html = "
					<option>level ".$level." dropdown</option>";
			while ($filter = $result->FetchRow()) {
				$html .=  "<option value='{$filter['filter_id']}'>{$filter["filter_name"]}</option>";
			}
			$html .= "
					<option>clear level ".$level."</option>";
			echo "<select onchange='geoFilter.updatePreview( {$level} );' id='level_{$level}_preview'>" . $html . "</select>";
		} else {
			echo "";
		}
	}
	
	function getJavascript() {
		return file_get_contents( "js/Filter.js" );
	}
}

?>