{* 7.1beta1-1209-g2ec630b *}

<div class="content_box">
	<h1 class="title my_account">{$messages.500170}</h1>
	<h1 class="subtitle">{$messages.500172}</h1>
	<p class="page_instructions">{$messages.102788}</p>
</div>
<br />
<div class="content_box">
	{if $count > 0}
		<form action="{$formTarget}" method="post">
			<table style="border-style: none; width: 100%;">
				<tr class="column_header">
					<td>{$messages.102831}</td>
					{if $showEmail}<td>{$messages.102832}</td>{/if}
					<td>{$messages.102833}</td>
					<td>{$messages.102834}</td>
				</tr>
				{foreach from=$users item=user key=i}
					<tr class="{cycle values='row_odd,row_even'}">
						<td>{$user.username}</td>
						{if $user.email}<td>{$user.email}</td>{/if}
						<td>{$user.feedback}</td>
						<td><input type="checkbox" name="d[user_id][{$i}]" value="{$user.id}" /></td>
					</tr>
				{/foreach}
			</table>
			<div class="center">
				<input type="hidden" name="d[insertcount]" value="{$count}" />
				<input type="submit" name="addUsers" value="{$messages.102842}" class="button" />
			</div>
		</form>
	{else}
		<div class="field_error_box">{$messages.102835}</div>
	{/if}
</div>