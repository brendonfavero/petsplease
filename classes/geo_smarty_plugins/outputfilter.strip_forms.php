<?php
//outputfilter.strip_forms.php
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
## ##    6.0.7-2-gc953682
## 
##################################

//this smarty plugin is nice

function smarty_outputfilter_strip_forms ($output, Smarty_Internal_Template $smarty)
{
	return preg_replace('/\<form[^>]*\>/ei','_replaceFormTag("\\0")',$output);
}

function _replaceFormTag ($form_tag)
{
	if (strpos($form_tag,'id="switch_product"') === false) {
		//this is not the switch product form tag.
		$form_tag = '';
	}
	return $form_tag;
}
