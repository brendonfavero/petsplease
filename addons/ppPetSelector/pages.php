<?php
class addon_ppPetSelector_pages extends addon_ppPetSelector_info
{
	public function detail() {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');

		$view = geoView::getInstance();

		$breedID = $_REQUEST['id'];

		// Get nav info
		$sql = "SELECT id, pettype_id, breed FROM petsplease_petselector_data";
		$nav = $db->GetAll($sql);
		$view->setBodyVar('nav', $nav);

		if ($breedID) {
			// Get detailed info
			$sql = "SELECT * FROM petsplease_petselector_data WHERE id = ?";
			$detail = $db->GetAll($sql, array($breedID));
			$view->setBodyVar('detail', $detail);
		}

		$view->setBodyTpl('detail.tpl', $this->name);
	}
}