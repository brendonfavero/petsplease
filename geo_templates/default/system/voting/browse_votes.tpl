{* 7.0.2-66-g28e6e7b *}

<div class="content_box">
	<h1 class="title">{$messages.2000}: {$listing.title|fromDB}</h1>
	<p class="page_instructions">{$messages.2288}</p>

	<div class="row_even">
		<label class="field_label">{$messages.2001}</label>
		<strong class="text_highlight">{$oneVotesPercentage}</strong>
	</div>
	<div class="row_odd">
		<label class="field_label">{$messages.2002}</label>
		<strong class="text_highlight">{$twoVotesPercentage}</strong>
	</div>
	<div class="row_even">
		<label class="field_label">{$messages.2003}</label>
		<strong class="text_highlight">{$threeVotesPercentage}</strong>
	</div>
	<div class="row_odd">
		<label class="field_label">{$messages.2004}</label>
		<strong class="text_highlight">{$totalVotes}</strong>
	</div>	
	{if $totalVotes > 0}
		<br /><br />
		<table style="width: 100%;">
			<tr>
				<td class="column_header">{$messages.2005}</td>
				<td class="column_header">{$messages.2007}</td>
				{if $canDeleteVotes}<td class="column_header"></td>{/if}
			</tr>
			{foreach from=$votes item=vote}
				<tr class="row_{cycle values="even,odd"}">
					<td class="nowrap"><strong>{$vote.voteType}</strong></td>
					
					<td style="width: 100%;">
						<span ><strong><a href="{$classifieds_file_name}?a=6&amp;b={$vote.voter_id}" class="text_highlight">{$vote.voter}</a></strong></span> ({$vote.date})
						<br />
						<strong>{$vote.title}</strong>
						<br />
						<span class="sub_note">{$vote.comment}</span>
					</td>
					{if $canDeleteVotes}<td class="nowrap"><a href="{$classifieds_file_name}?a=27&amp;b={$listing.id}&amp;d={$vote.id}" class="mini_button">Remove Vote</a></td>{/if}
				</tr>
			{/foreach}
		</table>
	{/if}
</div>
<br />
{if $showPagination}
	{$messages.24} {$pagination}
{/if}
<br />
<div class="center">
	<a href="{$backToCurrentAdLink}" class="button">{$messages.2012}</a>
</div>