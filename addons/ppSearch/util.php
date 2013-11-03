<?php
class addon_ppSearch_util extends addon_ppSearch_info
{
	public function core_Search_classifieds_generate_query ($vars)
	{
		$searchClass = $vars['this'];
		$classTable = geoTables::classifieds_table;

		$isSold = $searchClass->search_criteria["sold_displayed"];
		
		if ($isSold) {
			$query = $searchClass->db->getTableSelect(DataAccess::SELECT_SEARCH);
			$query->where("$classTable.`sold_displayed` = $isSold", 'sold_displayed');	
		}	
	}
}
?>