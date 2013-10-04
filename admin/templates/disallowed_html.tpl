{* 6.0.7-3-gce41f93 *}

{$notifications}
<fieldset>
	<legend>HTML Allowed Settings</legend>
	<div style="text-align: center;">
		<form action='' method='post'>
			<div style="text-align: left; margin-left: auto; margin-right: auto; width: 600px;">
				<div class="center"><input type="submit" name="auto_save" value="Save Changes" class="mini_button" /></div>
				<br />
				<div class="col_hdr_top">Tags matching &lt;tag ...&gt; or &lt;/tag&gt;</div>
				{foreach from=$tag_columns item=tags key=column}
					<table style="width: 290px; border: 3px solid #DDD;{if $column==0} float: left; margin-right: 20px;{/if}">
						<thead>
							<tr class="col_hdr">
								<td>Tag</td>
								<td>Allowed?</td>
								<td>Delete</td>
							</tr>
						</thead>
						<tbody>
							{foreach from=$tags item='info' name='htmlAllowed'}
								<tr class="{cycle values='row_color1,row_color2'}">
									<td>
										{$info.tag_name|escape}
										{if $info.strongly_recommended == 1}<span class="medium_error_font">*</span>{/if}
									</td>
									<td class="center">
										<input type="hidden" name="b[{$info.tag_id}]" value="1" />
										<input type="checkbox" name="b[{$info.tag_id}]" value="0"{if !$info.tag_status} checked="checked"{/if} />
									</td>
									<td class="center">
										{if $info.strongly_recommended == 2}
											<input type="checkbox" name="b[{$info.tag_id}]" value="2" />
										{/if}
									</td>
								</tr>
							{/foreach}
							{if $column==0}
								<tr class="col_hdr">
									<td>Tag</td>
									<td>Allowed?</td>
									<td>&nbsp;</td>
								</tr>
								<tr class="{cycle values='row_color1,row_color2'}">
									<td>
										tags not in this list <span class="medium_error_font">*</span>
									</td>
									<td align=center>
										<input type="hidden" name="b[keep_tags_not_defined]" value="0" />
										<input type="checkbox" name="b[keep_tags_not_defined]" value="1" {if $keep_tags_not_defined} checked="checked"{/if} />
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr class="{cycle values='row_color1,row_color2'}">
									<td>
										Add tag:
										<strong>&lt;<input type="text" name="b[new_tag]" value="" size="5" /> &gt;
									</td>
									<td align=center>
										<input type="checkbox" name="b[new_tag_allowed]" />
									</td>
									<td>&nbsp;</td>
								</tr>
							{/if}
						</tbody>
					</table>
				{/foreach}
				
				<div class="clearColumn"></div>
				<div class="center"><input type="submit" name="auto_save" value="Save Changes" class="mini_button" /></div>
			</div>
			
		</form>
	</div>
</fieldset>