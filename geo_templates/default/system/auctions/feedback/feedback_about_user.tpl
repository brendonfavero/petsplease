{* 7.0.2-66-g28e6e7b *}

{if $no_feedbacks} 
	<div class="content_box">
		<h1 class="title">{$messages.102416}</h1>
		<div class="note_box">{$messages.102499}</div>
	</div>
	
	<br />
	<div class="center">
		{if $feedback_home_link}<a href="{$feedback_home_link}" class="button">{$messages.102422}</a>{/if}
		{if $auction_link}<a href="{$auction_link}" class="button">{$messages.103372}</a>{/if}
	</div>
{else}

	<div class="content_box">
		<div class="row_even" style="font-weight: bold;">
			<span style="font-size: 16px;">{$messages.500811} <span style="color: #7DAA3B;">{$rated_user_name}</span></span><br /> 
			{$messages.102736} {$member_since}
		</div>
	</div>
	
	<br />
	
	<div id="half_column_left">
		<div class="content_box">
			<h1 class="title">{$messages.102416}</h1>
		
			<div class="row_even">
				<label class="field_label">{$messages.102967}</label>
				{$feedback_score}
			</div>
			
			<div class="row_odd">
				<label class="field_label">{$messages.102972}</label>
				{$feedback_percentage}%
			</div>
		
			<div class="row_even">
				<label class="field_label">{$messages.102974}</label>
				<strong class="positive">{$pos_count}</strong>
			</div>
		
			<div class="row_odd">
				<label class="field_label">{$messages.102976}</label>
				<strong class="negative">{$neg_count}</strong>
			</div>
		</div>	
	</div>
	
	<div id="half_column_right">
		<div class="content_box">
			<table style="width: 100%;">
				<tr class="column_header">
					<td>{$messages.102968}</td>
					<td>{$messages.102969}</td>
					<td>{$messages.102970}</td>
					<td>{$messages.102971}</td>
				</tr>
				<tr class="row_even">
					<td class="positive">{$messages.102973}</td>
					<td class="positive">{$one_month_pos}</td>
					<td class="positive">{$six_month_pos}</td>
					<td class="positive">{$twelve_month_pos}</td>
				</tr>
				<tr class="row_odd">
					<td class="neutral">{$messages.102975}</td>
					<td class="neutral">{$one_month_neu}</td>
					<td class="neutral">{$six_month_neu}</td>
					<td class="neutralnegative">{$twelve_month_neu}</td>
				</tr>
				<tr class="row_even">
					<td class="negative">{$messages.102977}</td>
					<td class="negative">{$one_month_neg}</td>
					<td class="negative">{$six_month_neg}</td>
					<td class="negative">{$twelve_month_neg}</td>
				</tr>
			</table>
		</div>
		
		{if $score_percentage}
			<br />
			
			<div class="content_box">
				<div class="row_even">
					<label class="field_label">{$messages.102498}</label> {$feedback_score} ({$score_percentage}%)
				</div>
			</div>
		{/if}
	</div>
	
	<div class="clr"><br /></div>
	
	
	<div class="content_box">
		<table style="border: none; width: 100%;">
			<tr class="column_header">
				<td class="nowrap center">{$messages.102417}</td>
				<td class="title">{$messages.102418}</td>
				<td class="nowrap center">{$messages.102419}</td>
				<td class="nowrap center">{$messages.102421}</td>
			</tr>
		
			{foreach from=$display_feedbacks item=fb}
				<tr class="{cycle values="row_even,row_odd" advance=false} bold feedback_cells">
					<td class="nowrap">{$fb.rater_username} {if $fb.user_is_seller}{$messages.103361}{else}{$messages.103362}{/if}</td>
					<td>{$fb.title} - {$fb.auction_id}</td>
					<td class="nowrap">{$fb.rating}</td>
					<td class="nowrap">{$fb.date}</td>
				</tr>
				<tr class="{cycle values="row_even,row_odd"}">
					<td colspan="4"><p class="sub_note"><strong>{$messages.102497}</strong>: {$fb.feedback}</td>
				</tr>
			{/foreach}
		</table>
	</div>
	
	{if $pagination}
		<br />
		{$messages.200175} {$pagination}
	{/if}
	
	<br />
	<div class="center">
		{if $feedback_home_link}<a href="{$feedback_home_link}" class="button">{$messages.102422}</a>{/if}
		{if $auction_link}<a href="{$auction_link}" class="button">{$messages.103372}</a>{/if}
	</div>
{/if}