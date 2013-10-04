{* 7.0.2-66-g28e6e7b *}


<form action="" method="post">

	<table style="width: 100%;">
		<tr class="language_page_title">
			<td colspan="2" style="width: 100%">
				{$messages.327}
			</td>
		</tr>
		<tr class="page_description">
			<td colspan="2" style="width: 100%">
				{$messages.328}
			</td>
		</tr>
		<tr class="field_label">
			<td align="right" class="medium_font" style="width: 50%">
				{$messages.329}
			</td>
			<td style="width: 50%">
				<select name="set_language_cookie">
					{foreach $languages as $l}
						<option value="{$l.id}"{if $l.selected} selected="selected"{/if}>{$l.name}</option>
					{/foreach}
				</select>
			</td>
		</tr>
		<tr class="save_language_choice_button">
			<td colspan="2" align="center" class="medium_font">
				<input type="submit" name="submit" value="{$messages.330}" />
			</td>
		</tr>
	</table>
</form>
