{* 7.4.3-70-g7bb148b *}

<fieldset class="admin_home_stat_box">
	<legend>Listing Extras</legend>
	<table style="width: 100%; height: 80px;" cellspacing="0" cellpadding="3">
		<tr class='row_color{cycle reset=true values="2,1"}'>
			<td class="stats_txt2">Live listings with Bolding:</td>
			<td class="stats_txt3">{$stats.extras.bolding}</td>
		</tr>
		<tr class='row_color{cycle values="2,1"}'>
			<td class="stats_txt2">Live listings with Better Placement:</td>
			<td class="stats_txt3">{$stats.extras.better_placement}</td>
		</tr>
		{foreach $stats.extras.featured as $fLevel => $fCount}
			<tr class='row_color{cycle values="2,1"}'>
				<td class="stats_txt2">Live listings with Featured Level {$fLevel}:</td>
				<td class="stats_txt3">{$fCount}</td>
			</tr>
		{/foreach}
		{if $stats.extras.attention_getter !== false}
			<tr class='row_color{cycle values="2,1"}'>
				<td class="stats_txt2">Live listings with an Attention Getter:</td>
				<td class="stats_txt3">{$stats.extras.attention_getter}</td>
			</tr>
		{/if}
		{if $stats.extras.charitable !== false}
			<tr class='row_color{cycle values="2,1"}'>
				<td class="stats_txt2">Live listings with a Charitable Badge:</td>
				<td class="stats_txt3">{$stats.extras.charitable}</td>
			</tr>
		{/if}
	</table>
</fieldset>