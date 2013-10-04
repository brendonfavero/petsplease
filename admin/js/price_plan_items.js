//price_plan_items.js
/**************************************************************************
Geodesic Classifieds & Auctions Platform 7.1
Copyright (c) 2001-2013 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
/*
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    6.0.7-2-gc953682
## 
##################################
*/


//js code to do fancy stuff for payment gateway page
configuresOpen = 0;
function configureItem (item, price_plan, category, time)
{
	$('container_'+item).show();
	CJAX.exe_html ('<url>AJAX.php?controller=price_plan_items&action=display_config&item='+item+'&price_plan_id='+price_plan+'&category_id='+category+'&time='+time+'</url><image>cjax/core/images/loading.gif</image>');
	$('row_for'+item).style.borderColor='#006699';
	configuresOpen++;
}

function cancelItem (item, price_plan, category)
{
	$('container_'+item).hide();
	CJAX.exe_html('<url>AJAX.php?controller=price_plan_items&action=cancel&item='+item+'&price_plan_id='+price_plan+'&category_id='+category+'</url><image>cjax/core/images/loading.gif</image>');
	$('row_for'+item).style.borderColor='#EAEAEA';
	configuresOpen--;
}

function saveItem (item, price_plan, category, time)
{
	$('container_'+item).hide();
	CJAX.exe_form ('<url>AJAX.php?controller=price_plan_items&action=save&item='+item+'&price_plan_id='+price_plan+'&category_id='+category+'&time='+time+'</url><form>frm_all_settings</form><image>cjax/core/images/loading.gif</image>');
	$('row_for'+item).style.borderColor='#EAEAEA';
	configuresOpen--;
}