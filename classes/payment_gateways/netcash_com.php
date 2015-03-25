<?php
//payment_gateways/netcash_com.php
/**************************************************************************
Geodesic Classifieds & Auctions Platform 7.4
Copyright (c) 2001-2014 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    7.4beta2-28-g57a2d9f
## 
##################################

/**
 * This requires the geoPaymentGateway class, so include it just to be on the
 * safe side.
 */
require_once CLASSES_DIR . PHP5_DIR . 'PaymentGateway.class.php';

/**
 * This is the "developer template" payment gateway handler, a developer could use
 * this file as a starting point for creating a new payment gateway in the system.
 * 
 * @package System
 * @since Version 4.0.0
 */
class netcash_comPaymentGateway extends geoPaymentGateway
{
	/**
	 * Required, the name of this gateway, should be the same as the file name without the .php
	 *
	 * @var string
	 */
	public $name = 'netcash_com';
	
	/**
	 * Required, Usually the same as the name, this can be used as a means
	 * to warn the admin that they may be using 2 gateways that
	 * are the same type.  Mostly used to distinguish CC payment gateways
	 * (by using type of 'cc'), but can be used for other things as well.
	 *
	 * @var string
	 */
	public $type = 'netcash_com';
	
	/**
	 * For convenience, should be same as $name
	 *
	 */
	const gateway_name = 'netcash_com';
	
	/**
	 * Optional.
	 * Used in admin, in paymentGatewayManage::getGatewayTable() which is used in both ajax calls,
	 * and to initially display the gateway page.
	 * 
	 * Expects to return an array:
	 * array (
	 * 	'name' => $gateway->name,
	 * 	'title' => 'What to display in list of gateways',
	 * )
	 * 
	 * Note: if need extra settings besides just being turned on or not,
	 *  see the method admin_custom_config()
	 * @return array
	 *
	 */
	public static function admin_display_payment_gateways ()
	{
		$return = array (
			'name' => self::gateway_name,
			'title' => 'Netcash.com',//how it's displayed in admin
		);
		
		return $return;
	}
	
	/**
	 * Optional.
	 * Used: in admin, on payment gateway pages, to see if should show configure button,
	 * and to display the contents if that button is clicked.
	 * 
	 * If this function exists, it will be used to display custom
	 * settings specific for this gateway using ajax.  If the function does not
	 * exist, no settings button will be displayed beside the gateway.
	 *
	 * @return string HTML to display below gateway when user clicked the settings button
	 */
	public function admin_custom_config ()
	{
		$db = DataAccess::GetInstance();

		$tpl = new geoTemplate('admin');
		
		$tpl->assign('payment_type', self::gateway_name);

		$tpl->assign('commonAdminOptions', $this->_showCommonAdminOptions(false));

		$tooltips['merchant_id'] = geoHTML::showTooltip('Merchant ID','This is your Merchant ID, assigned by Netcash');
		$tooltips['secret_key'] = geoHTML::showTooltip('Secret Key','This is your Secret Key, assigned by Netcash');

		$tpl->assign('tooltips', $tooltips);
		
		$values = array(
			'merchant_id' => $this->get('merchant_id'),
			'currency_type' => $this->get('currency_type'), //valid: USD or EUR
			'secret_key' => $this->get('secret_key')
		);
		$tpl->assign('values', $values);
		
		return $tpl->fetch('payment_gateways/netcash_com.tpl');
	}
	
	/**
	 * Optional.
	 * Used: in admin, in paymentGatewayManage::update_payment_gateways()
	 * 
	 * Use this function to save any additional settings.  Note that this is done IN ADDITION TO the
	 * normal "back-end" stuff such as enabling or disabling the gateway and serializing any changes.  
	 * If this returns false however, that additional stuff will not be done.
	 *
	 * @return boolean True to continue with rest of update stuff, false to prevent saving rest of settings
	 *  for this gateway.
	 */
	public function admin_update_payment_gateways ()
	{
		if (isset($_POST[self::gateway_name]) && is_array($_POST[self::gateway_name]) && count($_POST[self::gateway_name]) > 0){
			$settings = $_POST[self::gateway_name];

			//save common settings
			$this->_updateCommonAdminOptions($settings);

			//save non-common settings
			$this->set('merchant_id',trim($settings['merchant_id']));
			$this->set('currency_type',trim($settings['currency_type']));
			$this->set('secret_key',trim($settings['secret_key']));
			$this->save();
		}
		
		return true;
	}
	

