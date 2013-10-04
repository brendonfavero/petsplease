{* 6.0.7-3-gce41f93 *}
<div class="content_box">
	<h1 class="title">{$interrupted_action|capitalize} {$messages.500386}</h1>
	
	<p class="page_instructions">
		{if $interrupted_action==$new_action}
			{$messages.500812} {$interrupted_action}{$messages.500813}
		{else}
			{if $allFree}{$messages.500408}{else}{$messages.500387}{/if}
		{/if}
	</p>
	
	<div class="center">
		{if $interrupted_action==$new_action}
			<a href="{$cart_url}" class="button">{$messages.500814}</a>
			<a href="{$new_action_url}" class="cancel">{$messages.500815}</a>
		{else}
			<a href="{$cart_url}" class="button">{$messages.500388} {$interrupted_action}</a>
			<a href="{$new_action_url}" class="cancel">{$messages.500389} {$interrupted_action}{$messages.500390} {$new_action}</a>
		{/if}
	</div>
</div>
