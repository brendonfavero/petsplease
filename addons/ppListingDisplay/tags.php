<?php
class addon_ppListingDisplay_tags extends addon_ppListingDisplay_info
{
	// This is a special listing tag (called with {listing addon=blaablaabla} not {addon blaa}) 
	public function extraQuestionValue($params, Smarty_Internal_Template $smarty)
	{		
		$listing_id = $params['listing_id'];
		$question_id = $params['qid'];
		$default_value = $params['default'];
		
		$extra_questions = geoListing::getExtraQuestions($listing_id);
		$extra_question = $extra_questions[$question_id];

		if (!$extra_question) {
			return $default_value;
		}
		else {
			return $extra_question['value'];
		}
	}

	public function extraCheckboxValue($params, Smarty_Internal_Template $smarty)
	{		
		$listing_id = $params['listing_id'];
		$question_id = $params['qid'];
		$true_value = $params['true'] ?: "Yes";
		$false_value = $params['false'] ?: "No";
		
		$extra_questions = geoListing::getCheckboxes($listing_id);
		$extra_question = $extra_questions[$question_id];

		if (!$extra_question) {
			return $false_value;
		}
		else {
			return $true_value;
		}
	}

	public function extraLeveledValue($params, Smarty_Internal_Template $smarty)
	{		
		$listing_id = $params['listing_id'];
		$question_id = $params['qid'];
		$level = $params['level'];
		$default_value = $params['default'];
		
		$extra_questions = geoListing::getLeveledValues($listing_id);
		$extra_levels = $extra_questions[$question_id];
		$extra_level = $extra_levels[$level];

		if (!$extra_level) {
			return $default_value;
		}
		else {
			return $extra_level['name'];
		}
	}

	public function extraMultiCheckboxDisplay($params, Smarty_Internal_Template $smarty)
	{		
		$joined = $params['joined'];
		$values = explode(";", $joined);

		$tpl_vars = array('values' => $values);

		return geoTemplate::loadInternalTemplate($params, $smarty, 'multicheckDisplay.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}

	public function extraMultiCheckboxSelect($params, Smarty_Internal_Template $smarty)
	{		
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$type = $params['typeid'];
		$listingfield = $params['listingfield'];
		$value = $params['value'];
		$values = explode(";", $value);

		$sql = "SELECT * FROM ".geoTables::sell_choices_table." WHERE `type_id` = ".$type." ORDER BY `display_order`,`value`";
		$options = $db->GetAll($sql);

		foreach ($options as &$option) {
			if (in_array($option['value'], $values)) {
				$option['checked'] = true;
			}
		}

		$tpl_vars = array(
			'listingfield' => $listingfield,
			'options' => $options
		);

		return geoTemplate::loadInternalTemplate($params, $smarty, 'multicheckSelector.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}


	public function extraLeveledMutliCheckboxDisplay($params, Smarty_Internal_Template $smarty)
	{		
		$fieldvalue = $params['joined'];

		$strbygroup = explode("|", $fieldvalue);

		$groups = array();
		foreach ($strbygroup as $strgroup) {
			$startbrace = strpos($strgroup, "{");

			$grouplabel = substr($strgroup, 0, $startbrace);
			$groupvaluesstr = substr($strgroup, $startbrace + 1, -1);

			$groupvalues = explode(";", $groupvaluesstr);

			$groups[$grouplabel] = $groupvalues;
		}

		$tpl_vars = array('groups' => $groups);

		return geoTemplate::loadInternalTemplate($params, $smarty, 'leveledmulticheckDisplay.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}


	public function extraLeveledMutliCheckboxSelect($params, Smarty_Internal_Template $smarty)
	{		
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$type = $params['typeid'];
		$listingfield = $params['listingfield'];
		$fieldvalue = $params['value'];

		$strbygroup = explode("|", $fieldvalue);

		$values = array();
		foreach ($strbygroup as $strgroup) {
			$startbrace = strpos($strgroup, "{");

			$grouplabel = substr($strgroup, 0, $startbrace);
			$groupvaluesstr = substr($strgroup, $startbrace + 1, -1);

			$groupvalues = explode(";", $groupvaluesstr);
			$values = array_merge($values, $groupvalues);
		}

		$sql = "SELECT parent, name as value, field.id FROM ".geoTables::leveled_field_value." field JOIN ".geoTables::leveled_field_value_languages." language ON field.id = language.id  
				WHERE `leveled_field` = ".$type." AND enabled='yes' ORDER BY `level`,`display_order`,`name`";
		$options = $db->GetAll($sql);

		$groups = array();
		foreach ($options as &$option) {
			$option['value'] = urldecode($option['value']);

			if ($option['parent'] == 0) {
				$option['values'] = array();
				$groups[$option['id']] = $option;
			}
			else {
				if (in_array($option['value'], $values)) {
					$option['checked'] = true;
				}

				$groups[$option['parent']]['values'][] = $option;
			}
		}

		$tpl_vars = array(
			'listingfield' => $listingfield,
			'groups' => $groups
		);

		return geoTemplate::loadInternalTemplate($params, $smarty, 'leveledmulticheckSelector.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}
}