	/**
	 * Optional.
	 * Used: in geoCart::payment_choicesDisplay()
	 * 
	 * Should return an associative array that is structured as follows:
	 * array(
	 * 	'title' => string,
	 * 	'title_extra' => string,
	 * 	'label_name' => string, //needs to be: self::gateway_name,
	 * 	'radio_value' => string, //should be self::gateway_name
	 * 	'help_link' => string, //entire link including a tag and link text, example: $cart->site->display_help_link(3240),
	 * 	'checked' => boolean, //leave false to let system determine if it is checked or not, true to force being checked
	 * 	//Items below will be auto generated if left as empty string.
	 * 	'radio_name' => string,//usually c[self::gateway_name] - this set by system if left as empty string.
	 * 	'choices_box' => string,//use custom stuff for the entire choice box.
	 * 	'help_box' => string,//use custom stuff for help link and box surrounding it.
	 * 	'radio_box' => string,//use custom box for radio
	 * 	'title_box' => string,//use custom box for title
	 * 	'radio_tag' => string//use custom tag for radio tag
	 * )
	 * 
	 * @param array $vars Array of info, see source of method for further documentation.
	 * @return array Associative Array as specified above.
	 *
	 */
	public static function geoCart_payment_choicesDisplay ($vars)
	{
		$cart = geoCart::getInstance(); //get cart to use the display_help_link function
		
		/**
		 * An array of "cost details" is passed in, this is what each order item returns in 'getCostDetails'
		 * if that item is affecting the cart total (is not zero).  This allows order items to NOT
		 * display themselves if they see something in the cart that the payment gateway should not pay
		 * for, due to user agreement or other reason.  Example of this is 2CO gateway is not able to pay
		 * for when user is adding to account balance, effectively "pre-paying" which is not allowed in 2CO policies.
		 */
		$itemCostDetails = $vars['itemCostDetails'];
		
		//if there are any types of things that this gateway cannot pay for, loop through the $itemCostDetails array
		//to see if it is in there, and if so simply return false to avoid showing this gateway as a payment choice.
		
		$msgs = $cart->db->get_text(true, 10203);
		$return = array(
			//Items that don't auto generate if left blank
			'title' => $msgs[502279],//$msgs[######]
			'title_extra' => '',//usually make this empty string.
			'label_name' => self::gateway_name,
			'radio_value' => self::gateway_name,//should be same as gateway name
			'help_link' => '',//$cart->site->display_help_link(3240),
			'checked' => false,//let system figure out if it is checked or not
			
			//Items below will be auto generated if left blank string.
			'radio_name' => '',//normally you leave all these blank.
			'choices_box' => '',
			'help_box' => '',
			'radio_box' => '',
			'title_box' => '',
			'radio_tag' => '',
		
		);
		return $return;
	}
	
	/**
	 * Optional.
	 * Used: in geoCart::payment_choicesCheckVars()
	 * 
	 * Called no matter what selection is made when selecting payment type, so before doing
	 * any checks you need to make sure the payment type selected (in var $_POST['c']['payment_type'])
	 * matches this payment gateway.  If there are any problems, use $cart->addError() to specify
	 * that it should not go onto the next step, processing the order (aka geoCart_payment_choicesProcess())
	 *
	 */
	public static function geoCart_payment_choicesCheckVars ()
	{
		//nothing to do here
	}
	
