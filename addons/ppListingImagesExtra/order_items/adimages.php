<?php
/*
	By Chris (Ardex)
	Means for attaching a banner images to certain listings
*/

require_once CLASSES_DIR . PHP5_DIR . 'OrderItem.class.php';
require_once CLASSES_DIR . PHP5_DIR . 'ExtraImage.class.php';

class adimagesOrderItem extends geoOrderItem {
	var $defaultProcessOrder = 40;
	protected $type = 'adimages';
	const type = 'adimages';
	const renewal = 1; //easier way to access what is renew/upgrade
	const upgrade = 2;

	const max_banners = 3; // Number of banners they can upload
	public static $allowedCategories = array(318, 319, 316, 411, 412, 420); // Categories that are can have banner ads
	const extraimages_table = "petsplease_classifieds_extraimages_urls";
	const extraimage_type = 1; // Each listing can have seperate types of extra images which are handled seperately
	const max_width = 710;
	const max_height = 210;
	
	public function displayInAdmin() {
		return true;
	}
	
	/**
	 * used in admin to show which upgrades are attached to a Listing Renewal item
	 *
	 * @return String "user-friendly" name of this item
	 */
	public function friendlyName() {
		return 'Ad Images';
	}
	
	public function geoCart_previewDisplay(){
		self::_start();
		$cart = geoCart::getInstance();
		//get the listing id
		$listingId = $this->getParent()->get('listing_id',0);
		$images_captured = $this->get('adimages_captured',array());
        //bit of a hack to display banner in preview
		self::_updateImageListingId($this->get('adimages_captured'), $listingId );
		$ids = array();
		foreach ($images_captured as $info) {
			$ids[] = (int)$info['id'];
            self::_updateImageListingId($info, $listingId);
		}
		
		$sql = "SELECT * FROM ".self::extraimages_table." WHERE `image_id` IN (".implode(', ',$ids).") ORDER BY FIELD(`image_id`, ".implode(',',$ids).")";
		
		$result_set = DataAccess::getInstance()->Execute($sql);
		//Make call to get images and pass in the result set we wish it to use
		//so that it will cache this info, and it will be used when retrieving
		//image info to display the listing preview
		geoListing::getImages($listingId, $result_set);
	}
	
	/**
	 * Required by interface.
	 * Used: in geoCart::initSteps()
	 * 
	 * Determine whether or not the other_details step should be added to the steps of adding this item
	 * to the cart.  This should also check any child items if it does not need other_details itself.
	 *
	 * @return boolean True to add other_details to steps, false otherwise.
	 */
	public static function geoCart_initSteps_addOtherDetails(){
		return false;
	}
	
	public static function geoCart_other_detailsCheckVars(){
		$cart = geoCart::getInstance();
		if (!(isset($cart->item->renew_upgrade) && $cart->item->renew_upgrade > 0)){
			//this is not a renewal or upgrade, and we only display on other details for renew/upgrade
			trigger_error('DEBUG CART: Here in images.');
			return '';
		}

		$cart->site->get_ad_configuration();
		self::fixPricePlan();

		// REMOVED CODE FROM HERE FOR PAID IMAGES
	}
	public static function geoCart_other_detailsProcess(){
		//everything done in check vars...
	}
	public static function geoCart_other_detailsDisplay(){
		$cart = geoCart::getInstance();
		trigger_error('DEBUG CART: Here in images.');
		if (!(isset($cart->item->renew_upgrade) && $cart->item->renew_upgrade > 0)){
			//this is not a renewal or upgrade, and we only display on other details for renew/upgrade
			trigger_error('DEBUG CART: Here in images.');
			return '';
		}
		if (!geoMaster::is('site_fees')) {
			trigger_error('DEBUG CART: Here in images.');
			return '';
		}
		self::fixPricePlan();
		$cart->site->get_ad_configuration();

		// REMOVED CODE FROM HERE FOR PAID IMAGES 
	}
	
	public function getDisplayDetails ($inCart,$inEmail=false)
	{
		$db = DataAccess::getInstance();
		$price = $this->getCost(); //people expect numbers to be positive...
		//Figure out how many photos, how many are being charged, etc.
		$renew_upgrade = (($this->getParent())? $this->getParent()->get('renew_upgrade') : false);
		
		//can edit if not renewing/upgrading and not editing listing
		$can_edit = !($renew_upgrade > 0 || ($this->getParent() && $this->getParent()->getType() == 'listing_edit'));
		//if can't edit, don't allow to delete either, it could mess things up
		$can_delete = $can_edit;
		
		$msgs = $db->get_text(true, 10202);
		$return = array (
			'css_class' => '',
			'title' => 'Banners', 
			'canEdit' => $can_edit, //whether can edit it or not
			'canDelete' => $can_delete, //whether can remove from cart or not
			'canPreview' => false, //whether can preview the item or not
			'canAdminEditPrice' => true, //show edit price button for item, if displaying in admin panel cart?
			'priceDisplay' => geoString::displayPrice($price, false, false, 'cart'), //price to display
			'cost' => $price, //amount this adds to the total, what getCost returns
			'total' => $price, //amount this and all children adds to the total
			'children' => false
		);
		
		//trigger_error('ERROR CART: image item: <pre>'.print_r($this,1).'</pre>');
		//charge per picture
		
		$parentItem = $this->getParent();
		
		$total = $this->get('image_count_total');
		if (is_object($parentItem) && $parentItem->getType() == 'listing_edit') {
			//special case when editing listing
			$imgsAtStart = $parentItem->get('numImagesAtStart');
			if($total <= $imgsAtStart) {
				//haven't actually added any new images
				
				//this will only trigger during an edit,
				//since imgsAtStart is otherwise 0
				$return['title'] .= ' '.$msgs[500320];
				$return['cost'] = 0.00;
				$return['total'] = 0.00;
				$return['priceDisplay'] = geoString::displayPrice(0.00, false, false, 'cart');
			} else {
				$total_paid = $this->get('image_count_not_free');
				//subtract pre-existing images from total number of free images displayed, to make it less confusing
				$free = intval($this->get('number_free_images')) - $imgsAtStart;
				if ($total < 0) {
					$total = 0;
				}
				if ($total_paid < 0){
					$total_paid = 0;
				}
				//adding pictures during an edit
				$return['title'] = $total_paid . $msgs[500359];
			}
		} else {
		
			$total_paid = $this->get('image_count_not_free',0);
			//subtract pre-existing images from total number of free images displayed, to make it less confusing
			$free = intval($this->get('number_free_images'));
			if ($total < 0){
				$total = 0;
			}
			if ($total_paid < 0){
				$total_paid = 0;
			}
			
			$free = ($free > 0) ? $free.$msgs[500339]: '';
			
			$display_per_pic_cost = geoString::displayPrice($this->get('cost_per_image'));
			$ts = ($total > 1)? 's': '';
			if (geoMaster::is('site_fees')) {
				$title = "($free {$total_paid} X $display_per_pic_cost )";
				$return['title'] .= $title;
			}
		}
		
		//go through children...
		$order = $this->getOrder();
		$items = $order->getItem();
		$children = array();
		foreach ($items as $i => $item){
			if (is_object($item) && is_object($item->getParent()) && $item->getId() != $this->getId()){
				$p = $item->getParent();
				if ($p->getId() == $this->getId()){
					//This is a child of mine...
					$displayResult = $item->getDisplayDetails($inCart,$inEmail);
					if ($displayResult !== false) {
						//only add if they do not return bool false
						$children[$item->getId()] = $displayResult;
						$return['total'] += $children[$item->getId()]['total']; //add to total we are returning.
					}
				}
			}
		}
		if (count($children)){
			$return['children'] = $children;
		}
		return $return;
	}
	
	public function getCostDetails ()
	{
		//Most use this exactly AS-IS...
	
		$return = array (
					'type' => $this->getType(),
					'extra' => null,
					'cost' => $this->getCost(),
					'total' => $this->getCost(),
					'children' => array(),
		);
	
		//call the children and populate 'children'
		$order = $this->getOrder();//get the order
		$items = $order->getItem();//get all the items in the order
		$children = array();
		foreach ($items as $i => $item) {
			if (is_object($item) && $item->getType() != $this->getType() && is_object($item->getParent())) {
				$p = $item->getParent();//get parent
				if ($p->getId() == $this->getId()){
					//Parent is same as me, so this is a child of mine, add it to the array of children.
					//remember the function is not static, so cannot use callDisplay() or callUpdate(), need to call
					//the method directly.
					$costResult = $item->getCostDetails();
					if ($costResult !== false) {
						//only add if they do not return bool false
						$children[$item->getId()] = $costResult;
						$return['total'] += $costResult['total']; //add to total we are returning.
					}
	
				}
			}
		}
		if ($return['total']<>0) {
			//total is 0, even after going through children!  no cost details to return
			return false;
		}
		if (count($children)) {
			//add children to the array
			$return['children'] = $children;
		}
		return $return;
	}

	/**
	 * Update Functions : called from main software using geoOrderItem::callUpdate(), and that
	 * function calls the one here if the function exists.  To avoid name conflicts, if you need
	 * custom functions specific for this orderItem, prepend the var or function name with an
	 * underscore.
	 */
	
	public static function getParentTypes(){
		//this is attached to classifieds, auctions, and 
		//dutch auctions.
		return array(
			'classified',
			'auction',
			'listing_renew_upgrade',
			'listing_edit',
			'listing_change_admin',
		);
	}
	
