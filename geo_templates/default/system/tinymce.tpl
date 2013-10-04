{* 6.0.7-3-gce41f93 *}

<!-- tinyMCE -->
{if $use_gzip}
<script type="text/javascript">
	//<![CDATA[
	tinyMCE_GZ.init({
		themes : 'advanced',
		languages : 'en',
		disk_cache : true, //breaks load/unload to turn this on
		debug : false
	});
	//]]>
</script>
{/if}

<script type="text/javascript">
	//<![CDATA[
	
	geoWysiwyg.loadTiny = function () {
		if (geoWysiwyg.tinyLoaded) {
			return false;
		}
		geoWysiwyg.tinyLoaded = true;
		
		
		tinyMCE.init({
			theme : 'advanced',
			language : 'en',
			mode : 'textareas',
			plugins: 'advlink',//fix link to work in gzip
			editor_selector : 'editor',
			{if $blank_screen_fix}strict_loading_mode : true,{/if}
			theme_advanced_disable : 'visualaid,help,styleselect,cleanup,image',
			theme_advanced_buttons3_add : 'separator,forecolor,backcolor',
			extended_valid_elements : "iframe[src|width|height|name|align|style|scrolling|frameborder|allowtransparency]",
			{if $width > 0}
				width: '{$width}',
			{/if}
			{if $height > 0}
				height: '{$height}',
			{/if}
			//make it NOT automatically add the <p> around everything...  Comment the line out if it is needed.
			forced_root_block : '',
			
			content_css: '{if $inAdmin}../{/if}{external file="css/wysiwyg.css"}'
		});
		
		{if $inAdmin}
			geoWysiwyg.editors = $$('.editor');
		{/if}
		
		return true;
	};
	
	{if !$inAdmin}
		//load tiny right away when not in admin
		geoWysiwyg.loadTiny();
		
		//when page is loaded, init the editor
		Event.observe(window,'load',function () {
			//load tiny mce
			geoWysiwyg.editors = $$('.editor');
			
			if (geoUtil.getCookie('tinyMCE') == 'off') {
				//now restore values, un-doing any damage that might have been done...
				geoWysiwyg.toggleTinyEditors();
			}
		});
	{/if}
	//]]>
</script>
<!-- tinyMCE end -->
