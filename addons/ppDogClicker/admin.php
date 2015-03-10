<?php
class addon_ppDogClicker_admin extends addon_ppDogClicker_info
{
	public function init_pages ($menuName)
	{
		menu_page::addonAddPage('addon_photos_settings', '', 'Photos', 'ppDogClicker', '');
        menu_page::addonAddPage('addon_link_settings', '', 'Article Link', 'ppDogClicker', '');
        menu_page::addonAddPage('addon_content_settings', '', 'Content', 'ppDogClicker', '');
	}

	public function display_addon_photos_settings() {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');
		$view = geoView::getInstance();
		$view->setBodyVar('messages', geoAdmin::getInstance()->message());

		$id = $_REQUEST['edit_id'];

		$db->Execute("set names 'utf8'"); 

		if ($_REQUEST['action'] == "images") {
			// Get info about breed
			$sql = "SELECT * FROM petsplease_competition WHERE id = ?";
			$result = $db->GetRow($sql, array($id));
			$view->setBodyVar('competition', $result);

			$view->setBodyTpl('admin/upload.tpl', $this->name);
		}
		else {
			if ($id) {
				if ($id != "new") {
					$sql = "SELECT * FROM petsplease_dogclicker_images WHERE id = ?";
					$result = $db->GetRow($sql, array($id));
					$view->setBodyVar('detail', $result);
				}

				$view->setBodyTpl('admin/upload.tpl', $this->name);
			}
			else {
				$sql = "SELECT * FROM petsplease_dogclicker_images";
				$result = $db->GetAll($sql);

				$view->setBodyVar('pets', $result);
				$view->setBodyTpl('admin/doglist.tpl', $this->name);
			}
		}
	}

	public function update_addon_photos_settings() {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');

		$vars = $_REQUEST['d'];
        $cols = array("breed", "description", "height", "weight", "size", "lifespan", "hypoallergenic", 
            "colours", "coatlength", "housing", "familyfriendly", "trainability", "energy", "grooming", "shedding");
        
        $name = $_REQUEST['dogname'];
        $trainer = $_REQUEST['trainer'];
        $age = $_REQUEST['age'];
        $comments = $_REQUEST['comments'];
        
        if (@is_uploaded_file($_FILES['imagefile']['tmp_name'])) {
            if ($_FILES['imagefile']['error']) {
                geoAdmin::getInstance()->message("ERROR: PHP upload error (id:" . $_FILES['imagefile']['error'] . ")");
                return;
            }

            if (!@getimagesize($_FILES["imagefile"]['tmp_name'])) {
                geoAdmin::getInstance()->message("ERROR: File is invalid image");
                return;
            }

            // If you find that this section errors out without explanation it is probably
            //  due to the file being sent being too big. We should try put a useful
            //  error in here but not exactly sure how to go about that

            $siteRoot = $_SERVER['DOCUMENT_ROOT'];
            $uploads_url =  '/addons/ppDogClicker/images/';
            $now = time();
            
            do {
                $full_name = $name . "-$now-large.jpg";
                $thumb_name = $name . "-$now-thumb.jpg";
                $full_url = $uploads_url . $full_name;
                $thumb_url = $uploads_url . $thumb_name;
                $full_path = $siteRoot . $full_url;
                $thumb_path = $siteRoot . $thumb_url;

                $now++;
            } while (file_exists($full_path));

            try {
                $fullImage = geoImage::resize($_FILES['imagefile']['tmp_name'], 600, 500);
                imagejpeg($fullImage['image'], $full_path, 80);
                imagedestroy($fullImage['image']);
                $resizedImage = geoImage::resize($_FILES['imagefile']['tmp_name'], 300, 200);
                imagejpeg($resizedImage['image'], $thumb_path, 80);
                imagedestroy($resizedImage['image']);
            }
            catch (Exception $e) {
                geoAdmin::getInstance()->message("ERROR: Could not move file to addon images folder. Please check directory permissions");
                return;
            }                    
        }              

		if ($vars['id']) {
			if ($_REQUEST['dodelete'] == true) {
				$sql = "DELETE FROM petsplease_dogclicker_images WHERE id = ?";
				$db->Execute($sql, array($vars['id']));

				if (!$db->ErrorMsg()) {
					geoAdmin::getInstance()->message("Competition (id:".$vars['id'].") successfully deleted");
				}
				else {
					geoAdmin::getInstance()->message("Competition (id:".$vars['id'].") could not be deleted<br><br>The error returned was:<br>" . $db->ErrorMsg());
				}
			}
			else {

				$sql = "UPDATE petsplease_dogclicker_images SET age = '$age', dogname = '$name', thumb_url = '$thumb_url',";
				$sql .= " full_url = '$full_url', trainer = '$trainer' WHERE id = " . $vars['id'];
				$db->Execute($sql);

				if (!$db->ErrorMsg()) {
					geoAdmin::getInstance()->message("Competition (id:".$vars['id'].") successfully updated");
				}
				else {
					geoAdmin::getInstance()->message("Competition (id:".$vars['id'].") could not be updated<br><br>The error returned was:<br>" . $db->ErrorMsg());
				}
			}
		}
		else {	
            
            //upload image
            
            // Insert new row
            $sql = "INSERT INTO petsplease_dogclicker_images (thumb_url, full_url, dogname, trainer, age) VALUES ";

			$sql .= "('$thumb_url', '$full_url', '$name', '$trainer', $age)"; 

			$db->Execute($sql);

			if (!$db->ErrorMsg()) {
				geoAdmin::getInstance()->message("Image has been successfully uploaded");
			}
			else {
				geoAdmin::getInstance()->message("Image could not be inserted<br><br>The error returned was:<br>" . $db->ErrorMsg() . "<br><br>The sql was:<br>"  . $sql);
			}
		}
		
	}

