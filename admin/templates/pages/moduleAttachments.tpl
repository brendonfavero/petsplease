{* 6.0.7-3-gce41f93 *}

<fieldset>
	<legend>Module Attachments</legend>
	<div>
		<p class="page_note">The following templates currently have this module attached to them.</p>
		{if $attachments}
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<tr class="col_hdr">
					<td style="text-align: left;">Set In Template Set</td>
					<td style="text-align: left;">Template File in main_page/</td>
					<td style="width: 100px;"></td>
				</tr>
				{foreach from=$attachments key=tset item=tpls}
					{foreach from=$tpls key=tpl item=tplExists}
						<tr class="{cycle values='row_color1,row_color2'}">
							<td style="padding: 10px; padding-right: 50px;">{$tset}</td>
							<td style="padding: 10px; padding-right: 50px;">{$tpl}</td>
							<td class="center" style="padding: 5px;">
								{if $tplExists}
									<a href="index.php?page=design_edit_file&amp;file={$tset|escape}/main_page/{$tpl}" class="mini_button">Edit Template</a>
								{else}
									Template not found in {$tset}.
								{/if}
							</td>
						</tr>
					{/foreach}
				{/foreach}
			</table>
		{else}
			<strong>Not Attached to any templates!</strong>  To start using this module, insert the module's tag into a template.
		{/if}
	</div>
</fieldset>