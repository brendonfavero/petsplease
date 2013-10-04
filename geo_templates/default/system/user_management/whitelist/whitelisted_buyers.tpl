{* 7.1beta1-1209-g2ec630b *}

<div class="content_box">
	<h1 class="title my_account">{$messages.500171}</h1>
	<h1 class="subtitle">{$messages.102844}</h1>
	<p class="page_instructions">{$messages.102980}</p>
</div>
<br />
<div class="content_box">
	{if $showUsers}
		<form action="{$formTarget}" method="post">
			<table style="border-style: none; width: 100%;">
				<tr class="column_header">
					<td>{$messages.102845}</td>
					{* By default, should not show e-mail addresses!  Un-comment to show anyways *}
					{* <td>{$messages.102846}</td> *}
					<td>{$messages.102847}</td>
					<td>{$messages.102848}</td>
				</tr>
				{foreach from=$users item=user key=i}
					<tr class="row_{cycle values="even,odd"}">
						<td>{$user.username}</td>
						{* By default, should not show e-mail addresses!  Un-comment to show anyways *}
						{* <td>{$user.email}</td> *}
						<td>{$user.feedback}</td>
						<td><input type="checkbox" name="d[user_id][{$i}]" value="{$user.id}" /></td>
					</tr>
				{/foreach}
			</table>
			<div class="center">
				<input type="hidden" name="d[updatecount]" value="{$count}" />
				<input type="submit" name="submit_value" value="{$messages.102992}" class="button" />
			</div>
		</form>
	{else}
		{* no users to show *}
		<div class="note_box">{$messages.102981}</div>
	{/if}
</div>