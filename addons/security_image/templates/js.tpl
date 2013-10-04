{* 6.0.7-3-gce41f93 *}
<script type="text/javascript">
//<![CDATA[
	var securityImageType = '{$imageType}';
{literal}
	var changeSecurityImage = function()
	{
		if (securityImageType=='recaptcha') {
			//nothing to do
			return;
		}
		var a = new Date();
		var load_image = new Element('img');
		var new_image = new Element('img');
		load_image.setAttribute('src', 'addons/security_image/loader.gif');
					
		var secure_image = $('addon_security_image').select('img')[0];
		secure_image.setAttribute('src', load_image.src);
		new_image.setAttribute('src', '{/literal}{$classifieds_file_name}{literal}?a=ap&addon=security_image&page=image&no_ssl_force=1&time='+a.getTime());
		new_image.observe('load', function(){
			setTimeout(	function(){ secure_image.setAttribute('src', new_image.src); }, 250);
		});
	}
{/literal}
//]]>
</script>