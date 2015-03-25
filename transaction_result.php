<?php
//transaction_result.php
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
## 
##    7.4.3-73-gfdd7df2
## 
##################################

/* This is a way to show the success or failure page for a given transaction without affecting anything else
	it only allows showing transactions belonging to the current user
	and should be used for display ONLY
	
	mainly, this is used for some rare payment gateways like Paypal Advanced that like to put things in an iframe and leave them there,
		as a way to break out of that and show the results full-screen
*/
	

require_once 'app_top.main.php';

class geoFakeGateway extends geoPaymentGateway {
	//just a cheap way to get access to geoPaymentGateway's protected function
	public static function show($success, $status, $render, $invoice, $transaction)
	{
		parent::_successFailurePage($success, $status, $render, $invoice, $transaction);
	}
}

$session = geoSession::getInstance();
$session->initSession();

$transaction_id = (int)$_GET['transaction'];
if(!$transaction_id || !$session->getUserId()) {
	//no transaction id given, or user isn't logged in. nothing to show.
	die('INVALID');
}

$transaction = geoTransaction::getTransaction($transaction_id);
$invoice = $transaction->getInvoice();
if(!$invoice) {
	//no invoice associated with this transaction. nothing to do here.
	die('INVALID');
}
$order = $invoice->getOrder();
if(!$order) {
	//no order associated with this transaction. nothing to do here.
	die('INVALID');
}
$buyer = (int)$order->getBuyer();
if($session->getUserId() != 1 && $session->getUserId() != $buyer) {
	//this isn't your transaction. go away.
	die('INVALID');
}

$success = $transaction->getStatus() == 1 ? true : false;
$status = $order->getStatus();
geoFakeGateway::show($success, $status, true, ($success)?$invoice:null, $transaction);

