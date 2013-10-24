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

	public function extraMultiCheckboxSelect($params, Smarty_Internal_Template $smarty)
	{		
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$type = $params['typeid'];
		$value = $params['value'];
		$values = explode(";", $value);

		$sql = "SELECT * FROM ".geoTables::sell_choices_table." WHERE `type_id` = ".$type." ORDER BY `display_order`,`value`";
		$services = $db->GetAll($sql);

		foreach ($services as &$service) {
			if (in_array($service['value'], $values)) {
				$service['checked'] = true;
			}
		}

		$tpl_vars = array('services' => $services);

		return geoTemplate::loadInternalTemplate($params, $smarty, 'serviceOptions.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}
}