    public function display_addon_link_settings() {
        $db = true;
        include (GEO_BASE_DIR.'get_common_vars.php');
        $view = geoView::getInstance();
        $view->setBodyVar('messages', geoAdmin::getInstance()->message());

        $id = $_REQUEST['edit_id'];

        $db->Execute("set names 'utf8'"); 
        
        // Get info about breed
        $sql = "SELECT * FROM petsplease_dogclicker_links";
        $result = $db->GetAll($sql);
        $view->setBodyVar('links', $result);

        $view->setBodyTpl('admin/links.tpl', $this->name);
       
    }

    public function update_addon_link_settings() {
         $db = true;
        include (GEO_BASE_DIR.'get_common_vars.php');
        
        $id = $_REQUEST['id'];
        $url = $_REQUEST['url'];
        
        $sql = "SELECT * FROM petsplease_dogclicker_links WHERE id = $id";
        $result = $db->GetRow($sql);
        if ($result) {
            // Insert new row
            $sql = "UPDATE petsplease_dogclicker_links SET link = '$url' WHERE id = $id";
            
            $db->Execute($sql);
    
            if (!$db->ErrorMsg()) {
                geoAdmin::getInstance()->message("Content was successfully updated");
            }
            else {
                geoAdmin::getInstance()->message("Content couldn't be updated<br>The error returned was:<br>" . $db->ErrorMsg());
            }
        }
        else {
                geoAdmin::getInstance()->message("Error updating link");
        }
        
    }

    public function display_addon_content_settings() {
        $db = true;
        include (GEO_BASE_DIR.'get_common_vars.php');
        $view = geoView::getInstance();
        $view->setBodyVar('messages', geoAdmin::getInstance()->message());

        $id = $_REQUEST['edit_id'];

        $db->Execute("set names 'utf8'"); 
        
        // Get info about breed
        $sql = "SELECT content FROM petsplease_dogclicker_pages WHERE id = 1";
        $result = $db->GetRow($sql);
        $view->setBodyVar('competition', $result['content']);

        $view->setBodyTpl('admin/content.tpl', $this->name);
    }

    public function update_addon_content_settings() {
        $db = true;
        include (GEO_BASE_DIR.'get_common_vars.php');
        
        $content = $_REQUEST['content'];
        
        $sql = "SELECT * FROM petsplease_dogclicker_pages WHERE id = 1";
        $result = $db->GetRow($sql);
        if ($result) {
            // Insert new row
            $sql = "UPDATE petsplease_dogclicker_pages SET content = '$content' ";
            
            $db->Execute($sql);
    
            if (!$db->ErrorMsg()) {
                geoAdmin::getInstance()->message("Content was successfully updated");
            }
            else {
                geoAdmin::getInstance()->message("Content couldn't be updated<br>The error returned was:<br>" . $db->ErrorMsg());
            }
        }
        else {
            // Insert new row
            $sql = "INSERT INTO petsplease_dogclicker_pages (id,content) VALUES ";
    
            $sql .= "('1', '$content')"; 
    
            $db->Execute($sql);
    
            if (!$db->ErrorMsg()) {
                geoAdmin::getInstance()->message("Content was successfully added");
            }
            else {
                geoAdmin::getInstance()->message("Content couldn't be added<br>The error returned was:<br>" . $db->ErrorMsg());
            }
        }
        
    }

}