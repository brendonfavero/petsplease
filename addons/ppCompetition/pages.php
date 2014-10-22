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

		$sql = "SELECT * FROM petsplease_competition where current = 1 LIMIT 1";
		$current = $db->GetAll($sql);
		$view->setBodyVar('current', $current);		
        
        $sql = "SELECT * FROM petsplease_competition where current = 0 ORDER BY RAND()";
        $competitions = $db->GetAll($sql);
        $view->setBodyVar('competitions', $competitions); 

		$view->setBodyTpl('competition.tpl', $this->name);
	}
    
    public function terms() {
        $db = true;
        include (GEO_BASE_DIR.'get_common_vars.php');
        $db->Execute("set names 'utf8'"); 

        $view = geoView::getInstance();

        $view->setBodyTpl('terms.tpl', $this->name);
    }

   
}