<?php
//function.body_html.php
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
## ##    7.1.2-40-gd8b05fa
## 
##################################

//This fella takes care of {body_html}
function smarty_function_body_html ($params, Smarty_Internal_Template $smarty)
{
	if ((!isset($params['_sub_body_html']) || $smarty->getTemplateVars('_sub_body_html')) && $smarty->getTemplateVars('_inside_body_html')) {
		//already in body var, prevent infinite recursion
		return '{body_html}';
	}
	
	//figure out the file to use
	$file = '';
	
	$geo_inc_files = $smarty->getTemplateVars('geo_inc_files');
	
	if (isset($params['file'])) {
		//use file
		$file = $params['file'];
	} else if (isset($geo_inc_files['body_html'])) {
		$file = $geo_inc_files['body_html'];
	}
	
	if (!$file) {
		//no main page file to use for template...
		if ($smarty->getTemplateVars('body_html')) {
			return $smarty->getTemplateVars('body_html');
		}
		return '';
	}
	$tpl_vars = (array)$smarty->getTemplateVars('body_vars');
	$tpl_vars['_inside_body_html'] = 1;
	
	$g_type = $g_resource = null;
	if (isset($geo_inc_files['body_html_addon'])) {
		$g_type = geoTemplate::ADDON;
		$g_resource = $geo_inc_files['body_html_addon'];
	} else if (isset($geo_inc_files['body_html_system'])) {
		$g_type = geoTemplate::SYSTEM;
		$g_resource = $geo_inc_files['body_html_system'];
	}
	//stick anything assigned to body_html at the end
	$post = ''.$smarty->getTemplateVars('body_html');
	
	return geoTemplate::loadInternalTemplate($params, $smarty, $file, $g_type, $g_resource, $tpl_vars, '', $post);
}
