{* 6.0.7-3-gce41f93 *}
{if $notifications}
<fieldset><legend>Notifications</legend>
<div class='notifications'>
	<ul>
		{foreach from=$notifications item="notification"}
		<li class='medium_font'>
			{$notification}
		</li>
		{/foreach}
	</ul>
</div>
</fieldset>
{/if}