{* 7.1beta1-1139-g3d5df08 *}
{$adminMsgs}
<form action="" method="post">
	<fieldset>
		<legend>Featured Listing Gallery Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="featured_show_automatically" value="1"
						{if $featured_show_automatically}checked="checked"{/if} />
				</div>
				<div class="rightColumn">Show featured gallery automatically?</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="featured_2nd_page" value="1"
						{if $featured_2nd_page}checked="checked"{/if} />
				</div>
				<div class="rightColumn">Show gallery on 2nd page and up?</div>
				<div class="clearColumn"></div>
			</div>
			{if $is.classifieds&&$is.auctions}
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">
						<input type="checkbox" name="featured_show_listing_type" value="1"
							{if $featured_show_listing_type}checked="checked"{/if} />
					</div>
					<div class="rightColumn">Display Listing Type (Classified/Auction)?</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="featured_carousel" value="1"
						{if $featured_carousel}checked="checked"{/if} />
				</div>
				<div class="rightColumn">Use jQuery simple carousel?</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Max # of featured listings
				</div>
				<div class="rightColumn">
					<input type="number" name="featured_max_count" value="{$featured_max_count}" size="4" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					# Columns in each Row<br />
					(Recommended 5 or less)
				</div>
				<div class="rightColumn">
					<input type="number" name="featured_column_count" value="{$featured_column_count}" size="4" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Show Featured Level(s)
				</div>
				<div class="rightColumn">
					{for $level=1 to 5}
						<label><input type="checkbox" name="featured_levels[{$level}]" value="1"
							{if $featured_levels.$level}checked="checked"{/if} /> Level {$level}</label>
					{/for}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Thumbnail Max Size
				</div>
				<div class="rightColumn">
					<input type="number" name="featured_thumb_width" value="{$featured_thumb_width}" size="3" />pixels (width) X
					<input type="number" name="featured_thumb_height" value="{$featured_thumb_height}" size="3" />pixels (height)
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Max Length of Description (if set to show)<br />
					(0 for no limit)
				</div>
				<div class="rightColumn">
					<input type="number" name="featured_desc_length" value="{$featured_desc_length}" size="3" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					Edit Which Fields are Displayed
				</div>
				<div class="rightColumn">
					Edit on <a href="index.php?page=fields_to_use&amp;activeTab=addons">Listing Setup &gt; Fields to Use</a>
				</div>
				<div class="clearColumn"></div>
			</div>
			
			<div class="center">
				<input type="submit" name="auto_save" value="Save" />
			</div>
		</div>
	</fieldset>
</form>