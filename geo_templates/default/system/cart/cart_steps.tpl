{* 7.1.2-81-g1c2af76 *}
{if $cartSteps}
	<div class="content_box">
		<ul id="cart_steps">
			{foreach from=$cartSteps item=label key=step name=cartsteploop}
				{if $label}
					<li{if $step == $currentCartStep} class="current"{/if}>
						{$label}
					</li>
				{/if}
			{/foreach}
		</ul>
		<div class="clear"></div>
	</div>
	<br />
{/if}