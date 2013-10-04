{* 6.0.7-3-gce41f93 *}
{$adminMsgs}
{include file='design/parts/designModeBox.tpl'}
<form action="index.php?page=design_sets" method="post">
	<fieldset>
		<legend>Template Sets</legend>
		<div>
			<table style="border: 3px solid #DDD;">
				<thead>
					<tr class="col_hdr">
						<th style="width: 50px;">Active</th>
						<th style="width: 80px;">Admin Editing</th>
						{if ($advMode && $t_sets_used|@count > 2)||(!$advMode && $t_sets_used|@count>1)}
							<th style="width: 100px;">Template<br />Seek Order</th>
						{/if}
						<th>Template Set Name</th>
						<th style="width: 80px;">Language</th>
						<th colspan="5">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					{foreach from=$t_sets_used item="t_set_used" name="sets_used"}
						{if $t_set_used!=='default'||$canEditDefault||$advMode}
							{assign var=tsetDisplayed value=1}
							<tr class="{cycle values="row_color1,row_color2"}">
								<td style="text-align: center;">
									<input type="checkbox" name="activeSets[{$t_set_used}]" value="1" checked="checked"{if $t_set_used=='default'} disabled="disabled"{else} class="tset_active"{/if} />
								</td>
								<td style="text-align: center;">
									<input type="{if $advMode}checkbox{else}radio{/if}" name="workWith[]" {if in_array($t_set_used,$workWithList)}checked="checked"{/if} value="{$t_set_used}" />
								</td>
								{if ($advMode && $t_sets_used|@count > 2)||(!$advMode && $t_sets_used|@count>1)}
									<td class="{$row_color}" style="text-align: center;">
										<button name="move[{$t_set_used|escape}]" value="up" alt="Move up" title="Move up"{if $smarty.foreach.sets_used.first || $t_set_used=='default'} style="visibility: hidden;"{/if}>
											<img src="admin_images/admin_arrow_up2.gif" alt="Move up" />
										</button>
										<button name="move[{$t_set_used|escape}]" value="down" alt="Move down" title="Move down"{if $smarty.foreach.sets_used.last||($advMode&&($smarty.foreach.sets_used.total-2)<$smarty.foreach.sets_used.iteration)} style="visibility: hidden;"{/if}>
											<img src="admin_images/admin_arrow_down2.gif" alt="Move up" />
										</button>
									</td>
								{/if}
								<td>{$t_set_used}</td>
								<td>
									{if $t_set_used!='default'}
										<select name="language[{$t_set_used|escape}]">
											<option value="0">Any Language</option>
											{foreach $languages as $language_id => $language}
												<option value="{$language_id}"{if $t_sets_lang[$t_set_used]==$language_id} selected="selected"{/if}>{$language}</option>
											{/foreach}
										</select>
									{/if}
								</td>
								<td class="center" style="width: 80px;">
									<a href="index.php?page=design_manage&amp;forceEditTset={$t_set_used}&amp;forceChange=1&amp;location={$t_set_used}/main_page/" class="mini_button">
										Edit Templates
									</a>
								</td>
								<td class="center" style="width: 80px;">
									<a href="{if $t_set_used=='default'}index.php?page=design_sets_create_main{else}index.php?page=design_sets_copy&amp;t_set={$t_set_used}{/if}" class="mini_button lightUpLink">Copy</a>
								</td>
								<td class="center" style="width: 80px;">
									<a href="index.php?page=design_sets_download&amp;t_set={$t_set_used}" class="mini_button lightUpLink">Download</a>
								</td>
								<td class="center" style="width: 80px;">
									<a href="index.php?page=design_sets_scan&amp;t_set={$t_set_used}" class="mini_button lightUpLink">Re-Scan Attachments</a>
								</td>
								<td class="center" style="width: 10px;">
									{if $importTextTsets.$t_set_used}
										<a href="index.php?page=design_sets_import_text&amp;t_set={$t_set_used}" class="mini_button lightUpLink">
											Import Text Changes
										</a>
									{/if}
								</td>
							</tr>
						{/if}
					{/foreach}
					
					{foreach $t_sets as $t_set}
						{if !in_array($t_set,$t_sets_used)}
							{if !$inactiveTsetDisplayed}
								{assign var=inactiveTsetDisplayed value=1}
								<tr>
									<td colspan="8"><hr style="width: 70%; color: #DDD;" /></td>
								</tr>
							{/if}
							<tr class="{cycle values="row_color1,row_color2"}">
								<td style="text-align: center;">
									<input type="checkbox" name="activeSets[{$t_set}]" value="1" class="tset_active" />
								</td>
								<td style="text-align: center;"><input type="{if $advMode}checkbox{else}radio{/if}" name="workWith[]" {if in_array($t_set,$workWithList)}checked="checked"{/if} value="{$t_set}" /></td>
								{if ($advMode && $t_sets_used|@count > 2)||(!$advMode && $t_sets_used|@count>1)}<td class="{$row_color}"></td>{/if}
								<td>{$t_set}</td>
								<td>
									<select name="language[{$t_set|escape}]" style="display: none;">
										<option value="0">Any Language</option>
										{foreach $languages as $language_id => $language}
											<option value="{$language_id}"{if $t_sets_lang[$t_set]==$language_id} selected="selected"{/if}>{$language}</option>
										{/foreach}
									</select>
								</td>
								<td class="center" style="width: 80px;">
									<a href="index.php?page=design_manage&amp;forceEditTset={$t_set}&amp;forceChange=1&amp;location={$t_set}/main_page/" class="mini_button">
										Edit Templates
									</a>
								</td>
								<td style="text-align: center;">
									<a href="index.php?page=design_sets_copy&amp;t_set={$t_set}" class="mini_button lightUpLink">Copy</a>
								</td>
								<td style="text-align: center;">
									<a href="index.php?page=design_sets_download&amp;t_set={$t_set}" class="mini_button lightUpLink">Download</a>
								</td>
								<td style="text-align: center;">
									<a href="index.php?page=design_sets_scan&amp;t_set={$t_set}" class="mini_button lightUpLink">Re-Scan Attachments</a>
								</td>
								<td>
									<a href="index.php?page=design_sets_delete&amp;t_set={$t_set}" class="mini_cancel lightUpLink">Delete</a>
								</td>
							</tr>
						{/if}
					{/foreach}
					{if !$tsetDisplayed && !$inactiveTsetDisplayed}
						<tr>
							<td colspan="7" class="center">
								No Template Sets Found!
							</td>
						</tr>
					{/if}
				</tbody>
			</table>
			<br />
			<div style="text-align: center;">
				<input type="hidden" name="auto_save" value="1" />
				<input type="submit" name="auto_save" value="Save Settings" />
				<br /><br />
				<a href='index.php?page=design_sets_upload' class='lightUpLink mini_button'>Upload Template Set</a>
				<br /><br />
				<a href="index.php?page=design_sets_copy&amp;t_set=merged" class="mini_button lightUpLink">Merge Sets Together</a>
				
				{if $showExport}
					<br /><br />
					<a class="lightUpLink mini_button" href="index.php?page=design_sets_export">Export Pre-5.0 Design to Template Set</a>
				{/if}
				<br /><br />
				<a class="lightUpLink mini_button" href="index.php?page=design_sets_create_main">Create Main Template Set</a>
				<div class="clearColumn"></div>
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Advanced Settings</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">
					<input type="checkbox" name="useGoogleLibApi" value="1" id="useGoogleLibApi" {if $useGoogleLibApi}checked="checked"{/if} />
				</div>
				<div class="rightColumn">
					Use Google Libraries API (Allows faster loading of available JS libraries - <a href="http://code.google.com/apis/libraries/devguide.html" onclick="window.open(this.href); return false;">Info Here</a>)
				</div>
				<div class="clearColumn"></div>
			</div>
			
			
			<div class="{cycle values='row_color1,row_color2'}"{if !$advMode} style="display: none;"{/if}>
				<div class="leftColumn">
					<input name="useCHMOD" id="chmod" type="checkbox" value="1" {if $useCHMOD}checked="checked"{/if} />
				</div>
				<div class="rightColumn">
					<label for="chmod">CHMOD 777 Files<br />(affects operations on files)</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			{if $canEditSystemTemplates && $advMode}
				{include file="design/parts/editSystemWarning.tpl"}
			{/if}
			<div class="{cycle values='row_color1,row_color2'}"{if !$advMode} style="display: none;"{/if}>
				<div class="leftColumn">
					<input name="canEditSystemTemplates" id="canEditSystemTemplates" type="checkbox" value="1" {if $canEditSystemTemplates}checked="checked"{/if} />
				</div>
				<div class="rightColumn">
					<label for="canEditSystemTemplates">Allow Edit of system, module, and addon Templates</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			{if $iamdeveloper}
				<div class="{cycle values='row_color1,row_color2'}"{if !$advMode} style="display: none;"{/if}>
					<div class="leftColumn">
						<input name="allowDefaultTsetEdit" id="allowDefaultTsetEdit" type="checkbox" value="1" {if $allowDefaultTsetEdit}checked="checked"{/if} />
					</div>
					<div class="rightColumn">
						<label for="allowDefaultTsetEdit">Allow Edit of default template set<br />(IAMDEVELOPER Setting)</label>
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			
			<div class="center">
				<br />
				<input type="submit" name="auto_save" value="Save Settings" />
			</div>
		</div>
	</fieldset>
</form>


