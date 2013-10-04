{* 6.0.7-3-gce41f93 *}
<div class="page_note_error">
<strong>Warning</strong>: Changing settings on this page can have drastic effects if your server
is not configured correctly.  It is important that you <strong>consult the user manual</strong>,
 so that you may fully understand what is happening, before changing any settings on this page.
 </div>

<fieldset id="cache_stats_fieldset">
	<legend>Cache Stats</legend>
	<div id="cache_stats_fieldsetContents">
		<div class="{$row_color.0}">
			<div class="leftColumn">Cache Storage Method</div>
			<div class="rightColumn">{$GEO_CACHE_STORAGE}</div>
			<div class="clearColumn"></div>
		</div>
{if $use_storage_cache ne 1}
	{if $GEO_CACHE_STORAGE eq 'filesystem'}
		<div class='{$row_color.1}'>
			<div class="leftColumn">Main Cache Directory</div>
			<div class="rightColumn">{$CACHE_DIR}</div>
			<div class="clearColumn"></div>
		</div>
		{if $geoCache_is_not_writable eq 1}
		<div class='{$row_color.2}'>
			<div class="leftColumn"><strong style="color:red">Cache directory not writable!</strong></div>
			<div class="rightColumn" style="text-align:left;">Make sure the directory exists, and is writable (Usually by using CHMOD 777).<br />The cache may not be able to be updated or added to until the directory is writable.</div>
			<div class="clearColumn"></div>
		</div>
		{/if}
	{elseif $GEO_CACHE_STORAGE eq 'memcache'}
		<div class='{$row_color.2}'>
			<div class="leftColumn">Memcache Setting Prefix</div>
			<div class="rightColumn">{$smarty.const.GEO_MEMCACHE_SETTING_PREFIX}</div>
			<div class="clearColumn"></div>
		</div>
		{if $memcache_exists eq 0}
		<div class="{$row_color.memcache_exists}">
			<div class="leftColumn"><strong style="color:red">Memcache PHP extension not installed!</strong></div>
			<div class="rightColumn" style="text-align:left;">In your config.php, you currently have the setting GEO_CACHE_STORAGE set to memcache, however it appears that the Memcache extension for PHP is not installed.  Either install the Memcache extension (talk to your host for this), or change the setting to &quot;filesystem&quot; so that it uses file-based caching.</div>
			<div class="clearColumn"></div>
		</div>
		{/if}
	{/if}
{/if}
		<div class='{$row_color.1}'>
			<div class="leftColumn">Total # cached items</div>
			<div class="rightColumn">{$countTOT}</div>
			<div class="clearColumn"></div>
		</div>
		<div class="{$row_color.0}">
			<div class="leftColumn">Cache Item Breakdown</div>
			<div class="rightColumn" style="text-align: left;">
				Module/Page Data <em>(per module, page)</em>: {$countM}<br />
				Module/Page Output <em>(per module, page, category, language, logged in status)</em>: {$countP}<br />
				Setting/Design Data <em>(per setting &quot;type&quot;)</em>: {$countS}<br />
				Text Data <em>(per page, module, language)</em>: {$countTXT}
			</div>
			<div class="clearColumn"></div>
		</div>
		<div class='{$row_color.1}'>
			<div class='leftColumn'>Cache Controls</div>
			<div class='rightColumn' style='text-align: left;'>
				<form action='index.php?page=clear_cache' method='post'>
					<input type='submit' name='auto_save' value='Clear All Cache' /><br />
					<input type='submit' name='auto_save' value='Clear Output Cache'/><br />
					<input type='submit' name='auto_save' value='Clear Data Cache'/><br />
				</form>
			</div>
			<div class='clearColumn'></div>
		</div>
	</div>
</fieldset>
<form action='index.php?page=cache_config' method='post'>
	<fieldset id="cache_manage_general_settings_fieldset">
		<legend>General Settings</legend>
		<div class="id="cache_manage_general_settings_fieldsetContents">
			<div class="leftColumn">Cache System</div>
			<div class="rightColumn">
				<label><input type="radio" name="use_cache" value="1" {if $use_cache eq 1}checked='checked' {/if}/>On</label>
				<label><input type="radio" name="use_cache" value="0" {if $use_cache eq 0}checked='checked' {/if}/>Off</label>
			</div>
			<div class="clearColumn"></div>
		</div>
	</fieldset>
	<div style="text-align: center;"><input type=submit value="Save" name="auto_save" /></div>
</form>