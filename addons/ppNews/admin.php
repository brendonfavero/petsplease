<?php

//ampse

/**
 * @package ppNews
 */
class addon_ppNews_admin
{
	
	public function init_pages ()
	{		
		menu_page::addonAddPage('addon_ppNews_news','','Manage','ppNews');				
	}
	

	var $default_addon_text = array (
	// TEXT FOR STORE LISTING PAGE
		'px_form' => array (
			'name' => 'Additional Text for PX Form',
			'desc' => '',
			'type' => 'textarea',
			'default' => ''
		)
	);

	/*function init_text($language_id) 
	{
		//Rename the function to remove _no_use if we need to start using addon text.
		//TODO: Need to make all "built in" text use addon text instead.
		
		return $this->default_addon_text;
		
	}*/
	
	
	public function display_addon_ppNews_news() {
		$targetDir = dirname( __FILE__ ) . '/pdfs/';

		require_once( dirname(__FILE__) . '/lib/news/Article.php' );
		require_once( dirname(__FILE__) . '/lib/news/Category.php' );
		$db = DataAccess::getInstance();
		$category = new Category($db);
		$article = new Article($db);
		$article->admin = true; // requred to get non-published content
		if( isset($_REQUEST['CKEditorFuncNum']) ) {
			echo $this->uploadImage();
			exit;
    }

    //handle file uploads
    if ( isset( $_REQUEST['fileUpload'] ) ) {
      $json = $this->uploadFile( $targetDir );
      $fileResult = json_decode( $json, true );
      $result = false;
      if ( isset( $fileResult['filename'] ) ) {
        $filename = $fileResult['filename'];
        // add filename to article
        $result = $article->addFile( $filename, $_REQUEST['id'] );
      }else {
        $result = "no filename?";
      }
      echo json_encode( array( 'result' => $result, 'file' => $fileResult,  'messages' => $category->messages ) );
      exit;
    }

    //handle file removal
    if ( isset( $_REQUEST['removeFile'] ) && strlen( $_REQUEST['removeFile'] ) > 0 ) {
      $filename = $_REQUEST['removeFile'];
      unlink( $targetDir . str_replace( '..', '', $filename ) );
      $result = $article->removeFile( $filename, $_REQUEST['id'] );
      echo json_encode( array( 'result' => $result, 'messages' => $category->messages ) );
      exit;
    }
		// ajax only
		if( isset($_POST['category_action']) ) {
			$result = $category->run( $_POST['category_action'], $_POST );
			echo json_encode( array('result' => $result, 'messages' => $category->messages ) );
			exit;
		}
		if( isset($_POST['article_action']) ) {
			$result = $article->run( $_POST['article_action'], $_POST );
			echo json_encode( array('result' => $result, 'messages' => $article->messages ) );
			exit;
		}
		if( isset($_REQUEST['megaUpdate']) ) {
			$result = $db->Execute("SELECT * FROM `petsplease_news` WHERE `id` > 63");
			if( $result && $result->RecordCount() > 0 ) {
				while( $row = $result->FetchRow() ) {
					echo $article->run('save', $row);
				}
			}else {
				echo "error in query";
			}
			exit;
		}




		// first run stuff
		$vars = array( 'pathAddon' => '/addons/ppNews', 'url' => $_SERVER['REQUEST_URI']); // returned template vars
		$vars['categories'] = $category->getAll();

		geoView::getInstance()->setBodyTpl('newsAdmin.tpl','ppNews');
		geoView::getInstance()->setBodyVar($vars);
	}
 
 function uploadFile( $targetDir ) {
    /*
    * * Copyright 2009, Moxiecode Systems AB
    * Released under GPL License.
    *
    * License: http://www.plupload.com/license
    * Contributing: http://www.plupload.com/contributing
    */

    // HTTP headers for no cache etc
   
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    // Settings
   
    // 5 minutes execution time
    @set_time_limit(5 * 60);

    // Uncomment this one to fake upload time
    // usleep(5000);

    // Get parameters
    $chunk = isset($_REQUEST["chunk"]) ? $_REQUEST["chunk"] : 0;
    $chunks = isset($_REQUEST["chunks"]) ? $_REQUEST["chunks"] : 0;
    $fileName = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

    // Clean the fileName for security reasons
    $fileName = preg_replace('/[^\w\._]+/', '', $fileName);

    // Make sure the fileName is unique but only if chunking is disabled
    if ($chunks < 2 && file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName)) {
      $ext = strrpos($fileName, '.');
      $fileName_a = substr($fileName, 0, $ext);
      $fileName_b = substr($fileName, $ext);

      $count = 1;
      while (file_exists($targetDir . DIRECTORY_SEPARATOR . $fileName_a . '_' . $count . $fileName_b))
        $count++;

      $fileName = $fileName_a . '_' . $count . $fileName_b;
    }

