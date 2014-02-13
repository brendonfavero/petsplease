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

		if ($_REQUEST['action'] == "images") {
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
					$file_name = $vars['id'] . "-$now.jpg";
					$file_url = $uploads_url . $file_name;
					$file_path = $siteRoot . $file_url;

					$now++;
				} while (file_exists($file_path));

				try {
					$resizedImage = geoImage::resize($_FILES['imagefile']['tmp_name'], 300, 200);
					imagejpeg($resizedImage['image'], $file_path, 80);
					imagedestroy($resizedImage['image']);
				}
				catch (Exception $e) {
					geoAdmin::getInstance()->message("ERROR: Could not move file to addon images folder. Please check directory permissions");
					return;
				}

				// Now tell the db about it
				$sql = "INSERT INTO petsplease_competition_images (breed_id, image_url, full_filename) VALUES (?, ?, ?)";
				$db->Execute($sql, array($vars['id'], $file_url, $file_name));

				if (!$db->ErrorMsg()) {
					geoAdmin::getInstance()->message("Image successfully uploaded");
				}
				else {
					geoAdmin::getInstance()->message("Image could not be added<br><br>The sql error returned was:<br>" . $db->ErrorMsg());
				}

			}
			else if ($_REQUEST['deleteimage'] != "") {
				// we're deleting an image
				$image_id = $_REQUEST['deleteimage'];

				// get info on file so we can delete it
				$sql = "SELECT * FROM petsplease_competition_images WHERE image_id = ?";
				$result = $db->GetRow($sql, array($image_id));

				$siteRoot = $_SERVER['DOCUMENT_ROOT'];
				$image_path = $result['image_url'];
				unlink($siteRoot . $image_path);

				// now delete from db
				$sql = "DELETE FROM petsplease_competition_images WHERE image_id = ?";
				$db->Execute($sql, array($image_id));

				if (!$db->ErrorMsg()) {
					geoAdmin::getInstance()->message("Image successfully deleted");
				}
				else {
					geoAdmin::getInstance()->message("Image could not be deleted<br><br>The error returned was:<br>" . $db->ErrorMsg());
				}
			}
		}
		else {
			if ($vars['id']) {
				if ($_REQUEST['dodelete'] == true) {
					$sql = "DELETE FROM petsplease_competition_breed WHERE id = ?";
					$db->Execute($sql, array($vars['id']));

					if (!$db->ErrorMsg()) {
						geoAdmin::getInstance()->message("Breed (id:".$vars['id'].") successfully deleted");
					}
					else {
						geoAdmin::getInstance()->message("Breed (id:".$vars['id'].") could not be deleted<br><br>The error returned was:<br>" . $db->ErrorMsg());
					}
				}
				else {
					// Update existing row
					$sets = array_map(function($col) use ($vars)  {
						return "$col = '" . addslashes($vars[$col]) . "'";
					}, $cols);

					$sql = "UPDATE petsplease_competition_breed SET " . implode(",", $sets) . " WHERE id = " . $vars['id'];
					$db->Execute($sql);

					if (!$db->ErrorMsg()) {
						geoAdmin::getInstance()->message("Breed (id:".$vars['id'].") successfully updated");
					}
					else {
						geoAdmin::getInstance()->message("Breed (id:".$vars['id'].") could not be updated<br><br>The error returned was:<br>" . $db->ErrorMsg());
					}
				}
			}
			else {				
                
                //upload image
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
                        $full_name = $_REQUEST['d']['name'] . "-$now-large.jpg";
                        $thumb_name = $_REQUEST['d']['name'] . "-$now-thumb.jpg";
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
                // Insert new row
                $sql = "INSERT INTO petsplease_competition (week, thumb_url, full_url, petname, sender_name, current) VALUES ";

				$sql .= "('" . $_REQUEST['d']['week'] . "', '" . $thumb_url . "', '" . $full_url . "'"; 
				$sql .= ", '" . $_REQUEST['d']['name'] . "', '" . $_REQUEST['submitter'] . "', " . $current . ")";

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
}