<ul class="product_categories">
	<li>
		{if $currentcategory eq ''}
			<b>View All</b>
		{else}
			<a href="{$link}">View All</a>
		{/if}
	</li>

	{foreach from=$categories item=topcategory key=topid}
		<li>
			{if $currentcategory eq $topid}
				<b>{$topcategory.name}</b>
			{else}
				<a href="{$link}&c={$topid}">{$topcategory.name}</a>
			{/if}

			<ul>
				{foreach from=$topcategory.categories item=category}
					<li>
						{if $currentcategory eq $category.id}
							<b>{$category.name}</b>
						{else}
							<a href="{$link}&c={$category.id}">{$category.name}</a>
						{/if}
					</li>
				{/foreach}
			</ul>
		</li>
	{/foreach}
</ul>
