{* 7.1beta1-1209-g2ec630b *}

<div class="content_box">
	{if $uid}
		<h1 class="title my_account">{$messages.623}</h1>
		<h1 class="subtitle">{$messages.399}</h1>
	{/if}
	
	<div class="success_box">{$messages.407}</div>
</div>

{if $uid}
	<div class="center">
		<a href="{$userManagementHomeLink}" class="button">{$messages.408}</a>
	</div>
{/if}