{* 6.0.7-3-gce41f93 *}
{$adminMsgs}

{include file="design/parts/workingOn.tpl"}

<h1 class="no_border" style="font-size: 14pt;">
	Template(s) Attached to: 
	<span class="text_blue">
		{if $addonTitle}
			{$addonTitle} - 
		{/if}
		{$pageName}
		{if $advMode}
			(Page ID: {$pageId})
		{/if}
		{if $pageInfo.admin_label} - {$pageInfo.admin_label}{/if}
	</span>
</h1>
<fieldset>
	<legend>Edit Template(s) Attached for This Page</legend>
	<div>
		{if !$read_only}<form action="index.php?page=page_attachments_edit&amp;pageId={$pageId|escape}&amp;t_set={$t_set|escape}" method="post">{/if}
			{if $read_only}
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">VIEW ONLY</div>
					<div class="rightColumn">
						Changes to default template set not permitted!
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			{if $advMode}
				<input type="hidden" name="t_set" value="{$t_set}" />
				{if $workWithList.1}
					<div class="{cycle values="row_color1,row_color2"}">
						<div class="leftColumn">Attachment(s) Saved For:</div>
						<div class="rightColumn">
							<span class="text_green">{$t_set}</span>
							<br />
							<strong>Change to:</strong> 
							{foreach from=$workWithList item=workWith}
								{if $workWith!=$t_set}
									[<a href="index.php?page=page_attachments_edit&amp;pageId={$pageId|escape}&amp;t_set={$workWith|escape}">{$workWith}</a>] &nbsp;
								{/if}
							{/foreach}
						</div>
						<div class="clearColumn"></div>
					</div>
				{/if}
				{*  Commenting out for now, don't think this is a feature that
					would be used much, and it just makes things more complicated...
					The below would be the alternate to showing "Attachment(s) Saved For:" above
					
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">Attachments from:</div>
					<div class="rightColumn">
						<strong>{$t_set}</strong>/main_page/attachments/templates_to_page/{$pageId}.php
						{if $workWithList.1}
							<br />
							<strong>Change to:</strong> 
							{foreach from=$workWithList item=workWith}
								{if $workWith!=$t_set}
									[<a href="index.php?page=page_attachments_edit&amp;pageId={$pageId|escape}&amp;t_set={$workWith|escape}">{$workWith}</a>] &nbsp;
								{/if}
							{/foreach}
						{/if}
					</div>
					<div class="clearColumn"></div>
				</div>
				{if !$read_only}
					<div class="{cycle values="row_color1,row_color2"}">
						<div class="leftColumn">Save attachment changes to:</div>
						<div class="rightColumn">
							<select name="t_set">
								{foreach from=$workWithList item="workWith"}
									{if $workWith!='default' || $canEditDefault}
										<option{if $t_set==$workWith} selected="selected"{/if}>{$workWith}</option>
									{/if}
								{/foreach}
							</select>
							/main_page/attachments/templates_to_page/{$pageId}.php
						</div>
						<div class="clearColumn"></div>
					</div>
				{/if}
				*}
			{else}
				<input type="hidden" name="t_set" value="{$t_set}" />
			{/if}
			{if $addonTitle}
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">Addon:</div>
					<div class="rightColumn">
						{$addonTitle}
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			{if $addon||$pageInfo.extraPage}
				<div class="{cycle values="row_color1,row_color2"}">
					<div class="leftColumn">Page URL:</div>
					<div class="rightColumn">
						{if $addon}
							<a href="{$classifieds_url}?a=ap&amp;addon={$addon}&amp;page={$addonPage}" onclick="window.open(this.href); return false;">{$classifieds_url}?a=ap&amp;addon={$addon}&amp;page={$addonPage}</a>
						{/if}
						{if $pageInfo.extraPage}
							<a href="{$classifieds_url}?a=28&amp;b={$pageId}" onclick="window.open(this.href); return false;">{$classifieds_url}?a=28&amp;b={$pageId}</a>
						{/if}
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			
			<div class="col_hdr_top" style="margin-top: 15px;">Default Template Attachment</div>
			
			<div class="{cycle values="row_color1,row_color2"}">
				<div class="leftColumn">Default Template:</div>
				<div class="rightColumn">
					{if $attachments.1.0 && !$templates[$attachments.1.0]}
						<div class="error"><strong>Warning:</strong> Could not find the current template attached (<strong>{$attachments.1.0}</strong>) within working-with template sets.
							If you save changes, the default template will be changed to the selected template below.
						</div>
					{/if}
					{if $read_only}
						{$attachments.1.0}
					{else}
						{include file="design/parts/templateDropdown.tpl" templateSelected=$attachments.1.0 selectName='attachments[1][0]' selectId=defaultTemplate}
					{/if}
					{if $from_defaults}
						<br /><div class="error"><strong>Note:</strong>  Attachments loaded from defaults.  Verify/change template attachment(s) and save changes.</div>
					{elseif !$attachments.1.0}
						<br /><div class="error">Not currently set in the template set!  Choose a default template and save changes.</div>
					{/if}
				</div>
				<div class="clearColumn"></div>
			</div>
			<br />
			
			<br />
			<div class="col_hdr_top">{if $pageInfo.categoryPage && $is_ent}Category &amp; {/if}Language-Specific Template Attachments</div>
			<p class="page_note">
				You can attach templates on a language 
				{if $pageInfo.categoryPage && $is_ent}and/or category{/if} specific basis 
				by adding such attachments below.  The <strong>default template</strong> 
				attachment set above will be used if there is no template 
				attached for a specific language {if $pageInfo.categoryPage && $is_ent}or category{/if}.
			</p>
			
			<table>
				<thead>
					<tr class="col_hdr">
						<th style="color: red; width: 5px;">x</th>
						<th>Language</th>
						{if $pageInfo.categoryPage && ($is_ent||$has_cat_tpls)}
							<th>Category</th>
						{/if}
						<th>Attached Template</th>
					</tr>
				</thead>
				<tbody>
					{assign var=categoryCount value=0}
					{foreach from=$attachments item=cats key=languageId}
						{if is_numeric($languageId)}
							{foreach from=$cats item=attachment key=catId}
								{if !($languageId==1&&$catId==0) && !is_array($attachment)}
									{assign var=categoryCount value=1}
									<tr class="{cycle values="row_color1,row_color2"}">
										<td style="text-align: center;"><input type="checkbox" name="delete[{$languageId}][{$catId}]" value="1" /></td>
										<td>
											{if $languages.$languageId}
												{if $languageId!=1}{$languageId} - {/if}{$languages.$languageId}
											{else}
												{$languageId} (Language not found!)
											{/if}
										</td>
										{if $pageInfo.categoryPage && ($is_ent||$has_cat_tpls)}
											<td>{$catId}{if $catNames.$catId} - {$catNames.$catId}{/if}</td>
										{/if}
										<td>
											{if $catId && !$is_ent}
												{* Only display and allow to remove *}
												<input type="hidden" name="attachments[{$languageId}][{$catId}]" value="{$attachment|escape}" />
												{$attachment} <span style="color: red;">**Attachment not Used</span>
											{else}
												{capture assign=selectName}attachments[{$languageId}][{$catId}]{/capture}
												{include file="design/parts/templateDropdown.tpl" templateSelected=$attachment}
											{/if}
										</td>
									</tr>
								{/if}
							{/foreach}
						{/if}
					{/foreach}
					{if !$categoryCount}
						<tr>
							<td colspan="4"><div class="page_note_error">No {if $pageInfo.categoryPage&&$is_ent}Category or {/if}Language-Specific templates currently attached to this page.</div></td>
						</tr>
					{/if}
					{if !$read_only}
						<tr class="col_ftr">
							<td style="text-align: center; vertical-align: middle; font-size: 16px; font-weight: bold;" class="text_green">+</td>
							<td>
								
								<select name="new[cat][languageId]">
									{foreach from=$languages item=lang key=langId}
										<option value="{$langId}">{if $langId!=1}{$langId} - {/if}{$lang}</option>
									{/foreach}
								</select>
								{if !$pageInfo.categoryPage||!$is_ent}
									<input type="hidden" name="new[cat][category]" value="0" />
								{/if}
							</td>
							{if $pageInfo.categoryPage && ($is_ent||$has_cat_tpls)}
								<td>
									{if $is_ent}
										{$catDropdown}
										- OR - <label>Category ID: <input type="text" size="3" name="new[cat][catId]" /></label>
									{else}
										<input type="hidden" name="new[cat][category]" value="0" />
									{/if}
								</td>
							{/if}
							<td>
								{include file="design/parts/templateDropdown.tpl" showBlankTemplate=1 selectName='new[cat][template]'}
							</td>
						</tr>
					{/if}
				</tbody>
			</table>
			{if $has_cat_tpls}
				<br />
				<p class="page_note">
					<strong style="color: red;">** Note:</strong> You have at least one category-specific template attachment that
					will not be used, because category specific templates is an Enterprise only feature.
					
					You can view and remove category specific template attachments, but you will not be able to add new ones or
					edit existing category attachments.  Note that you CAN have language-specific template attachments
					on any edition.
				</p>
			{/if}
			{if $is_ent && $pageInfo.affiliatePage && $attachments.affiliate_group}
				<br /><br />
				<div class="col_hdr_top">Group-Specific Affiliate Template Attachments</div>
				<p>
					Note: If a group has affiliate Privileges turned off, attachments for that
					group are never used by the system.
				</p>
				<table>
					<thead>
						<tr class="col_hdr">
							<th style="color: red; width: 5px;">x</th>
							<th>Language</th>
							<th>Group</th>
							<th>Attached Template</th>
						</tr>
					</thead>
					<tbody>
						{assign var=groupCount value=0}
						{foreach from=$attachments.affiliate_group item=groups key=languageId}
							{foreach from=$groups item=attachment key=groupId}
								{if $languages.$languageId && $groupNames.$groupId}
									{assign var=groupCount value=1}
									<tr class="{cycle values="row_color1,row_color2"}">
										<td style="text-align: center;"><input type="checkbox" name="delete[affiliate_group][{$languageId}][{$groupId}]" value="1" /></td>
										<td>
											{if $languageId!=1}{$languageId} - {/if}{$languages.$languageId}
										</td>
										<td>{$groupId} - {$groupNames.$groupId}</td>
										<td>
											{capture assign=selectName}attachments[affiliate_group][{$languageId}][{$groupId}]{/capture}
											{include file="design/parts/templateDropdown.tpl" templateSelected=$attachment}
										</td>
									</tr>
								{/if}
							{/foreach}
						{/foreach}
						{if !$categoryCount}
							<tr>
								<td colspan="4"><div class="page_note_error">No group specific templates currently attached to this page.</div></td>
							</tr>
						{/if}
					</tbody>
				</table>
			{/if}
			{if $pageInfo.extraPage}
				<br /><br />
				<div class="col_hdr_top">Extra Page {$pageInfo.page_id-134} {ldelim}body_html} Attachments</div>
				<table>
					<thead>
						<tr class="col_hdr">
							<th style="color: red; width: 5px;">x</th>
							<th>Language</th>
							<th>Attached Template</th>
						</tr>
					</thead>
					<tbody>
						{assign var=extraCount value=0}
						{foreach from=$attachments.extra_page_main_body item=row key=languageId}
							{if $languages.$languageId && $row.0}
								{assign var=extraCount value=1}
								<tr class="{cycle values="row_color1,row_color2"}">
									<td style="text-align: center;"><input type="checkbox" name="delete[extra_page_main_body][{$languageId}]" value="1" /></td>
									<td>
										{if $languageId!=1}{$languageId} - {/if}{$languages.$languageId}
									</td>
									<td>
										{capture assign=selectName}attachments[extra_page_main_body][{$languageId}]{/capture}
										{include file="design/parts/templateDropdown.tpl" templateSelected=$row.0}
									</td>
								</tr>
							{/if}
						{/foreach}
						{if !$extraCount}
							<tr>
								<td colspan="4"><div class="page_note_error">No Extra Page {$pageInfo.page_id-134} {ldelim}body_html} Attachments!</div></td>
							</tr>
						{/if}
						{if !$read_only}
							<tr class="col_ftr">
								<td style="text-align: center; vertical-align: middle; font-size: 16px; font-weight: bold;" class="text_green">+</td>
								<td>
									
									<select name="new[extra][languageId]">
										{foreach from=$languages item=lang key=langId}
											<option value="{$langId}">{if $langId!=1}{$langId} - {/if}{$lang}</option>
										{/foreach}
									</select>
								</td>
								<td>
									{include file="design/parts/templateDropdown.tpl" showBlankTemplate=1 selectName='new[extra][template]'}
								</td>
							</tr>
						{/if}
					</tbody>
				</table>
			{/if}
			
			
			{if !$read_only}
				<div style="text-align: center;"><input type="submit" name="auto_save" value="Save" /></div>
			{/if}
		{if !$read_only}</form>{/if}
		<div style="text-align: center;">
			<br /><br />
			<a href="index.php?page=page_attachments" class="mini_button">List All Pages</a>
			{if $addonTitle}
				<br /><br />
				<a href="index.php?page=page_attachments&amp;addon={$addon|escape}" class="mini_button">List All {$addonTitle} Pages</a>
			{/if}
		</div>
	</div>
</fieldset>
