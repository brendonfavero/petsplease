<?php
class addon_ppExtraPages_pages extends addon_ppExtraPages_info
{
	    
    public function advertisingPets() {
        $db = true;
        include (GEO_BASE_DIR.'get_common_vars.php');
        $db->Execute("set names 'utf8'"); 

        $view = geoView::getInstance();

        $view->setBodyTpl('advertisingpets.tpl', $this->name);
    }
    
    public function advertisingBusinesses() {
        $db = true;
        include (GEO_BASE_DIR.'get_common_vars.php');
        $db->Execute("set names 'utf8'"); 

        $view = geoView::getInstance();

        $view->setBodyTpl('advertisingbusiness.tpl', $this->name);
    }
    
    public function petsAndProducts() {
        $db = true;
        include (GEO_BASE_DIR.'get_common_vars.php');
        $db->Execute("set names 'utf8'"); 

        $view = geoView::getInstance();

        $view->setBodyTpl('petsandproducts.tpl', $this->name);
    }
    
    public function dogClicker() {
        $db = true;
        include (GEO_BASE_DIR.'get_common_vars.php');
        $db->Execute("set names 'utf8'"); 

        $view = geoView::getInstance();

        $view->setBodyTpl('dogclicker.tpl', $this->name);
    }
}