	public static function getImageData()
	{
		$cart = geoCart::getInstance();
		//instead of just getting the image price, get the other stuff about the images at the same time.
		
		//default values for image data we return
		$image_data = array(
			'image_count_total' => 0,
			'image_count_not_free' => 0,
			'number_free_images' => 0,
			'cost_per_image' => 0,
			'total_cost' => 0
		);
		if (!$cart->price_plan) {
			trigger_error('ERROR CART: Price plan must be set first, nogo on image data');
			
			return $image_data;
		}
		
		$allFree = !geoMaster::is('site_fees');
		
		$numImagesAtStart = $cart->item->get('numImagesAtStart',0);
		
		//Is this correct now?  the logic was backwards before...
		$image_data['cost_per_image'] = (($allFree)? 0: $cart->price_plan['charge_per_picture']);
		
		$number_of_images = $image_data['image_count_total'] = count(self::getImagesCaptured());
		$free_image_count = $image_data['number_free_images'] = (geoPC::is_ent() && !$allFree)? $cart->price_plan['num_free_pics']: 0;
		
		if($numImagesAtStart >= $number_of_images || $allFree) {
			//no new images on this edit, so no charge
			$number_of_images_not_free = 0;
		} else {
			$number_of_new_images = $number_of_images - $numImagesAtStart;
			if($number_of_new_images < 0) {
				$number_of_new_images = 0;
			}

			if($free_image_count >= $number_of_images) {
				//all free
				$number_of_images_not_free = 0; 
			} else if ($free_image_count <= $numImagesAtStart) {
				//no new free images
				$number_of_images_not_free = $number_of_new_images;				
			} else {
				//some new images free, some not
				$new_images_free = $free_image_count - $numImagesAtStart;
				if($new_images_free < 0) {
					//just a sanity check -- this should never happen.
					$new_images_free = 0;
				}
				$new_images_not_free = $number_of_new_images - $new_images_free;
				$number_of_images_not_free = $new_images_not_free;
			}
		}
		if($number_of_images_not_free < 0) {
			$number_of_images_not_free = 0;
		}
		
		$image_data['image_count_not_free'] = $number_of_images_not_free;
		$image_data['total_cost'] = ($cart->price_plan['charge_per_picture'] * $number_of_images_not_free);
		
		return $image_data;
	}
	
	
	public static function mediaCheckVars ()
	{
		if (!self::addMedia()) {
			//no images on media page
			return;
		}
		$cart = geoCart::getInstance();
		self::_start();
	
		if (isset($_REQUEST["f"]) && isset($_REQUEST["g"])) {
			//LEGACY user deleting an image, this will NOT be called like this if
			//deleting an image the "ajax" way, this is used by the "fallback"
			//method (if the user is not able to use the new image uploader)
			$order_item = false;
			
			//let removeImage do the rest of the work for us
			self::removeImage($_REQUEST["f"],$_REQUEST["g"]);
			
			$cart->addError(); //set error to send user back to image display page when we're done
		} elseif ((isset($_REQUEST["c"]) && $_REQUEST["c"]) || (isset($_REQUEST["d"]) && $_REQUEST["d"]) || isset($_FILES['Filedata'])) {
			$cart->site->get_badword_array();
			$images_captured = self::processImages($_REQUEST['c'],$_FILES);				
			
			$item = self::_getImageItem();
			if (!is_object($item)) {
				$item = new adimagesOrderItem;
				$item->setParent($cart->item);//this is a child of the parent
				$item->setOrder($cart->order);
				$item->save();//make sure it's serialized
				$cart->order->addItem($item);
				trigger_error('DEBUG CART: Adding images: <pre>'.print_r($item,1).'</pre>');
			}
			if (is_object($item)){
				$item->set('adimages_captured',$images_captured);
				$item->save();
			}
		}
		//Note: processing images adds error to the cart itself now.
		
		//but children might, get steps from children as well.
		$children = geoOrderItem::getChildrenTypes(self::type);
		geoOrderItem::callUpdate('mediaCheckVars',null,$children);
	}
	
	public static function mediaProcess ()
	{
		if (!self::addMedia()) {
			//no images on media page
			return;
		}
		$cart = geoCart::getInstance();
		trigger_error('DEBUG CART: Top of images process.');
		$order_item = self::_getImageItem();
		$parent = null;
		if (is_object($order_item) && is_object($order_item->getParent())){
			//make sure if price plan id is set, to use that price plan when getting prices!
			$parent = $order_item->getParent();
			$cart->setPricePlan($parent->getPricePlan(),$parent->getCategory());
		}
		
		$image_data = self::getImageData();
		$use_images = true;
		if ($image_data['image_count_total'] == 0){
			//do not care about adding item if images are free
			$use_images = false ;
		}
		
		if (!$use_images){
			trigger_error('DEBUG CART: Not Using Images, image data: <pre>'.print_r($image_data,1).'</pre>');
			
			if ($order_item) {
				$id = $order_item->getId();
				geoOrderItem::remove($id);
				$cart->order->detachItem($id);
			}
		} else {
			if (!$order_item){
				$order_item = new adimagesOrderItem;
				$order_item->setParent($cart->item);//this is a child of the parent
				$order_item->setOrder($cart->order);
				$order_item->save();//make sure it's serialized
				$cart->order->addItem($order_item);
				trigger_error('DEBUG CART: Adding images: <pre>'.print_r($order_item,1).'</pre>');
				$parent = $cart->item;
			} else {
				trigger_error('DEBUG CART: Images already attached: <pre>'.print_r($order_item,1).'</pre>');
				$cart->order->addItem($order_item);
			}
			//get the price for bolding
			$order_item->setCreated($cart->order->getCreated());
			$order_item->setCost($image_data['total_cost']);
			$order_item->setPricePlan($parent->getPricePlan());
			$order_item->setCategory($parent->getCategory());
			
			//set details specific to images
			//set number of images total
			$order_item->set('image_count_total', $image_data['image_count_total']);
			
			if (is_object($parent)){
				//make sure image count is also set in session variables
				if ($parent->getType() != 'listing_edit' || $image_data['cost_per_image']==0 || $parent->get('adimage_slots') < $image_data['number_free_images']) {
					//either this is a normal listing placement, or this is a listing edit and
					//the number of slots open is less than the number of free images, or there is no charge for image.
					trigger_error('DEBUG CART: Image count being added to session vars, count: '.$image_data['image_count_total']);
					$session_variables = $parent->get('session_variables');
					$session_variables['image'] = $image_data['image_count_total'];
					$parent->set('session_variables',$session_variables);
					if (is_array($cart->site->session_variables)){
						$cart->site->session_variables['image'] = $image_data['image_count_total'];
					}
				}
				if ($parent->getType() == 'listing_edit' && $image_data['image_count_total'] <= $parent->get('adimage_slots')) {
					//no charge, they already paid for the extra image slots!
					$order_item->setCost(0);
				}
			} else {
				trigger_error('DEBUG CART: Image count NOT ADDED, parent not object!');
			}
			//set number of images that apply to cost
			$order_item->set('image_count_not_free', $image_data['image_count_not_free']);
			//set number of free images for this price plan, as of this time
			$order_item->set('number_free_images', $image_data['number_free_images']);
			//set cost of each not-free image
			$order_item->set('cost_per_image', $image_data['cost_per_image']);
			//set id of listing, if known
			if (isset($cart->site->classified_id) && $cart->site->classified_id > 0) {
				$order_item->set('listing_id',$cart->site->classified_id);
			}
			$order_item->save();
		}
		//make sure everything is set up correctly now
		self::_start();
	}
	
	public static function mediaLabel()
	{
		$cart = geoCart::getInstance();
		return $cart->site->messages[500501];
	}
	
	public static function fixPricePlan ()
	{
		$cart = geoCart::getInstance();
		
		//get plan item
		$category = $cart->item->getCategory();
		$price_plan = $cart->item->getPricePlan();
		
		if($cart->price_plan['price_plan_id'] != $price_plan) {
			//workaround for "images incorrectly using default priceplan" bug
			//make sure cart is using the right priceplan
			
			$cart->setPricePlan($price_plan, $category);
		}
	}
	public static function getMaxImages ()
	{
		return self::max_banners;
	}
	
