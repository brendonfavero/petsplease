<?php
class addon_ppStoreSeller_pages extends addon_ppStoreSeller_info
{
	const SHOP_CATEGORY = 412;

	// private $paypal_api_endpoint_pay = "http://www.google.com";
	private $paypal_api_endpoint = "https://svcs.sandbox.paypal.com/";
	private $paypal_payment_url = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment";

	private $paypal_api_caller_email = "chris+merchant@ardex.com.au";

	private $paypal_api_username = "chris+merchant_api1.ardex.com.au";
	private $paypal_api_password = "1381449150";
	private $paypal_api_signature = "AFcWxV21C7fd0v3bYYYRCpSSRl31A5sNxRo0m.YXuBMy5XrIgGIiL0iW";
	private $paypal_api_applicationid = "APP-80W284485P519543T"; // this is the sandbox application id

	private $paypal_api_returnbaseurl = "http://54.252.238.130/index.php";
	private $paypal_api_cancelbaseurl = "http://54.252.238.130/index.php";
	private $paypal_api_ipnnotifyurl = "http://54.252.238.130/index.php?a=ap&addon=ppStoreSeller&page=ipnNotify";
	
	public function merchantCart() {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		// need to be logged in
		$user_id = geoSession::getInstance()->getUserId();
		if ($user_id == 0) {
			header("Location: /?a=10");
			exit;
		}

		$ppStoreHelperUtil = geoAddon::getInstance()->getUtil('ppStoreHelper');
		$ppListingDisplayUtil = geoAddon::getInstance()->getUtil('ppListingDisplay');

		$action = $_REQUEST['action'];
		if ($action != "") {
			$errors = array();
			$redirectToCartHome = true;

			if ($action == "additem") {
				$listing_id = $_REQUEST['b'];
				$qty = $_REQUEST['qty'];

				if (!is_numeric($qty) || $qty < 1) { // Invalid quantity
					$qty = 1;
				}

				$doinsert = true;

				// should verify that listing is product and is connected to shop
				if (!$ppStoreHelperUtil->listingIsValidStoreProduct($listing_id)) {
					$errors[] = 1;
					$doinsert = false;
				}

				// make sure they aren't trying to put they're own product into the cart
				$seller = geoListing::getListing($listing_id)->seller;
				if ($seller == $user_id) {
					$errors[] = 2;
					$doinsert = false;
				}

				// add the listing to cart
				if ($doinsert) {
					$sql = "INSERT INTO petsplease_merchant_cart (user_id, listing_id, vendor_id, qty, time_added) VALUES (?,?,?,?,?)
							ON DUPLICATE KEY UPDATE qty = ?";
					$db->Execute($sql, array($user_id, $listing_id, $seller, $qty, time(), $qty));
				}
			}
			elseif ($action == "removeitem") {
				$listing_id = $_REQUEST['b'];
				$vendor_id = $_REQUEST['vendor'];

				if ($listing_id > 0) {
					$sql = "DELETE FROM petsplease_merchant_cart WHERE user_id = ? AND listing_id = ?";
					$db->Execute($sql, array($user_id, $listing_id));
				}
				elseif ($vendor_id > 0) {
					$sql = "DELETE FROM petsplease_merchant_cart WHERE user_id = ? AND vendor_id = ?";
					$db->Execute($sql, array($user_id, $vendor_id));
				}
			}
			elseif ($action == "updateqty") {
				$listing_id = $_REQUEST['b'];
				$new_qty = $_REQUEST['qty'];

				// !! Do any checking to allow new qty here
				
				$sql = "UPDATE petsplease_merchant_cart SET qty = ?
						WHERE user_id = ? AND listing_id = ?";
				$db->Execute($sql, array($new_qty, $user_id, $listing_id));
			}

			if ($redirectToCartHome) {
				$redirect_loc = "?a=ap&addon=ppStoreSeller&page=merchantCart";

				if (count($errors) > 0)
					$redirect_loc .= "&msgs=" . implode(",", $errors);
				
				header("Location: " . $redirect_loc);
				exit;
			}
		}

		// Other show the contents of the cart
		$view = geoView::getInstance();

		$cart_messages = array(
			1 => "ERROR: Listing is not valid merchant product",
			2 => "ERROR: Can't add your own product to the cart"
		);

		if ($_REQUEST['msgs'] != '') {
			$message_ids = explode(",", $_REQUEST['msgs']);
			$messages = array_map(function($i) use(&$cart_messages) { return $cart_messages[$i]; }, $message_ids);
			$view->setBodyVar('msgs', $messages);
		}

		// get all products currently in cart
		$sql = "SELECT mcart.listing_id, mcart.qty, mcart.time_added, c.seller 
				FROM petsplease_merchant_cart mcart JOIN geodesic_classifieds c ON mcart.listing_id = c.id 
				WHERE user_id = ? ORDER BY c.seller ASC, mcart.time_added DESC";
		$cart_items = $db->GetAll($sql, array($user_id));

		$data = array();
		foreach ($cart_items as $cart_item) {
			$seller = $cart_item['seller'];
			if (!array_key_exists($seller, $data)) {
				$vendor_info = array();
				$vendor_info['shop_listing'] = $ppStoreHelperUtil->getUserStoreListing($seller)->toArray();
				$vendor_info['total_price'] = 0;
				// fill in needed info about vendor here

				$data[$seller] = $vendor_info;
			}


			$listing = geoListing::getListing($cart_item['listing_id']);
			$listingdata = $listing->toArray();

			$listing_price_total = $cart_item['qty'] * ($listingdata['price'] + $listingdata['optional_field_20']);
			$listingdata['total_price'] = geoString::displayPrice($listing_price_total);

			$data[$seller]['total_price'] += $listing_price_total;
			$data[$seller]['total_price_display'] = geoString::displayPrice($data[$seller]['total_price']);

			$listingdata['qtyavailable'] = $listingdata['optional_field_2'];
			$listingdata['cartqty'] = $cart_item['qty'];
			$listingdata['image_thumbnail'] = geoImage::getInstance()->display_thumbnail($listing->id);

			$listingdata['price'] = geoString::displayPrice($listingdata['price']);
			$listingdata['shipping'] = geoString::displayPrice($listingdata['optional_field_20']);

			// PRICE ? (base,shipping, total)
			// total price = qty * (base + shipping)

			$data[$seller]['listings'][] = $listingdata;
		}

		$view->setBodyVar('cart_items', $data);

		// continue shopping btn
		$lastShopVisited = $_COOKIE['laststorevisited'];
		if ($lastShopVisited > 0)
			$view->setBodyVar('laststorevisited', $lastShopVisited);

		$view->setBodyTpl('merchantCart.tpl','ppStoreSeller');
	}

