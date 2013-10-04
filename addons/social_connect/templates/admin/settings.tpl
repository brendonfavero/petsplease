{* 67d0e9c *}
{$admin_msgs}

<form action="" method="post">
	<fieldset>
		<legend>Facebook App/Site Settings</legend>
		<div>
			<p class="page_note">
				To use Facebook Connect, you need to register your website with Facebook using
				a verified Facebook account.  You can do so <a href="http://developers.facebook.com/setup" onclick="window.open(this.href); return false;">here</a>.  Once you have
				successfully registered your website, it will tell you the App ID and App Secret,
				which you will enter in the fields below.
			</p>
			
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Facebook App ID</div>
				<div class="rightColumn"><input type="text" name="fb_app_id" value="{$fb_app_id|escape}" /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Facebook App Secret</div>
				<div class="rightColumn"><input type="text" name="fb_app_secret" value="{$fb_app_secret|escape}" /></div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Default Group</div>
				<div class="rightColumn">
					<select name="default_group">
						{foreach $groups as $group}
							<option value="{$group.group_id}"{if $default_group==$group.group_id} selected="selected"{/if}>{$group.name}</option>
						{/foreach}
					</select>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn"><input type="checkbox" name="fb_logout" value="1"{if $fb_logout} checked="checked"{/if} /></div>
				<div class="rightColumn">Logout of Facebook (causes endless redirect on some sites when logging out)</div>
				<div class="clearColumn"></div>
			</div>
		</div>
	</fieldset>
	
	<div class="center">
		<input type="submit" name="auto_save" value="Save" class="mini_button" />
	</div>
</form>