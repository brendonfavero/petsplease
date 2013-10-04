{* 7.1beta4-99-g84389d8 *}
{$adminMessages}
<form action="" method="post">
	<fieldset>
		<legend>Enabled Sharing Methods</legend>
		<div>
			{foreach $methods as $method => $enabled}
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn"><input type="checkbox" value="1" name="enabled[{$method}]" {if $enabled}checked="checked"{/if} /></div>
					<div class="rightColumn">{$method|replace:'_':' '|capitalize}</div>
					<div class="clearColumn"></div>
				</div>
			{/foreach}
		</div>
	</fieldset>
	<div style="text-align: center;"><input type="submit" name="auto_save" value="Save" /></div>
</form>