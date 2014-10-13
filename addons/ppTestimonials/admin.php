<?php
class addon_ppTestimonials_admin extends addon_ppTestimonials_info {
    public function init_pages($menuName) {
        menu_page::addonAddPage('addon_testimonials_settings', '', 'Add Testimonial', 'ppTestimonials', '');
    }

    public function display_addon_testimonials_settings() {
        $db = true;
        include (GEO_BASE_DIR . 'get_common_vars.php');
        $view = geoView::getInstance();
        $view -> setBodyVar('messages', geoAdmin::getInstance() -> message());

        $breedId = $_REQUEST['edit_id'];

        $db -> Execute("set names 'utf8'");

        if ($_REQUEST['action'] == "images") {
            // Get info about breed
            $sql = "SELECT * FROM petsplease_testimonials WHERE id = ?";
            $result = $db -> GetRow($sql, array($breedId));
            $view -> setBodyVar('detail', $result);

            // Get existing images
            $sql = "SELECT * FROM petsplease_testimonials WHERE breed_id = ?";
            $result = $db -> GetAll($sql, array($breedId));
            $view -> setBodyVar('images', $result);

            $view -> setBodyTpl('admin/images.tpl', $this -> name);
        } else {
            if ($breedId) {
                if ($breedId != "new") {
                    $sql = "SELECT * FROM petsplease_testimonials WHERE id = ?";
                    $result = $db -> GetRow($sql, array($breedId));
                    $view -> setBodyVar('detail', $result);
                    $pettype = $result['pettype_id'];
                }
                $view -> setBodyTpl('admin/changedetail.tpl', $this -> name);

            } else {
                $sql = "SELECT * FROM petsplease_testimonials";
                $result = $db -> GetAll($sql);

                $view -> setBodyVar('testimonials', $result);
                $view -> setBodyTpl('admin/testimoniallist.tpl', $this -> name);
            }
        }
    }

    public function update_addon_testimonials_settings() {
        $db = true;
        include (GEO_BASE_DIR . 'get_common_vars.php');

        $vars = $_REQUEST['d'];

            // Insert new row
            $sql = "INSERT INTO petsplease_testimonials (description) VALUES '" .$_REQUEST['d']['description'] . "'";

            $db -> Execute($sql);

            if (!$db -> ErrorMsg()) {
                geoAdmin::getInstance() -> message("New Testimonial was successfully inserted");
            } else {
                geoAdmin::getInstance() -> message("New Testimonial could not be inserted<br><br>The error returned was:<br>" . $db -> ErrorMsg());
            }

    }

}
