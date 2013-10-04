{* 6.0.7-3-gce41f93 *}
<div style="width: 100%;">
	<div style="width: 50px; margin: 10px auto;">
		<img src="{external file='images/loading.gif'}" alt="" />
	</div>
	<form action="{$post_url}" method="post" id="gateway_post">
		{foreach from=$post_fields item=field key=index}
			<input type="hidden" name="{$index}" value="{$field}" />
		{/foreach}
		<input type="submit" value="continue to gateway" style="display: none;" />
	</form>
	<script type="text/javascript">
		//<![CDATA[
		geoUtil.autoSubmitForm("gateway_post");
		//]]>
	</script>
</div>