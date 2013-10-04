{* 7.2beta3-74-g8da7071 *}
{* Google "+1" Button
	See http://www.google.com/webmasters/+1/button/index.html for options *}
{if $activeMethods.google_plus == 1}
	<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
	{if false}
		<g:plusone size="medium" count="false" href="{$listing_url_unencoded}"></g:plusone>
	{else}
		{* HTML5 valid *}
		<div class="g-plusone" data-size="medium" data-count="false" style="width: auto;" data-href="{$listing_url_unencoded}"></div>
	{/if}
{/if}