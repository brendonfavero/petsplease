<?php
class addon_ppStoreSeller_pages extends addon_ppStoreSeller_info
{
	const SHOP_CATEGORY = 412;

	private $paypal_api_endpoint = "https://svcs.paypal.com/"; //"https://svcs.sandbox.paypal.com/"; // - Live
	private $paypal_payment_url = "https://www.paypal.com/cgi-bin/webscr?cmd=_ap-payment"; //"https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment"; // - Live
	private $paypal_ipn_postback_url = "https://www.paypal.com/cgi-bin/webscr"; // "https://www.sandbox.paypal.com/cgi-bin/webscr"; // - Live

	private $paypal_api_username = "natasha_api1.petsplease.com.au";
	private $paypal_api_password = "Z3PQMDKG3F688FCP";
	private $paypal_api_signature = "AiPC9BjkCyDFQXbSkoZcgqH3hpacA.lnuVBb7kSxe-Vn9U6iIuhsvBs5";
	private $paypal_api_applicationid = "APP-6A514625UE341384J"; // this is the sandbox application id

	private $success_url = "?a=ap&addon=ppStoreSeller&page=success";
	private $paypal_api_cancelbaseurl = "?a=ap&addon=ppStoreSeller&page=merchantCart";
	private $paypal_api_ipnnotifyurl = "?a=ap&addon=ppStoreSeller&page=ipnNotify";
	
