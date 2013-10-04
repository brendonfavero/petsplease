{* 7.2.4-9-g12e56c0 *}
{$messages}
<fieldset>
<legend>Storefront Subscription Choices</legend>
	<form action='index.php?page=storefront_subscription_choices_add' method='post'>
		<table cellpadding=3 cellspacing=1 border=0 align=center style='width:100%'>
			<tr class=row_color_black>
				<td class='col_hdr'>Period duration name </td>
				<td class='col_hdr'>Period </td>
				<td class='col_hdr'>Cost </td>
				<td class="col_hdr">Trial Period</td>
				<td class='col_hdr'>&nbsp; </td>
				<td class='col_hdr'>&nbsp; </td>
			</tr>
		{foreach from=$count_display item=id}
			{$choice_id=$period_ids.$id}
			<tr class="{$color_class.$id}">
				<td class="medium_font center">
					{$display_values.$id}
				</td>
				<td class="medium_font center">
					{$numberofdays.$id} day{$value_plural.$id}
				</td>
				<td class="medium_font center">
					{$amount.$id|displayPrice}
				</td>
				<td class="medium_font center">
					{if $trial.$id}Yes{else}No{/if}
				</td>
				<td class="medium_font center">		
					<a href="index.php?page=storefront_subscription_choices_edit&amp;period_id={$choice_id}" class="mini_button">edit</a>
				</td>
				<td class="medium_font center">
					<a href="index.php?page=storefront_subscription_choices_delete&amp;period_id={$choice_id}&amp;auto_save=1" class="lightUpLink mini_cancel">delete</a>
				</td>
			</tr>
		
			{if $add_header.$id ne ''}
			<tr class=row_color_black>
				<td class='col_hdr'>Period duration name </td>
				<td class='col_hdr'>Period </td>
				<td class='col_hdr'>Cost </td>
				<td class="col_hdr">Trial Period</td>
				<td class='col_hdr'>&nbsp; </td>
				<td class='col_hdr'>&nbsp; </td>
			</tr>
			{/if}
		{foreachelse}
			<tr><td colspan="6"><div class='page_note_error'>No Subscription Choices</div></td></tr>
		{/foreach}
			
			<tr class='col_ftr'>
				<td class='medium_font center'>
					<label style="white-space: nowrap;"><input type=text name=d[display_value] value='30 Days' /> label</label>
				</td>
				<td class='medium_font center'>
					<label style="white-space: nowrap;"><input type='text' name='d[value]' value='30' size="8" /> days</label>
				</td>
				<td class='medium_font center'>
					<label style="white-space: nowrap;">{$precurrency}<input type='text' name='d[cost]' value='5.00' size="8" />{$postcurrency}</label>
				</td>
				<td class='medium_font center'>
					<input type="checkbox" name="d[trial]" value="1" />
				</td>
				<td class='medium_font center'>
					<input type='submit' class='mini_button' name='auto_save' value='Add Choice' />
				</td>
				<td>&nbsp;</td>
				
			</tr>
			
		</table>
	</form>
</fieldset>
{if $plans}
<fieldset>
	<legend>Price Plan Specific Usage</legend>
	<div class=page_note>
		<p>Each price plan can have which ever subscription choices available to it,
			or even have the Storefront disabled totally. By default
			none of the subscription choices listed above are enabled for any
			price plans. To enable them for a price plan, click the appropriate 
			link below to see settings for that price plan, then
			click <strong>configure</strong> next to the option for <strong>Storefront Subscription</strong>.
			<br /><br />See the User Manual for more information.</p>
		{$plans}
	</div>
</fieldset>
{/if}