{* 6.0.7-60-g4a6c66e *}

<fieldset class="admin_home_stat_box2">
	<legend>User Groups</legend>
	<table style="width: 100%;" cellspacing="0" cellpadding="3">	
		{foreach from=$stats.groupsplans.groups item=group key=id}
			<tr class='row_color{cycle name=groupcycle values="2,1"}'>
				<td class="stats_txt2">
					{$group.name}
				</td>
				<td class="stats_txt3">
					<a href="index.php?mc=users&amp;page=users_group_edit&amp;c={$id}">{$group.count}</a>
				</td>
			</tr>
		{/foreach}
	</table>
</fieldset>