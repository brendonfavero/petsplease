{* 7.0.0-19-g6002751 *}

{$admin_msgs}

<div class="group_price_hdr">User Group: {$group.name}</div>
<form action="index.php?page=users_group_edit&amp;c={$group_id}" method="post">
	<fieldset>
		<legend>User Group Details</legend>
		<div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Group ID#</div>
				<div class="rightColumn">
					{$group_id}
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Group Name</div>
				<div class="rightColumn">
					<input type="text" name="d[name]" size="30" maxsize="30" value="{$group.name|escape}" />
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Group Description</div>
				<div class="rightColumn">
					<textarea name="d[description]" rows="3" cols="30">{$group.description|escape}</textarea>
				</div>
				<div class="clearColumn"></div>
			</div>
			{if $is_ent||$is_premier}
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Registration Code</div>
					<div class="rightColumn">
						<input type="text" name="d[registration_code]" size="30" maxsize="30" value="{$group.registration_code|escape}" />
					</div>
					<div class="clearColumn"></div>
				</div>
			{else}
				<input type="hidden" name="d[registration_code]" value="" />
			{/if}
			{if $is_ent}
				<div class="{cycle values='row_color1,row_color2'}">
					<div class="leftColumn">Questions Attached to this Group:</div>
					<div class="rightColumn">
						{foreach $questions as $question}
							{$question.name}<br />
						{foreachelse}
							None
						{/foreach}
						<br />
						<a href="index.php?mc=users&amp;page=users_group_questions&amp;d={$group_id}" class="mini_button">Edit / Add Group Questions</a>
					</div>
					<div class="clearColumn"></div>
				</div>
			{/if}
			{$addonSettings}
			<div style="text-align:right;">
				<input type="submit" name="auto_save" value="Quick Save" class="mini_button" />
			</div>
		</div>
	</fieldset>
	
{*
TODO:  finish templatizing the entire page..  Only did this section for now since just
trying to quickly add addon hook to allow showing settings.  
Closing </form> tag added in PHP file. 
*}