    // Create target dir
    if (!file_exists($targetDir))
      @mkdir($targetDir);

    // Look for the content type header
    if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
      $contentType = $_SERVER["HTTP_CONTENT_TYPE"];

    if (isset($_SERVER["CONTENT_TYPE"]))
      $contentType = $_SERVER["CONTENT_TYPE"];

    // Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
    if (strpos($contentType, "multipart") !== false) {
      if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
        // Open temp file
        $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
        if ($out) {
          // Read binary input stream and append it to temp file
          $in = fopen($_FILES['file']['tmp_name'], "rb");

          if ($in) {
            while ($buff = fread($in, 4096))
              fwrite($out, $buff);
          } else
            return '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}';
          fclose($in);
          fclose($out);
          @unlink($_FILES['file']['tmp_name']);
        } else
          return '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}';
      } else
        return '{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}';
    } else {
      // Open temp file
      $out = fopen($targetDir . DIRECTORY_SEPARATOR . $fileName, $chunk == 0 ? "wb" : "ab");
      if ($out) {
        // Read binary input stream and append it to temp file
        $in = fopen("php://input", "rb");

        if ($in) {
          while ($buff = fread($in, 4096))
            fwrite($out, $buff);
        } else
          return '{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}';

        fclose($in);
        fclose($out);
      } else
        return '{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}';
    }

    // Return JSON-RPC response
    return '{"jsonrpc" : "2.0", "result" : null, "id" : "id", "filename" : "' . $fileName. '"}';

  }

	function uploadImage() {
		// i just copied this script off the web to save time - it's hideous i know
		 ini_set("memory_limit", "200000000"); // for large images so that we do not get "Allowed memory exhausted"
		// file needs to be jpg,gif,bmp,x-png and 4 MB max
		if (($_FILES["upload"]["type"] == "image/jpeg" || $_FILES["upload"]["type"] == "image/pjpeg" || $_FILES["upload"]["type"] == "image/gif" || $_FILES["upload"]["type"] == "image/x-png" || $_FILES["upload"]["type"] == "image/png") && ($_FILES["upload"]["size"] < 4000000))
		{

			// some settings
			$max_upload_width = 385;
			$max_upload_height = 500;

			$fs_upload_width = 700;
			$fs_upload_height = 900;

			$thumb_upload_width = 150;
			$thumb_upload_height = 150;


			// if uploaded image was JPG/JPEG
			if($_FILES["upload"]["type"] == "image/jpeg" || $_FILES["upload"]["type"] == "image/pjpeg"){
				$image_source = imagecreatefromjpeg($_FILES["upload"]["tmp_name"]);
				$thumb_image_source = imagecreatefromjpeg($_FILES["upload"]["tmp_name"]);
				$fs_image_source = imagecreatefromjpeg($_FILES["upload"]["tmp_name"]);
			}
			// if uploaded image was GIF
			if($_FILES["upload"]["type"] == "image/gif"){
				$image_source = imagecreatefromgif($_FILES["upload"]["tmp_name"]);
				$thumb_image_source = imagecreatefromgif($_FILES["upload"]["tmp_name"]);
				$fs_image_source = imagecreatefromgif($_FILES["upload"]["tmp_name"]);

			}
			// BMP doesn't seem to be supported so remove it form above image type test (reject bmps)
			// if uploaded image was BMP
			if($_FILES["upload"]["type"] == "image/bmp"){
				$image_source = imagecreatefromwbmp($_FILES["upload"]["tmp_name"]);
				$thumb_image_source = imagecreatefromwbmp($_FILES["upload"]["tmp_name"]);
				$fs_image_source = imagecreatefromwbmp($_FILES["upload"]["tmp_name"]);
			}
			// if uploaded image was PNG
			if($_FILES["upload"]["type"] == "image/x-png" || $_FILES["upload"]["type"] == "image/png"){
				$image_source = imagecreatefrompng($_FILES["upload"]["tmp_name"]);
				$thumb_image_source = imagecreatefrompng($_FILES["upload"]["tmp_name"]);
				$fs_image_source = imagecreatefrompng($_FILES["upload"]["tmp_name"]);
			}

			$newFilename = $this->filename_generator($_FILES['upload']['name']);
			$thumbFilename = $this->filename_generator($newFilename, "tn", 0);
			$fsFilename = $this->filename_generator($newFilename, "fs", 0);
			$remote_file = dirname(__FILE__).'/newsImages/' . $newFilename;
			$thumb_remote_file = dirname(__FILE__).'/newsImages/' . $thumbFilename;
			$fs_remote_file = dirname(__FILE__).'/newsImages/' . $fsFilename;

			imagejpeg($image_source,$remote_file,100);
			imagejpeg($thumb_image_source,$thumb_remote_file,100);
			imagejpeg($fs_image_source,$fs_remote_file,100);
			chmod($remote_file,0644);
			chmod($thumb_remote_file,0644);
			chmod( $fs_remote_file, 0644 );



			// get width and height of original image
			list($image_width, $image_height) = getimagesize($remote_file);

			if($image_width>$max_upload_width || $image_height >$max_upload_height){
				$proportions = $image_width/$image_height;

				if($image_width>$image_height){
					$new_width = $max_upload_width;
					$new_height = round($max_upload_width/$proportions);
				}
				else{
					$new_height = $max_upload_height;
					$new_width = round($max_upload_height*$proportions);
				}


				$new_image = imagecreatetruecolor($new_width , $new_height);
				$image_source = imagecreatefromjpeg($remote_file);

				imagecopyresampled($new_image, $image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
				imagejpeg($new_image,$remote_file,100);

				imagedestroy($new_image);
			}



			// make thumb
			if($image_width>$thumb_upload_width || $image_height >$thumb_upload_height){
				$proportions = $image_width/$image_height;

				if($image_width>$image_height){
					$new_width = $thumb_upload_width;
					$new_height = round($thumb_upload_width/$proportions);
				}
				else{
					$new_height = $thumb_upload_height;
					$new_width = round($thumb_upload_height*$proportions);
				}


				$thumb_new_image = imagecreatetruecolor($new_width , $new_height);
				$thumb_image_source = imagecreatefromjpeg($thumb_remote_file);

				imagecopyresampled($thumb_new_image, $thumb_image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
				imagejpeg($thumb_new_image,$thumb_remote_file,100);

				imagedestroy($thumb_new_image);
			}

			// handle fs
			if($image_width>$fs_upload_width || $image_height >$fs_upload_height){
				$proportions = $image_width/$image_height;

				if($image_width>$image_height){
					$new_width = $fs_upload_width;
					$new_height = round($fs_upload_width/$proportions);
				}
				else{
					$new_height = $fs_upload_height;
					$new_width = round($fs_upload_height*$proportions);
				}
			}else {
				$new_height = $image_height;
				$new_width = $image_width;
			}


				$fs_new_image = imagecreatetruecolor($new_width , $new_height);
				$fs_image_source = imagecreatefromjpeg($fs_remote_file);

				imagecopyresampled($fs_new_image, $fs_image_source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);
				imagejpeg($fs_new_image,$fs_remote_file,100);

				imagedestroy($fs_new_image);


			imagedestroy($image_source);
			imagedestroy($thumb_image_source);
			imagedestroy($fs_image_source);


			return '<html><body><script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$_REQUEST['CKEditorFuncNum'].', "/addons/ppNews/newsImages/'.$newFilename.'","Successful upload.");</script></body></html>';
		}
		else{
			return false;
		}
	}

function filename_generator($filename, $prefix = "", $length = -1) {
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";
		srand((double)microtime()*1000000);
		$i = 0;
		$rand = '' ;
		// IF $length isn't specified, pick a number between 4 and 8.
		if($length == -1 ) {
			$length = rand(4,8);
		}

		// Build random string $rand
		while ($i <= ($length - 1 )) {
			$num = rand() % 33;
			$tmp = substr($chars, $num, 1);
			$rand = $rand . $tmp;
			$i++;
		}

		// Explode extension and add prefix and random string then return imploded result
		$filenameB = explode(".", $filename);
		if($prefix != "") { $prefix = "_" . $prefix;}
		$filenameB[count($filenameB)-2] .= $prefix . (($rand == "") ? "" : "_") . $rand;

		return implode(".", $filenameB );
	}
	
	

}
