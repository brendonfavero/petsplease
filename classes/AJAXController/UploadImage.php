<?php
/**************************************************************************
Geodesic Classifieds & Auctions Platform 7.1
Copyright (c) 2001-2013 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    7.1.2-136-g3163357
## 
##################################

if( class_exists( 'classes_AJAX' ) or die());

class CLASSES_AJAXController_UploadImage extends classes_AJAX {	
	public $messages = null;
	
	public function __construct()
	{
		$db = DataAccess::getInstance();
		$this->messages = $db->get_text(true, 10);
	}
	
	public function deleteImage ()
	{
		$this->jsonHeader();
		
		$imageSlot = (int)$_POST['imageSlot'];
		if (!$imageSlot) {
			//invalid image slot (0), could not delete
			return $this->_returnError($this->messages[500681]);
		}
		
		$adminId = (int)$_POST['adminId'];
		if ($adminId) {
			define('IN_ADMIN',1);
			$_COOKIE['classified_session'] = $_COOKIE['admin_classified_session'];
		}
		
		$session = geoSession::getInstance();
		$session->initSession();
		
		$cart = geoCart::getInstance();
		$userId = ($adminId)? (int)$_POST['userId'] : null;
		$cart->init(true, $userId);
		
		if (!$this->_validateCartStep()) {
			//invalid it seems?
			return;
		}
		
		//data to be returned
		$data = array();
		
		$max = $data['maxSlots'] = imagesOrderItem::getMaxImages();
		
		$imagesCaptured = imagesOrderItem::getImagesCaptured('cart',true);
		if (!isset($imagesCaptured[$imageSlot])) {
			return $this->_returnError($this->messages[500681]);
		}
		//delete the image
		$removeResult = imagesOrderItem::removeImage($imagesCaptured[$imageSlot]['id'], $imageSlot);
		if (!$removeResult) {
			//problem removing image
			return $this->_returnError($this->messages[500682]);
		}
		//re-get the images captured
		$imagesCaptured = imagesOrderItem::getImagesCaptured('cart',true);
		
		//remove all the empty slots (mainly to push everything over where the old
		//image used to be, but also to remove any that somehow got in there before
		$newCaptured = array();
		$displayOrder = 1;
		foreach ($imagesCaptured as $captured) {
			$newCaptured[$displayOrder] = $captured;
			$displayOrder++;
		}
		
		//TODO: if no more images, and not in listing edit, remove the image
		//order item.
		
		//save the new order
		//apply changes to order item
		imagesOrderItem::setImagesCaptured($newCaptured);
		//note: don't put in the image slot # if text is blank, as blank text
		//indicates no message to be displayed.
		$data['msg'] = ($this->messages[500683])? $this->messages[500683].$imageSlot: '';
		
		return $this->_returnNewImages($data, $newCaptured);
	}
	
	public function sortImages ()
	{
		//set the header to signify this is returning json
		$this->jsonHeader();
		
		$adminId = (int)$_POST['adminId'];
		if ($adminId) {
			define('IN_ADMIN',1);
			$_COOKIE['classified_session'] = $_COOKIE['admin_classified_session'];
		}
		
		//init the session, this one is a normal ajax call so don't need to do
		//fancy stuff
		$session = geoSession::getInstance();
		$session->initSession();
		
		$cart = geoCart::getInstance();
		//start up the cart
		$userId = ($adminId)? (int)$_POST['userId'] : null;
		$cart->init(true, $userId);
		if (!$this->_validateCartStep()) {
			//invalid it seems?
			return;
		}
		//parse the order into an array
		parse_str($_POST['imageSlots'], $inputData);
		
		$slots = $inputData['imagesCapturedBox'];
		if (!is_array($slots) || !$slots) {
			//no order returned...
			return $this->_returnError($this->messages[500684]);
		}
		
		//data to be returned
		$data = array();
		
		$max = $data['maxSlots'] = imagesOrderItem::getMaxImages();
		
		//Unlike other methods, this one does all the actual work instead of
		//passing it off to the image order item to do.
		
		$imagesCaptured = imagesOrderItem::getImagesCaptured('cart',true);
		
		$newCaptured = array();
		
		$applyChanges = true;
		//it's going to be an array like array (0 => 2, 1 => 3, 2 => 1, 3 => 4)
		//so it's our job to re-sort them, the value is the "old" display
		//order, the key is the "new" display order - 1.
		foreach ($slots as $key => $existingOrder) {
			//First, get the new order by adding 1 to the key
			$newOrder = $key + 1;
			
			if (!isset($imagesCaptured[$existingOrder])) {
				//this one doesn't exist?  skip it..
				continue;
			}
			$newCaptured[$newOrder] = $imagesCaptured[$existingOrder];
		}
		
		//before we apply the new order, make sure all the old imagesCaptured are
		//found in the new one
		foreach ($imagesCaptured as $key => $oldSlot) {
			$matching = false;
			foreach ($newCaptured as $newSlot) {
				if ($oldSlot == $newSlot) {
					//this one is found
					$matching = true;
					break;
				}
			}
			if (!$matching) {
				//found one not found in new array!  Do NOT proceed!
				$applyChanges = false;
				break;
			}
		}
		if (!$applyChanges) {
			//something went wrong
			
			return $this->_returnError($this->messages[500685]);
		}
		
		//apply changes to order item
		imagesOrderItem::setImagesCaptured($newCaptured);
		$data['msg'] = $this->messages[500686];
		
		return $this->_returnNewImages ($data, $newCaptured);
	}
	
	public function uploadImage ($data)
	{
		$isSwf = false;
		
		$adminId = (int)$_POST['adminId'];
		if ($adminId) {
			define('IN_ADMIN',1);
		}
		
		//do some moving around of stuff, so all is in places that are expected.
		if (isset($_POST['classified_session'], $_POST['user_agent'])) {
			//set cookie for session and user agent from post vars.
			//cookies aren't sent correctly by swfupload (due to IE cookie bug
			//in flash).
			$_COOKIE['classified_session'] = $_POST['classified_session'];
			
			//manually set user agent, so session handling works since flash
			//will send something different than normal page load.
			$_SERVER['HTTP_USER_AGENT'] = $_POST['user_agent'];
			//remember that this is swf so we know to not pass back extended results
			$isSwf = true;
		} else {
			$this->jsonHeader();
			if ($adminId) {
				$_COOKIE['classified_session'] = $_COOKIE['admin_classified_session'];
			}
		}
		
		//massage things a bit to get them in the right places...
		
		//first, set up the session
		$session = geoSession::getInstance();
		$session->initSession();
		
		$cart = geoCart::getInstance();
		//start up the cart
		$userId = ($adminId)? (int)$_POST['userId'] : null;
		$cart->init(true, $userId);
		
		if (!$this->_validateCartStep()) {
			//invalid it seems?
			return;
		}
		
		//data to be returned
		$data = array();
		
		$max = imagesOrderItem::getMaxImages();
		
		$doNormalProcessing = true;
		
		if (isset($_POST['editImage'], $_POST['editImageSlot']) && $_POST['editImage']) {
			//editing an existing image...
			$imagesCaptured = imagesOrderItem::getImagesCaptured();
			
			
			$slotNum = $_POST['uploadSlot'] = (int)$_POST['editImageSlot'];
			
			if (!$slotNum || !isset($imagesCaptured[$slotNum])) {
				//Oops! slot number not correct!
				return $this->_returnError($this->messages[500687]);
			}
			if (isset($_FILES) && count($_FILES) > 0) {
				//replacing the image...  First delete the existing one
				$removeResult = imagesOrderItem::removeImage($imagesCaptured[$slotNum]['id'], $slotNum);
				if (!$removeResult) {
					//error removing old image
					return $this->_returnError($this->messages[500688]);
				}
				//now let it fall down to where it does things as if a new image was being uploaded
			} else {
				//just changing the title...
				$this->jsonHeader();//expects to be json headers, as this was sent with Ajax call, not flash call
				
				$title = (isset($_POST['imageTitle']))? trim($_POST['imageTitle']): '';
				$title = $cart->site->check_for_badwords($title);
				$cart->site->get_ad_configuration();
				$title = geoImage::shortenImageTitle($title, $cart->site->ad_configuration_data->MAXIMUM_IMAGE_DESCRIPTION);
				$imgId = (int)$imagesCaptured[$slotNum]['id'];
				
				if (!$imgId) {
					//Oops!  Couldn't find the image ID for that image slot...
					return $this->_returnError($this->messages[500689]);
				}
				//update the image, be sure to set the listing ID to 0 so image is not used
				//until it is approved.
				$sql = "UPDATE ".geoTables::images_urls_table." SET `classified_id` = 0, `image_text` = ? WHERE `image_id` = $imgId";
				$cart->db->Execute($sql, array($title));
				$doNormalProcessing = false;
			}
		}
		if ($doNormalProcessing) {
			//Manually call checkVars and process on main images order item,
			//let it do all the work of processing the image.  Since we are
			//processing here and not through the cart, the step will not be
			//incremented before we're finished.
			
			geoOrderItem::callUpdate('mediaCheckVars',null,'images');
			if (!$cart->errors) {
				geoOrderItem::callUpdate('mediaProcess',null,'images');
			}
			
			if ($cart->errors > 0) {
				//oops! return error
				$msg = $cart->getErrorMsg('images');
				if (!$msg) {
					$msg = 'Unknown problem processing image.';
				}
				return $this->_returnError($msg);
			}
		}
		
		if ($isSwf) {
			$data['uploadSlot'] = intval($_POST['uploadSlot']);
			$data['imagesDisplay'] = 'get';
			$data['maxSlots'] = $max;
			$data['editImage'] = (isset($_POST['editImage']))? $_POST['editImage'] : 0;
			//figure out the next image slot
			$imagesCaptured = imagesOrderItem::getImagesCaptured('cart',true);
			for ($i = 1; $i <= $max; $i++) {
				if (!isset($imagesCaptured[$i])) {
					$data['nextUploadSlot'] = $i;
					break;
				}
			}
			//$data['imagesDisplay'] .=  "Images: <pre>".print_r($imagesCaptured,1)."</pre><br />image title: {$_POST['imageTitle']}";
			//changes applied : new file upload successful
			include GEO_BASE_DIR . 'app_bottom.php';
			
			echo $this->encodeJSON($data);
		} else {
			$this->imageSlotContents(true);
		}
	}
	
	public function imageSlotContents ($skipInit = false)
	{
		//set the header to signify this is returning json
		$this->jsonHeader();
		//first, set up the session
		
		$adminId = (int)$_POST['adminId'];
		if ($adminId) {
			define('IN_ADMIN',1);
			$_COOKIE['classified_session'] = $_COOKIE['admin_classified_session'];
		}
		
		$session = geoSession::getInstance();
		if ($skipInit !== true) $session->initSession();
		
		$cart = geoCart::getInstance();
		//start up the cart
		$userId = ($adminId)? (int)$_POST['userId'] : null;
		if ($skipInit !== true) $cart->init(true, $userId);
		
		if ($skipInit !== true && !$this->_validateCartStep()) {
			//invalid it seems?
			return;
		}
		
		//data to be returned
		$data = array();
		
		$max = imagesOrderItem::getMaxImages();
		
		$imagesCaptured = imagesOrderItem::getImagesCaptured('cart',true);
		
		//we're only giving them the latest image added
		$addSlot = $data['uploadSlot'] = intval($_POST['uploadSlot']);
		$latestCaptured = (isset($imagesCaptured[$addSlot]))? array ($addSlot=> $imagesCaptured[$addSlot]) : array();
		if ($latestCaptured) {
			//force to be 95 x 95 or it won't fit right into the windows
			$maxW = 95;//$cart->site->ad_configuration_data->MAXIMUM_IMAGE_WIDTH;
			$maxH = 95;//$cart->site->ad_configuration_data->MAXIMUM_IMAGE_HEIGHT;
			
			$imageData = imagesOrderItem::getImgsData($latestCaptured, $maxW, $maxH);
			
			$cost = false;
			
			if (geoMaster::is('site_fees')) {
				$imageInfo = imagesOrderItem::getImageData();
				if ($imageInfo['cost_per_image'] > 0) {
					//show price
					if ($addSlot <= $imageInfo['number_free_images']) {
						$cost = $cart->site->messages[500679];
					} else {
						$cost = geoString::displayPrice($imageInfo['cost_per_image']);
					}
				}
			}
			$tpl_vars = $cart->getCommonTemplateVars();
			$tpl_vars['slotData'] = array (
				'image' => $imageData[$addSlot],
				'cost' => $cost
			);
			$tpl_vars['position'] = $addSlot;
			$tpl_vars['process_form_url'] = $cart->getProcessFormUrl();
			$tpl = new geoTemplate('system','order_items');
			$tpl->assign($tpl_vars);
			$data['imagesDisplay'] = $tpl->fetch('images/image_box.tpl');
		} else {
			$data['imagesDisplay'] = false;
		}
		
		$data['nextUploadSlot'] = 0;
		
		//figure out the next image slot
		for ($i = 1; $i <= $max; $i++) {
			if (!isset($imagesCaptured[$i])) {
				$data['nextUploadSlot'] = $i;
				break;
			}
		}
		$data['maxSlots'] = $max;
		include GEO_BASE_DIR . 'app_bottom.php';
		
		//$data['imagesDisplay'] .=  "Images: <pre>".print_r($imagesCaptured,1)."</pre><br />image title: {$_POST['imageTitle']}";
		//changes applied : new file upload successful
		$data['msg'] = (isset($_POST['editImage']) && $_POST['editImage'])? $this->messages[500690]: $this->messages[500691];
		
		echo $this->encodeJSON($data);
	}
	
	/**
	 * Run AFTER cart->init() has been already run.  This checks to make sure user
	 * is currently in the middle of editing or placing something new and that they
	 * are on the images step.
	 * @return unknown_type
	 */
	private function _validateCartStep()
	{
		//simulate server error, un-comment line below
		//return;
		
		$cart = geoCart::getInstance();
		
		$step = $cart->cart_variables['step'];
		$userId = (int)$_POST['userId'];
		$adminId = (int)$_POST['adminId'];
		
		$session = geoSession::getInstance();
		
		$sessionUser = $session->getUserId();
		
		$checkUser = ($adminId)? $adminId : $userId;
		
		if ($checkUser && !$sessionUser) {
			//user was logged in, now logged out
			
			return $this->_returnError($this->messages[500692],'errorSession');
		}
		if ($sessionUser != $checkUser) {
			//user different than when started?
			
			return $this->_returnError($this->messages[500693],'errorSession');
		}
		
		//check to make sure there is an item in there
		if (!$cart->item) {
			//oops, no item in cart, can't go forward.  Not on images step error msg
			return $this->_returnError($this->messages[500694],'errorSession');
		}
		
		//make sure the step is not one of the built in ones
		if ($step !== 'combined' && strpos($step, ':') === false) {
			//They are on a built-in step, not image upload.  Not on images step error msg
			return $this->_returnError($this->messages[500694],'errorSession');
		}
		
		//make sure the order items that are OK to be attached to
		$validItems = geoOrderItem::getParentTypesFor('images');
		$validItems[] = 'images'; //images would be the item if they clicked on edit button in cart.
		
		if (!in_array($cart->item->getType(), $validItems)) {
			//oops! this isn't a valid order item...  Not on images step error msg
			return $this->_returnError($this->messages[500694],'errorSession');
		}
		
		//got this far, they should be on images step...
		return true;
	}
	
	/**
	 * Internal method to easily "throw an error", it even returns false so you
	 * can return the method call if you need to return false anyways.  Note that
	 * this calls app bottom, so you should be finished with any cleanup before
	 * calling this.
	 * 
	 * @param string $errorMsg Error message to display to user, if blank it will
	 *  display "err txt" so don't leave it blank.
	 * @param string $errField by default it is "error", but can pass in "errorSession"
	 *  to make it throw a session related error.  This gets interpreted as the
	 *  key to the error message so js needs to know what to do with it (it
	 *  is built to handle "error" and "errorSession" automatically as errors)
	 * 
	 * @return bool Always returns false.
	 */
	private function _returnError($errorMsg, $errField = 'error') {
		if (!strlen($errorMsg)) {
			//make sure message has something in it, if this happens then admin
			//has blanked out the text message.
			$errorMsg = 'err txt';
		}
		$data = array ($errField => $errorMsg);
		include GEO_BASE_DIR . 'app_bottom.php';
		//echo "keys: ".print_r($_FILES,1)."\n";
		//$data['imagesDisplay'] .=  "Images: <pre>".print_r($imagesCaptured,1)."</pre><br />image title: {$_POST['imageTitle']}";
		
		echo $this->encodeJSON($data);
		return false;
	}
	
	/**
	 * Internal method (obviously) used to return back the array with nextUploadSlot
	 * populated and uploadImageBox populated.  Requires $data[maxSlots] to be already
	 * populated or it won't work right.  Also assumes session is already inited
	 * and cart is already inited.
	 * 
	 * @param array $data The data with maxSlots already set.
	 * @param array $imagesCaptured The images captured, or null to make it
	 *  retrieve the images captured for you.
	 * @return null
	 */
	private function _returnNewImages ($data = array(), $imagesCaptured = null)
	{
		if ($imagesCaptured === null) {
			$imagesCaptured = imagesOrderItem::getImagesCaptured();
		}
		
		//apply them to the DB only at the time changes are being applied!
		//(which is when the order item is approved)
		
		//need to return back all the new stuff
		//firgure out the next image
		for ($i = 1; $i <= $data['maxSlots']; $i++) {
			if (!isset($imagesCaptured[$i])) {
				$data['nextUploadSlot'] = $i;
				break;
			}
		}
		
		//re-render the innards so that all the id's and stuff will be correct
		
		$data['uploadImageBox'] = geoOrderItem::callDisplay('mediaDisplay','justImageSlots','','images');
		
		include GEO_BASE_DIR . 'app_bottom.php';
		//echo "apply changes: $applyChanges \n\n images captured: \n\n".print_r($imagesCaptured,1)."\n\nSlots: \n\n".print_r($slots,1);
		echo $this->encodeJSON($data);
	}
}