	public function checkout() {
		$user_id = geoSession::getInstance()->getUserId();
		if ($user_id == 0) {
			header("Location: /?a=10");
			exit;
		}

		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$vendor_id = $_REQUEST['vendor'];

		if (!($vendor_id > 0)) {
			return "ERROR: must pass in vendor we are checking out";
		}

		if ($_REQUEST['c'] && $_REQUEST['c']['payment_type']) {
			$c = $_REQUEST['c'];

			// Billing fields
			$billing_firstname = $_REQUEST['f'];

			// CHECK FIELDS
			// 	billing fields
			// 	 firstname
			// 	 lastname
			//   address
			//   address2
			//   city
			//   country
			//   state
			//   zipcode
			//   phone
			//   email

			//  shipping fields
			//   firstname
			//   lastname
			//   address
			//   address2
			//   city
			//   country
			//   state
			//   zipcode


			// if everything checks out process the checkout
		}

		// Need to collect shipping info + payment method
		// need to get user info so we can pre-populate shipping fields
		$view = geoView::getInstance();

		// Billing address
		$regions = geoRegion::billingRegionSelector('billing',$userLocation);
		$view->setBodyVar('billingCountries', $regions['countries']);
		$view->setBodyVar('billingStates', $regions['states']); 

		// Shipping address
		$regions2 = geoRegion::billingRegionSelector('shipping',$userLocation);
		$view->setBodyVar('shippingCountries', $regions2['countries']);
		$view->setBodyVar('shippingStates', $regions2['states']); 

		// Get summary information about listings to purchase
		$sql = "SELECT mcart.listing_id, mcart.qty, mcart.time_added, c.seller 
				FROM petsplease_merchant_cart mcart JOIN geodesic_classifieds c ON mcart.listing_id = c.id 
				WHERE user_id = ? AND vendor_id = ? ORDER BY c.seller ASC, mcart.time_added DESC";
		$cart_items = $db->GetAll($sql, array($user_id, $vendor_id));

		$ppStoreHelperUtil = geoAddon::getInstance()->getUtil('ppStoreHelper');
		$ppListingDisplayUtil = geoAddon::getInstance()->getUtil('ppListingDisplay');

		$data = array();
		$data['shop_listing'] = $ppStoreHelperUtil->getUserStoreListing($vendor_id)->toArray();
		$data['total_price'] = 0;
		foreach ($cart_items as $cart_item) {
			$listing = geoListing::getListing($cart_item['listing_id']);
			$listingdata = $listing->toArray();

			$listing_price_total = $cart_item['qty'] * ($listingdata['price'] + $listingdata['optional_field_20']);
			$listingdata['total_price'] = geoString::displayPrice($listing_price_total);

			$data['total_price'] += $listing_price_total;
			$data['total_price_display'] = geoString::displayPrice($data['total_price']);

			$listingdata['cartqty'] = $cart_item['qty'];
			$listingdata['image_thumbnail'] = geoImage::getInstance()->display_thumbnail($listing->id);

			$listingdata['subtotal'] = geoString::displayPrice($listing_price_total);
			$listingdata['price'] = geoString::displayPrice($listingdata['price']);
			$listingdata['shipping'] = geoString::displayPrice($listingdata['optional_field_20']);

			$data['listings'][] = $listingdata;
		}
		$view->setBodyVar('order', $data); 

		$view->setBodyTpl('shipping.tpl','ppStoreSeller');
	}

