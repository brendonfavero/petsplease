{include file="head.tpl"}

<div class="outer_shell">
	{include file='header.tpl'}
	<div class="content_shell">
		<div id="tag_search_column">
			<div class="content_box">
				{module tag='tag_search'}
			</div>
		</div>
		
		<div id="content_column">
			{body_html}
		</div>
		<div class="clr"></div>
	</div>
</div>

{include file="footer.tpl"}
