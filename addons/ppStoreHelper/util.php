<?php
class addon_ppStoreHelper_util extends addon_ppStoreHelper_info
{
	const SHOP_CATEGORY = 412;

	public function userHasStoreListing() {
		$db = true;
		require (GEO_BASE_DIR."get_common_vars.php");

		$userID = geoSession::getInstance()->getUserId();

		if ($userID == 0) return false;

		$sql = "SELECT COUNT(*) FROM geodesic_classifieds WHERE seller = ? AND category = ? AND live = 1";
		$result = $db->GetOne($sql, array($userID, self::SHOP_CATEGORY));

		return $result > 0;
	}
}
?>