	public static function mediaDisplay ($full_step)
	{
		if (!self::addMedia()) {
			return '';
		}

		$cart = geoCart::getInstance();
		
		//make sure it is NOT ssl mode for this step
		if (geoSession::isSSL() && !isset($_GET['no_ssl_force'])) {
			//oops! can't be in SSL mode!
			$url = $cart->db->get_site_setting('classifieds_url').'?a=cart&no_ssl_force=1';
			if (isset($_POST['media_submit_form']) && $_POST['media_submit_form']) {
				$url .= '&media_submit_form_ssl=1';
			}
			header('Location: '.$url);
			//let normal closing stuff happen.
			return;
		}
		
		self::fixPricePlan();
		//get plan item
		$category = $cart->item->getCategory();
		$price_plan = $cart->item->getPricePlan();
		$planItem = geoPlanItem::getPlanItem(self::type,$price_plan,$category);
		
		//Need to initialize things before attempting to get images captured
		self::_start();
		
		$verify = ($cart->item->getType() == 'listing_edit')? true: false;
		$images_captured = self::getImagesCaptured('cart', $verify);
		
		trigger_error('DEBUG CART: Images captured: <pre>'.print_r($images_captured,1).'</pre>');
		//whether images are allowed or not is checked when this step is added, so don't need to check it here.
		$cart->site->get_ad_configuration();
		$slotsAvailable = self::getMaxImages();

		if ($full_step) {
			$cart->site->messages = $cart->db->get_text(true, 10);
		} else {
			$cart->site->page_id = 10;
			$cart->site->get_text();
		}
		
		$tpl_vars = $headerVars = $cart->getCommonTemplateVars();
		//set order item specific vars in a sub-var to help prevent var name collisions between order items
		$images = array();
		if ($cart->main_type == 'listing_edit') {
			//set text for edit
			$images['section_title'] = $cart->site->messages[500909];
			$images['legacy_description'] = $cart->site->messages[500374];
			$images['description'] = $cart->site->messages[500718];
		} else if ($cart->main_type == 'auction' || $cart->main_type == 'reverse_auction') {
			//set text for auction placement
			$images['section_title'] = $cart->site->messages[500908];
			$images['legacy_description'] = $cart->site->messages[500381];
			$images['description'] = $cart->site->messages[500717];
		} else if ($cart->main_type == self::type) {
			//have to set all the settings
			$images['section_title'] = $cart->site->messages[500962];
			$images['legacy_description'] = $cart->site->messages[500963];
			$images['description'] = $cart->site->messages[500964];
			
			//set these as well
			$tpl_vars['title1'] = $cart->site->messages[500965];
			$tpl_vars['title2'] = $cart->site->messages[500966];
			$tpl_vars['page_description'] = $cart->site->messages[500967];
			$tpl_vars['cancel_txt'] = $cart->site->messages[500968];
		} else {
			//set text for normal classified (or unknown type) placement
			$images['section_title'] = $cart->site->messages[500907];
			$images['legacy_description'] = $cart->site->messages[167];
			$images['description'] = $cart->site->messages[500716];
		}
		
		$tpl_vars['error_msgs']['images_error'] = $cart->site->images_error;
		
		$images['uploading_image'] = $cart->db->get_site_setting('uploading_image');
		$images['old_config'] = $cart->db->GetRow("SELECT * FROM ".geoTables::ad_configuration_table);
		$images['adimages_captured'] = $images_captured;
		//allow images to be removed in "legacy" uploader
		$images['show_delete'] = true;
		
		//use standard uploader if able to
		if (isset($_GET['useLegacyUploader']) && $_GET['useLegacyUploader']) {
			//don't use legacy uploader, since page was submitted without legacy uploader.
			$images['useStandardUploader'] = false;
		} else {
			$images['useStandardUploader'] = $cart->db->get_site_setting('useStandardUploader');
		}
		
		//force to be 100 x 100 or it won't fit right into the windows
		$maxW = 100;//$cart->site->ad_configuration_data->MAXIMUM_IMAGE_WIDTH;
		$maxH = 100;//$cart->site->ad_configuration_data->MAXIMUM_IMAGE_HEIGHT;
		$images['images_data'] = self::getImgsData($images_captured, $maxW, $maxH);
		
		$not_keys_yet = array();
		$max = $images['max'] = $slotsAvailable;
		$freeSlot = 0;
		$imageSlots = array();
		$imgInfo = self::getImageData();
		//die ('images_captured:<pre>'.print_r($images_captured,1).'</pre>images data: <pre>'.print_r($tpl_vars['images_data'],1));
		for ($n=1;$n<=$max;$n++) {
			if (isset($images_captured[$n])) {
				$imageSlots[$n]['image'] = $images['images_data'][$n];
			} else {
				$imageSlots[$n]['empty'] = 1;
				array_push($not_keys_yet,$n);
				if (!$freeSlot) {
					//first free slot
					$freeSlot = $n;
				}
			}
		}
		$images['imageSlots'] = $imageSlots;
		$images['not_keys_yet'] = $not_keys_yet;
		$images['imgMaxTitleLength'] = $cart->site->ad_configuration_data->MAXIMUM_IMAGE_DESCRIPTION;
		$images['full_step'] = $full_step;
		
		$tpl_vars['adimages'] = $images;
		unset($images);
		
		if ($full_step === 'justImageSlots') {
			//special case, just the image slots (for ajax calls)
			$tpl = new geoTemplate('system','order_items');
			$tpl->assign($tpl_vars);
			return $tpl->fetch('adImages/images_captured_box.tpl');
		}
		$view = geoView::getInstance();
		$session = geoSession::getInstance();
		
		$headerVars['classified_session']= $session->getSessionId();
		$headerVars['user_agent']= $_SERVER['HTTP_USER_AGENT'];
		$headerVars['userId'] = (int)$cart->user_data['id'];
		$headerVars['adminId'] = (defined('IN_ADMIN'))? $session->getUserId() : 0;
		$headerVars['adfreeSlot'] = $freeSlot;
		$headerVars['maximum_upload_size'] = (int)$cart->site->ad_configuration_data->MAXIMUM_UPLOAD_SIZE;
		//during beta period, add timestamp onto URL so that files are not cached
		
		if ($tpl_vars['adimages']['useStandardUploader']) {
			//using "standard" uploader
			$pre = (defined('IN_ADMIN'))? '../' : '';
			
			//don't set header TPL/VAR like we used to, because now multiple
			//things use same page, and they may want to also...
			$tpl = new geoTemplate(geoTemplate::SYSTEM, 'order_items');
			$tpl->assign($headerVars);
			
			$view->addCssFile($pre.geoTemplate::getUrl('css','vendor/upload_images.css'))
				->addJScript($pre.'classes/swfupload/swfupload.js')
				->addJScript($pre.geoTemplate::getUrl('js','system/adimage_handlers.js'))
				->addTop($tpl->fetch('adImages/upload_images_head.tpl'));
		}
		//tell it to include CSS for message box
		$view->useMessageBox = 1;
		if ($full_step == 'tpl') {
			$view->setBodyVar($tpl_vars);
			return array (
				'file' => 'adImages/upload_images.tpl',
				'g_type' => 'system',
				'g_resource' => 'order_items',
			);
		}
		
		if ($cart->main_type == self::type) {
			//- Editing images part by clicking edit next to images in cart
			$tpl_vars ['mediaTemplates']['adimages'] = array (
				'file' => 'adImages/upload_images.tpl',
				'g_type' => 'system',
				'g_resource' => 'order_items',
			);
			
			$view->setBodyTpl('shared/media.tpl','','order_items')
				->setBodyVar($tpl_vars);
			$cart->site->display_page();
			return;
		}
		
		
		//TODO:  Code here for other methods of display:
		//- uploading images as part of listing details page
	}
	
	public static function geoCart_initSteps ($allPossible=false) {
		trigger_error('DEBUG CART: top of images.php geoCart_initSteps.');
		$cart = geoCart::getInstance();
		
		if ($cart->main_type != self::type) {
			//don't add any steps, since we will be called by parent
			return;
		}
		
		trigger_error('DEBUG CART: checking if images allowed.');
		if (self::addMedia()) {
			//Only add step if images are allowed
			trigger_error('DEBUG CART: adding image step in images.php.');
			$cart->addStep('adimages:admedia');
		}
	}
	
	public static function addMedia ()
	{
		trigger_error('DEBUG CART: top of images.php addMedia.');
		$cart = geoCart::getInstance();
		
		$category = $cart->item->getCategory();
		
		// if we are in one of the allowed categories then yes, we can upload banners
		return in_array($category, self::$allowedCategories);
	}
	
	public static function geoCart_initItem_forceOutsideCart () {
		return false;
	}
	
	public static function removeImage ($image_id=0,$image_key=0, $skipEditChecks = false)
	{
		self::_start();

		$cart = geoCart::getInstance();
		$images_captured = self::getImagesCaptured();
		$image_id = intval($image_id);
		$image_key = intval($image_key);
		if (!$image_id || !isset($images_captured[$image_key])){
			//invalid
			return false;
		}
		//delete url images
		//get image urls to
		$sql = "SELECT * FROM ".self::extraimages_table." WHERE `image_id` = ?";
		$imgData = $cart->db->GetRow($sql, array($image_id));
		//echo $sql."<br />\n";
		if (!$imgData) {
			//did not find that image in the database
			return false;
		}
		
		//get geoImage::remove() do the guts of the work.
		if (!self::deleteImage($image_id)) {
			//error happened when doing the actual removal of the image.
			return false;
		}
		$findEntry = $images_captured[$image_key];
		//remove it
		unset($images_captured[$image_key]);
		
		self::setImagesCaptured($images_captured);
		
		//figure out parent to see if edit, to see if need to remove additional
		//stuff
		$parent = $cart->item;
		if (is_object($parent) && $parent->getType() == self::type) {
			$parent = $parent->getParent();
		}
		
		if (!$skipEditChecks && is_object($parent) && $parent->getType() == 'listing_edit') {
			//currently editing a listing, so images may span multiple order items
			//need to figure out which one to remove from
			
			$map = $parent->get('admapImageItems');
			if ($map && isset($map[$image_id])) {
				//find item IDs
				$itemIds = $map[$image_id];
				//get items
				foreach($itemIds as $orderItemId) {
					$order_item = geoOrderItem::getOrderItem($orderItemId);
					//to remove from order item's images...
					$imgCap = $order_item->get('adimages_captured');
					$position = array_search($findEntry, $imgCap);
					
					$imgRemoved = $order_item->get('images_removed', array());
					
					//...and reset the item to have correct values
					if ($position && isset($imgCap[$position]) && $imgCap[$position]['id'] == $image_id) {
						unset($imgCap[$position]);
						$order_item->set('adimages_captured',$imgCap);
						if ($cart->site->debug_image_delete) {
							//FOR DEBUG: Save removed info
							$imgRemoved[] = array ('slot' => $position, 'id' => $image_id);
							$order_item->set('images_removed', $imgRemoved);
						}
						$order_item->save();
					}
				}
			}
		}
		return true;
	}
	private static $session_id_to_use = 0;
	
	public static function _initPhotos($processCart = true){
		if (self::$session_id_to_use > 0){
			return;
		}
			
		if ($processCart) $cart = geoCart::getInstance();
		
		$order_item = self::_getImageItem();
		
		if (!is_object($order_item) || !is_object($order_item->getParent())){
			//image item not found, nothing to initialize
			trigger_error('DEBUG CART: Image Item Not found! or Parent Not found! order_item: <pre>'.print_r($order_item,1).'</pre>' );
			return;
		}
		$parent = $order_item->getParent();
		trigger_error('DEBUG CART: Photo: init photos TOP');
		$images_captured = $order_item->get('adimages_captured');
		if (!is_array($images_captured)){
			$images_captured = array();
			trigger_error('DEBUG CART: here, images captured not set.');
		}
		ksort($images_captured);
		
		//don't set image in session_vars here, set it in upload_imagesProcess()
		
		if ($processCart) {
			$session_vars = (is_object($parent))? $parent->get('session_variables'): array();
			$cart->site->session_variables['image'] = (isset($session_vars['image']))? $session_vars['image']: 0;
		}
		//trigger_error('DEBUG CART: Photo: init photos END, session vars: <pre>'.print_r($session_vars,1).'</pre>');
	}

