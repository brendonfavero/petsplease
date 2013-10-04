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
## ##    6.0.7-33-g97c7eec
## 
##################################
defined('IN_ADMIN')||die('NO ACCESS');

//include_once '../app_top.common.php';

$software_name = "GeoCore";

	$footer_html = (isset($footer_html))? $footer_html : '';
	$footer_html .= "
			</td>
		</tr>
	</table>
	<div style='padding: 0.5em 0; background-color: #000066; text-align: right;'>
		<div class='medium_font_light' style='float: left;'>
			&nbsp;Created by <a href=http://www.geodesicsolutions.com class=medium_font_light style='color: white;'>Geodesic Solutions LLC</a>
		</div>
		<div class='medium_font_light' style=' text-align: right; white-space:nowrap;'>
			".$software_name." DB Ver. ".geoPC::getVersion()."&nbsp; [ <a href=\"http://geodesicsolutions.com/changelog\" class=\"medium_font_light\">Release Notes</a> ]&nbsp; 
		</div>
	</div>
</html>";


return $footer_html;
