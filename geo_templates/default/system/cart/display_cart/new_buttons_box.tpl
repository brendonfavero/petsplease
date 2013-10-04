{* 6.0.7-3-gce41f93 *}

{if $new_item_buttons}
	<div class="content_box">
		<h2 class="title">
			<span id="addToCartButton">{if $allFree}{$messages.500407}{else}{$messages.500398}{/if}</span>
		</h2>
		
		<ul id="cart_buttons">
			{foreach from=$new_item_buttons key=k item=s}
				{if $s}
					<li><a href="{$cart_url}&amp;action=new&amp;main_type={$k}">{$s}</a></li>
				{/if}
			{/foreach}
		</ul>
	</div>
	
	<script type="text/javascript">
		//<![CDATA[
		//makes the section able to be collapsed/expanded.
		$('addToCartButton').observe('click',function () {ldelim}
			$('cart_buttons').toggle();
		});
		
		{if $no_use_checkout ne 1}
			//comment the next line in the template to make it un-collapsed
			//by default
			//$('cart_buttons').hide();
		{/if}
		//]]>	
	</script>

{/if}