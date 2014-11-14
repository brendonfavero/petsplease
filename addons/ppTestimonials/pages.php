<?php
class addon_ppTestimonials_pages extends addon_ppTestimonials_info
{
	public function detail() {
		$db = true;
		include (GEO_BASE_DIR.'get_common_vars.php');
		$db->Execute("set names 'utf8'"); 

		$view = geoView::getInstance();

		$breedID = $_REQUEST['id'];

		// Get nav info
		$pettypes = array("1" => "Dog", "2" => "Cat", "3" => "Bird");
		$view->setBodyVar("pettypes", $pettypes);

		$sql = "SELECT id, pettype_id, breed FROM petsplease_testimonials_breed ORDER BY pettype_id, breed";
		$nav = $db->GetAll($sql);
		$view->setBodyVar('nav', $nav);

		if ($breedID) {
			// Get detailed info
			$sql = "SELECT * FROM petsplease_testimonials_breed WHERE id = ?";
			$detail = $db->GetRow($sql, array($breedID));
			$view->setBodyVar('detail', $detail);

			$sql = "SELECT * FROM petsplease_testimonials_images WHERE breed_id = ?";
			$images = $db->GetAll($sql, array($breedID));
			$view->setBodyVar('images', $images);
		}

		$view->setBodyTpl('detail.tpl', $this->name);
	}

      public function imageUploader() {
    /* I put this code here (pull from admin.php) to allow my python script to mass upload files for the pet selector */
        $db = true;
        include (GEO_BASE_DIR.'get_common_vars.php');

        $vars = $_REQUEST['d'];

        if (@is_uploaded_file($_FILES['imagefile']['tmp_name'])) {
            if ($_FILES['imagefile']['error']) {
                echo "ERROR: PHP upload error (id:" . $_FILES['imagefile']['error'] . ")";
                return;
            }

            if (!@getimagesize($_FILES["imagefile"]['tmp_name'])) {
                echo "ERROR: File is invalid image";
                return;
            }

              // If you find that this section errors out without explanation it is probably
               // due to the file being sent being too big. We should try put a useful
               // error in here but not exactly sure how to go about that

            $siteRoot = $_SERVER['DOCUMENT_ROOT'];
            $uploads_url =  '/addons/ppTestimonials/images/';
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
                echo "ERROR: Could not move file to addon images folder. Please check directory permissions";
                return;
            }

            $sql = "INSERT INTO petsplease_testimonials_images (breed_id, image_url, full_filename) VALUES (?, ?, ?)";
            $db->Execute($sql, array($vars['id'], $file_url, $file_name));

            if (!$db->ErrorMsg()) {
                echo "Image successfully uploaded";
            }
            else {
                echo "Image could not be added<br><br>The sql error returned was:<br>" . $db->ErrorMsg();
            }
        }
        else {
            echo "No image supplied";
        }


        $view = geoView::getInstance();
        $view->setRendered(true);
      }

    public function testimonials() {
        $db = true;
        include (GEO_BASE_DIR.'get_common_vars.php');
        $db->Execute("set names 'utf8'"); 

        $view = geoView::getInstance();
        
        $sql = "SELECT * FROM petsplease_testimonials";
        $result = $db -> GetAll($sql);

        $view -> setBodyVar('testimonials', $result);

        $view->setBodyTpl('testimonials.tpl', $this->name);
    }
    
    public function testimonialsform() {
        $db = true;
        include (GEO_BASE_DIR.'get_common_vars.php');
        $db->Execute("set names 'utf8'"); 

        $view = geoView::getInstance();

        $view->setBodyTpl('testimonialsform.tpl', $this->name);
    }
}
