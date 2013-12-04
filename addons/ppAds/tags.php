<?php
class addon_ppAds_tags extends addon_ppAds_info
{
	public static $NETWORKCODE = 142558614;
	public static $ADSPOTS = array(
		0 => array('unitname' => 'Homepage-Footer-Leaderboard', 'width' => 728, 'height' => 90),
		1 => array('unitname' => 'Search-Left-1-Medrec', 'width' => 300, 'height' => 250),
		2 => array('unitname' => 'Search-Left-2-Medrec', 'width' => 300, 'height' => 250),
		3 => array('unitname' => 'Search-Centre-Results-Leaderboard', 'width' => 728, 'height' => 90)
	);

	private $adsLoaded = array();

	public function adspot($params, Smarty_Internal_Template $smarty) {
		$adId = $params['aid'];

		$adData = self::$ADSPOTS[$adId];

		if (!in_array($adId, $this->adsLoaded)) {
			$this->adsLoaded[$adId] = 0;
		}

		$tpl_vars['ad'] = $adData;
		$tpl_vars['networkcode'] = self::$NETWORKCODE;
		$tpl_vars['divid'] = 'div-gad-' . $adId . '-' . $this->adsLoaded[$adId];  

		$this->adsLoaded[$adId]++; // append times shown on this page (in case shown more than once)

		return geoTemplate::loadInternalTemplate($params, $smarty, 'adspot.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}

	public function header($params, Smarty_Internal_Template $smarty) {
		return geoTemplate::loadInternalTemplate($params, $smarty, 'header.tpl',
				geoTemplate::ADDON, $this->name, $tpl_vars);
	}
}
?>