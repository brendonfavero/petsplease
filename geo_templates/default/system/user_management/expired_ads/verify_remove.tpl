{* 7.1beta1-1209-g2ec630b *}

<div class="content_box">
	<h1 class="title my_account">{$messages.635}</h1>
	<h1 class="subtitle">{$messages.500145}</h1>
	<p class="page_instructions">{$messages.500144}</p>
	
	<form action="{$formTarget}" method="post">
		<input type="hidden" name="c[id]" value="{$classifiedId}" />
	
		<div class="center">
			<input type="submit" name="z[remove]" value="{$messages.500146}" class="button" />
		</div>
	</form>
</div>

<div class="center">
	<a href="{$expiredAdsLink}" class="button">{$messages.500147}</a>
</div>