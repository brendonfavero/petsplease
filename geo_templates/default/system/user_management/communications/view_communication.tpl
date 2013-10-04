{* 7.1beta1-1209-g2ec630b *}
{if $sender != '--'}<form action="{$formTarget}" method="post">{/if}
	<div class="content_box">
		<h1 class="title my_account">{$messages.625}</h1>
		<h1 class="subtitle">{$messages.410}</h1>
		<p class="page_instructions">{$messages.411}</p>
	</div>
	<br />
	<div class="content_box">
		<h2 class="title">{$messages.502049}</h2>
		<div class="row_even">
			<label class="field_label">{$messages.412}</label>
			{if $sender_id}<a href="{$classifieds_file_name}?a=6&amp;b={$sender_id}">{/if}
			{$sender}
			{if $sender_id}</a>{/if}
		</div>
	
		<div class="row_odd">
			<label class="field_label">{$messages.413}</label>
			{$dateSent}
		</div>
	
		<div class="row_even">
			<label class="field_label">{$messages.1186}</label>
			<span class="text_highlight">{$listingTitle}</span>
		</div>
	</div>
	<br />
	<div class="content_box">
		<h1 class="subtitle">{$messages.414}</h1>
		<div class="box_pad">
			{$message}
		</div>
	</div>
	{if $sender != '--'}
		<br />
		<div class="content_box">
			<h1 class="title">{$messages.415}</h1>
			<textarea cols="138" rows="15" name="d[message]" class="field">{$messages.254}</textarea>
		
			<div class="center">
				{if $isPublicQuestion}
					<input class="field" type="checkbox" name="d[public_answer]" value="1" /> {$messages.500893}<br />
				{/if}
				
				<input type="submit" name="z" value="{$messages.1197}" class="button" />
				
				<input type="hidden" name="d[replied_to_this_messages]" value="{$comm_id}" />
				<input type="hidden" name="d[message_to]" value="{$newMessage.to}" />
				<input type="hidden" name="d[from]" value="{$newMessage.from}" />
				<input type="hidden" name="d[regarding_ad]" value="{$newMessage.about}" />
			</div>
		
		</div>
	{/if}
	
	<div class="center">
		<a href="{$userManagementHomeLink}" class="button">{$messages.416}</a>
	</div>
	
{if $sender != '--'}</form>{/if}