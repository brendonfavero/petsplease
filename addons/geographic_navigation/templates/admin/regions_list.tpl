{* 6.0.7-3-gce41f93 *}
{strip}
<ul class="regionList {$regions.0.childClass|replace:'isRegion':'isRegion_'}" id="regionList_{$regions.0.id}{if $regions.0.level=='Country'}Country{/if}">
	{foreach from=$regions item=region}
		<li{if $region.id!='new' && $region.childClass=='isRegion'} class="regionMovable" id="regionLi_{$region.id}"{/if}>
			{include file="admin/region.tpl"}
		</li>
	{/foreach}
</ul>
{/strip}
{if $ajax}
	<script type="text/javascript">
		addonGeographic.initRegions();
	</script>
{/if}