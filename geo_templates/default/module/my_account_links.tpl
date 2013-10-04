{* 6.0.7-3-gce41f93 *}

{if $show_cart}
	{* Need to let the main cart know, wherever it is, that a mini cart was displayed *}
	{$geo_mini_cart_displayed=1 scope='global'}
	<div id="left_cart">
		<h2 class="title">
			<a href="{$classifieds_file_name}?a=cart">
				{if $allFree}
					{$messages.500647}
				{else}
					{$messages.500646}
				{/if}
			</a>
		</h2>
		{* Mini-cart information *}
		<ul>
			<li class="my_account_links_{if $cartLink.active}active{else}inactive{/if}">
				<h1 class="subtitle">
					{if !$allFree}
						<span class="alignright price">{$cartTotal|displayPrice}</span>
					{/if}
					{$cartItemCount} {if $cartItemCount == 1}{$messages.501015}{else}{$messages.500648}{/if}
				</h1>
				<div style="clear: both;"></div>
			</li>
			<li><a href="{$classifieds_file_name}?a=cart">{$messages.500655}</a></li>
		</ul>
		{* Cart actions *}
		{if $cartAction}
			<h1 class="subtitle">{$messages.500649} <span class="sub_note">{$cartAction}</span></h1>
			<ul>
				{if !$inCart || !$cartStepIndex}
					{* This is the resume button - only shown if on a page "ouside" the cart *}
					<li class="my_account_links_inactive">
						<a href="{$classifieds_file_name}?a=cart">{$messages.500650} {$messages.500651}</a>
					</li>
				{/if}
				{*  This is the cancel button *}
				<li class="my_account_links_inactive">
					<a href="{$classifieds_file_name}?a=cart&amp;action=cancel">{$messages.500652} {$messages.500653}</a>
				</li>
			</ul>
		{/if}

		{if $cartLinks}
			<h1 class="subtitle">
				{$messages.500654}
			</h1>
			<ul>
				{foreach from=$cartLinks item=listItem}
					{if $listItem.icon||$listItem.label}
						<li class="my_account_links_{if $listItem.active}active{else}inactive{/if}">
							{if $listItem.link}
								<a href="{$listItem.link}" class="user_links{if $listItem.needs_attention} needs_attention{/if}">
							{else}
								<span class="user_links{if $listItem.needs_attention} needs_attention{/if}">
							{/if}
								{if $listItem.icon}
									{$listItem.icon}
								{/if}
								{$listItem.label}
							{if !$listItem.link}
								</span>
							{else}
								</a>
							{/if}
						</li>
					{/if}
				{/foreach}
			</ul>
		{/if}
	</div>
{/if}

{if $show_account_finance_section && ($orderItemLinks || $paymentGatewayLinks)}
	{capture assign=accountFinanceContents}
		{* Capture the links, then put them where we want them. *}
		{if $orderItemLinks}
			<ul>
				{foreach from=$orderItemLinks item=itemLink}
					{if $itemLink.icon||$itemLink.label}
						<li class="my_account_links_{if $itemLink.active}active{else}inactive{/if}">
							{if $itemLink.link}
								<a href="{$itemLink.link}"{if $itemLink.needs_attention} class="needs_attention"{/if}>
							{else}
								<h1 class="subtitle normal_text {if $itemLink.needs_attention}needs_attention{/if}">
							{/if}
								{if $itemLink.icon}
									{$itemLink.icon}
								{/if}
								{$itemLink.label}
								
							{if !$itemLink.link}</h1>{else}</a>{/if}
						</li>
					{/if}
				{/foreach}
			</ul>
		{/if}
		{if $paymentGatewayLinks}
			<ul>
				{foreach from=$paymentGatewayLinks item=gatewayLink}
					{if $gatewayLink.icon||$gatewayLink.label}
						<li class="my_account_links_{if $gatewayLink.active}active{else}inactive{/if}">
							{if $gatewayLink.link}
								<a href="{$gatewayLink.link}"{if $gatewayLink.needs_attention} class="needs_attention"{/if}>
							{else}
								<h1 class="subtitle normal_text {if $gatewayLink.needs_attention}needs_attention{/if}">
							{/if}
								{if $gatewayLink.icon}{$gatewayLink.icon}{/if}
								{$gatewayLink.label}
							
							{if !$gatewayLink.link}</h1>{else}</a>{/if}
						</li>
					{/if}
				{/foreach}
			</ul>
		{/if}
	{/capture}
{/if}
{if $accountFinanceContents && $messages.500803}
	<br />
	<div class="content_box">
		<h1 class="title">
			{$messages.500803}
		</h1>
		{$accountFinanceContents}
		{assign var=accountFinanceContents value=''}
	</div>
{/if}
{if $show_my_account_section}
	<br />
	<div class="content_box">
		<h2 class="title"><a href="{$classifieds_file_name}?a=4">{$messages.500542}</a></h2>
		<ul>
			{foreach from=$links item=listItem}
				{if $listItem.icon||$listItem.label}
					<li class="my_account_links_{if $listItem.active}active{else}inactive{/if}">
						{if $listItem.link}
							<a href="{$listItem.link}" class="user_links{if $listItem.needs_attention} needs_attention{/if}">
						{else}
							<span class="user_links{if $listItem.needs_attention} needs_attention{/if}">
						{/if}
						{if $listItem.icon}
							{$listItem.icon}
						{/if}
			
						{$listItem.label}
						
						{if !$listItem.link}
							</span>
						{else}
							</a>
						{/if}
					</li>
				{/if}
			{/foreach}
		</ul>
		{$accountFinanceContents}
	</div>
{/if}