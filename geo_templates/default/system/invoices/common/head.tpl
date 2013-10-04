<!DOCTYPE HTML>
<html>
<head>
	<title>{$messages.501152}{$invoice.invoice_id} {$messages.501153}{$invoice.order_id}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="{if $in_admin}../{/if}{external file='css/theme_styles.css'}" rel="stylesheet" type="text/css" />
	<link href="{if $in_admin}../{/if}{external file='css/primary_theme_styles.css'}" rel="stylesheet" type="text/css" />
	<link href="{if $in_admin}../{/if}{external file='css/secondary_theme_styles.css'}" rel="stylesheet" type="text/css" />
	<link href="{if $in_admin}../{/if}{external file='css/system/invoices/invoice_styles.css'}" rel="stylesheet" type="text/css" />
	{if $print}
		<style type="text/css" media="print">
		.printBox_printFriendly
		{
			display:none;
		}
		</style>
	{/if}
</head>
<body {if $print}onload="window.print();" class="print"{/if}>