	public function merchantCart() {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		// need to be logged in
		$user_id = geoSession::getInstance()->getUserId();
		if ($user_id == 0) {
			header("Location: /?a=10&login_trackback=1");
			exit;
		}

		$util = geoAddon::getInstance()->getUtil($this->name);
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

				// make sure this isnt the admin
				if ($user_id == 1) {
					$errors[] = 4;
					$doinsert = false;
				}

				// should verify that listing is product and is connected to shop
				if (!$util->listingIsValidStoreProduct($listing_id)) {
					$errors[] = 1;
					$doinsert = false;
				}

				// make sure they aren't trying to put they're own product into the cart
				$seller = geoListing::getListing($listing_id)->seller;
				if ($seller == $user_id) {
					$errors[] = 2;
					$doinsert = false;
				}

				// make sure the product has enough quantity for this
				$listing = geoListing::getListing($listing_id);
				$listing_qty = $listing->optional_field_2;

				if ($qty > $listing_qty) {
					$errors[] = 3;
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
			elseif ($action == "movetofavourites") {
				$listing_id = $_REQUEST['b'];

				if ($listing_id > 0) {
					// Delete from cart...
					$sql = "DELETE FROM petsplease_merchant_cart WHERE user_id = ? AND listing_id = ?";
					$db->Execute($sql, array($user_id, $listing_id));

					// ...then add to favourites
					header('Location: ?a=20&b=' . $listing_id);
					exit;
				}
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
			2 => "ERROR: Can't add your own product to the cart",
			3 => "ERROR: You are trying to add more quantity to the cart than the product has available",
			4 => "ERROR: the admin cannot purchase products"
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
				$vendor_info['shop_listing'] = $util->getUserStoreListing($seller)->toArray();
				$vendor_info['total_price'] = 0;
				// fill in needed info about vendor here

				$data[$seller] = $vendor_info;
			}


			$listing = geoListing::getListing($cart_item['listing_id']);
			$listingdata = $listing->toArray();

			$listing_price_total = $cart_item['qty'] * ($listingdata['price']);
			$listingdata['total_price'] = geoString::displayPrice($listing_price_total);			

			$listingdata['qtyavailable'] = $listingdata['optional_field_2'];
			$listingdata['cartqty'] = $cart_item['qty'];
			$listingdata['image_thumbnail'] = geoImage::getInstance()->display_thumbnail($listing->id);
            
            if ($listingdata['optional_field_20'] == 91234.56) {
                $listingdata['optional_field_20'] = 0;    
            }
            
			$listingdata['price'] = geoString::displayPrice($listingdata['price']);
			$data[$seller]['shipping_price'] += $listingdata['optional_field_20'];            
            
            $storeQuestions = geoListing::getExtraQuestions($vendor_info['shop_listing']['id']);
            $flatShipping = $vendor_info['shop_listing']['optional_field_19'];
            
            if (isset($flatShipping) && $flatShipping > 0) {
                $shippingTotal = $flatShipping;
                $data[$seller]['total_shipping_display'] = geoString::displayPrice($flatShipping);
            }
            else {
                $shippingTotal = $data[$seller]['shipping_price'];
                $data[$seller]['total_shipping_display'] = geoString::displayPrice($data[$seller]['shipping_price']);
            }
            
            $data[$seller]['total_price'] += $listing_price_total;
            $data[$seller]['total_price_display'] = geoString::displayPrice($data[$seller]['total_price'] + $shippingTotal );

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

		if (!empty($_REQUEST['payment_type'])) {
			// Pull in form data
			$fields = array();
			$fields['billing'] = array('firstname', 'lastname', 'address', 'address2', 'city', 'country', 'state', 'zip', 'phone', 'email');
			$fields['shipping'] = array('copy_billing', 'firstname', 'lastname', 'address', 'address2', 'city', 'country', 'state', 'zip');
			$fields[] = 'additional_info';
			$fields[] = 'payment_type';

			$fielddata = $this->getDataFromFields($fields, $_REQUEST);

			// Validate
			$fields_validate = array();
			$fields_validate['billing'] = array('firstname', 'lastname', 'address', 'city', 'country', 'state', 'zip', 'email');
			if ($fielddata['shipping']['copy_billing'] != "1") {
				// Since shipping fields are subset of billing, don't need to validate if simply copying
				$fields_validate['shipping'] = array('firstname', 'lastname', 'address', 'city', 'country', 'state', 'zip');
			}

			$invalidFields = $this->validateData($fields_validate, $fielddata);

			if (count($invalidFields) == 0) {
				// Validation successful, process the checkout

				// Expand abbreviated billing country/state to full
				$billingCountryID = geoRegion::getRegionIdFromAbbreviation(urlencode($fielddata['billing']['country']));
				$billingStateID = geoRegion::getRegionIdFromAbbreviation(urlencode($fielddata['billing']['state']));
				$billingCountryInfo = geoRegion::getRegionInfo($billingCountryID);
				$fielddata['billing']['countryCode'] = $billingCountryInfo['billing_abbreviation'];
				$fielddata['billing']['country'] = geoRegion::getNameForRegion($billingCountryID);
				$fielddata['billing']['state'] = geoRegion::getNameForRegion($billingStateID);

				// Shipping address - copy billing
				if ($fielddata['shipping']['copy_billing'] == "1") {
					$fieldsToCopy = array('firstname', 'lastname', 'address', 'address2', 'city', 'country', 'state', 'zip', 'countryCode');
					foreach ($fieldsToCopy as $fieldToCopy)
						$fielddata['shipping'][$fieldToCopy] = $fielddata['billing'][$fieldToCopy];
				}
				else {
					// Do same as before for shipping
					$shippingCountryID = geoRegion::getRegionIdFromAbbreviation(urlencode($fielddata['shipping']['country']));
					$shippingStateID = geoRegion::getRegionIdFromAbbreviation(urlencode($fielddata['shipping']['state']));
					$shippingCountryInfo = geoRegion::getRegionInfo($shippingCountryID);
					$fielddata['shipping']['countryCode'] = $shippingCountryInfo['billing_abbreviation'];
					$fielddata['shipping']['country'] = geoRegion::getNameForRegion($shippingCountryID);
					$fielddata['shipping']['state'] = geoRegion::getNameForRegion($shippingStateID);
				}

				$this->processCheckout($fielddata, $vendor_id);
			}
		}

		if (!$fielddata) {
			// Fill out default form
			$user = geoUser::getUser($user_id);
			$fielddata = array();
			$fielddata['billing'] = array(
				'firstname' => $user->firstname,
				'lastname' => $user->lastname,
				'address' => $user->address,
				'address2' => $user->address_2,
				'city' => $user->city,
				'country' => $user->country,
				'state' => $user->state,
				'zip' => $user->zip,
				'phone' => $user->phone,
				'email' => $user->email
			);
			$fielddata['shipping']['copy_billing'] = "1";
			$fielddata['payment_type'] = "paypal";
		}

		// Need to collect shipping info + payment method
		// need to get user info so we can pre-populate shipping fields
		$view = geoView::getInstance();

		if ($invalidFields) {
			$view->setBodyVar('invalidfields', $invalidFields);
		}

		// Billing address
		$billing_location[1] = geoRegion::getRegionIdFromAbbreviation(urlencode($fielddata['billing']['country']));
		$billing_location[2] = geoRegion::getRegionIdFromAbbreviation(urlencode($fielddata['billing']['state']));
		$regions = geoRegion::billingRegionSelector('billing',$billing_location);
		$view->setBodyVar('billingCountries', $regions['countries']);
		$view->setBodyVar('billingStates', $regions['states']); 

		// Shipping address
		$shipping_location[1] = geoRegion::getRegionIdFromAbbreviation(urlencode($fielddata['shipping']['country']));
		$shipping_location[2] = geoRegion::getRegionIdFromAbbreviation(urlencode($fielddata['shipping']['state']));
		$regions2 = geoRegion::billingRegionSelector('shipping',$shipping_location);
		$view->setBodyVar('shippingCountries', $regions2['countries']);
		$view->setBodyVar('shippingStates', $regions2['states']); 

		// Get summary information about listings to purchase
		$sql = "SELECT mcart.listing_id, mcart.qty, mcart.time_added, c.seller 
				FROM petsplease_merchant_cart mcart JOIN geodesic_classifieds c ON mcart.listing_id = c.id 
				WHERE user_id = ? AND vendor_id = ? ORDER BY c.seller ASC, mcart.time_added DESC";
		$cart_items = $db->GetAll($sql, array($user_id, $vendor_id));

		$util = geoAddon::getInstance()->getUtil($this->name);

		$data = array();
		$data['shop_listing'] = $util->getUserStoreListing($vendor_id)->toArray();
		if ($data['shop_listing']['payment_options'] != "") {
			$data['shop_listing']['payment_options'] = explode("||", $data['shop_listing']['payment_options']);
		}
		
		$data['total_price'] = 0;
        $data['shipping_price'] = 0;
		foreach ($cart_items as $cart_item) {
		    
            $seller = $cart_item['seller'];
            $vendor_info['shop_listing'] = $util->getUserStoreListing($seller)->toArray();           
            
			$listing = geoListing::getListing($cart_item['listing_id']);
			$listingdata = $listing->toArray();
			
			if ($listingdata['optional_field_20'] == 91234.56) {
                $listingdata['optional_field_20'] = 0;    
            }
            
            $flatShipping = $vendor_info['shop_listing']['optional_field_19'];   
            
            if (isset($flatShipping) && $flatShipping > 0) {
                $shipping_total = $flatShipping;
                $data['shipping_price'] = geoString::displayPrice($flatShipping);
            }
            else {
                $shipping_total += $listingdata['optional_field_20'];
                $data['shipping_price'] = geoString::displayPrice($shipping_total);   
            }                         

			$listing_price_total = ($cart_item['qty'] * $listingdata['price']);
			$listingdata['total_price'] = geoString::displayPrice($listing_price_total);

			$data['total_price'] += $listing_price_total;
			$data['total_price_display'] = geoString::displayPrice($data['total_price'] + $shipping_total);			

			$listingdata['cartqty'] = $cart_item['qty'];
			$listingdata['image_thumbnail'] = geoImage::getInstance()->display_thumbnail($listing->id);

			$listingdata['subtotal'] = geoString::displayPrice($listing_price_total);
			$listingdata['price'] = geoString::displayPrice($listingdata['price']);                                 

			$data['listings'][] = $listingdata;
		}       
        
        $view->setBodyVar('cart_items', $data);
		$view->setBodyVar('order', $data); 
		$view->setBodyVar('fielddata', $fielddata);

		$view->setBodyTpl('shipping.tpl','ppStoreSeller');
	}

	// Paramters: fields is the name of the fields passed in, $userParms holds the collection to be inspected (e.g. $_REQUEST or some subset)
	private function getDataFromFields($fields, $userParms) {
		$content = array();

		foreach ($fields as $key => $fieldrow) {
			if (is_array($fieldrow)) {
				$content[$key] = $this->getDataFromFields($fieldrow, $userParms[$key]);
			}
			else {
				$content[$fieldrow] = $userParms[$fieldrow];
			}
		}

		return $content;
	}


	// Paramters: fields is the name of the fields passed in, $userParms holds the collection to be inspected (e.g. $_REQUEST or some subset)
	private function validateData($fields, $userParms) {
		$invalid = array();

		foreach ($fields as $key => $fieldrow) {
			if (is_array($fieldrow)) {
				$sub_invalid = $this->validateData($fieldrow, $userParms[$key]);
				if (!empty($sub_invalid)) $invalid[] = $sub_invalid;
			}
			else {
				if (empty($userParms[$fieldrow])) $invalid[] = $fieldrow;
			}
		}

		return $invalid;
	}

	private function processCheckout($fielddata, $vendor_id) {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$user_id = geoSession::getInstance()->getUserId();
		$user = geoUser::getUser($user_id);


		$sql = "SELECT mcart.listing_id, mcart.qty, mcart.time_added, c.seller 
				FROM petsplease_merchant_cart mcart JOIN geodesic_classifieds c ON mcart.listing_id = c.id 
				WHERE user_id = ? AND vendor_id = ? ORDER BY c.seller ASC, mcart.time_added DESC";
		$cart_items = $db->GetAll($sql, array($user_id, $vendor_id));

		$util = geoAddon::getInstance()->getUtil($this->name);

		$data = array();
		$shop_listing = $util->getUserStoreListing($vendor_id)->toArray();
		$total_price = 0;
		$total_shipping = 0;
		$grand_total = 0;
		foreach ($cart_items as $cart_item) {
		    $seller = $cart_item['seller'];
            $vendor_info['shop_listing'] = $util->getUserStoreListing($seller)->toArray();
                
			$listing = geoListing::getListing($cart_item['listing_id']);
			$listingdata = $listing->toArray();

			$listingdata['cartqty'] = $cart_item['qty'];
			
			if ($listingdata['optional_field_20'] == 91234.56) {
                $listingdata['optional_field_20'] = 0;    
            } 

			$listingdata['price'] = $listingdata['price'];
			$listingdata['shipping'] = $listingdata['optional_field_20'];
            
            $flatShipping = $vendor_info['shop_listing']['optional_field_19'];
            
            if (isset($flatShipping) && $flatShipping > 0) {
                $shippingTotal = $flatShipping;
                $total_shipping = $flatShipping;
            }
            else {
                $shippingTotal = $listingdata['cartqty'] * $listingdata['shipping'];
                $total_shipping += $shippingTotal;
            }                     
            
			$listing_total_price = $listingdata['cartqty'] * $listingdata['price'];
			$listing_sub_total = $listing_total_price;

			$total_price += $listing_total_price;
			
			$grand_total += $listing_sub_total;		

			$listingdata['price_total'] = $listing_total_price;
			$listingdata['subtotal'] = $listing_sub_total;

			$listingdata['subtotal_display'] = geoString::displayPrice($listingdata['subtotal']);
			$listingdata['price_display'] = geoString::displayPrice($listingdata['price']);
			$listingdata['shipping_display'] = geoString::displayPrice($shippingTotal);

			$listings[] = $listingdata;
		}


		// Create the order
		$sql = "INSERT INTO petsplease_merchant_order (buyer, seller, `date`, total_price) VALUES (?, ?, ?, ?)";
		$db->Execute($sql, array($user_id, $vendor_id, time(), $grand_total + $total_shipping));
		$orderid = $db->Insert_Id();

		// Now create the order items
		foreach ($listings as $listing) {
			$sql = "INSERT INTO petsplease_merchant_orderitem (order_id, listing_id, qty, unit_price, unit_shipping, price_total) 
					VALUES (?, ?, ?, ?, ?, ?)";
			$db->Execute($sql, array($orderid, $listing['id'], $listing['cartqty'], $listing['price'], $listing['shipping'], $listing['subtotal']));
		}

		// Store our form info
		$sql = "INSERT INTO petsplease_merchant_order_registry (order_id, data) VALUES (?, ?)";
		$db->Execute($sql, array($orderid, json_encode($fielddata)));

		if ($fielddata['payment_type'] == "paypal") {
			// If paying with Paypal, create the payment with the Paypal API and send them through the process of actually paying

			// from store listing get paypal acc
			$storeQuestions = geoListing::getExtraQuestions($shop_listing['id']);
			$vendorPaypal = $storeQuestions[192]['value'];

			// replace spaces with +'s since they get lost when being pulled out of the db
			$vendorPaypal = str_replace(' ', '+', $vendorPaypal);

			// Create payment
			$payKey = $this->api_createPayment($vendorPaypal, $grand_total + $total_shipping, $orderid);

			// Now add extra info to payment
			$params = array();
			$params['senderOptions']['shippingAddress'] = array(
				'addresseeName' => $fielddata['shipping']['firstname'] . ' ' . $fielddata['shipping']['lastname'],
				'street1' => $fielddata['shipping']['address'],
				'city' => $fielddata['shipping']['city'],
				'state' => $fielddata['shipping']['state'],
				'zip' => $fielddata['shipping']['zip'],
				'country' => $fielddata['shipping']['countryCode']
				// 'phone' => !!NEED:'type', 'countryCode', 'number' //$fielddata['shipping']['phone']
			);

			if ($fielddata['shipping']['address2'] != "")
				$params['senderOptions']['shippingAddress']['street2'] = $fielddata['shipping']['address2'];

			$params['displayOptions']['businessName'] = "Pets Please";
            $params['receiverOptions'][0]['customId'] = $orderid;
			$params['receiverOptions'][0]['description'] = $fielddata['additional_info'];
			$params['receiverOptions'][0]['receiver']['email'] = $vendorPaypal;
			$params['receiverOptions'][0]['invoiceData']['totalShipping'] = $total_shipping;	
			$params['receiverOptions'][0]['invoiceData']['totalTax'] = 0.0;			
			foreach ($listings as $i => $listing) {
				$params['receiverOptions'][0]['invoiceData']['item'][] = array(
					'name' => urldecode($listing['title']),
					'identifier' => $listing['id'],
					'price' => $listing['subtotal'],
					'itemPrice' => $listing['price'],
					'itemCount' => $listing['cartqty']
				);
			}

			$res = $this->api_setPaymentInformation($payKey, &$params);
			echo '<pre>' . print_r($params, true) . '</pre>';
			// exit;

			// if we're here update to order to include the paykey
			$sql = "UPDATE petsplease_merchant_order SET paypal_status=?, paypal_paykey=? WHERE order_id=?";
			
			$db->Execute($sql, array("started", $payKey, $orderid));            
            if ($payKey) {
                
            }
			// if successful redirect user to paypal to complete payment
			header('Location: ' . $this->paypal_payment_url . '&paykey=' . $payKey);         
                
			exit;

		}
		else {
			// Otherwise simply get the order details and email it through to the merchant
			$tpl = new geoTemplate('addon', 'ppStoreSeller');
			$mailVars['listings'] = $listings;
			$mailVars['grand_total'] = $grand_total;
			$mailVars['grand_total_display'] = geoString::displayPrice($grand_total);
			$mailVars['fielddata'] = $fielddata;
			$mailVars['shoplisting'] = $shop_listing;
			$mailVars['baseUrl'] = $db->get_site_setting('classifieds_url');
			$tpl->assign($mailVars);
			$email_message = $tpl->fetch('emails/other_payment_order_received.tpl');
			geoEmail::sendMail(geoString::fromDB($shop_listing['email']), "Pets Please - Shop Order Received", $email_message, 
				$db->get_site_setting('site_email'), 0, 0, 'text/html');

			// For non-paypal payments we consider this point for our part of the order chain to be finished, so subtract item quantities
			foreach ($listings as $listing) {
				$this->subtractQuantityFromProduct($listing['id'], $listing['cartqty']);
			}

			// Send to success page
			$redirect_url = $db->get_site_setting('classifieds_url') . $this->success_url . '&o=' . $orderid;
			header('Location: ' . $redirect_url);
		}
	}

	/* 
		Calls the Paypal api to begin the payment process
		Returns payKey (identifier for this payment) 
	*/
	private function api_createPayment($vendorPaypal, $amount, $order_id) {
		$site_baseurl = DataAccess::getInstance()->get_site_setting('classifieds_url');

		$params = array();
		$params["actionType"] = "CREATE";
		$params["currencyCode"] = "AUD";
		$params["returnUrl"] = $site_baseurl . $this->success_url . '&o=' . $order_id;
		$params["cancelUrl"] = $site_baseurl . $this->paypal_api_cancelbaseurl;
		$params["ipnNotificationUrl"] = $site_baseurl .  $this->paypal_api_ipnnotifyurl;
		$params["receiverList"]["receiver"][0]["email"] = $vendorPaypal;
		$params["receiverList"]["receiver"][0]["amount"] = $amount; 
		$params["requestEnvelope"]["errorLanguage"] = "en_US";
		$params["requestEnvelope"]["detailLevel"] = "ReturnAll";

		$api_result = $this->api_callPaypalClassicAPI("AdaptivePayments/Pay", $params);
        
        
		return $api_result->payKey;
	}

	private function api_setPaymentInformation($payKey, $params) {
		$params['payKey'] = $payKey;
		$params["requestEnvelope"]["errorLanguage"] = "en_US";
		$params["requestEnvelope"]["detailLevel"] = "ReturnAll";

		return $this->api_callPaypalClassicAPI("AdaptivePayments/SetPaymentOptions", $params);
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
			"X-PAYPAL-APPLICATION-ID: " . $this->paypal_api_applicationid,
			"X-PAYPAL-REQUEST-DATA-FORMAT: JSON",
			"X-PAYPAL-RESPONSE-DATA-FORMAT: JSON"
		);	

		$headers_cmd = "";
		foreach ($headers as $header) {
			$headers_cmd .= "-H \"" . $header . "\" ";
		}

		$output = array();
		$api_cmd = "curl -s " . $headers_cmd . " " . $this->paypal_api_endpoint . $call . " -d \"" . $payload . "\"";
        
        geoEmail::sendMail('brendon@ardex.com.au', 'test', $api_cmd, 0);
		exec($api_cmd, $output);
                
		$result = json_decode($output[0]);
		return $result;
	}

