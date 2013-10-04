{* 6.0.7-3-gce41f93 *}

<div class="content_box">
	<h1 class="title">{$section_title}</h1>
	<h1 class="subtitle">{$page_title}</h1>
	<p class="page_instructions">{$instructions}</p>
	{if count($errors) > 0}
		{foreach from=$errors item=err}
			<span class="error_message">{$err}</span><br />
		{/foreach}
	{/if}
	<form action="{$form_target}" method="post">
	
	{* </form> in form_bottom.tpl *}
	
{* </div> in form_bottom.tpl *}