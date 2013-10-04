{* 6.0.7-240-g1f35f4f *}
<span class="listing_region_breadcrumb">
{foreach $regions as $level => $name}
<span class="region_level_{$level}">{$name}</span> 
{if !$name@last}<span class="region_level_divider">&gt;</span>{/if}
{/foreach}
</span>