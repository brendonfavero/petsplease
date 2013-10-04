{* 6.0.7-3-gce41f93 *}

<select name="{$selectName}" id="{$selectId}" class="templateDropdown">
	{if $showBlankTemplate}
		<option value="none"></option>
	{/if}
	{foreach from=$templates item=tsetList key=template}
		<option value="{if $tsetList}{$template|escape}{else}none{/if}"{if $templateSelected&&$templateSelected==$template} selected="selected"{/if}>
			{strip}
				{$template}
				{if $tsetList && $advMode}
					&nbsp;- [
					{foreach from=$tsetList item=t_set name=tSets}
						{$t_set}{if !$smarty.foreach.tSets.last}, {/if}
					{/foreach}
					]
				{/if}
			{/strip}
		</option>
	{/foreach}
</select>