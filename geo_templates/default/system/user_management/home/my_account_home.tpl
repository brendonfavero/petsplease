{* 7.1beta1-1209-g2ec630b *}

<div class="content_box">
	<h1 class="title my_account">{$messages.500793}</h1>
</div>
<br />
{counter print=false start=0 assign=box_count}
{foreach from=$boxes item=box}
	<div class="content_box highlight_links">
		{if $box_count % 2 == 0}
			<h2 class="title">{$box.title}</h2>
		{else}
			<h1 class="title">{$box.title}</h1>
		{/if}
		
		{foreach from=$box.rows item=row}
			{cycle values='row_even,row_odd' assign='cellCSS'}
			{if $row.table}
				<h1 class="subtitle">{$row.label}</h1>
				{foreach from=$row.table item=tableRow}
					<div class="{$cellCSS}">
						<a href="{$tableRow.link}">{$tableRow.title}</a>
						{if $tableRow.link2}<a href="{$tableRow.link2}" class="mini_button">{$tableRow.link2text}</a>{/if}
					</div>
				{/foreach}
			{else}
				<div class="{$cellCSS}">{$row.label} {if $row.link}<a href="{$row.link}" class="mini_button">{/if}{$row.data}{if $row.link}</a>{/if}</div>
			{/if}
		{/foreach}
	</div>
	<br />
	{counter print=false assign=box_count}
{/foreach}

<a style="display:block; text-align:center" href="javascript:void( window.open('/index.php?a=ap&addon=ppExtraPages&page=dogClicker', 'blank','scrollbars=yes,toolbar=no,width=700,height=1200'))"><img src="/geo_templates/default/external/images/registration-button.gif" alt=""></a>
