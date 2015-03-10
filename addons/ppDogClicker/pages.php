<?php
class addon_ppDogClicker_pages extends addon_ppDogClicker_info
{
	
    
    public function dogclicker() {
        $db = true;
        include (GEO_BASE_DIR.'get_common_vars.php');
        $db->Execute("set names 'utf8'"); 

        $view = geoView::getInstance();
        
        $imagessql = "SELECT * FROM petsplease_dogclicker_images";
        $imagesresult = $db->GetAll($imagessql);
        $view->setBodyVar('dogs', $imagesresult);
        
        $linkssql = "SELECT * FROM petsplease_dogclicker_links";
        $linksresult = $db->GetAll($linkssql);
        $view->setBodyVar('links', $linksresult);
        
        $contentsql = "SELECT * FROM petsplease_dogclicker_pages";
        $contentresult = $db->GetRow($contentsql);
        $view->setBodyVar('content', $contentresult['content']);
            
        $view->setBodyTpl('dogclicker.tpl', $this->name);
    }

   
}