	public function processCheckout() {

	}

	////////

	public function buyNow() {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$listing_id = $_REQUEST['b'];

		// get seller
		$listing = geoListing::getListing($listing_id);
		$seller_id = $listing->seller;

		// get sellers store listing
		$sql = "SELECT id FROM geodesic_classifieds WHERE seller = ? AND category = ? AND live = 1";
		$store_id = $db->GetOne($sql, array($seller_id, self::SHOP_CATEGORY));

		// from store listing get paypal acc
		$storeQuestions = geoListing::getExtraQuestions($store_id);
		$vendorPaypal = $storeQuestions[192]['value'];

		// replace spaces with +'s since they get lost when being pulled out of the db
		$vendorPaypal = str_replace(' ', '+', $vendorPaypal);

		// get price and shipping of item being purchased
		$price = $listing->price;
		$shipping = $listing->optional_field_20;
		$total = $price + $shipping;

		// make rest call to Paypal for payment key
		$payKey = $this->api_createPayment($vendorPaypal, $total, $listing_id);
		$this->api_setPaymentInformation($payKey);

		// if successful redirect user to paypal to complete payment
		header('Location: ' . $this->paypal_payment_url . '&paykey=' . $payKey);
		exit;
	}

	/* 
		Calls the Paypal api to begin the payment process
		Returns payKey (identifier for this payment) 
	*/
	private function api_createPayment($vendorPaypal, $amount, $listing_id) {
		$params = array();
		$params["actionType"] = "CREATE";
		$params["currencyCode"] = "AUD";
		$params["returnUrl"] = $this->paypal_api_returnbaseurl;
		$params["cancelUrl"] = $this->paypal_api_cancelbaseurl;
		$params["ipnNotificationUrl"] = $this->paypal_api_ipnnotifyurl;
		$params["receiverList"]["receiver"][0]["email"] = $vendorPaypal;
		$params["receiverList"]["receiver"][0]["amount"] = $amount; 
		$params["requestEnvelope"]["errorLanguage"] = "en_US";
		$params["requestEnvelope"]["detailLevel"] = "ReturnAll";

		$api_result = $this->api_callPaypalClassicAPI("AdaptivePayments/Pay", $params);

		return $api_result->payKey;
	}

	private function api_setPaymentInformation($payKey) {
		$params = array();
		$params['payKey'] = $payKey;
		// displayOptions
		$params['senderOptions']['shippingAddress'] = array(
			 'addresseeName' => 'Mister Misterson',
			 'street1' => '84 Pitt St',
			 'city' => 'Sydney',
			 'state' => 'NSW',
			 'zip' => '2000',
			 'country' => 'Australia',
			 'phone' => '0404 234 567'
		);
		// receiverOptions
		$params["requestEnvelope"]["errorLanguage"] = "en_US";
		$params["requestEnvelope"]["detailLevel"] = "ReturnAll";

		$api_result = $this->api_callPaypalClassicAPI("AdaptivePayments/SetPaymentsOptions", $params);
	}

