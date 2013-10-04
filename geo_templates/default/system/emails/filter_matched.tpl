{* 7.1.2-29-g394fa8f *}
{$messageBody}<br />
<br />
{foreach $data as $id => $filter}
	{if $filter.type === 'string'}
		{$filterLabel} {$filter.value}<br />	
	{elseif $filter.type === 'category'}
		{$categoryLabel} {$filter.value}<br />
	{/if}
	{$titleLabel} {$filter.title}<br />
	{$linkLabel} <a href="{$filter.url}">{$filter.url}</a><br />
	{if !$filter@last}<br />{/if}
{/foreach}