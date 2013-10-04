{* 7.1beta1-1209-g2ec630b *}
<br />
<div class="content_box">
	<h1 class="title my_account">{$messages.102984}</h1>
	<p class="page_instructions">{$messages.102985}</p>
	
	<form action="{$searchFormTarget}" method="post">
		<div class="row_even">
			{if $searchError}
				<span class="error_message">{$searchError|fromDB}</span>
				<br /><br />
			{/if}
			<input type="radio" name="d[field_type]" value="1" checked="checked" /> {$messages.102986} 
			<input type="radio" name="d[field_type]" value="2" checked="checked" /> {$messages.102987} 
			<input type="radio" name="d[field_type]" value="3" checked="checked" /> {$messages.102988}
			<br /><br />
			<input type="text" name="d[text_to_search]" size="40" maxlength="30" class="field" />
			<input type="submit" name="search" value="{$messages.102989}" class="button" />
		</div>
	</form>
</div>


<div class="center">
	<a class="button" href="{$userManagementHomeLink}">{$messages.102979}</a>
</div>