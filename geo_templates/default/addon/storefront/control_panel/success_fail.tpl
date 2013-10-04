{* 6.0.7-3-gce41f93 *}
{* if we just updated, show success/fail message *}
{if $show === true}
	<div class="success_box">{$msgs.usercp_common_savesuccess}</div>
{elseif $show === false}
	<div class="field_error_row">{$msgs.usercp_common_savefailure}</div>
{/if}