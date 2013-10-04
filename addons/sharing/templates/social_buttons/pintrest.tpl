{* 7.1beta4-99-g84389d8 *}
{* Pinterest "Pin it" Button
	See http://business.pinterest.com/widget-builder/#builder for options
	--NOTE: Requires at least one image. *}
{if $lead_image && $activeMethods.pinterest == 1}
		<a data-pin-config="none" data-pin-do="buttonPin" href="//pinterest.com/pin/create/button/?url={$listing_url}&amp;media={$lead_image|escape:'url'}&amp;description={$listing_data.description|escape:'url'}{if $price}%20{$price|escape:'url'}{/if}"><img src="//assets.pinterest.com/images/pidgets/pin_it_button.png" alt="" /></a>
		<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
{/if}