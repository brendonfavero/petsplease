<?php
class addon_ppPetSelector_tags extends addon_ppPetSelector_info
{
	public function breedDescription($params, Smarty_Internal_Template $smarty) {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');

		$tpl_vars = array();

		$breed = $params['breed'];

		return geoTemplate::loadInternalTemplate($params, $smarty, 'searchSidebar.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);	
	}
}
