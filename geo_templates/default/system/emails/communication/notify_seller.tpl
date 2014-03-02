{* 7.0.2-68-gf8d0efd *}
{$introduction} {$salutation},<br />
<br />
You have received a new enquiry regarding your listing <a href="{$listingURL}">{$listingURL}</a><br />
<br />
To respond to this enquiry navigate to <a href="http://petsplease.com.au/index.php?a=4&b=8">http://petsplease.com.au/index.php?a=4&b=8</a> or respond via email
<br/><br/>
{if $senderName}
{$senderNameLabel} {$senderName}<br />
{/if}
{if $senderPhone}
{$senderPhoneLabel} {$senderPhone}<br />
{/if}
{if $senderEmail}
contact email: {$senderEmail}<br/>
{/if}
<br />
{if $senderComments}
{$senderCommentsLabel}<br />
{$senderComments}<br />
{/if}
<br />
{if $replyLink}
{$replyLinkInstructions}<br />
<br />
<a href="{$replyLink}">{$replyLink}</a><br />
{/if}
<br />