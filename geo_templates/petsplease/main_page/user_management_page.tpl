{include file="head.tpl"}
<div class="outer_shell">
	{include file='header.tpl'}
	<div class="content_shell">
		<!-- START LEFT COLUMN -->
		<div id="user_column">
			<!-- <h1 class="title">My PetsPlease</h1> -->
			{module tag='my_account_links'}
		</div>
		<!-- END LEFT COLUMN -->
		
		<!-- START CONTENT BLOCK -->
		<div id="content_column_wide">
			{body_html}
		</div>
		<div class="clr"></div>
		<!-- END CONTENT BLOCK -->
	</div>
</div>

{include file="footer.tpl"}
