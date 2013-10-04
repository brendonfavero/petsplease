{* 7.2beta3-83-g806659c *}

<div style='position:relative; padding: 8px;'>
	{if $msgs.map_label}
		<div class="googleMapsLabel">
			{$msgs.map_label}
		</div>
	{/if}
	<div id='map_canvas{$listing_id|escape}' style='position:relative; width: {$width}{$width_type}; height: {$height}{$height_type}'></div>
	<div id='map_directions'></div>
</div>
<script type='text/javascript'>
//<![CDATA[
	jQuery(document).ready(function () {
  		addon_google_maps.init({$coords}, '{$location|escape_js}', 'map_canvas{$listing_id|escape_js}');
	});
//]]>
</script>