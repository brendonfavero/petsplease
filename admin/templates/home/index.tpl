{* 7.0.0-71-g719f0c5 *}
<div style="width: 100%;">

	{$adminMsgs}
	<table style="width: 100%;">
		<tr style="vertical-align: top;">
			<td style="width: 33%; vertical-align: top;">
				{include file='home/version.tpl'}
			</td>
			{if $is_trial_demo}
				<td style="width: 33%; vertical-align: top">
				</td>
				<td style="width: 33%; vertical-align: top">
					{include file='home/demo.tpl'}
				</td>
			{else}
				<td style="width: 33%; vertical-align: top;">
					{if $product.leased}
						{include file='home/leased.tpl'}
					{else}
						{include file='home/downloads.tpl'}
					{/if}
				</td>
				<td style="width: 33% vertical-align: top;">
					{include file='home/support.tpl'}
				</td>
			{/if}
		</tr>
		<tr>
			<td colspan="2">
				{include file='home/orders.tpl'}
			</td>
			<td style="width: 33%">
				{include file='home/users.tpl'}
			</td>
		</tr>
		
		<tr>
			{if $product.auctions}
				<td style="width: 33%">
					{include file='home/auctions.tpl'}
				</td>
			{/if}
			
			{if $product.classifieds}
				<td style="width: 33%">
					{include file='home/classifieds.tpl'}
				</td>
			{/if}
			
			<td style="width: 33%">
				{include file='home/other_stats.tpl'}
			</td>
		</tr>
		<tr>
			<td style="width: 33%; vertical-align: top;">
				{include file='home/landingPage.tpl'}
			</td>
			<td style="width: 33%; vertical-align: top;">
				{include file='home/groups.tpl'}
			</td>
			<td style="width: 33%; vertical-align: top;">
				{include file='home/plans.tpl'}
			</td>
		</tr>
		<tr>
			<td colspan="3">
				{include file='home/news.tpl'}
			</td>
		</tr>
	</table>
	{if $debug}
		<fieldset>
			<legend>DEBUG</legend>
			<div>
				<pre>{$debug|escape}</pre>
			</div>
		</fieldset>
	{/if}
</div>