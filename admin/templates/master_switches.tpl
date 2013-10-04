{* 7.0.2-32-g33d5722 *}

<fieldset>
	<legend>Master Switches</legend>

	<div>
		{$admin_msgs}
		<div class="page_note">
			These site-wide switches <strong>control entire groups of functionality</strong>.<br />
			<br />
			They will:<br />
			<ul>
				<li>Show/Hide functionality on the front end, in areas like My Account</li>
				<li>Show/Hide related Admin pages and/or settings</li>
				<li>Allow the software to be as simple or as complex as you need it to be!</li>
			</ul>
			They will not:<br />
			<ul>
				<li>Control text or templates that might mention the affected feature but are not specifically a part of it</li>
				<li>Walk your dog</li>
			</ul>
		</div>
		<form action="index.php?page=master_switches" method="post" id="toggle_form">
			<input type="hidden" name="toggle" id="toggle_form_input" value="" />
			<input type="hidden" name="auto_save" value="1" />
		</form>
		<div class="master_switches_grid">
			{strip}
				{foreach $switches as $switch => $info}
					<div class="master_switch_{if $info.value=='on'}on{else}off{/if}" onclick="$('toggle_form_input').value='{$switch}'; $('toggle_form').submit();">
						<div class="master_switch_label">{$info.label}</div>
						<div class="master_switch_status">
							{if $info.value=='on'}
								<img src="admin_images/bullet_success.gif" alt="on" /><br />
								On
							{else}
								<img src="admin_images/bullet_error.gif" alt="off" /><br />
								Off				
							{/if}
						</div>
						<div class="clr"></div>
						<div class="master_switch_description">{$info.description}</div>
					</div>
				{/foreach}
			{/strip}
		</div>
	</div>
</fieldset>