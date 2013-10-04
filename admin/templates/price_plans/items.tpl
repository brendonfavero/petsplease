{* 6.0.7-3-gce41f93 *}
<form id="frm_all_settings" method="post" action="">
<fieldset id="price_plan_items">
	<legend>Price Plan Items</legend>
	<div>
		{if $saveMe}
			<div class="page_note">
				Save the page to start using category specific pricing and enable editing of plan item settings.
			</div>
		{else}
			<ul class="expandable_list">
				{foreach from=$plan_items item="plan_item" key="index"}
					<li id="row_for{$index}">
						<div id="requireAdmin{$index}" class="btn_require">
							{if count($plan_item.parents) == 0}
								<a href="javascript:void(0);" {$plan_item.require_onclick} class="{if $plan_item.admin_approve}mini_cancel{else}mini_button{/if}">
									{if $plan_item.admin_approve}
										Stop requiring admin approval
									{else}
										Require admin approval
									{/if}
								</a>
							{else}
								&nbsp;
							{/if}
						</div>
						<div class="itemTitle">{$plan_item.title}</div>
						<div class="btn_config">
							{$plan_item.config_button}
						</div> 
						<div class="clr"></div>
						{if $plan_item.config_button}
							<div id="container_{$index}" style="display: none;"></div>
						{/if}
					</li>
				{foreachelse}
					<li>No plan items found!</li>
				{/foreach}
			</ul>
		{/if}
	</div>
</fieldset>
</form>