	public static function geoCart_payment_choicesProcess(){
		$cart = geoCart::getInstance();
		
		$items = $cart->order->getItem(self::type);
		if (!is_array($items) || !count($items)){
			//no images in order
			return;
		}

		foreach ($items as $item){
			if (is_object($item)) {
				$cart->initItem($item->getId());
				$parent = $item->getParent();
				if (!is_object($parent)){
					//something wrong with this one...
					trigger_error('ERROR CART: Error with doing final stuff for inserting images to listing, the parent not found.');
					continue;
				}
				
				$cart->site->session_variables = $parent->get('session_variables');
				$cart->site->classified_id = $parent->get('listing_id');
				
				$img_item = self::_getImageItem();
				
				self::$session_id_to_use = 0;
				
				$images_captured = $img_item->get('adimages_captured');

				//don't run insert_classified_images() if editing -- we'll take care of it at approval time
				if($parent->getType() == 'listing_edit') {
					$parent->set('adimages_captured', $images_captured);
					
					//bounce these to parent for easier reverts
					$parent->set('revertState', $cart->item->get('dontDeleteThese'));
					$parent->save();
					return true;
				}
				if (!self::_updateImageListingId($images_captured, $parent->get('listing_id'))) {
					return false;
				}
			}
		}
	}
	public static function geoCart_deleteProcess()
	{
		//Remove from the session_variables
		$cart = geoCart::getInstance();
		
		//go through each child, and call deleteProcess
		$original_id = $cart->item->getId();
		$items = $cart->order->getItem();
		foreach ($items as $k => $item){
			if (is_object($item) && $item->getId() != $cart->item->getId() && is_object($item->getParent()) && $item->getParent()->getId() == $cart->item->getId()){
				//this is a child of this item...
				//Set the cart's main item to be this item, so that the deleteProcess gets
				//what it is expecting...
				$cart->initItem($item->getId(),false);
				geoOrderItem::callUpdate('geoCart_deleteProcess',null,$item->getType());
			}
		}
		if ($cart->item->getId() != $original_id){
			//change the item back to what it was originally.
			$cart->initItem($original_id);
		}
		
		
		self::_start();
		
		//for each of $images_captured, call self::removeImage(id, key)
		
		//echo 'images:<pre>'.print_r($cart->site->images_captured ,1).'</pre>';
		$images_captured = self::getImagesCaptured();
		$noDelete = $cart->item->get('dontDeleteThese', array());
		if (!$noDelete && $cart->item->getType() == self::type && $cart->item->getParent()) {
			//see if nodelete is saved on parent
			$noDelete = $cart->item->getParent()->get('dontDeleteThese',array());
		}
		foreach ($noDelete as $preserve) {
			//position can change so have to manually find and remove images
			//that should not be removed
			$position = array_search($preserve, $images_captured);
			if ($position && isset($images_captured[$position])) {
				unset ($images_captured[$position]);
			}
		}
		//can't do array diff as the index (display order) could be changed
		//$images_captured = array_diff($images_captured, $cart->item->get('dontDeleteThese'));
		foreach($images_captured as $key => $image) {
			self::removeImage($image['id'],$key, true);
		}
		$cart->item->set('adimages_captured',array());
		$parent = $cart->item->getParent();
		if (is_object($parent) && $parent->getType() != 'listing_edit') {
			//note that this would not be called from listing edit or renewal
			$session_vars = $parent->get('session_variables');
			$session_vars['images'] = 0;
			$parent->set('session_variables',$session_vars);
			$parent->save();
			$cart->site->session_variables['images'] = 0;
		}
	}
	private static function _getImageItem()
	{
		$cart = geoCart::getInstance();
		
		return geoOrderItem::getOrderItemFromParent($cart->item,self::type);
	}
	
	public static function getImagesCaptured ($item = 'cart', $verify = false)
	{
		if ($item == 'cart') {
			$cart = geoCart::getInstance();
			
			$item = self::_getImageItem();
			trigger_error('DEBUG CART: Got item');
			if (!is_object($item) && $cart->item->getType() == 'listing_edit') {
				$imagesCaptured = $cart->item->get('all_adimages_captured',array());
				if ($verify) {
					return self::verifyImagesCaptured($imagesCaptured);
				}
				return $imagesCaptured;
			}
		}
		if (!is_object($item)) {
			//item does not exist, or is not valid or something
			trigger_error('DEBUG CART: No image item found! item: <pre>'.print_r($cart->order,1).'</pre>');
			return array();
		}
		$imagesCaptured = $item->get('adimages_captured',array());
		if ($verify) {
			return self::verifyImagesCaptured($imagesCaptured);
		}
		return $imagesCaptured;
	}
	
	public static function verifyImagesCaptured ($imagesCaptured)
	{
		$db = DataAccess::getInstance();
		
		foreach ($imagesCaptured as $displayOrder => $imgData) {
			$row = $db->GetRow("SELECT `image_id` FROM ".self::extraimages_table." WHERE `image_id`=?", array($imgData['id']));
			if (!$row) {
				//not found!
				unset($imagesCaptured[$displayOrder]);
			}
		}
		return $imagesCaptured;
	}
	
	/**
	 * Mostly used to re-order images
	 * 
	 * @param array $newImagesCaptured
	 * @param string|imagesOrderItem $item
	 */
	public static function setImagesCaptured ($newImagesCaptured)
	{
		$item = self::_getImageItem();
		$cart = geoCart::getInstance();
		
		if (!is_object($item) && $cart->item->getType() == 'listing_edit' && $cart->item->get('all_adimages_captured')) {
			//special case for image edits.
			$cart->item->set('all_adimages_captured', $newImagesCaptured);
		}
		if (is_object($item)){
			$item->set('adimages_captured',$newImagesCaptured);
			$item->save();
		}
	}
	
	private static function _getPreExistingImages()
	{
		$cart = geoCart::getInstance();
		
		if ($cart->item->getType() != 'listing_edit') {
			//we only get pre existing images from listing edit.
			return false;
		}
		if ($cart->item->get('existingadImages',false)) {
			//we've already done this -- don't do it again
			return true;
		}
		//remember that we've already done this at least once.
		$cart->item->set('existingadImages',1);
		$listing_id = $cart->item->get('listing_id',false);
		$items = array();
		$ids = array();
		
		if (!$listing_id) {
			return false;
		}
		//get rid of any duplicates
		ppExtraImage::fixDuplicates($listing_id);
		
		//get number of image slots available
		$listing = geoListing::getListing($listing_id);
		if(is_object($listing) && $listing->id > 0) {
			$slots = $listing->image;
			$cart->item->set('adimage_slots', $slots);

			//get priceplan for this listing
			$priceplan = $listing->price_plan_id;
			$category = $listing->category;
			if ($priceplan) {
				$cart->item->setPricePlan($priceplan, $listing->seller);
			}
		}
		//Get all the image items using this listing.
		//If you change this query or logic, TEST ON INSTALL WITH LARGE NUMBERS OF LISTINGS for speed!
		
		$sql = "SELECT `item`.`id` from `geodesic_order_item` as item, `geodesic_order_item_registry` as regi
					WHERE regi.index_key='listing_id' AND regi.val_string='$listing_id' AND item.id = regi.order_item ORDER BY item.id";
		$itemsResult = $cart->db->GetAll($sql);
		
		foreach ($itemsResult as $row_item) {
			//This is broken up into 2 queries for speed, if you combine this
			//with the query above, TEST FOR SPEED on install with large
			//numbers of listings!  Yes we know how to nest queries but in this
			//case doing so caused huge slow-downs on large sites, this solution
			//fixes the slow-down.
			$sql = "SELECT `id` FROM `geodesic_order_item` WHERE `type`='adimages' AND `parent` = {$row_item['id']}";
			$row = $cart->db->GetRow($sql);
			//each main item should only have 1 image item attached.
			if (isset($row['id'])) {
				$items[$row['id']] = geoOrderItem::getOrderItem($row['id']);
			}
		}
		
		$images_captured = array();
		
		$mapImageItems = $ids = array(); 
		if (count($items)) {
			foreach($items as $item) {
				if (!is_object($item)) {
					continue;
				}
				
				$imageData = $item->get('adimages_captured', array());
				
				foreach($imageData as $key => $val) {
					//see if that same data already exists
					$found = array_search($val, $images_captured);
					if ($found !== false && $found !== null && isset($images_captured[$found]) && $images_captured[$found] == $val) {
						//This is to fix re-ordering of things.  Remove it from the
						//old location
						unset($images_captured[$found]);
					}
					//check to make sure image exists in the images urls table
					if (!$cart->db->GetRow("SELECT `image_id` FROM ".self::extraimages_table." WHERE `image_id`=?", array($val['id']))) {
						//no found, probably deleted at a later date
						continue;
					}
					$images_captured[$key] = $val;
					$ids[$val['id']] = (int)$val['id'];
					//map image ids to their item ids, for use in deleting later
					$mapImageItems[$val['id']][] = $item->getId();
					
					if ($item->getStatus() == 'active') {
						//keep track of which images were originally here
						$saveMe[] = array('type' => $val['type'], 'id' => $val['id']);
					}
				}
			}
		}
		
		$cart->item->set('admapImageItems', $mapImageItems);
		$notThese = ($ids)? " AND `image_id` NOT IN (".implode(', ',$ids).") " : '';
		$sql = "SELECT `image_id`, `display_order` FROM ".self::extraimages_table." WHERE `type_id` = ".self::extraimage_type." AND `classified_id` = ? $notThese ORDER BY `display_order`";
		$legacyResults = $cart->db->GetAll($sql, array($listing_id));
		
		if(count($items) + count($legacyResults) < 1) {
			return false;
		}
		
		if (count($legacyResults)) {
			//get the next display order
			$dorder = 0;
			$slotsAvailable = self::getMaxImages();
			for ($i = 1; $i <= $slotsAvailable; $i++) {
				if (!isset($images_captured[$i])) {
					$dorder = $i;
					break;
				}
			}
			if ($dorder) {
				//only proceed if there is another higher display order
				foreach ($legacyResults as $pic) {
					$val = array ('type' => '1', 'id' => $pic['image_id']);
					
					//it's not in there yet!  Cheesy comments Batman, we better add it in!
					$images_captured[$dorder] = $val;
					$saveMe[] = $val;
					for ($i = $dorder; $i <= $slotsAvailable; $i++) {
						$dorder = 0;
						if (!isset($images_captured[$i])) {
							$dorder = $i;
							break;
						}
					}
					if (!$dorder) {
						//we are now higher than allowed images, no need to go on in our quest.
						break;
					}
				}
			}
		}
		
		//flatten images_captured array for return
		
		$numImagesReturned = count($images_captured);
		if(!$cart->item->get('numImagesAtStart')) {
			// only if this is the first time through this function for this item
			
			//save the starting number of images, for use in price calculations later
			$cart->item->set('numImagesAtStart', $numImagesReturned);
		}
		if(!$cart->item->get('dontDeleteThese')) {
			//save IDs of original images, so they can be not deleted if order canceled
			$cart->item->set('dontDeleteThese', $saveMe);
		}
		$cart->item->set('all_adimages_captured',$images_captured);
		$cart->item->save();
		
		return $images_captured;
	}
	
