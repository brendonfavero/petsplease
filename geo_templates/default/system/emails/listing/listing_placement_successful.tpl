{* 6.0.7-3-gce41f93 *}
{$introduction}{if $salutation} {$salutation}{/if},<br />
<br />
{$messageBody}<br />
<br />
<br />
<a href="{$listingURL}">{$listingURL}</a><br />
{if $isAnonymousListing}
<br />
{$anonymousEmailText} {$anonymousEditPassword}<br />
<br />
{$editLinkLabel}<br />
<a href="{$editLink}">{$editLink}</a>
{/if}