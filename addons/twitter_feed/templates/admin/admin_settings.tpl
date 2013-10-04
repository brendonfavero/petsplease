{* 612208c *}

{$adminMsgs}

<fieldset>
	<legend>Twitter Feed Settings</legend>
	<div>
		<form action="" method="post">
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					Scroll Behavior
				</div>
				<div class="rightColumn">
					<input type="radio" name="d[behavior]" value="default" {if $config.behavior == 'default'}checked="checked"{/if} onclick="if(this.checked)$('interval').disabled=false;" /> Default (scrolling)<br />
					<input type="radio" name="d[behavior]" value="all" {if $config.behavior == 'all'}checked="checked"{/if} onclick="if(this.checked)$('interval').disabled=true;" /> Alternate (static)
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					Scroll Delay
				</div>
				<div class="rightColumn">
					Show a new tweet every <input type="text" name="d[interval]" value="{$config.interval}" {if $config.behavior == 'all'}disabled="disabled"{/if} size="4" id="interval" /> seconds
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					Total number of tweets to show
				</div>
				<div class="rightColumn">
					<input type="text" name="d[rpp]" value="{$config.rpp}" size="4" />
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					Default Username
				</div>
				<div class="rightColumn">
					<input type="text" name="d[defaultuser]" value="{$config.defaultuser}" size="10" />
				</div>
				<div class="clearColumn"></div>
			</div>
			
			{* NOTE: the settings for 'scrollbar,' 'loop,' and 'live' have been intentionally ommitted from the admin form. Very few users will need to modify those. *}
						
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					Show Hashtags
				</div>
				<div class="rightColumn">
					<input type="radio" name="d[hashtags]" value="1" {if $config.hashtags == 1}checked="checked"{/if} /> Yes<br />
					<input type="radio" name="d[hashtags]" value="0" {if $config.hashtags == 0}checked="checked"{/if} /> No
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					Show Timestamps
				</div>
				<div class="rightColumn">
					<input type="radio" name="d[timestamps]" value="1" {if $config.timestamps == 1}checked="checked"{/if} /> Yes<br />
					<input type="radio" name="d[timestamps]" value="0" {if $config.timestamps == 0}checked="checked"{/if} /> No
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					Show Avatars
				</div>
				<div class="rightColumn">
					<input type="radio" name="d[avatars]" value="1" {if $config.avatars == 1}checked="checked"{/if} /> Yes<br />
					<input type="radio" name="d[avatars]" value="0" {if $config.avatars == 0}checked="checked"{/if} /> No
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					Dimensions of Widget
				</div>
				<div class="rightColumn">
					Width: <input type="text" name="d[width]" value="{$config.width}" size="4" {if $config.autowidth}disabled="disabled"{/if} id="width" /> pixels, or auto-detect: <input type="checkbox" name="d[autowidth]" {if $config.autowidth}checked="checked"{/if} onclick="if(this.checked)$('width').disabled=true; else $('width').disabled=false;" /><br />
					Height: <input type="text" name="d[height]" value="{$config.height}" size="4" /> pixels
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">
					Widget Theme
				</div>
				<div class="rightColumn">
					Shell Color: #<input type="text" id="shell" name="d[shell]" onchange="colorizeSwatches()" value="{$config.shell}" size="6" /> <span id="swatch_shell" style="border: 1px solid black; padding: 1px; font-size: 12px;">Color Swatch</span><br />
					Heading Text Color: #<input type="text" id="heading" name="d[heading]" onchange="colorizeSwatches()" value="{$config.heading}" size="6" /> <span id="swatch_heading" style="border: 1px solid black; padding: 1px; font-size: 12px;">Color Swatch</span><br />
					Background Color: #<input type="text" id="background" name="d[background]" onchange="colorizeSwatches()" value="{$config.background}" size="6" /> <span id="swatch_background" style="border: 1px solid black; padding: 1px; font-size: 12px;">Color Swatch</span><br />
					Main Text Color: #<input type="text" id="text" name="d[text]" onchange="colorizeSwatches()" value="{$config.text}" size="6" /> <span id="swatch_text" style="border: 1px solid black; padding: 1px; font-size: 12px;">Color Swatch</span><br />
					Link Color: #<input type="text" id="links" name="d[links]" onchange="colorizeSwatches()" value="{$config.links}" size="6" /> <span id="swatch_links" style="border: 1px solid black; padding: 1px; font-size: 12px;">Color Swatch</span>
				</div>
				<script type="text/javascript">
				colorizeSwatches = function() {
					$('swatch_shell').style.backgroundColor = "#" + $('shell').value;
					$('swatch_heading').style.backgroundColor = "#" + $('heading').value;
					$('swatch_background').style.backgroundColor = "#" + $('background').value;
					$('swatch_text').style.backgroundColor = "#" + $('text').value;
					$('swatch_links').style.backgroundColor = "#" + $('links').value;
				}
				Event.observe('window','load',colorizeSwatches());
				</script>
				<div class="clearColumn"></div>
			</div>
			
			<div style="margin: 0 auto; width: 200px;"><input type="submit" class="button" name="auto_save" value="Save" onclick="$('width').disabled=false;$('interval').disabled=false;" /></div>
		</form>
	</div>
</fieldset>