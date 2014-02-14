<?php
class addon_ppCompetition_admin extends addon_ppCompetition_info
{
	public function init_pages ($menuName)
	{
		menu_page::addonAddPage('addon_Competition_settings', '', 'Competition Settings', 'ppCompetition', '');
	}

	public function display_addon_Competition_settings() {
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
					$sql = "SELECT * FROM petsplease_competition WHERE id = ?";
					$result = $db->GetRow($sql, array($id));
					$view->setBodyVar('detail', $result);
				}

				$view->setBodyTpl('admin/upload.tpl', $this->name);
			}
			else {
				$sql = "SELECT * FROM petsplease_competition";
				$result = $db->GetAll($sql);

				$view->setBodyVar('pets', $result);
				$view->setBodyTpl('admin/petlist.tpl', $this->name);
			}
		}
	}

	public function update_addon_Competition_settings() {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');

		$vars = $_REQUEST['d'];
        $cols = array("breed", "description", "height", "weight", "size", "lifespan", "hypoallergenic", 
            "colours", "coatlength", "housing", "familyfriendly", "trainability", "energy", "grooming", "shedding");
        $week = $_REQUEST['week'];
        $name = $_REQUEST['name'];
        $sender = $_REQUEST['sender'];
        $full_url = $_REQUEST['full_url'];
        $thumb_url = $_REQUEST['thumb_url'];
        
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
            $uploads_url =  '/addons/ppCompetition/images/';
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
                    
        $current = 0;
        if(isset($_POST['formCurrent']) && 
           $_POST['formCurrent'] == 'Yes') 
        {
            $updateSql = "UPDATE petsplease_competition set current = 0 where current = 1 and id > 0";
            $db->Execute($updateSql);
            $current = 1;
        }            

		if ($vars['id']) {
			if ($_REQUEST['dodelete'] == true) {
				$sql = "DELETE FROM petsplease_competition WHERE id = ?";
				$db->Execute($sql, array($vars['id']));

				if (!$db->ErrorMsg()) {
					geoAdmin::getInstance()->message("Competition (id:".$vars['id'].") successfully deleted");
				}
				else {
					geoAdmin::getInstance()->message("Competition (id:".$vars['id'].") could not be deleted<br><br>The error returned was:<br>" . $db->ErrorMsg());
				}
			}
			else {

				$sql = "UPDATE petsplease_competition SET week = '$week', petname = '$name', thumb_url = '$thumb_url',";
				$sql .= " full_url = '$full_url', current = $current WHERE id = " . $vars['id'];
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
            $sql = "INSERT INTO petsplease_competition (week, thumb_url, full_url, petname, sender_name, current) VALUES ";

			$sql .= "('$week', '$thumb_url', '$full_url', '$name ', '$sender', $current)"; 

			$db->Execute($sql);

			if (!$db->ErrorMsg()) {
				geoAdmin::getInstance()->message("New pet of the week was successfully inserted week = " . $_REQUEST['d']['week']);
			}
			else {
				geoAdmin::getInstance()->message("New pet of the week could not be inserted<br><br>The error returned was:<br>" . $db->ErrorMsg());
			}
		}
		
	}
}