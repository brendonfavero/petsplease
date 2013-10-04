{* 7.1beta3-60-g2459f98 *}

{$adminMsgs}

<br />
<h2>Multi-Level Field Group: <span class="text_blue">{$leveled_field_label}</span></h2>
<br /><br />
<fieldset>
	<legend>Multi-Level Field Values</legend>
	<div>
		{include file='leveled_fields/levelInfo.tpl'}
		
		<form action="index.php?leveled_field={$leveled_field}&amp;parent={$parent}&amp;p={$page}" method="post" id="massForm">
			<table style="border: 2px solid">
				<thead>
					<tr class="col_hdr_top">
						<th style="width: 21px;"><input type="checkbox" id="checkAllValues" /></th>
						<th>Value (ID#)</th>
						<th style="width: 60px;">Listings</th>
						<th style="width: 60px;">Enabled?</th>
						<th>Display Order</th>
						<th style="width: 90px;"></th>
					</tr>
					<tr class="col_ftr">
						<td colspan="6" style="padding-left: 20px;">With Selected:
							<a href="#" class="mini_button massEditButton">Mass Edit</a>
							<a href="#" class="mini_button copyButton">Copy</a>
							<a href="#" class="mini_button moveButton">Move</a>
							&nbsp; &nbsp; &nbsp; &nbsp;
							<a href="#" class="mini_cancel massDeleteButton">Delete</a>
							{$pagination}
						</td>
					</tr>
				</thead>
				<tfoot>
					<tr class="col_ftr">
						<td colspan="6" style="padding-left: 20px;">With Selected:
							<a href="#" class="mini_button massEditButton">Mass Edit</a>
							<a href="#" class="mini_button copyButton">Copy</a>
							<a href="#" class="mini_button moveButton">Move</a>
							&nbsp; &nbsp; &nbsp; &nbsp;
							<a href="#" class="mini_cancel massDeleteButton">Delete</a>
							{$pagination}
						</td>
					</tr>
				</tfoot>
				<tbody>
					{if $parent}
						{foreach $parents as $value}
							{if $value.enabled=='no'}
								<tr class="{cycle values='row_color1,row_color2'}" id="row_{$value.id}">
									<td class="center"></td>
									<td colspan="5">
										<div class="disabledSection">
											<strong style="color: red;">Warning:</strong> Parent Value <strong class="text_blue">{$value.name}</strong>  is <strong>Disabled</strong>!  Sub-Values below are not currently usable on the site.
										</div>
									</td>
								</tr>
							{/if}
						{/foreach}
					{/if}
					{if !$values}
						<tr><td colspan="10"><p class="page_note_error">No values were found at this level!  You can create some new values at this level using the "Add Value" or "Bulk Add" buttons at the bottom...</p></td></tr>
					{else}
						{foreach $values as $value}
							<tr class="{cycle values='row_color1,row_color2'}" id="row_{$value.id}">
								<td class="center"><input type="checkbox" name="values[]" class="valueCheckbox" value="{$value.id}" /></td>
								<td{if $value.enabled=='no'} class="disabled"{/if}>
									<a href="index.php?page=leveled_field_values&amp;leveled_field={$leveled_field}&amp;parent={$value.id}">{$value.name|fromDB} ({$value.id})</a>
								</td>
								<td class="center">{$value.listing_count}</td>
								<td class="center">
									{include file='leveled_fields/enabled.tpl'}
								</td>
								<td class="center">{$value.display_order}</td>
								<td class="center">
									<a href="index.php?page=leveled_field_values&amp;leveled_field={$leveled_field}&amp;parent={$value.id}" class="mini_button">Enter</a>
									<a href="index.php?page=leveled_field_value_edit&amp;leveled_field={$leveled_field}&amp;value={$value.id}&amp;p={$page}" class="mini_button lightUpLink">Edit</a>
								</td>
							</tr>
						{/foreach}
					{/if}
				</tbody>
			</table>
		</form>
		<br />
		<div class="center">
			<a href="index.php?page=leveled_field_value_create&amp;leveled_field={$leveled_field}&amp;parent={$parent}" class="mini_button lightUpLink">Add Value</a>
			<a href="index.php?page=leveled_field_value_create_bulk&amp;leveled_field={$leveled_field}&amp;parent={$parent}" class="mini_button lightUpLink">Bulk Add</a>
		</div>
	</div>
</fieldset>

<div class="center">
	<br /><br />
	<a href="index.php?page=leveled_fields" class="mini_button">View All Multi-Level Field Groups</a>
</div>