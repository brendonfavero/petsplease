{* 7.1.2-34-g32e1eb3 *}

<div class="content_box">
	<h1 class="title my_account">{$messages.627}</h1>
	<h1 class="subtitle">{$messages.374}{$helpLink}</h1>
	
	<form action="" method="post">
		<div class="center">
			{if $frequencySaved}<div class="success_box">{$messages.502081}</div>{/if}
			{$messages.502072} 
			<select name="alert_frequency">
				{if $messages.502074}<option value="1"{if $frequencySetting == 1} selected="selected"{/if}>{$messages.502074}</option>{/if}
				{if $messages.502075}<option value="2"{if $frequencySetting == 2} selected="selected"{/if}>{$messages.502075}</option>{/if}
				{if $messages.502076}<option value="3"{if $frequencySetting == 3} selected="selected"{/if}>{$messages.502076}</option>{/if}
				{if $messages.502077}<option value="4"{if $frequencySetting == 4} selected="selected"{/if}>{$messages.502077}</option>{/if}
				{if $messages.502078}<option value="5"{if $frequencySetting == 5} selected="selected"{/if}>{$messages.502078}</option>{/if}
				{if $messages.502079}<option value="6"{if $frequencySetting == 6} selected="selected"{/if}>{$messages.502079}</option>{/if}
				{if $messages.502080}<option value="7"{if $frequencySetting == 7} selected="selected"{/if}>{$messages.502080}</option>{/if}
			</select>
			<input type="submit" class="button" value="{$messages.502073}" />
		</div>
	</form>
	
	<p class="page_instructions">{$table_description|fromDB}</p>
	{if $showFilters}
		<table style="border: none; width: 100%;">
			<tr class="column_header">
				<td>{$messages.378}</td>
				<td>{$messages.379}</td>
				<td>{$messages.380}</td>
				<td></td>
			</tr>
			
			{foreach from=$filters item=f}
				<tr class="{cycle values='row_odd,row_even'}">
					<td>{$f.category_name} {if $f.sub_cat_check}{$messages.382}{/if}</td>
					<td>{$f.search_terms}</td>
					<td>{$f.date}</td>
					<td><a href="{$f.link}" class="delete">{$messages.381}</a></td>
				</tr>
			{/foreach}
		</table>
	{/if}
</div>

<div class="center">
	<a href="{$addRemoveFilterLink}" class="button">{$messages.384}</a>
	{if $addRemoveFilterLink2}
		<a href="{$addRemoveFilterLink2}" class="cancel">{$messages.383}</a>
	{/if}
	<a href="{$userManagementHomeLink}" class="button">{$messages.385}</a>
</div>