	public static function copyListing($parentItem){
		if (!class_exists('geoCart',false)) {
			//this copy listing needs a cart environment to copy images
			//TODO: make it not need cart...
			return;
		}
		$cart = geoCart::getInstance();
		if (!$cart->site->session_variables) {
			$cart->site->session_variables = $parentItem->get('session_variables');
		}
		$session_variables = ($parentItem)? $parentItem->get('session_variables'): $cart->site->session_variables;
		
		//see if there are any images, don't rely on session_variables['image'] by itself
		//as it might not be accurate.
		$sql = "SELECT count(*) as count FROM ".self::extraimages_table." WHERE `type_id` = ".self::extraimage_type." AND `classified_id` = ? ORDER BY `display_order` ASC";
		$row = $cart->db->GetRow($sql, array($session_variables['listing_copy_id']));
		
		if ((isset($row['count']) && $row['count'] > 0) || $cart->site->session_variables['image']) {
			trigger_error('DEBUG CART: Copy Listing Here');
			
			if (!isset($cart->site->session_variables['image'])) {
				//from listing placed when there was that problem with setting 'image', set it now
				$cart->site->session_variables['image'] = (int)$row['count'];
				if ($parentItem) {
					$parentItem->set('session_variables', $cart->site->session_variables);
				}
			}
			
			if(!$cart->site->ad_configuration_data){
				$cart->site->get_ad_configuration();
				trigger_error('DEBUG CART: Copy Listing Here');
			}
			
			$image_proc = ppExtraImage::getInstance();
			$image_proc->setAdConfig($cart->site->ad_configuration_data);
			
			$item = self::_getImageItem();
			if (!is_object($item)){
				trigger_error('DEBUG CART: Copy Listing Here');
				$item = new adimagesOrderItem;
				$parentUse = ($parentItem)? $parentItem: $cart->item;
				$order = ($parentUse->getOrder())? $parentUse->getOrder(): $cart->order;
				if (!$order) {
					//if we can't get the order, we can't do much
					return false;
				}
				$item->setParent($parentUse);//this is a child of the parent
				
				$item->setOrder($order);
				$item->save();//make sure it's serialized
				$order->addItem($item);
			}

			//new geoImage($this->ad_configuration_data, $this->session_id);
			$images_captured = $image_proc->copyImages($cart->site->session_variables['listing_copy_id']);
			
			$item->set('adimages_captured',$images_captured);
			trigger_error('DEBUG CART: Copy Listing Here, copy_id: '.$cart->site->session_variables['listing_copy_id'].' adimages_captured: <pre>'.print_r($images_captured,1).'</pre>');
			$image_data = self::getImageData();
			//NOTE: Initially, prices may be set wrong, but they will be fixed
			//when we are "higher up" (in same page load) so we can better tell
			//what price plan and cat to use.
			trigger_error('DEBUG CART: Copy Listing Here, image data: <pre>'.print_r($image_data,1).'</pre>');
			$item->setCreated($cart->order->getCreated());
			$item->setCost($image_data['total_cost']);
			trigger_error('DEBUG CART: Copy Listing Here');
			self::_start();
			//set details specific to images
			//set number of images total
			$item->set('image_count_total', $image_data['image_count_total']);
			//set number of images that apply to cost
			$item->set('image_count_not_free', $image_data['image_count_not_free']);
			//set number of free images for this price plan, as of this time
			$item->set('number_free_images', $image_data['number_free_images']);
			//set cost of each not-free image
			$item->set('cost_per_image', $image_data['cost_per_image']);
			
			$item->set('adimages_captured',$images_captured);
			
			trigger_error('DEBUG CART: Copy Listing Here, image item: <pre>'.print_r($item,1).'</pre>');
		}
		trigger_error('DEBUG CART: Copy Listing Here');
	}
	
	public function processStatusChange($newStatus, $sendEmailNotices = true, $updateCategoryCount = false)
	{
		$parent = $this->getParent();
		if ($newStatus == 'active') {
			self::_updateImageListingId($this->get('adimages_captured'), $parent->get('listing_id'));
		}
		parent::processStatusChange($newStatus, $sendEmailNotices, $updateCategoryCount);
	}
	
	private static function _start()
	{
		//get pre-existing images from other order items
		//or try to get them from just this item
		if(!self::_getPreExistingImages()) {
			self::_initPhotos();	
		}
	}
	
	
	public function processRemove ()
	{
		//this is handled by calls to removeImage() in geoCart_deleteProcess()
		//having it here, too, makes things behave erratically
		
		return true;
	}
	
	public static function adminItemDisplay ($item_id)
	{
		if (!$item_id){
			return '';
		}
		$parent = geoOrderItem::getOrderItem($item_id);
		if (!is_object($parent)) {
			return '';
		}
		$item = geoOrderItem::getOrderItemFromParent($parent,self::type);
		if (!is_object($item)) {
			//no images attached
			return '';
		}
		$db = DataAccess::getInstance();
		$images_captured = $item->get('adimages_captured');
		$images = array();
		$base_url = dirname($db->get_site_setting('classifieds_url')).'/';
		//the max width and height for displaying thumbnails in admin.
		$maxW = $maxH = 100;
		foreach ($images_captured as $display_order => $image_data) {
			if ($image_data['type'] == 1) {
				$sql = "SELECT * FROM ".self::extraimages_table." WHERE `image_id` = ?";
				$result = $db->GetRow($sql, array($image_data['id']));
				
				if (isset($result['image_url'])){
					//figure out scaled width/height
					$width = $result['image_width'];
					$height = $result['image_height'];
					//scale it...
					if ($width > $maxW) {
						$scale = ($maxW / $width);
						$width = $width * $scale;
						$height = $height * $scale;
					}
					if ($height > $maxH) {
						$scale = ($maxH / $height);
						$width = $width * $scale;
						$height = $height * $scale;
					}
					//see if it appears the url includes the entire domain name or not
					$base = (substr($result['image_url'],0,4) == 'http')? '': $base_url;
					
					//figure out if the thumb can be used or not
					$thumb = (($result['thumb_url'])?$result['thumb_url']: $result['image_url']);
					$images[$image_data['id']] = array (
						'thumb' => $base . $thumb,
						'full' => $base . $result['image_url'],
						'caption' => $result['image_text'],
						'width' => $width,
						'height' => $height,
						'slot' => $display_order
					);
				}
			}
		}
		$tpl = new geoTemplate('admin');
		$tpl->assign('images', $images);
		$tpl->assign('current_color', geoHTML::adminGetRowColor());
		return $tpl->fetch('order_items/images/item_details.tpl');
	}
	
