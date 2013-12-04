<div id='{$divid}' style='width:{$ad.width}px; height:{$ad.height}px;'>
<script type='text/javascript'>

googletag.cmd.push(function() { 
	googletag.defineSlot('/{$networkcode}/{$ad.unitname}', [{$ad.width}, {$ad.height}], '{$divid}').addService(googletag.pubads());
	googletag.display('{$divid}'); 
});
</script>
</div>