{* 7.1.2-70-g2e86b49 *}
{include file="head.tpl" alt_css="css/addon/storefront/default_style.css"}

<div class="outer_shell">
	{include file='header.tpl'}
	
	<div class="content_shell">
		{if $storefront_logo}
			<div class="center">
				{$storefront_logo}
			</div>
			<div class="clr"><br /></div>
		{/if}
		
		{if !$classified_id}
			<!-- START LEFT COLUMN -->
			<div id="storefront_side_column">
				{if $is_owner}
					<a href='index.php?a=ap&amp;addon=storefront&amp;page=control_panel' class='large_button'>Manage My Storefront</a>
					<br />
				{/if}
				
				{* uncomment to hide categories box if no categories: {if $storefront_categories} *}
				{* note: if the conditional is here, a storefront's "home" link will not appear if there are no categories *}
					<div class="content_box">
						<h1 class="title">Store Categories</h1>
						<ul>
							<li>{$storefront_homelink}</li>
							{foreach from=$storefront_categories item='cat'}
								<li><a href='{$cat.url}'>{$cat.category_name}</a></li>
									{foreach $cat.subcategories as $sub_id => $sub}
										<li><a href='{$sub.url}' class="subcategory">{$sub.category_name}</a></li>
									{/foreach}
							{/foreach}
						</ul>
					</div>
					<br />
				{* uncomment to hide categories box if no categories: {/if} *}
				
				{if $storefront_pages}
					<div class="content_box">
						<h2 class="title">Store Pages</h2>
						<ul>
							{foreach from=$storefront_pages item='page'}
								<li><a href='{$page.url}'>{$page.link_text}</a></li>
							{/foreach}
						</ul>
					</div>
					<br />
				{/if}
				
				{if $display_newsletter}
					<div class="content_box">
						<form action='' id='newSubscriber' method='post'>
							<h1 class="title">Newsletter</h1>
							{if $storefront_email_added}
								<div class="success_box">Thank You!</div>
							{else}
								<div class="center">
									<input type='hidden' name='newSubscriber' value='1' />
									<input type='text' name='email' id='email' value='Email Address' onfocus='javascript: document.getElementById("subscribeSubmit").disabled = false;' class="field" />
									<input type='submit' name='subscribeSubmit' id='subscribeSubmit' value='subscribe' disabled='disabled' class="button" />
								</div>
							{/if}
						</form>
					</div>
				{/if}
				
			</div>
			<!-- END LEFT COLUMN -->
		{/if}
		
		<!-- START CONTENT BLOCK -->
		<div id="content_column_wide">
			
			
			{if $updateResult}
				<div class="success_box">
					{$updateResult}
				</div>
			{/if}
			
			
			{if $storefront_welcome_note}
				<div class="content_box row_even">
					{$storefront_welcome_note}
					<div class="clr"></div>
				</div>
				<br />
			{/if}
			{body_html}
		</div>
		<!-- END CONTENT BLOCK -->
	</div>
</div>

{include file="footer.tpl"}