	private function api_callPaypalClassicAPI($call, $data) {
		// Just a note about this function: I tried writing it using the php bindings for curl (e.g. curl_init()),
		//  but due to Amazon Linux's PHP's cUrl being compiled with NSS (not openSSL), I could not wrap my head
		//  around getting the SSL client certificates to work (since NSS for some reason comes with none, meaning
		//  you're unable to connect to any SSL secured resource). 

		$payload = addslashes(json_encode($data));

		$headers = array(
			"X-PAYPAL-SECURITY-USERID: " . $this->paypal_api_username,
			"X-PAYPAL-SECURITY-PASSWORD: " . $this->paypal_api_password,
			"X-PAYPAL-SECURITY-SIGNATURE: " . $this->paypal_api_signature,
			"X-PAYPAL-APPLICATION-ID: APP-80W284485P519543T",
			"X-PAYPAL-REQUEST-DATA-FORMAT: JSON",
			"X-PAYPAL-RESPONSE-DATA-FORMAT: JSON"
		);

		$headers_cmd = "";
		foreach ($headers as $header) {
			$headers_cmd .= "-H \"" . $header . "\" ";
		}

		$output = array();
		$api_cmd = "curl -s " . $headers_cmd . " " . $this->paypal_api_endpoint . $call . " -d \"" . $payload . "\"";
		exec($api_cmd, $output);

		$result = json_decode($output[0]);
		return $result;
	}

	public function ipnNotify() {
		// This page will be called by Paypal to notify us of any changes to payments (e.g. when a payment is completed)

		// Adapting code from: https://developer.paypal.com/webapps/developer/docs/classic/ipn/ht_ipn/
		// Step 1: read post data
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		
		$myPost = array();
		foreach ($raw_post_array as $keyval) {
			$keyval = explode ('=', $keyval);
			if (count($keyval) == 2)
				$myPost[$keyval[0]] = urldecode($keyval[1]);
		}
		
		// read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
		$req = 'cmd=_notify-validate';
		foreach ($myPost as $key => $value) {
			$value = urlencode($value);
			$req .= "&$key=$value";
		}
		
		$req = str_replace("%2F", "/", $req); // unencode forward slashes (paypal doesn't encode theirs)

		// Step 2: post the data back to paypal for verification
		// $postback_url = "https://www.paypal.com/cgi-bin/webscr";
		$postback_url = "https://www.sandbox.paypal.com/cgi-bin/webscr";

		$output = array();
		$api_cmd = "curl -s -H \"Connection: Close\" " . $postback_url . " -d \"" . $req . "\"";
		exec($api_cmd, $output);
		$res = $output[0];

		if (strcmp ($res, "VERIFIED") == 0) {
			// The IPN is verified, process it
			$item_name = $_POST['item_name'];
			$item_number = $_POST['item_number'];
			$payment_status = $_POST['payment_status'];
			$payment_amount = $_POST['mc_gross'];
			$payment_currency = $_POST['mc_currency'];
			$txn_id = $_POST['txn_id'];
			$receiver_email = $_POST['receiver_email'];
			$payer_email = $_POST['payer_email'];	

			$notify = print_r($myPost, true);	

			geoEmail::sendMail ("chris@ardex.com.au","IPN Delivered VERIFIED Payment notification",$notify);
		} else if (strcmp ($res, "INVALID") == 0) {
			// IPN invalid, log for manual investigation
			$notify = "api cmd: " . $api_cmd . "\r\n";
			$notify .= "api cmd result: " . $res . "\r\n";
			$notify .= "raw input: " . $raw_post_data . "\r\n";
			$notify .= "raw output: " . $req . "\r\n";

			geoEmail::sendMail ("chris@ardex.com.au","IPN Delivered INVALID Payment notification", $notify);
		}

		geoView::getInstance()->setRendered(true); // don't bother loading templates
	}

	public function showSuccessfulOrder() {
		// This is the page that the user should be directed to after they have made a payment.
		// This may be loaded before IPN has arrived from paypal, so we should wait until that has happened 
		//  (i.e. we have received a notification of successful payment)


	}
}