<?php
class addon_ppPetSelector_pages extends addon_ppPetSelector_info
{
	public function detail() {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');
		$db->Execute("set names 'utf8'"); 

		$view = geoView::getInstance();

		$breedID = $_REQUEST['id'];

		// Get nav info
		$pettypes = array("1" => "Dog", "2" => "Cat");
		$view->setBodyVar("pettypes", $pettypes);

		$sql = "SELECT id, pettype_id, breed FROM petsplease_petselector_breed ORDER BY pettype_id, breed";
		$nav = $db->GetAll($sql);
		$view->setBodyVar('nav', $nav);

		if ($breedID) {
			// Get detailed info
			$sql = "SELECT * FROM petsplease_petselector_breed WHERE id = ?";
			$detail = $db->GetRow($sql, array($breedID));
			$view->setBodyVar('detail', $detail);

			$sql = "SELECT * FROM petsplease_petselector_images WHERE breed_id = ?";
			$images = $db->GetAll($sql, array($breedID));
			$view->setBodyVar('images', $images);
		}

		$view->setBodyTpl('detail.tpl', $this->name);
	}
}