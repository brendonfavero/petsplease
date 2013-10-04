{* 7.1beta1-1148-g92cabfc *}
{$adminMsgs}

<script>
	//<![CDATA[
	jQuery(document).ready(function () {
		jQuery('a.resetText').click(function () {
			var defaultText = jQuery(this).find('input').val();
			jQuery(this).closest('div').find('.langText').val(defaultText);
			return false;
		});
	});      
	//]]>
</script>

<form action="" method="post">
	<fieldset>
		<legend>Text for {$addon_title}</legend>
		<div>
			<input type="hidden" name="auth_tag" value="{$addon_auth_tag}" />
			{foreach $text_info as $index => $info}
				{if $info.section && $current_section!=$info.section}
					{if $current_section}
						<div class="center"><input type="submit" name="auto_save" value="Save" /></div>
						{* Close the <fieldset><div> from previous iteration *}
						</div></fieldset>
					{/if}
					{$current_section=$info.section}
					<fieldset><legend>{$info.section}</legend><div>
				{/if}
				<div class="col_hdr">{$info.name}</div>
				{if $info.desc}
					<p class="page_note">{$info.desc}</p>
				{/if}
				{foreach $info.lang as $lang_id => $lang_val}
					<div class="{cycle values='row_color1,row_color2'}">
						<div class="leftColumn">
							{$languages.$lang_id} Language
						</div>
						<div class="rightColumn">
							{if $info.type=='input'}
								<input type="text" class="langText" size="40" name="tag[{$lang_id}][{$index}]" value="{$lang_val|escape}" />
							{else}
								<textarea class="langText" name="tag[{$lang_id}][{$index}]" cols="50" rows="5">{$lang_val|escape}</textarea>
							{/if}
							<br />
							<a href="#" class="resetText">
								Reset to Default
								<input type="hidden" class="defaultText" value="{$info.default|escape}" />
							</a>
						</div>
						<div class="clearColumn"></div>
					</div>
				{/foreach}
			{/foreach}
			<div class="center">
				<input type="submit" name="auto_save" value="Save" />
			</div>
			{if $current_section}
				</div></fieldset>
			{/if}
		</div>
	</fieldset>
</form>