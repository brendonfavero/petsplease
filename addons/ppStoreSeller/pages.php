<?php
class addon_ppStoreSeller_pages extends addon_ppStoreSeller_info
{
	const SHOP_CATEGORY = 412;

	// private $paypal_api_endpoint_pay = "http://www.google.com";
	private $paypal_api_endpoint = "https://svcs.sandbox.paypal.com/";
	// private $paypal_api_endpoint_pay = "https://svcs.sandbox.paypal.com/AdaptivePayments/Pay";
	private $paypal_payment_url = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_ap-payment";

	private $paypal_api_caller_email = "chris+merchant@ardex.com.au";

	private $paypal_api_username = "chris+merchant_api1.ardex.com.au";
	private $paypal_api_password = "1381449150";
	private $paypal_api_signature = "AFcWxV21C7fd0v3bYYYRCpSSRl31A5sNxRo0m.YXuBMy5XrIgGIiL0iW";
	private $paypal_api_applicationid = "APP-80W284485P519543T"; // this is the sandbox application id

	private $paypal_api_returnbaseurl = "http://54.252.238.130/index.php";
	private $paypal_api_cancelbaseurl = "http://54.252.238.130/index.php";
	private $paypal_api_ipnnotifyurl = "http://54.252.238.130/index.php?a=ap&addon=ppStoreSeller&page=ipnNotify";
	
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
		$payKey = $this->api_getPayKey($vendorPaypal, $total, $listing_id);

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
		// senderOptions
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