	/**
	 * Optional.
	 * Used: in geoCart::payment_choicesProcess()
	 * 
	 * This function is where any processing is done, and is also where things like re-directing to an external 
	 * payment site would be done, or updating account balance, etc.
	 * 
	 * Note that this is only called if this payment gateway is the one that was chosen, and there were no errors
	 * generated by geoCart_payment_choicesCheckVars().
	 * 
	 * This is where you would create a transaction that would pay for the order, into the invoice.
	 *
	 */
	public static function geoCart_payment_choicesProcess()
	{
		$cart = geoCart::getInstance();
		$gateway = geoPaymentGateway::getPaymentGateway(self::gateway_name);
		$user_data = $cart->user_data;
		
		//get invoice on the order
		$invoice = $cart->order->getInvoice();
		$invoice_total = $due = $invoice->getInvoiceTotal();
		
		if ($due >= 0){
			//DO NOT PROCESS!  Nothing to process, no charge (or returning money?)
			return ;
		}
		
		$transaction = new geoTransaction();
		$transaction->setGateway(self::gateway_name);
		$transaction->setUser($cart->user_data['id']);
		$transaction->setStatus(0); //for now, turn off until it comes back from gateway
		$transaction->setAmount(-1 * $due);//set amount that it affects the invoice
		$msgs = $cart->db->get_text(true,183);
		
		$transaction->setDescription($msgs[502280]);
		
		$transaction->setInvoice($invoice);
		
		$transaction->save();
		
		$testing = $gateway->get('testing_mode');
		
		
		
		//build redirect
		$formdata = $cart->user_data['billing_info'];
		
		$amount = number_format($transaction->getAmount(),2,'.',''); //using this in a hash, so make sure it has 2 decimal places
		$post_fields = array(
			'merchant_id' => $gateway->get('merchant_id'),
			'amount' => $amount,
			'currency' => $gateway->get('currency_type'),
			'secret_key' => sha1($amount . $gateway->get('merchant_id') . $gateway->get('secret_key')),
			'apid' => '105',
			'site_id' => geoFilter::getBaseHref(),
			'post_back_url' => geoFilter::getBaseHref().'transaction_process.php?gateway=netcash_com',
			'return_url' => geoFilter::getBaseHref().'transaction_process.php?gateway=netcash_com',
			'trans_id' => $transaction->getId(),
			'fname' => $formdata['firstname'],
			'lname' => $formdata['lastname'],
			'email' => $formdata['email'],
			'address' => $formdata['address'],
			'country' => $formdata['country'],
			'city' => $formdata['city'],
			'state' => $formdata['state'],
			'zip' => $formdata['zip']
		);
		$transaction->set('debug_fields',$post_fields);
		
		$post_url = 'https://payments.netcash.com/payform_api.php';
		
		$transaction->save();
		
		//add transaction to invoice
		$invoice->addTransaction($transaction);
		
		//set order to pending
		$cart->order->setStatus('pending');
		
		//stop the cart session
		$cart->removeSession();
		
		$gateway->_submitViaPost($post_url, $post_fields);
	}
	
	
	/**
	 * Optional.
	 * Used:  In transaction_process.php to allow processing of "signals" back
	 * from a payment processor.
	 * 
	 * Called from file /transaction_process.php - this function should
	 * be used when expecting some sort of processing to take place where
	 * the external gateway needs to contact the software back (like Paypal IPN)
	 * 
	 * It is up to the function to verify everything, and make any changes needed
	 * to the transaction/order.
	 * 
	 * Note that this is NOT where normal payment processing would happen when someone
	 * clicks the payment button, this is only called by transaction_process.php
	 * when a payment signal for this gateway is received.  To use, you would specify
	 * the url:
	 * 
	 * https://example.com/transaction_process.php?gateway=netcash_com
	 * 
	 * As the "signal/notification URL" to send notifications to (obviously would need
	 * to adjust for the actual payment gateway and actual site's URL).  Don't
	 * forget to authenticate the signal in some way, to validate it is indeed
	 * coming from the payment processor!
	 */
	public function transaction_process ()
	{
		if($_GET['trans_id']) {
			//this is the user returning to the site
			//show the success or failure page based on _GET data, but don't rely on that to change the db
			if($_GET['code'] === 'A') {
				//accepted
				//show "success" page directly, but don't do any of the actual success stuff (leave that for the postback)
				self::_successFailurePage(true, 'pending'); 
			} else {
				//declined -- go ahead and do the full failure here, since postback doesn't happen on fail
				self::_failure(geoTransaction::getTransaction($_GET['trans_id']), $_GET['code'], "Failed processing at netcash.com");
			}
		} elseif ($_POST['trans_id']) {
			//this is the postback signal from netcash -- this POST only happens on payment success (though that's kind of dumb) and isn't shown to the user
			if($_POST['payment_successful'] == 1 && $_POST['secret_key'] === $this->get('secret_key')) {
				$transaction = geoTransaction::getTransaction($_POST['trans_id']);
				$order = $transaction->getInvoice()->getOrder();
				self::_success($order, $transaction, $this, true);
			}
		}
	}

}