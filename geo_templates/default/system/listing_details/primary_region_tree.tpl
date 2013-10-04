{* 7.1beta1-1053-g114099e *}
{foreach $region_trees.0 as $region}
	{$region.name}{if !$region@last} &gt;{/if}
{/foreach}