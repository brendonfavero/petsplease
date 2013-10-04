{* 6.0.7-3-gce41f93 *}

{$admin_msgs}

<fieldset>
	<legend>{if $discount_id}Edit{else}Add{/if} Discount Code</legend>
	<div>
		<form action="" method="post">
			<p class="page_note">* indicates required fields.</p>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn"><input type="checkbox" name="edit[active]" id="isActive" value="1"{if $data.active} checked="checked"{/if} /></div>
				<div class="rightColumn">
					<label for="isActive">* Enabled</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">* Discount Code Name</div>
				<div class="rightColumn">
					<input type="text" name="edit[name]" value="{$data.name|fromDB|escape}" size="30" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Description</div>
				<div class="rightColumn">
					<textarea name="edit[description]" rows="3" cols="30">{$data.description|fromDB|escape}</textarea>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">* Code</div>
				<div class="rightColumn">
					<input type="text" name="edit[discount_code]" value="{$data.discount_code|fromDB|escape}" size="30" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">* Discount Percentage</div>
				<div class="rightColumn">
					<input type="text" name="edit[discount_percentage]" value="{$data.discount_percentage|escape}" size="5" /> % Off full price
				</div>
				<div class="clearColumn"></div>
			</div>
			{if $is_ent}
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">* Used For</div>
					<div class="rightColumn">
						<label><input type="checkbox" name="edit[apply_normal]" value="1"{if $data.apply_normal} checked="checked"{/if} /> Normal Cart Sub-Total (listing fees, listing extras, non-recurring subscription payments, etc)</label>
						<br />
						<label><input type="checkbox" name="edit[apply_recurring]" value="1"{if $data.apply_recurring} checked="checked"{/if} /> Automatic Recurring Payments (Periodic recurring payments, typically for user subscriptions)</label>
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Start Date
					<br /><span class="small_font">[Format: YYYY-MM-DD]</span>
				</div>
				<div class="rightColumn">
					<input type="text" name="edit[starts]" id="startDate" value="{if $data.starts}{$data.starts|format_date:'Y-m-d'}{/if}" size="10" />
					<img src="admin_images/calendar_button.gif" style="vertical-align: middle;" alt="Select Date" id="startDateCalButton" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					End Date
					<br /><span class="small_font">[Format: YYYY-MM-DD]</span>
				</div>
				<div class="rightColumn">
					<input type="text" name="edit[ends]" id="endDate" value="{if $data.ends}{$data.ends|format_date:'Y-m-d'}{/if}" size="10" />
					<img src="admin_images/calendar_button.gif" style="vertical-align: middle;" alt="Select Date" id="endDateCalButton" />
					<br />
					(Blank or 0 to never end)
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Group Specific?
				</div>
				<div class="rightColumn">
					<input type="checkbox" id="isGroupSpecificCheck" name="edit[is_group_specific]" value="1"{if $data.is_group_specific} checked="checked"{/if} />
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div id="groupSelect" class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Attached Groups
				</div>
				<div class="rightColumn">
					{foreach from=$groups item=group}
						<label>
							<input type="checkbox" name="edit[groups][{$group.group_id}]" value="1"{if $data.groups[$group.group_id]} checked="checked"{/if} /> 
							{$group.name}
						</label>
						<br />
					{/foreach}
				</div>
				<div class="clearColumn"></div>
			</div>
			
			
			
			{if $joe_edwards_discountLink}
				<div class="col_hdr_top">Joe Edwards</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">discount code email:</div>
					<div class="rightColumn">
						<input type="text" name="edit[discount_email]" value="{$data.discount_email|fromDB|escape}" />
					</div>
					<div class="clearColumn"></div>
				</div>
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Cross-Debit User ID:</div>
					<div class="rightColumn">
						<input type="text" name="edit[user_id]" value="{if $data.user_id>0}{$data.user_id|escape}{/if}" size="4" />
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			<div class="center">
				<br />
				<input type="submit" name="auto_save" value="Save" class="mini_button" />
			</div>
		</form>
	</div>
</fieldset>