	/**
	 * Processes images that were just uploaded, by making sure they are "valid", renaming them
	 * and putting them in user_images, creating re-sized thumbnails where needed, etc.
	 * 
	 * @param array|bool $url_info The array of image url post data, or false if using image URL's 
	 *  is turned off.
	 * @param array $post_files The $_FILES array.
	 * @return array The array of data for images captured.
	 */
	public static function processImages ($url_info=0, $post_files)
	{
		//Addon core event: overload_imagesOrderItem_processImages
		$addon_result = geoAddon::triggerDisplay('overload_imagesOrderItem_processImages', 
			array ('url_info'=>$url_info, 'post_files'=>$post_files), geoAddon::OVERLOAD);
		if ($addon_result !== geoAddon::NO_OVERLOAD) {
			//an addon has replaced this function
			return $addon_result;
		}
		
		if (isset($url_info["imageUploader"]) && $url_info["imageUploader"]) {
			//aurigma
			return self::_processAurigma($url_info, $post_files);
		}
		$cart = geoCart::getInstance();
		//get plan item
		$category = $cart->item->getCategory();
		$price_plan = $cart->item->getPricePlan();
		$planItem = geoPlanItem::getPlanItem('images',$price_plan,$category);
		$verify = ($cart->item->getType() == 'listing_edit')? true: false;
		$images_captured = self::getImagesCaptured('cart',$verify);
		
		$cart->site->page_id = 10;
		$cart->site->get_text();
		$sell_debug_images = 0;
		$cart->site->get_ad_configuration();
		trigger_error('DEBUG CART IMAGES: Top of process images!');
		$image_height = (self::max_height > $cart->site->ad_configuration_data->LEAD_PICTURE_HEIGHT) ?
			self::max_height : $cart->site->ad_configuration_data->LEAD_PICTURE_HEIGHT;
		$image_width = (self::max_width > $cart->site->ad_configuration_data->LEAD_PICTURE_WIDTH) ?
			self::max_width : $cart->site->ad_configuration_data->LEAD_PICTURE_WIDTH;
		
		$fullWidth = $cart->site->ad_configuration_data->MAXIMUM_FULL_IMAGE_WIDTH;
		$fullHeight = $cart->site->ad_configuration_data->MAXIMUM_FULL_IMAGE_HEIGHT;
		$imgPath = $cart->site->ad_configuration_data->IMAGE_UPLOAD_PATH;
		$imgUrl = $cart->site->ad_configuration_data->URL_IMAGE_DIRECTORY;
		$fullQuality = $thumbQuality = $cart->site->ad_configuration_data->PHOTO_QUALITY;
		if ($sell_debug_images) {
			echo "hello from _processImages top<br />\n";
			echo $url_info["no_images"]." is no images<br />\n";
		}
		//echo $url_info["no_images"]." is no images<br />\n";
		
		$cart->site->get_image_file_types_array();
		//process the images entered by the ad poster
		$max = (int)self::max_banners;
		
		for ($i = 1;$i <= $max;$i++) {
			if (isset($post_files['Filedata'])) {
				//normal "swfupload" way to send files
				if ($i > 1) {
					//already processed it
					$i = $max+1;
					break;
				}
				
				//check for errors
				if (isset($post_files['Filedata']['error']) && $post_files['Filedata']['error']) {
					$fileError = $post_files['Filedata']['error'];
				}
				
				$size = (isset($post_files['Filedata']['size']))? $post_files['Filedata']['size']: null;
				$name = (isset($post_files['Filedata']['name']))? $post_files['Filedata']['name']: null;
				$tmp_file = (isset($post_files['Filedata']['tmp_name']))? $post_files['Filedata']['tmp_name']: null;
				$imageTitle = (isset($_POST['imageTitle']))? trim($_POST['imageTitle']): '';
				$defaultType = (isset($post_files['Filedata']['mime_type']))? $post_files['Filedata']['mime_type']: null;
				//no use in sending defaultMime, as it will always be same for image uploader
				$type = ppExtraImage::getMimeType($tmp_file, $name);
				if ($sell_debug_images) echo "\n".__line__." got here! type: $type\n\n";
				//set i to whatever slot was passed in
				$i = (isset($_POST['uploadSlot']) && intval($_POST['uploadSlot']) > 0)? intval($_POST['uploadSlot']): $max+1;
				
				if (isset($_POST['editImageSlot'])) {
					//let it replace the normal slot
					$i = (int)$_POST['editImageSlot'];
				}
				if ($i > $max) {
					//oops! they tried to upload an image into an invalid slot!
					break;
				}
			} else {
				//assume sending using old-school form
				$size = (isset($post_files['d']['size'][$i]))? $post_files['d']['size'][$i]: null;
				$name = (isset($post_files['d']['name'][$i]))? $post_files['d']['name'][$i]: null;
				$tmp_file = (isset($post_files['d']['tmp_name'][$i]))? $post_files['d']['tmp_name'][$i]: null;
				$defaultType = (isset($post_files['d']['type'][$i]))? $post_files['d']['type'][$i]: null;
				
				$type = ppExtraImage::getMimeType($tmp_file, $name, $defaultType);
				
				$imageTitle = (isset($_POST['c'][$i]['text']))? trim($_POST['c'][$i]['text']): '';
				
				if (isset($post_files['d']['error'][$i]) && $post_files['d']['error'][$i] && $post_files['d']['error'][$i]!=UPLOAD_ERR_NO_FILE) {
					$fileError = $post_files['d']['error'][$i];
				}
			}
			//clean up image title
			$imageTitle = $cart->site->check_for_badwords($imageTitle);
			$imageTitle = ppExtraImage::shortenImageTitle($imageTitle, $cart->site->ad_configuration_data->MAXIMUM_IMAGE_DESCRIPTION);
			
			if ($fileError) {
				//error with upload.  Error codes can be found listed at
				//http://us.php.net/manual/en/features.file-upload.errors.php
				//for example, 1 is UPLOAD_ERR_INI_SIZE which lists as:
				//Value: 1; The uploaded file exceeds the upload_max_filesize directive in php.ini. 
				//see URL above for rest of codes listed.
				$cart->addError()
					->addErrorMsg('adimages',$cart->site->messages[500680].$fileError);
				return $images_captured;
			}
			if (isset($url_info[$i]["url"]["location"]) && strlen(trim($url_info[$i]["url"]["location"])) > 0) {
				//insert the url - only works on "legacy" uploader
				$image_dimensions = getimagesize($url_info[$i]["url"]["location"]);
				if ($image_dimensions) {
					if (($image_dimensions[0] > $image_width) && ($image_dimensions[1] > $image_height)) {
						$imageprop = ($image_width * 100) / $image_dimensions[0];
						$imagevsize = ($image_dimensions[1] * $imageprop) / 100 ;
						$final_image_width = $image_width;
						$final_image_height = ceil($imagevsize);

						if ($final_image_height > $image_height) {
							$imageprop = ($image_height * 100) / $image_dimensions[1];
							$imagehsize = ($image_dimensions[0] * $imageprop) / 100 ;
							$final_image_height = $image_height;
							$final_image_width = ceil($imagehsize);
						}
					} elseif ($image_dimensions[0] > $image_width) {
						$imageprop = ($image_width * 100) / $image_dimensions[0];
						$imagevsize = ($image_dimensions[1] * $imageprop) / 100 ;
						$final_image_width = $image_width;
						$final_image_height = ceil($imagevsize);
					} elseif ($image_dimensions[1] > $image_height) {
						$imageprop = ($image_height * 100) / $image_dimensions[1];
						$imagehsize = ($image_dimensions[0] * $imageprop) / 100 ;
						$final_image_height = $image_height;
						$final_image_width = ceil($imagehsize);
					} else {
						$final_image_width = $image_dimensions[0];
						$final_image_height = $image_dimensions[1];
					}
					//set the image type
					if (isset($image_dimensions['mime'])) {
						$type = $image_dimensions['mime'];
					}
					if ((!$cart->site->first_image_filled) && ($i > 1) && (count($images_captured) == 0)) {
						$image_position = 1;
					} else {
						$image_position = $i;
					}

					if($cart->site->image_accepted_type($type)) {
						$insertResult = self::_insertImage($images_captured, $image_position, $url_info[$image_position]["url"]["location"], $imageTitle, $final_image_width, $final_image_height, $image_dimensions,$type);
						
						if (!$insertResult) {
							$cart->addError();
							return $images_captured;
						}
					} else {
						//wrong image file type
						$cart->site->images_error = urldecode($cart->site->messages[1150]);
						$cart->addError();
						return $images_captured;
					}
				} else {
					//could not find url image
				}
			} elseif (($size > 0) && ($size < $cart->site->ad_configuration_data->MAXIMUM_UPLOAD_SIZE)) {
				//insert the image
				if ($sell_debug_images) echo "\n".__line__." got here!\n\n";
				if ($cart->site->ad_configuration_data->IMAGE_UPLOAD_TYPE) {
					$filename = strrchr($tmp_file,"/");
					$filename = str_replace("/","",$filename);
					//prepend with TEMP- then timestamp so admin can remove older images easily
					$filename = 'TEMP-'.date('Y-m-d',geoUtil::time()).'-'.$filename;
					if (!move_uploaded_file($tmp_file, stripslashes($cart->site->ad_configuration_data->IMAGE_UPLOAD_PATH).$filename)) {
						$filename = 0;
						if ($sell_debug_images) {
							echo "uploaded file NOT moved because of  error<br />\n";
							echo $filename." is the filename before image type check<br />\n";
						}
					} else {
						$filename = stripslashes($cart->site->ad_configuration_data->IMAGE_UPLOAD_PATH).$filename;
						if (!$type) {
							//attempt to re-get the image type in new location since getting it failed in tmp location
							$type = ppExtraImage::getMimeType($filename, $name, $defaultType);
						}
						if ($sell_debug_images) {
							echo "uploaded file moved successfully<br />\n";
							echo $filename." is the filename before image type check<br />\n";
						}
					}
				} else {
					$filename = $tmp_file;
					if ($sell_debug_images) {
						echo "uploaded file is set not to be moved<br />\n";
						echo $tmp_file." is the tmp_file before image type check<br />\n";
						echo $filename." is the filename before image type check<br />\n";
						echo $type." is the type\n";
					}
				}
				
				if (!$cart->site->image_accepted_type($type)) {
					//wrong image file type
					$cart->addError()
						->addErrorMsg('adimages', $cart->site->messages[1150]);
					return $images_captured;
				}
				
				if ($sell_debug_images) {
					echo strlen(trim($cart->site->current_file_type_icon))." is the strlen of the file type icon<br />\n";
					echo $cart->site->current_file_type_icon." is the icon to use<br />\n";
					echo $type." is the type<Br\n";
				}
				if (strlen(trim($cart->site->current_file_type_icon)) > 0) {
					//upload file and reference using icon
					
					$full_filename = ppExtraImage::generateFilename($imgPath, '.'.$cart->site->current_file_type_extension);
					$full_url = $cart->site->ad_configuration_data->URL_IMAGE_DIRECTORY.$full_filename;
					$filepath = $imgPath.$full_filename;
					
					if ($sell_debug_images) {
						echo $filepath." is the filepath within icon use<br />\n";
						echo $full_url." is the full_url within icon use<br />\n";
						echo $full_filename." is the full_filename within icon use<br />\n";
					}

					$a = array("B", "KB", "MB", "GB", "TB", "PB");

					$pos = 0;
					while ($size >= 1024) {
						$size /= 1024;
						$pos++;
					}
					$displayed_filesize = round($size,2)." ".$a[$pos];

					if (copy ($filename,$filepath)) {
						$full_size_image_copied = 1;
					} elseif (move_uploaded_file ($filename,$filepath)) {
						$full_size_image_copied = 1;
					} else {
						$full_size_image_copied = 0;
					}
					if ($full_size_image_copied) {
						if(!$image_dimensions) {
							$iconLocation = geoTemplate::getFilePath(geoTemplate::EXTERNAL, '', $cart->site->current_file_type_icon, false);
							$image_dimensions = getimagesize($iconLocation);
							if($image_dimensions) {
								if (($image_dimensions[0] > $image_width) && ($image_dimensions[1] > $image_height)) {
									$imageprop = ($image_width * 100) / $image_dimensions[0];
									$imagevsize = ($image_dimensions[1] * $imageprop) / 100 ;
									$final_image_width = $image_width;
									$final_image_height = ceil($imagevsize);

									if ($final_image_height > $image_height) {
										$imageprop = ($image_height * 100) / $image_dimensions[1];
										$imagehsize = ($image_dimensions[0] * $imageprop) / 100 ;
										$final_image_height = $image_height;
										$final_image_width = ceil($imagehsize);
									}
								} elseif ($image_dimensions[0] > $image_width) {
									$imageprop = ($image_width * 100) / $image_dimensions[0];
									$imagevsize = ($image_dimensions[1] * $imageprop) / 100 ;
									$final_image_width = $image_width;
									$final_image_height = ceil($imagevsize);
								} elseif ($image_dimensions[1] > $image_height) {
									$imageprop = ($image_height * 100) / $image_dimensions[1];
									$imagehsize = ($image_dimensions[0] * $imageprop) / 100 ;
									$final_image_height = $image_height;
									$final_image_width = ceil($imagehsize);
								} else {
									$final_image_width = $image_dimensions[0];
									$final_image_height = $image_dimensions[1];
								}
							}
						}
						$insertResult = self::_insertImage($images_captured, $i, $full_url, $imageTitle, $final_image_width, $final_image_height, $image_dimensions,$type, $full_filename, '', '', $imgPath);
						
						if(!$insertResult) {
							//there was an error inserting, return what images are captured up to this poing
							return $images_captured;
						}
					}
				} else {
					/******  Normal image upload processing  *******/
					
					if ($sell_debug_images) echo "\n".__line__." got here!\n\n";
					
					//make sure to reset vars for multiple iterations in loop
					$full = $fullName = $fullCreate = $thumbnail = null;
					$thumbName = $thumbCreate = null;
					
					//handy for debug ajax, get it to list files array
					//geoImage::_returnError('Files: <pre>'.print_r($post_files,1).'</pre>');
					$full = ppExtraImage::resize($filename, $fullWidth, $fullHeight);
					if ($full && function_exists('imagejpeg')) {
						//re-size of full image was successful, process it!
						$fullName = ppExtraImage::generateFilename($imgPath);
						
						$fullCreate = imagejpeg($full['image'], $imgPath.$fullName, $fullQuality);
						//now kill image to free up memory
						imagedestroy($full['image']);
						if ($fullCreate) {
							//attempt to create thumbnail
							$thumbnail = ppExtraImage::resize($filename, $image_width, $image_height, false);
							
							if ($thumbnail) {
								//save thumb image
								
								$thumbName = ppExtraImage::generateFilename($imgPath);
								$thumbCreate = imagejpeg($thumbnail['image'], $imgPath.$thumbName, $thumbQuality);
								//destroy image to free up memory
								imagedestroy($thumbnail['image']);
							}
							
							if (!$thumbnail || !$thumbCreate) {
								//re-size thumb not necessary, or re-size failed
								//when the resize full image did not.  Either
								//way, use same image for full and thumbnail.
								
								$thumbnail = $full;
								$thumbName = $fullName;
							}
							
							//image type is always going to be jpg since that
							//is what we re-size images to.
							$type = 'image/jpeg';
						}
					} else {
						//NOT able to resize full image/file!
						
						//do some investigating, see if we can figure out
						//the cause!
						
						//manually get the image size info
						$image_dimensions = getimagesize($filename);
						if (!$image_dimensions) {
							//error getting image info!
							//internal error could not process your image
							
							if ($size == 0) {
								//size was listed at 0, this won't happen very
								//often (if at all) as most will get caught by the check
								//on the $_FILES[..][error] done earlier.
								$cart->addError()
									->addErrorMsg('adimages', $cart->site->messages[1148].'('.__line__.')');
								return $images_captured;
							} elseif ($size > $cart->site->ad_configuration_data->MAXIMUM_UPLOAD_SIZE) {
								$cart->addError()
									->addErrorMsg('adimages', $cart->site->messages[1149]);
								return $images_captured;
							}
							if ($cart->site->ad_configuration_data->IMAGE_UPLOAD_TYPE) {
								unlink($filename);
							}
							//something generic happened, give generic error
							$cart->addError()
								->addErrorMsg('adimages', $cart->site->messages[1148].'('.__line__.')');
							return $images_captured;
						}
						
						//If re-sizing the image did not work, this might
						//just be on a crappy server that doesn't have GD
						//or something.  See if we can at least copy the
						//images over
						switch ($image_dimensions[2]) {
							case 1: 
								//gif
								$extension = ".gif";
								break;
								
							case 2: 
								//jpg
								$extension = ".jpg";
								break;
								
							case 3: 
								//png
								$extension = ".png";
								break;
								
							case 6: 
								//bmp
								$extension = ".bmp";
								break;
								
							case 7: 
								//tiff (intel)
								$extension = ".tif";
								break;
								
							default:
								// Check for accepted types in the database
								$sql = "SELECT `extension` FROM ".geoTables::file_types_table." WHERE `mime_type` = ? AND `accept` = 1";
								$result = $cart->db->Execute($sql, array ($image_dimensions['mime']));
								if(!$result) {
									$extension = 0;
									break;
								}

								if($result->RecordCount() == 0) {
									$extension = 0;
									break;
								} else {
									$file_type = $result->FetchRow();
								}

								$extension = ".".$file_type['extension'];
								break;
						}
						if ($sell_debug_images) echo $extension." is the extension<br />\n";
						
						if ($extension) {
							//do full size image & thumb as same file
							$fullName = $thumbName = ppExtraImage::generateFilename($imgPath, $extension);
							
							$filepath = $imgPath.$fullName;
							
							//attempt to move full file to end location
							if (copy ($filename,$filepath)) {
								$fullCreate = 1;
							} else if (move_uploaded_file ($filename,$filepath)) {
								$fullCreate = 1;
							} else {
								//move of file not successful.
								$fullCreate = 0;
							}
							
							if ($fullCreate) {
								//set sizes and all
								$thumbnail['width'] = $full['width'] = $image_dimensions[0];
								$thumbnail['height'] = $full['height'] = $image_dimensions[1];
							}
						}
						if ($cart->site->ad_configuration_data->IMAGE_UPLOAD_TYPE) {
							//be sure to remove old file because of way this
							//server does things.
							unlink($filename);
						}
					}
					
					if ($fullCreate) {
						//now then, put it in the DB
						$result = self::_insertImage($images_captured, $i, $imgUrl.$fullName.'',
							$imageTitle.'', (int)$thumbnail['width'], (int)$thumbnail['height'],
							array((int)$full['width'], (int)$full['height']), $type,
							$fullName, $imgUrl.$thumbName, $thumbName, $imgPath);
						
						if (!$result) {
							trigger_error('ERROR SQL IMAGE: Query failed!  Can not insert image into DB.');
							//delete the created images
							unlink ($imgUrl.$fullName);
							if ($fullName != $thumbName) {
								//also delete the thumb
								unlink($imgUrl.$thumbName);
							}
							//let them know of the error.
							$cart->addError()
								->addErrorMsg('adimages',$cart->site->messages[1148].'('.__line__.' db)');//.$cart->db->ErrorMsg());
							return $images_captured;
						}
					}
				}
				
				if ($cart->site->ad_configuration_data->IMAGE_UPLOAD_TYPE) {
					unlink($filename);
				}
			} else {
				//	echo $post_files['d']['size'][$i]." is size of ".$i." in else<br />\n";
				//	if ($post_files['d']['size'][$i] == 0)
				//	{
				//		$cart->site->images_error = urldecode($cart->site->messages[1148]);
				//		return $images_captured;
				//	}
				//echo $post_files['d']['size'][$i]." is the size<br />\n";
				//echo $cart->site->ad_configuration_data->MAXIMUM_UPLOAD_SIZE." is max size <br />\n";
				if ($size > $cart->site->ad_configuration_data->MAXIMUM_UPLOAD_SIZE) {
					$cart->addError()
						->addErrorMsg('adimages',$cart->site->messages[1148].'('.__line__.')');
					return $images_captured;
				}
			}
		}
		
		//if it gets here, everything went smoothly
		return $images_captured;
	}
	
