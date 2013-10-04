{* 7.2.4-14-g2241842 *}
{*
This tpl requires the following vars to be set:
recaptcha_theme
recaptcha_server
recaptcha_pub_key
If error:  recaptcha_error
*}
{if $is_ajax}
	{* Need to use AJAX version to load it differently *}
	<div class="inline">
		<div id="recaptcha_placeholder"></div>
	</div>
	<script>
		//<![CDATA[
		jQuery.getScript('//www.google.com/recaptcha/api/js/recaptcha_ajax.js', function () {
			Recaptcha.create('{$recaptcha_pub_key}',
				"recaptcha_placeholder",
				{
					theme: '{$recaptcha_theme}',
					//uncomment to auto-focus Recaptcha when it's created (can behave oddly with single-page listing placement)
					//callback: Recaptcha.focus_response_field
				}
			);
		});
		//]]>
	</script>
{else}
	<script type="text/javascript">
	{literal}
	//<![CDATA[
		var RecaptchaOptions = {
			//The theme can be modified by specifying options here.  Some options changed
			//with config options in admin.  To customize further, see the docs at the link below:
			//See:  http://code.google.com/apis/recaptcha/docs/customization.html
			theme : '{/literal}{$recaptcha_theme}{literal}'
		};
	//]]>
	{/literal}
	</script>
	{* need to surround the entire thing with a div with inline-block style, or it newlines
		in chrome/IE *}
	<div class="inline">
		<script type="text/javascript" src="{$recaptcha_server}/challenge?k={$recaptcha_pub_key}{if $recaptcha_error}&amp;error={$recaptcha_error}{/if}"></script>
		
		<noscript>
			<iframe src="{$recaptcha_server}/noscript?k={$recaptcha_pub_key}{if $recaptcha_error}&amp;error={$recaptcha_error}{/if}" height="300" width="500" frameborder="0"></iframe><br />
			<textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
			<input type="hidden" name="recaptcha_response_field" value="manual_challenge" />
		</noscript>
	</div>
{/if}