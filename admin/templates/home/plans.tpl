{* 6.0.7-60-g4a6c66e *}

<fieldset class="admin_home_stat_box2">
	<legend>Price Plans</legend>
	<table style="width: 100%;" cellspacing="0" cellpadding="3">
		{foreach from=$stats.groupsplans.plans item=plan key=id}
			<tr class='row_color{cycle name=plancycle values="2,1"}'>
				<td class="stats_txt2">
					{$plan.name}
				</td>
				<td class="stats_txt3">
					<a href="index.php?mc=pricing&amp;page=pricing_edit_plans&amp;g={$id}">{$plan.count}</a>
				</td>
			</tr>
		{/foreach}
	</table>
</fieldset>