	/**
	 * Split aurigma into it's own little method to keep things easier.  See
	 * {@link imagesOrderItem::processImages()} for documentation.
	 * 
	 * This has NOT BEEN TESTED in a very long time, as we don't have a modern
	 * version of aurigma around to test.  Just keeping it around for if/when
	 * we do re-do aurigma integration.
	 */
	private static function _processAurigma ($url_info=0,$post_files)
	{
		$cart = geoCart::getInstance();
		//get plan item
		$category = $cart->item->getCategory();
		$price_plan = $cart->item->getPricePlan();
		$planItem = geoPlanItem::getPlanItem('images',$price_plan,$category);
		$images_captured = self::getImagesCaptured();
		
		$cart->site->page_id = 10;
		$cart->site->get_text();
		$sell_debug_images = 0;
		$cart->site->get_ad_configuration();
		trigger_error('DEBUG CART IMAGES: Top of process images!');
		$image_height = ($cart->site->ad_configuration_data->MAXIMUM_IMAGE_HEIGHT > $cart->site->ad_configuration_data->LEAD_PICTURE_HEIGHT) ?
		$cart->site->ad_configuration_data->MAXIMUM_IMAGE_HEIGHT : $cart->site->ad_configuration_data->LEAD_PICTURE_HEIGHT;
		$image_width = ($cart->site->ad_configuration_data->MAXIMUM_IMAGE_WIDTH > $cart->site->ad_configuration_data->LEAD_PICTURE_WIDTH) ?
		$cart->site->ad_configuration_data->MAXIMUM_IMAGE_WIDTH : $cart->site->ad_configuration_data->LEAD_PICTURE_WIDTH;
		if ($sell_debug_images) {
			echo "hello from _processImages top<br />\n";
			echo $url_info["no_images"]." is no images<br />\n";
		}
		//echo $url_info["no_images"]." is no images<br />\n";
		
		//aurigma
		$not_keys_yet = array();
		$max = self::max_banners;
		for ($n=1;$n<=$max;$n++) {
			if (!isset($images_captured[$n])) {
				array_push($not_keys_yet,$n);
			}
		}
		reset($not_keys_yet);
		for($i=1;$i<=$_POST["FileCount"];$i++) {
			$image_position = current($not_keys_yet);

			$description = $_POST ['Description_' . $i];
			$thumbnailName1 = "Thumbnail1_" . $i;
			$thumbnailName2 = "Thumbnail2_" . $i;

			$size1=$post_files[$thumbnailName1][size];
			if($size1) {
				$fileName1 = $post_files[$thumbnailName1][name];
				$tempName1 = $post_files[$thumbnailName1][tmp_name];
				$type1 = $post_files[$thumbnailName1][type];
				$imageProperties1 = getimagesize($tempName1);
				$width1 = $imageProperties1[0];
				$height1 = $imageProperties1[1];
			}
			$size2=$post_files[$thumbnailName2][size];
			if($size2) {
				$fileName2 = $post_files[$thumbnailName2][name];
				$tempName2 = $post_files[$thumbnailName2][tmp_name];
				$type2 = $post_files[$thumbnailName2][type];
				$imageProperties2 = getimagesize($tempName2);
				$width2 = $imageProperties2[0];
				$height2 = $imageProperties2[1];
			}
			
			//SAVE IMAGE TO SERVER
			//do thumb first
			//get extension
			switch ($imageProperties1[2]) {
				case 1:
					//gif
					$extension = ".gif";
					break;
					
				case 2:
					//jpg
					$extension = ".jpg";
					break;
					
				case 3:
					//png
					$extension = ".png";
					break;
					
				case 6:
					//bmp
					$extension = ".bmp";
					break;
					
				case 7:
					//tiff (intel)
					$extension = ".tif";
					break;
					
				default:
					// Check for accepted types in the database
					$sql = "select extension from ".$cart->site->file_types_table." where mime_type like \"".$image_dimensions['mime']."\" and accept = 1";
					$file_type = $cart->db->GetRow($sql);
					if (!isset($file_type['extension'])) {
						$extension = 0;
						break;
					}
					
					$extension = ".".$file_type['extension'];
					break;
			}
			if ($sell_debug_images) {
				echo $extension." is the extension<br />\n";
			}
			if ($extension) {
				if ($size1) {
					do {
						$thumb_filename_root = rand(1000000,9999999);
						$thumb_filepath = stripslashes($cart->site->ad_configuration_data->IMAGE_UPLOAD_PATH).$thumb_filename_root.".jpg";
					} while (file_exists($thumb_filepath));
					if ($sell_debug_images)
						echo  $cart->site->ad_configuration_data->PHOTO_QUALITY." is the photo quality<br />\n";
					if (copy ($tempName1, $thumb_filepath)) {
						$image_done = 1;
					} elseif (move_uploaded_file ($tempName1, $thumb_filepath)) {
						$image_done = 1;
					} else {
						$image_done = 0;
					}

					if ($image_done) {
						$thumb_url = $cart->site->ad_configuration_data->URL_IMAGE_DIRECTORY.$thumb_filename_root.".jpg";
						$thumb_filename = $thumb_filename_root.".jpg";
					} else {
						if ($sell_debug_images)
							echo "image NOT created with imagejpeg<br />\n";
						$thumb_url = $thumb_filename = 0;
					}
					if ($sell_debug_images) {
						echo $thumb_url." is the thumb url<br />\n";
						echo $thumb_filename." is the thumb filename<br />\n";
					}
				} else {
					$thumb_url = $thumb_filename = 0;
				}
				//do full size image
				do {
					$filename_root = rand(1000000,9999999);
					$filepath = stripslashes($cart->site->ad_configuration_data->IMAGE_UPLOAD_PATH).$filename_root.$extension;
				} while (file_exists($filepath));
				$full_filename = $filename_root.$extension;
				$full_url = $cart->site->ad_configuration_data->URL_IMAGE_DIRECTORY.$full_filename;
				if ($sell_debug_images) {
					echo $filepath." is the filepath within full size image<br />\n";
					echo $full_url." is the full_url within full size image<br />\n";
					echo $full_filename." is the full_filename within full size image<br />\n";
					echo $filepath." is the filepath within full size image<br />\n";
					echo $filename." is the filename within full size image<br />\n";
				}

				if (copy ($tempName2,$filepath)) {
					$full_size_image_copied = 1;
				} elseif (move_uploaded_file ($tempName2,$filepath)) {
					$full_size_image_copied = 1;
				} else {
					$full_size_image_copied = 0;
				}

				if ($full_size_image_copied) {
					$sql = "INSERT INTO ".self::extraimages_table."
						(image_url,full_filename,image_text,thumb_url,thumb_filename,file_path,date_entered,image_width,image_height,original_image_width,original_image_height,display_order,filesize,mime_type,type_id)
						values
						(\"".$full_url."\",\"".$full_filename."\",\"".$cart->site->check_for_badwords($description)."\",\"".$thumb_url."\",\"".$thumb_filename."\",\"".$cart->site->ad_configuration_data->IMAGE_UPLOAD_PATH."\",".geoUtil::time().",".$width1.",".$height1.",".$width2.",".$height2.",".$image_position.",".$size2.",\"".$imageProperties1['mime']."\",".self::extraimage_type.")";
					if ($sell_debug_images) echo $sql."<br />\n";
					
					$result = $cart->db->Execute($sql);
					if (!$result) {
						$cart->site->error_message = urldecode($cart->site->messages[57]);
						if ($cart->site->ad_configuration_data->IMAGE_UPLOAD_TYPE) {
							unlink($filename);
						}
						$cart->addError();
						return $images_captured;
					}
					$images_captured[$image_position]["type"] = 1;
					$images_captured[$image_position]["id"] = $cart->db->Insert_ID();
					ksort($images_captured);
					
					$cart->site->first_image_filled = 1;
				}
				if ($cart->site->ad_configuration_data->IMAGE_UPLOAD_TYPE) {
					unlink($filename);
				}
			} // if ($extension)
			if ($cart->site->ad_configuration_data->IMAGE_UPLOAD_TYPE) {
				unlink($filename);
			}
			
			next($not_keys_yet);
		}
		//if it gets here, everything went smoothly
		return $images_captured;
	}
	
	private static function _insertImage (& $images_captured, $image_position, $url, $text, $final_image_width, $final_image_height, $image_dimensions, $mime_type, $full_filename='', $thumb_url = '', $thumb_filename='', $file_path = '')
	{
		$cart = geoCart::getInstance();
		trigger_error("DEBUG IMAGE CART: inserting image, ". $image_position." is image_position");
		
		$sql = "INSERT INTO ".self::extraimages_table." 
		(image_url, full_filename, thumb_url, thumb_filename, file_path, date_entered, image_text, image_width, image_height, original_image_width, original_image_height, display_order, icon, mime_type, type_id) 
		VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$query_data = array(
			$url.'', 
			$full_filename.'',
			$thumb_url.'',
			$thumb_filename.'',
			$file_path.'',
			geoUtil::time(),
			$text.'',
			(int)$final_image_width,
			(int)$final_image_height,
			(int)$image_dimensions[0],
			(int)$image_dimensions[1],
			(int)$image_position,
			$cart->site->current_file_type_icon.'',
			$mime_type.'',
			self::extraimage_type
		);
		
		$result = $cart->db->Execute($sql, $query_data);
		if (!$result) {
			$cart->addError()
				->addErrorMsg('adimages','Error inserting in database.');
			return false;
		}

		$cart->site->first_image_filled = 1;
		$images_captured[$image_position]["type"] = 1;
		$images_captured[$image_position]["id"] = $cart->db->Insert_ID();
		ksort($images_captured);
		
		$addonData = array(
			'id' => $images_captured[$image_position]["id"],
			'type' => 1, 'image_url' => $url.'', 'full_filename' => $full_filename.'',
			'thumb_url' => $thumb_url.'', 'thumb_filename' => $thumb_filename.'',
			'file_path' => $file_path.'', 'date_entered' => geoUtil::time(),
			'image_text' => $text.'', 'image_width' => (int)$final_image_width,
			'image_height' => (int)$final_image_height, 'original_image_width' => (int)$image_dimensions[0],
			'original_image_height' => (int)$image_dimensions[1], 'display_order' => (int)$image_position,
			'icon' => $cart->site->current_file_type_icon.'', 'mime_type' => $mime_type.'',
			);
		geoAddon::triggerUpdate('notify_image_insert', $addonData);
		
		return true;
	}
	
	private static function _updateImageListingId ($images_captured, $listingId)
	{
		$listingId = intval($listingId);
		
		if (count($images_captured) == 0 || !$listingId) {
			return true;
		}
		
		//tie the images in the two tables to this classifieds
		
		//images were captured
		//display them
		$db = DataAccess::getInstance();
		foreach ($images_captured as $key => $value) {
			$sql = "UPDATE ".self::extraimages_table." SET  `type_id` = ".self::extraimage_type.", `classified_id` = ?, `display_order`=? WHERE `image_id` = ?";
			$image_result = $db->Execute($sql, array($listingId, intval($key), intval($value["id"])));
			if (!$image_result) {
				//$this->body .=$sql." is the query<br />\n";
				$this->error_message = urldecode($this->messages[57]);
				return false;
			}
		}
		return true;
	}
	
	public static function getImgsData ($images_captured, $maxWidth, $maxHeight)
	{
		$db = DataAccess::getInstance();
		$ids = array();
		foreach ($images_captured as $key => $value) {
			$ids[$key] = $value['id'];
		}
		if (count($ids) == 0) {
			//nothing to return
			return array();
		}
		$all = $db->GetAll("SELECT * FROM ".self::extraimages_table." WHERE `image_id` IN ( ".implode(', ',$ids)." )
			ORDER BY `display_order`");
		$return = array();
		$map = array_flip($ids);
		foreach ($all as $row) {
			
			if(!$row['image_width'] || !$row['image_height'] || !$row['mime_type']) {
				//don't have image dimensions -- try to get them!
				$dims = ppExtraImage::getRemoteDims($row['image_id']);
				$row['image_width'] = $dims['width'];
				$row['image_height'] = $dims['height'];
				$row['mime_type'] = $dims['mime'];
			}
			
			$w = $row['image_width'];
			$h = $row['image_height'];
			$row['resized'] = 0;
			if ($w > $maxWidth) {
				//re-size w & h by proportion
				//w1/h1 = w2/h2
				//h2 = (w2 * h1)/w1
				$h = ceil(($maxWidth * $h) / $w);
				$w = $maxWidth;
				$row['resized'] = 1;
			}
			if ($h > $maxHeight) {
				//re-size w & h by proportion
				//w1/h1 = w2/h2
				//w2 = (w1*h2)/h1
				$w = ceil(($w * $maxHeight) / $h);
				$h = $maxHeight;
				$row['resized'] = 1;
			}
			$row['w'] = $w;
			$row['h'] = $h;
			if ($row['thumb_url']) {
				$url = $row['thumb_url'];
			} else {
				$url = $row['image_url'];
			}
			//if in admin, fix URL
			if (defined('IN_ADMIN')) $url = '../'.$url;
			$row['tag'] = ppExtraImage::display_image( $url, $w, $h, $row['mime_type']);
			$return[$map[$row['image_id']]] = $row;
		}
		return $return;
	}
	
	/**
	 * Optional.
	 * Used: in geoCart
	 * 
	 * This is used to display what the action is if this order item is the main type.  It should return
	 * something like "adding new listing" or "editing images".
	 * 
	 * @return string
	 */
	public static function getActionName ($vars)
	{
		//give it to parent to take care of
		$cart = geoCart::getInstance();
		$parent = $cart->item->getParent();
		return geoOrderItem::callDisplay('getActionName',$vars,'',$parent->getType());
	}

	public static function geoCart_other_detailsLabel ()
	{
		//TODO: implement or remove...
		
		return "Banner Ads";
	}

	/*
	* Adapted from geoImage:remove()
	*/
	public static function deleteImage ($imageId)
	{
		$imageId = (int)$imageId;
		if (!$imageId) {
			//invalid ID
			return false;
		}
		geoAddon::triggerUpdate('notify_image_remove', $imageId);
		$db = DataAccess::getInstance();
		$sql = "SELECT * FROM petsplease_classifieds_extraimages_urls WHERE `image_id`=?";
		$imgData = $db->GetRow($sql, array($imageId));
		if (!$imgData) {
			//either sql error or no image found
			return false;
		}
		if ($imgData['full_filename']) {
			unlink($imgData['file_path'].$imgData['full_filename']);
		}
		if ($imgData['thumb_filename']) {
			unlink($imgData['file_path'].$imgData['thumb_filename']);
		}
		$sql = "DELETE FROM petsplease_classifieds_extraimages_urls WHERE `image_id` = ?";
		$result = $db->Execute($sql, array($imageId));
		
		if (!$result) {
			//$cart->site->body .=$sql."<br />\n";
			return false;
		}
		return true;
	}
}
