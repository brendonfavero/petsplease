{* 7.0.3-340-g1a8ca41 *}
{* javascript code included here because it's slightly different than that in js.tpl (width/height specifiable via admin settings) *}
<script type="text/javascript">
//<![CDATA[
	var changeSecurityImage = function()
	{
		var a = new Date();
		var load_image = new Element('img');
		var new_image = new Element('img');
		load_image.setAttribute('src', 'addons/security_image/loader.gif');
		
		var secure_image = $('addon_security_image').select('img')[0];
		secure_image.setAttribute('src', load_image.src);
		new_image.setAttribute('src', '{$classifieds_file_name}?a=ap&addon=security_image&page=image&no_ssl_force=1&time='+a.getTime());
		new_image.observe('load', function(){
			setTimeout(	function(){ secure_image.setAttribute('src', new_image.src); }, 250);
		});
	};
//]]>
</script>


<div class="row_even">
	<label for="b[securityCode]" class="login_label">{$label}</label>
	{if $imageType=='recaptcha'}
		{include file='recaptcha.tpl'}
	{else}
		<input name="b[securityCode]" id="b[securityCode]" size="4" class="field" type="text" />
	
		<div id="addon_security_image" class="center" style="width: {$w}px; height: {$h}px;">
			<a href="javascript:void(0)" onclick="changeSecurityImage();">
				<img src="{$classifieds_file_name}?a=ap&amp;addon=security_image&amp;page=image&amp;no_ssl_force=1" alt="Security Image" style="width: {$w}px; height: {$h}px;" />
			</a>
		</div>
	{/if}
</div>