	private function subtractQuantityFromProduct($listing_id, $qty) {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$sql = "UPDATE geodesic_classifieds SET optional_field_2 = optional_field_2 - ? WHERE id = ?";
		$db->Execute($sql, array($qty, $listing_id));

		// Check new value of qty and alert if out of stock
		$sql = "SELECT optional_field_2 stock, title, seller FROM geodesic_classifieds WHERE id = ?";
		$listing = $db->GetRow($sql, array($listing_id));

		if ($listing['stock'] < 1) {
		    $sql = "UPDATE geodesic_classifieds SET live = 0 WHERE id = ?";
            $db->Execute($sql, array($listing_id));
            
			$util = geoAddon::getInstance()->getUtil($this->name);
			$shop_listing = $util->getUserStoreListing($listing['seller'])->toArray();

			$tpl = new geoTemplate('addon', 'ppStoreSeller');
			$mailVars['shoplisting_id'] = $shop_listing['id'];
			$mailVars['baseUrl'] = $db->get_site_setting('classifieds_url');
			$mailVars['listing_id'] = $listing_id;
			$mailVars['listing_title'] = $listing['title'];
			$tpl->assign($mailVars);
			$email_message = $tpl->fetch('emails/out_of_stock.tpl');
			geoEmail::sendMail(geoString::fromDB($shop_listing['email']), "Pets Please - Shop Order Received", $email_message, 
				$db->get_site_setting('site_email'), 0, 0, 'text/html');
			geoEmail::sendMail('brendon@ardex.com.au', "Pets Please - Shop Order Received", $email_message, 
                $db->get_site_setting('site_email'), 0, 0, 'text/html');
		}
	}

