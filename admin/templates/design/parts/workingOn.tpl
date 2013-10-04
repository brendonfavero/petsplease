{* 6.0.7-3-gce41f93 *}


{if $needsDefaultCopy}
	{* let the design mode box display the warning thingy *}
	{include file="design/parts/designModeBox.tpl"}
{else}
	
	<h1 class="no_border" style="float: left;" title="Template Set{if $advMode}s{/if} - This is Admin Editing column on Design &gt; Template Sets page.">
		Template Set{if $advMode}s{/if}:  
		{if $advMode && count($workWithList)>1}
			<ul class="text_green" style="display: inline-block; vertical-align: middle; margin: 2px; border: 1px solid #006699; padding: 3px; list-style: none;">
				{foreach from=$workWithList item=with}
					<li>{$with}</li>
				{/foreach}
			</ul>
		{else}
			<span class="text_green">
				{$workWith}
			</span>
		{/if}
		{if $advMode||count($allTSets)>1}
			&nbsp;&nbsp;
			<a href="index.php?page=design_change_editing" class="mini_button lightUpLink">
				Change
			</a>
		{/if}
		<div class="clearColumn"></div>
	</h1>
	{include file="design/parts/designModeBox.tpl" insideBox=1}
	
	<div class="clearColumn"></div>
{/if}

