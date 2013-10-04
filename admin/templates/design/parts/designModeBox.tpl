{* 6.0.7-3-gce41f93 *}

{if !$insideBox && $needsDefaultCopy}
	{include file="design/parts/copyTsetWarning.tpl"}
{/if}

<div style="float: right; vertical-align: middle;{if $insideBox} margin-top: 10px;{/if}" class="page_note">
	Design Mode:  <span class="text_gray">{if $advMode}Advanced{else}Standard{/if}</span>
	&nbsp;
	<a href="index.php?page=design_change_mode" class="mini_button lightUpLink">
		Switch to <em>{if $advMode}Standard{else}Advanced{/if} Mode</em>
	</a>  
</div>
{if !$insideBox}
	<div class="clearColumn"></div>
{/if}