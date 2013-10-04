{* 7.1beta4-83-g97e471e *}
<a href="{$file_name}?a=ap&amp;addon=sharing&amp;page=main&amp;share={$forListing}"><img src="{$shareButtonImage}" alt="" id="share_button" /></a>

{* split the links into two columns *}

{* be very careful when editing the "style" attributes here, as you may disrupt the behaviour of the popup *}
<div id="share_popup" style="display: none; border: 5px solid #CCCCCC; z-index: 50; width: 270px; background-color: #F7F7F7; position: absolute; font-size: 10pt; padding: 0px;">
	<h1 class="title">{$text.popup_title}<span class="share_clickToClose" onclick="doShareClose();">[X]</span></h1>
	
	<div id="share_lists" class="sharing_popup_list_container">
		<ul class="sharing_method_list">
			{foreach $shortLinks as $link}
				{if $link@iteration is odd by 1}
					<li style="margin: 2px 0px;">{$link}</li>
				{/if}
			{/foreach}
		</ul>
		<ul class="sharing_method_list">
			{foreach $shortLinks as $link}
				{if $link@iteration is even by 1}
					<li style="margin: 2px 0px;">{$link}</li>
				{/if}
			{/foreach}
			{if $showMoreLink}
				{* not in use yet... *}
				<li>
					<a href="{$file_name}?a=ap&amp;addon=sharing&amp;page=main&amp;share={$forListing}" title="{$title}"><img src="{$shareButtonImage}" alt="" style="width: 16px; height: 16px;" /></a>
					<a href="{$file_name}?a=ap&amp;addon=sharing&amp;page=main&amp;share={$forListing}" title="{$title}">{$text.shortlink_more}</a>
				</li>
			{/if}
		</ul>
	</div>
</div>
<div id="share_close" style="display: none; position: absolute; z-index: 48;"></div>

<script type="text/javascript">
{literal}
//<![CDATA[
Event.observe('share_button','mouseover', function() {
	
	//get x/y coords of button
	pos = $('share_button').positionedOffset();
	
	//move box into position
	$('share_popup').style.left = (pos.left) + 'px';
	$('share_popup').style.top = (pos.top) + 'px';
	
	//fade the box into view
	$('share_popup').appear({ duration: 0.5 });
	$('share_lists').show();
	
	//make another, transparent box around the border, for use in detecting when to close
	w = $('share_popup').getWidth();
	h = $('share_popup').getHeight();
	$('share_close').style.left = (pos.left - 15) + 'px';
	$('share_close').style.top = (pos.top - 15) + 'px';
	$('share_close').style.width = (w + 30) + 'px';
	$('share_close').style.height = (h + 30) + 'px';

	//don't activate close box until we're sure the main one is fully in
	//TODO: this can make it behave oddly if cursor is removed from button before popup fully fades in. investigate a better way to do it.
	setTimeout(function() { $('share_close').show(); }, 510);
	

	Event.observe('share_close','mouseover',function() {
		$('share_close').hide();
		$('share_popup').fade({ duration: 0.5 });		
	});

	
});

var doShareClose = function() {
	$('share_close').hide();
	$('share_popup').fade({ duration: 0.5 });	
}
//]]>
{/literal}
</script>

{foreach $social_buttons as $button_tpl}
	{include file="social_buttons/{$button_tpl}"}
{/foreach}

