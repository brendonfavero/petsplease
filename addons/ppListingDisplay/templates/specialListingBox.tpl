{if $lead}
	<div style="text-align:center;margin-bottom:14px;">
		<a href="?a=2&b={$lead.id}">
			{if $lead.logo}{$lead.logo}<br>{/if}
			{$lead.title|urldecode}
		</a>
	</div>
{/if}

{if $listings}
	See also:
	<ul>
	{foreach $listings as $listing}
		<li>
			<a href="?a=2&b={$listing.id}">{$listing.title|urldecode}</a>
		</li>	
	{/foreach}
	</ul>
{/if}
