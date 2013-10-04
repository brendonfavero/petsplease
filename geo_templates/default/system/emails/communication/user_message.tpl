{* 7.0.2-68-gf8d0efd *}
{$messageBody}<br />
<br />
{$fromLabel} {$messageFromUsername}{if $messageFromEmail} {$messageFromEmail}{/if}<br />
<br />
{if $showReplyLink}
{$privateCommMessage}<br />
<br />
<a href="{$privateReplyLink}">{$privateReplyLink}</a><br />
{/if}
<br />
<br />
{$senderIP} : {$senderHost}
