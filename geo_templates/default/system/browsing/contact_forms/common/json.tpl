{* 7.4beta1-51-g61fa416 *}

{if $success}
	<div class="success_box">{$success}</div>
{/if}
{if $errors}
	{foreach from=$errors item=err}
		<div class="error_message">{$err}</div>
	{/foreach}
{/if}