{* 7.1beta1-1209-g2ec630b *}

<div class="content_box">
	<h1 class="title my_account">{$messages.624}</h1>
	<h1 class="subtitle">{$messages.387} {$helpLink}</h1>
	<p class="page_instructions">{$messages.388}</p>
	
	{if $showCommunications}
		<table style="width: 100%;">
			<tr class="column_header">
				<td class="nowrap">{$messages.391}</td>
				<td class="title">{$messages.392}</td>
				<td class="nowrap">{$messages.393}</td>
				<td class="nowrap"></td>
				<td class="nowrap"></td>
			</tr>
			{foreach from=$communications item=comm}
				<tr class="{cycle values='row_odd,row_even'}{if !$comm.read}_highlight{/if}">
					<td>
						{if $comm.sender_id}<a href="{$classifieds_file_name}?a=6&amp;b={$comm.sender_id}">{/if}
						{$comm.sender}
						{if $comm.sender_id}</a>{/if}
					</td>
					<td>{$comm.listingTitle}</td>
					<td>{$comm.dateSent}</td>
					<td><a href="{$comm.viewLink}" class="mini_button">{$messages.394}</a></td>
					<td><a href="{$comm.deleteLink}" class="delete">{$messages.395}</a></td>
				</tr>
			{/foreach}
		</table>
	{else}
		{* no communications for this user *}
		<div class="field_error_box">{$messages.390}</div>
	{/if}
</div>
<div class="center">
	<a href="{$commConfigLink}" class="button">{$messages.396}</a>
	<a href="{$userManagementHomeLink}" class="button">{$messages.397}</a>
</div>