{* 7.2.1-19-g4c75465 *}

<div class="content_box">
	<h1 class="title">{$messages.760} {$username}</h1>
	
	{if count($exposed) > 0}
		{foreach from=$exposed item=e}
			<div class="{cycle values='row_even,row_odd'}">
				{if $e.value}
					<label class="field_label">{$e.label}</label>
					{$e.value}
				{/if}
			</div>
		{/foreach}
	{/if}
</div>

<br />

<div class="content_box">
	{* call the common browse results template to handle the meat and potatoes *}
	{include file="common/grid_view.tpl" g_resource="browsing"}
</div>

{if $pagination}
	{$pagination}
{/if}
