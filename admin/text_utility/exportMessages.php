<?php

if (PHP5_DIR) {
	$db = DataAccess :: getInstance();
} else {
	$db = & DataAccess :: getInstance();
}



$lang = $_GET['l'];
$query = "select text_id,pages.page_id,language_id,name,description,langs.text from geodesic_pages_messages as pages, geodesic_pages_messages_languages as langs where pages.message_id = langs.text_id and language_id = '{$lang}' order by text_id asc";	
$result = $db->Execute($query) or die($db->ErrorMsg());
echo "Text ID or addon info, Page ID or Addon Text ID, Language ID, Name, Description, Text\r\n";

$rowsRemain = true;
while($row = $result->FetchRow()) {
	echo geoArrayTools::toCSV($row, true).PHP_EOL;
}

//addon text
$addon = geoAddon::getInstance();
$addonsText = $addon->getTextAddons();

foreach ($addonsText as $info) {
	$text = $addon->getText($info->auth_tag, $info->name, $lang);
	$addonAdmin = $addon->getTextAddons($info->name);
	if (!$text || !is_object($addonAdmin)) {
		//something wrong with this one
		continue;
	}
	$textInfo = $addonAdmin->init_text();
	foreach ($text as $textI => $val) {
		$line = array (
			'addon.'.$info->name.'.'.$info->auth_tag,
			$textI,
			$lang,
			$textInfo[$textI]['name'],
			$textInfo[$textI]['desc'],
			$val
		);
		echo geoArrayTools::toCSV($line).PHP_EOL;
	}
}


