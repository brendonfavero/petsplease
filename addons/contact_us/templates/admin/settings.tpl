{* 6.0.7-3-gce41f93 *}

{$adminMsgs}

<form action="" method="post">
	<fieldset>
		<legend>Contact Us Form Settings</legend>
		<div>
			<div class="page_note">
				Note that each "department name" can be changed on this addon's
				<a href="index.php?page=edit_addon_text&amp;addon=contact_us">edit text page</a>.
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="show_ip" id="show_ip" value="1"{if $show_ip} checked="checked"{/if} />
				</div>
				<div class="rightColumn">
					<label for="show_ip">Include Sender's IP Address</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					E-Mail Subject Prefix
				</div>
				<div class="rightColumn">
					<input type="text" name="subject_prefix" value="{$subject_prefix|escape}" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Department 1 Send to e-mail(s)<br />
					({$msgs.dept_1})
				</div>
				<div class="rightColumn">
					<textarea name="dept_1_email" style="width: 300px; height: 100px;" />{$dept_1_email}</textarea><br />
					<span class="small_font" style="font-weight: bold;">Multiple e-mails seperated by <span class="text_blue">comma (,)</span></span>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Department 2 Send to e-mail(s)<br />
					({$msgs.dept_2})
				</div>
				<div class="rightColumn">
					<textarea name="dept_2_email" style="width: 300px; height: 100px;" />{$dept_2_email}</textarea><br />
					<span class="small_font" style="font-weight: bold;">Multiple e-mails seperated by <span class="text_blue">comma (,)</span></span>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="center">
				<input type="submit" name="auto_save" value="Save" class="button" />
			</div>
		</div>
	</fieldset>
</form>