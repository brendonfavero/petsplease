<?php
class addon_ppCompetition_pages extends addon_ppCompetition_info
{
	public function competition() {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');
		$db->Execute("set names 'utf8'"); 

		$view = geoView::getInstance();

		$breedID = $_REQUEST['id'];

		// Get nav info
		$pettypes = array("1" => "Dog", "2" => "Cat");
		$view->setBodyVar("pettypes", $pettypes);

		$sql = "SELECT id FROM petsplease_competition ";
		$nav = $db->GetAll($sql);
		$view->setBodyVar('nav', $nav);

		if ($breedID) {
			// Get detailed info
			$sql = "SELECT * FROM petsplease_competition WHERE id = ?";
			$detail = $db->GetRow($sql, array($breedID));
			$view->setBodyVar('detail', $detail);

			$sql = "SELECT * FROM petsplease_competition";
			$view->setBodyVar('images', $images);
		}

		$view->setBodyTpl('competition.tpl', $this->name);
	}

   
}