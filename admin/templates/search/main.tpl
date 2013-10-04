{* 6.0.7-3-gce41f93 *}


<form id="text_search_form" method="post" action="" style="text-align: center; width: 100%;">
	<div class="medium_font" style="text-align: left; margin-left: auto; margin-right: auto; display: inline-block; border: 3px solid #ccc; padding: 5px; background: #DDD url(admin_images/design/search_bg_icon.jpg) repeat-x scroll left top;">
		<input type="hidden" name="search_type" value="text" id="searchType" />
		<input type="hidden" name="auto_save" value="1" />
		<div style="float: left; margin: 20px;">
			<strong class="large_font" style="font-weight: bold;">Search for Text:</strong>
			<br />
			&nbsp; &nbsp;
			<input type="text" name="text" value="{$text|escape}" style="width: 400px;" id="text_query" />
			<br />
			&nbsp; &nbsp;
			Case insensitive exact phrase match
		</div>
		<div style="float: left; margin: 30px 20px;">
			<label>
				<input type="radio" name="show_first" id="show_first" value="1" {if $show_first}checked="checked"{/if} />
				Show first occurrence only
			</label>
			<br />
			<label>
				<input type="radio" name="show_first" value="0" {if !$show_first}checked="checked"{/if} />
				Show all occurrences
			</label>
		</div>
		<div class="clearColumn"></div>
		<div style="text-align: center;">
			<input type="submit" value="Search" class="mini_button" id="searchButton" />
		</div>
	</div>
</form>
<br /><br />
<div style="display: none;" id="searchResultsBox">
	<ul class="tabList">
		<li class="activeTab" id="textTab">Pages/Modules Text</li>
		<li id="addonTab">Addon Text</li>
		<li id="contentTab">Template Contents</li>
		<li id="filenameTab">Template Filenames</li>
	</ul>
	<div class="tabContents">
		<div id="loadingBox" style="text-align: center; margin: 10px;">
			<img src="admin_images/loading.gif" alt="loading..." style="vertical-align: middle;" /> Loading...
		</div>
		<div id="textTabContents"></div>
		<div id="addonTabContents"></div>
		<div id="contentTabContents"></div>
		<div id="filenameTabContents"></div>
	</div>
	<div style="margin-top: 5px; color: #666; display: none; float: right;" id="permaLinkBox">
		<div style="float: left; padding-right: 5px; border: none; background: transparent;" class="page_note">Search Permalink:</div>
		<div class="page_note" style="float: left;" id="permaLink"></div>
		<div class="clearColumn"></div>
	</div>
</div>
