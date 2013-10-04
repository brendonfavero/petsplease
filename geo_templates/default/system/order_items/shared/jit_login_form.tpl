{* 7.0.2-12-ge309744 *}

{include file="cart_steps.tpl" g_resource="cart"}

<div class="jit_login_form" style="text-align: center; width: 75%; margin: 0 auto;">

{if $emailExists}
	<p style="font-weight: bold;">{$messages.500769}</p>
	<p style="font-size: 18pt;"><a href="{$loginURL}">{$messages.500771}</a>{$messages.500772}<a href="{$backURL}">{$messages.500773}</a></p>
{elseif !$allow_user_pass}
	<p style="font-weight: bold;">{$messages.500770}</p>
	<form action="{$continueURL}" id="continueForm" method="post">
		{if $errorMsg}<p class="error_message">{$errorMsg}</p>{/if}
		{$securityImageHTML}
		<p style="font-size: 18pt;"><a href="{$loginURL}">{$messages.500771}</a>{$messages.500772}<a href="{$continueURL}" onclick="$('continueForm').submit(); return false;">{$messages.500774}</a></p>
	</form>
{else}
	<p style="font-weight: bold;">{$messages.500788}</p>
	<p style="font-size: 18pt;"><a href="{$loginURL}">{$messages.500771}</a></p>
	<p style="font-size: 18pt;">
		<form action="{$continueURL}" method="post">
			<table style="width: 100%; text-align: left;">
				<tr>
					<td colspan="2" style="text-align: center;" class="error_message">{$errorMsg}</td>
				</tr>
				<tr>
					<td style="text-align: right; width: 50%;">{$messages.500789}</td><td><input type="text" name="username" maxlength="{$max_user_length}" /></td>
				</tr>
				<tr>
					<td style="text-align: right; width: 50%;">{$messages.500790}</td><td><input type="password" name="password" maxlength="{$max_pass_length}" /></td>
				</tr>
				<tr>
					<td style="text-align: right; width: 50%;">{$messages.500791}</td><td><input type="password" name="confirm" maxlength="{$max_pass_length}" /></td>
				</tr>
				{if $securityImageHTML}
					<tr>
						<td colspan="2" style="text-align: center;">{$securityImageHTML}</td>
					</tr>
				{/if}
				<tr>
					<td colspan="2" style="text-align: center;"><input type="submit" value="{$messages.500792}" /></td>
				</tr>
			</table>
		</form>
	</p>
{/if}



</div>