	public function ipnNotify() {
		// This page will be called by Paypal to notify us of any changes to payments (e.g. when a payment is completed)

		// Adapting code from: https://developer.paypal.com/webapps/developer/docs/classic/ipn/ht_ipn/
		// Step 1: read post data
		geoEmail::sendMail('brendon@ardex.com.au', 'IPN-Notify', $api_cmd, 0);
		$raw_post_data = file_get_contents('php://input');
		$raw_post_array = explode('&', $raw_post_data);
		
		if (empty($raw_post_data)) {
			exit;
		}

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
		$output = array();
		$api_cmd = "curl -s -H \"Connection: Close\" " . $this->paypal_ipn_postback_url . " -d \"" . $req . "\"";
        geoEmail::sendMail('brendon@ardex.com.au', 'IPN api cmd', $api_cmd, 0);
		exec($api_cmd, $output);
		$res = $output[0];

		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		if (strcmp ($res, "VERIFIED") == 0) {
			// The IPN is verified, process it
			$payment_status = $_POST['status'];
			$pay_key = $_POST['pay_key'];

			// $notify .= "raw output: " . $req . "\r\n";
			geoEmail::sendMail ("brendon@ardex.com.au","IPN Delivered Payment notification", '<pre>' . print_r($notify, true) . '</pre>');

			if (strcasecmp($payment_status, "completed") == 0) {
				$sql = "SELECT * FROM petsplease_merchant_order WHERE paypal_paykey=? AND paypal_status <> 'paid'";
				$order = $db->GetRow($sql, array($pay_key));

				// if ($payment_amount != $order['total_price']) {
				// 	// The amount they paid didn't match up with how much they were charged

				// }

				$sql = "UPDATE petsplease_merchant_order SET paypal_status = 'paid' WHERE paypal_paykey=?";
				$db->Execute($sql, array($pay_key));

				$sql = "SELECT data FROM petsplease_merchant_order_registry WHERE order_id=?";
				$result = $db->GetOne($sql, array($order['order_id']));

				$fielddata = json_decode($result, true);


				$sql = "SELECT listing_id, qty, unit_price, unit_shipping, price_total FROM petsplease_merchant_orderitem oi WHERE order_id = ?";
				$order_items = $db->GetAll($sql, array($order['order_id']));

				$util = geoAddon::getInstance()->getUtil($this->name);

				$data = array();
				error_log($order['seller']);
				$shop_listing = $util->getUserStoreListing($order['seller'])->toArray();
				error_log(print_r($shop_listing, true));
				$total_price = 0;
				$total_shipping = 0;
				$grand_total = 0;
				foreach ($order_items as $order_item) {				    
                    
					$listing = geoListing::getListing($order_item['listing_id']);
					$listingdata = $listing->toArray();
                    
					$listingdata['cartqty'] = $order_item['qty'];

					$listingdata['price'] = $order_item['unit_price'];
					$listingdata['shipping'] = $order_item['unit_shipping'];

					$listing_total_price = $listingdata['cartqty'] * $listingdata['price'];
					$listing_total_shipping = $listingdata['cartqty'] * $listingdata['shipping'];
					$listing_sub_total = $listing_total_price;

					$total_price += $listing_total_price;
					$grand_total += $listing_sub_total;
                    
                    $flatShipping = $shop_listing['optional_field_19'];
            
                    if (isset($flatShipping) && $flatShipping > 0) {
                        $shippingTotal = $flatShipping;
                        $total_shipping = $flatShipping;
                    }
                    else {
                        $shippingTotal = $listingdata['cartqty'] * $listingdata['shipping'];
                        $total_shipping += $shippingTotal;
                    }   

					$listingdata['price_total'] = $listing_total_price;
					$listingdata['shipping_total'] = $listing_total_shipping; 
					$listingdata['subtotal'] = $listing_sub_total;

					$listingdata['subtotal_display'] = geoString::displayPrice($listingdata['subtotal']);
					$listingdata['price_display'] = geoString::displayPrice($listingdata['price']);
					$listingdata['shipping_display'] = geoString::displayPrice($listingdata['shipping']);

					$listings[] = $listingdata;
				}

				$tpl = new geoTemplate('addon', 'ppStoreSeller');
				$mailVars['listings'] = $listings;
				$mailVars['grand_total'] = $grand_total;
				$mailVars['grand_total_display'] = geoString::displayPrice($grand_total + $total_shipping);
                $mailVars['shipping_total'] = $total_shipping;
                $mailVars['shipping_display'] = geoString::displayPrice($total_shipping);
				$mailVars['fielddata'] = $fielddata;
				$mailVars['shoplisting'] = $shop_listing;
				$mailVars['baseUrl'] = $db->get_site_setting('classifieds_url');
				$mailVars['paypalPaid'] = true;
				$tpl->assign($mailVars);
				$email_message = $tpl->fetch('emails/other_payment_order_received.tpl');
				geoEmail::sendMail(geoString::fromDB($shop_listing['email']), "Pets Please - Shop Order Received", $email_message, 
					$db->get_site_setting('site_email'), 0, 0, 'text/html');
                geoEmail::sendMail('brendon@ardex.com.au', "Pets Please - Shop Order Received", $email_message, 
                    $db->get_site_setting('site_email'), 0, 0, 'text/html');
                    
                $email_message = $tpl->fetch('emails/other_payment_order_sent.tpl');
                geoEmail::sendMail(geoString::fromDB($fielddata['billing']['email']), "Pets Please - Shop Order Complete", $email_message, 
                    $db->get_site_setting('site_email'), 0, 0, 'text/html');
				// Subtract quantites now that order is done
				foreach ($listings as $listing) {
					$this->subtractQuantityFromProduct($listing['id'], $listing['cartqty']);
				}
			}
		} else if (strcmp ($res, "INVALID") == 0) {
			// IPN invalid, log for manual investigation
			$notify = "api cmd: " . $api_cmd . "\r\n";
			$notify .= "api cmd result: " . $res . "\r\n";
			$notify .= "raw input: " . $raw_post_data . "\r\n";
			$notify .= "raw output: " . $req . "\r\n";

			// TODO: Change this to send to specific petsplease error mailbox
			geoEmail::sendMail ("brendon@ardex.com.au","IPN Delivered INVALID Payment notification", $notify);
		}

		geoView::getInstance()->setRendered(true); // don't bother loading templates
	}

	public function success() {
		$db = true;
        require (GEO_BASE_DIR."get_common_vars.php");

        $orderid = $_REQUEST['o'];
        $user_id = geoSession::getInstance()->getUserId();

        // If we reach this page then we can clear the relevant items out of the cart
        $sql = "SELECT listing_id FROM petsplease_merchant_orderitem WHERE order_id=?";
        $listing_ids = $db->GetCol($sql, array($orderid));

        if (!empty($listing_ids)) {
            $sql = "DELETE FROM petsplease_merchant_cart WHERE user_id=? AND listing_id IN (" . implode(',', $listing_ids) . ")";
            $db->Execute($sql, array($user_id));
        }
        
        $view = geoView::getInstance();
        $view->setBodyTpl('success.tpl', $this->name);
	}
}