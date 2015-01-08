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
        $db -> Execute("set names 'utf8'");;
        
       
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

    public function update_addon_testimonials_settings() {
        
        $db = true;
        include (GEO_BASE_DIR . 'get_common_vars.php');
        $vars = $_REQUEST['d'];
        if ($vars['id']) {
            if ($_REQUEST['dodelete'] == true) {
                $sql = "DELETE FROM petsplease_testimonials WHERE id = ?";
                $db->Execute($sql, array($vars['id']));

                if (!$db->ErrorMsg()) {
                    geoAdmin::getInstance()->message("Testimonial (id:".$vars['id'].") successfully deleted");
                }
                else {
                    geoAdmin::getInstance()->message("Testimonial (id:".$vars['id'].") could not be deleted<br><br>The error returned was:<br>" . $db->ErrorMsg());
                }
            }
            else {

                $sql = "UPDATE petsplease_testimonials  SET description = " . $_REQUEST['d']['description'] . ", from_name = " . $_REQUEST['d']['from'] . ", title = " . $_REQUEST['d']['title'] . " WHERE id = " . $vars['id'];
                $db->Execute($sql);

                if (!$db->ErrorMsg()) {
                    geoAdmin::getInstance()->message("Testimonial (id:".$vars['id'].") successfully updated");
                }
                else {
                    geoAdmin::getInstance()->message("Testimonial (id:".$vars['id'].") could not be updated<br><br>The error returned was:<br>" . $db->ErrorMsg());
                }
            }
        }
        else {
            // Insert new row
            $sql = "INSERT INTO petsplease_testimonials (description, from_name, title) VALUES ('" . mysql_real_escape_string($_REQUEST['d']['description']) . "', '". $_REQUEST['d']['from']."', '".$_REQUEST['d']['title']."')";
            echo $sql;
            $db -> Execute($sql);

            if (!$db -> ErrorMsg()) {
                geoAdmin::getInstance() -> message("New Testimonial was successfully inserted");
            } else {
                geoAdmin::getInstance() -> message("New Testimonial could not be inserted<br><br>The error returned was:<br>" . $sql);
            }
        }

    }

}


