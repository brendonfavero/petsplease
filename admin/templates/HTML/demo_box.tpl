{* 7.1beta5-45-gbad7dcf *}
{* Used for demo box at top of page on demo installation *}
<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready(function () {
		jQuery('#demo_box_label,#demo_box').fadeTo('fast',0.9);
		
		var demoHeight=jQuery('#demo_box').outerHeight(true);
		var isDemoOpen = false;
		jQuery('#demo_box').css({ top: '-'+demoHeight+'px' });
		
		jQuery('#demo_box_label').css({ top: '-15px' });
		
		jQuery('#demo_box_label').hover (function () { 
			if (isDemoOpen) {
				//don't do anything when box is open
				return;
			}
			jQuery(this).stop().animate({ top: '-1px' },500);
		}, function () { 
			if (isDemoOpen) {
				//don't do anything when box is open
				return;
			}
			jQuery(this).stop().animate({ top: '-15px' }, 500);
		});
		
		jQuery('#demo_box_open_button').click(function () {
			var label = jQuery('#demo_box_label').stop();
			var box = jQuery('#demo_box').stop();
			
			isDemoOpen = !isDemoOpen;
			
			if (isDemoOpen) {
				//open the demo box
				
				//start the things off where they should be
				label.css({ top: '-1px' });
				box.css({ top: '-'+demoHeight+'px' });
				
				//now animate them to where they go
				label.animate({ top: (demoHeight-5)+'px' },800);
				box.animate({ top: '0px' },800);
				//show/hide the show/hide buttons
				jQuery('#demo_button_closed').hide('fast');
				jQuery('#demo_button_open').show('fast');
			} else {
				//close the demo box
				
				//start the things off where they should be
				label.css({ top: (demoHeight-5)+'px' });
				box.css({ top: '0px' });
				
				//now animate them closed
				label.animate({ top: '-15px' });
				box.animate({ top: '-'+demoHeight+'px' });
				//show/hide the show/hide buttons
				jQuery('#demo_button_closed').show('fast');
				jQuery('#demo_button_open').hide('fast');
			}
		});
	});
	//]]>
</script>
<div id="demo_box_label">
	{if $ctrl_msg}{$ctrl_msg}{else}Demo {/if}Controls &gt;
	
	<a href="#" onclick="return false;" class="mini_button" id="demo_box_open_button">
		<span id="demo_button_closed">
			Change <span class="text_green">Product</span> /
			<span class="text_green">Master Switches</span>
			{if !$in_admin} / <span class="text_green">Color Themes</span>{/if}
		</span>
		<span id="demo_button_open" style="display: none;">Hide Demo Controls</span>
	</a>
	|
	<a href="{if $in_admin}..{else}admin{/if}/index.php" class="mini_button">Go to <span class="text_green">{if $in_admin}Client{else}Admin{/if}</span> Demo</a>
</div>
<div id="demo_box">
	<div>
		{if $err}
			<div style="text-align: center; color: red;">{$err}</div>
		{/if}
		
		<form method="post" id="switch_product" action="" style="display: inline;">
			<div class="edition_dropdown">
				<h1>Product Selection</h1>
				<br />
				<select name="developer_force_type" onchange="$('switch_product').submit();">
					{foreach $valid_products as $name}
						<option value="{$name}"{if $name==$current_type} selected="selected"{/if}>
							GeoCore {$name|capitalize}
						</option>
					{/foreach}
				</select>
			</div>
		</form>
		
		<form method="post" id="switch_master" action="" style="display: inline;">
			<div class="master_dropdown">
				<h1>Master Switches</h1>
				{foreach $masters as $name => $value}
					
					<input type="hidden" value="off" name="master_{$name}" />
					<label{if $name==$only} onclick="alert('Note: can not change that master switch when using GeoCore {$name|capitalize} product.'); return false;"{/if}>
						<input type="checkbox" value="on" {if $value === 'on'}checked="checked"{/if} {if $name==$only}readonly="readonly"{/if} name="master_{$name}" />
						{$name|regex_replace:"/_/":" "|capitalize}
					</label>
					<br />
				{/foreach}
				<div style="text-align: center;">
					<input type="submit" value="Update" class="mini_button" />
				</div>
			</div>
		</form>
		
		{if !$in_admin}
			<form method="post" id="switch_theme" action="" style="display: inline;">
				<div class="theme_dropdown">
					<h1>Color Themes</h1>
					<label>Primary Color Theme: 
						<select name="css_primary_tset" class="theme_dropdown_text" onchange="$('switch_theme').submit();">
							{foreach from=$primary_tsets item='tset_title' key='tset'}
								<option value="{$tset}"{if $primary==$tset} selected="selected"{/if}>{$tset_title}</option>
							{/foreach}
						</select>
						<div class="theme_color_box" style="background-color: {$colors.$primary};">&nbsp;</div>
		
					</label>
					<br />
					<label>Secondary Color Theme: 
						<select name="css_secondary_tset" class="theme_dropdown_text" onchange="$('switch_theme').submit();">
							{foreach from=$secondary_tsets item='tset_title' key='tset'}
								<option value="{$tset}"{if $secondary==$tset} selected="selected"{/if}>{$tset_title}</option>
							{/foreach}
						</select>
						<div class="theme_color_box" style="background-color: {$colors.$secondary};">&nbsp;</div>
					</label>
					<p style="white-space: normal; max-width: 300px;">
						<a href="http://geodesicsolutions.com/support/geocore-wiki/doku.php/id,tutorials;design_adv;using_color_template_set/" onclick="window.open(this.href); return false;" style="color:blue;">How to apply color themes to your own site</a>
				</div>
			</form>
		{/if}
		
		<p><strong>Note:</strong> These controls will not show on a normal installation.</p>
	</div>
</div>