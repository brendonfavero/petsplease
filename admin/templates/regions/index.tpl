{* 6.0.7-118-g306ec78 *}

{$admin_msgs}

<fieldset>
	<legend>Regions</legend>
	<div>
		<br />
		
		<div class="breadcrumbBorder">
			<ul id="breadcrumb">
				<li class="current">Viewing Regions For:</li>
				<li><a href="index.php?page=regions">Top</a></li>
				{if $parent}
					{foreach $parents as $p}
						<li{if $p@last} class="current2"{/if}><a href="index.php?page=regions&amp;parent={$p.id}">{$p.name}</a></li>
					{/foreach}
				{/if}
			</ul>
		</div>
		<br />
		{include file='regions/levelInfo.tpl'}
		<br />
		
		<form action="index.php?parent={$parent}&amp;p={$page}" method="post" id="massForm">
			<table style="border: 2px solid">
				<thead>
					<tr class="col_hdr_top">
						<th style="width: 21px;"><input type="checkbox" id="checkAllRegions" /></th>
						<th>Region (ID#)</th>
						<th style="width: 60px;">Listings</th>
						<th style="width: 60px;">Enabled?</th>
						{if $level.region_type=='country'||$level.region_type=='state/province'}
							<th style="width: 50px;"><span title="{$level.type_label} Abbreviation">Abbr.</span></th>
						{/if}
						<th>Unique Name</th>
						<th>Tax</th>
						<th>Display Order</th>
						<th style="width: 90px;"></th>
					</tr>
					<tr class="col_ftr">
						<td colspan="9" style="padding-left: 20px;">With Selected:
							<a href="#" class="mini_button massEditButton">Mass Edit</a>
							<a href="#" class="mini_button moveButton">Move</a>
							&nbsp; &nbsp; &nbsp; &nbsp;
							<a href="#" class="mini_cancel massDeleteButton">Delete</a>
						</td>
					</tr>
				</thead>
				<tfoot>
					<tr class="col_ftr">
						<td colspan="9" style="padding-left: 20px;">With Selected:
							<a href="#" class="mini_button massEditButton">Mass Edit</a>
							<a href="#" class="mini_button moveButton">Move</a>
							&nbsp; &nbsp; &nbsp; &nbsp;
							<a href="#" class="mini_cancel massDeleteButton">Delete</a>
							{if $pagination}
								<br />{$pagination}
							{/if}
						</td>
					</tr>
					
				</tfoot>
				<tbody>
					<tr class="{cycle values='row_color1,row_color2'}">
						<td class="center"></td>
						<td>
							<a href="index.php?page=regions">Top</a>
						</td>
						<td class="center"></td>
						<td class="center"></td>
						{if $level.region_type=='country'||$level.region_type=='state/province'}
							<td class="center"></td>
						{/if}
						<td></td>
						<td class="center"></td>
						<td class="center"></td>
						<td class="center">
							{if $parent}<a href="index.php?page=regions" class="mini_button">Top Regions</a>{/if}
						</td>
					</tr>
					{if $parent}
						{foreach $parents as $region}
							<tr class="{cycle values='row_color1,row_color2'}" id="row_{$region.id}">
								<td class="center"></td>
								<td{if $region.enabled=='no'} class="disabled"{/if}>
									<span style="font-family: monospace; color: #777777;">{'&nbsp;&nbsp;&nbsp;&nbsp;'|str_repeat:($region.level-1)}|--</span> <a href="index.php?page=regions&amp;parent={$region.id}">{$region.name} ({$region.id})</a>
									<div class="disabledSection"{if $region.enabled=='yes'} style="display: none;"{/if}>
										<strong style="color: red;">Warning:</strong> Parent Region Disabled!  Sub-Regions below are not currently usable on the site.
									</div>
								</td>
								<td class="center">{$region.listing_count}</td>
								<td class="center">
									{include file='regions/enabled.tpl'}
								</td>
								{if $level.region_type=='country'||$level.region_type=='state/province'}
									<td class="center">{$region.billing_abbreviation}</td>
								{/if}
								<td>{$region.unique_name}</td>
								<td class="center">
									{if $region.tax_percent>0}{$region.tax_percent}%{/if}
									{if $region.tax_percent>0 && $region.tax_flat>0} + {/if}
									{if $region.tax_flat>0}{$region.tax_flat|displayPrice}{/if}
									{if $region.tax_percent==0 && $region.tax_flat==0}-{/if}
								</td>
								<td class="center">{$region.display_order}</td>
								<td class="center">
									<a href="index.php?page=regions&amp;parent={$region.id}" class="mini_button">Enter</a>
									<a href="#" class="mini_button" style="visibility: hidden;">Edit</a>
								</td>
							</tr>
						{/foreach}
					{/if}
					{if !$regions}
						<tr><td colspan="10"><p class="page_note_error">No regions were found at this level!  You can create some new regions at this level using the "Add Region" or "Bulk Add" buttons at the bottom...</p></td></tr>
					{else}
						{foreach $regions as $region}
							<tr class="{cycle values='row_color1,row_color2'}" id="row_{$region.id}">
								<td class="center"><input type="checkbox" name="regions[]" class="regionCheckbox" value="{$region.id}" /></td>
								<td{if $region.enabled=='no'} class="disabled"{/if}>
									<span style="font-family: monospace; color: #777777;">{'&nbsp;&nbsp;&nbsp;&nbsp;'|str_repeat:($region.level-1)}|--</span> <a href="index.php?page=regions&amp;parent={$region.id}">{$region.name|fromDB} ({$region.id})</a>
								</td>
								<td class="center">{$region.listing_count}</td>
								<td class="center">
									{include file='regions/enabled.tpl'}
								</td>
								{if $level.region_type=='country'||$level.region_type=='state/province'}
									<td class="center">{$region.billing_abbreviation}</td>
								{/if}
								<td>{$region.unique_name}</td>
								<td class="center">
									{if $region.tax_percent>0}{$region.tax_percent}%{/if}
									{if $region.tax_percent>0 && $region.tax_flat>0} + {/if}
									{if $region.tax_flat>0}{$region.tax_flat|displayPrice}{/if}
									{if $region.tax_percent==0 && $region.tax_flat==0}-{/if}
								</td>
								<td class="center">{$region.display_order}</td>
								<td class="center">
									<a href="index.php?page=regions&amp;parent={$region.id}" class="mini_button">Enter</a>
									<a href="index.php?page=region_edit&amp;region={$region.id}&amp;p={$page}" class="mini_button lightUpLink">Edit</a>
								</td>
							</tr>
						{/foreach}
					{/if}
				</tbody>
			</table>
		</form>
		<br />
		<div class="center">
			<a href="index.php?page=region_create&amp;parent={$parent}" class="mini_button lightUpLink">Add Region</a>
			<a href="index.php?page=region_create_bulk&amp;parent={$parent}" class="mini_button lightUpLink">Bulk Add</a>
		</div>
	</div>
</fieldset>