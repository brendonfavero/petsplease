{include file="head.tpl"}

<div class="outer_shell">
	{include file='header.tpl'}
	<div class="content_shell">
		<!-- START LEFT COLUMN -->
		<div id="user_column">
			{module tag='my_account_links'}
		</div>
		<!-- END LEFT COLUMN -->
		
		<!-- START CONTENT BLOCK -->
		<div id="content_column_wide">
			{body_html}
		</div>
		<!-- END CONTENT BLOCK -->
		<div class="clr"></div>
	</div>
</div>

{include file="footer.tpl"}
