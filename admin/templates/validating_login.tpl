<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Validating Login Credentials</title>
<link rel="stylesheet" type="text/css" href="css/body_html.css" />
<script type="text/javascript">
	{literal}
	window.onload = function () {
		setTimeout(	
			function(){
				myForm = document.getElementById('validate_login');
				if (myForm){
					window.location.replace('index.php');
					myForm.submit();
				}
			} , 2000);
		}
	{/literal}
</script>
</head>
<body>
	<div style="margin: auto; text-align: center; top: 50%; position:absolute; width: 100%">
		<form action="index.php" method="post" id="validate_login">
			<input type="hidden" name="b[username]" value="{$username|escape}" />
			<input type="hidden" name="b[pvalidate]" value="{$password|escape}" />
			{if $license_key}
				<input type="hidden" name="b[license_key]" value="{$license_key|escape}" />
				{if $agreed}
					<input type="hidden" name="agreed" value="1" />
				{/if}
			{/if}
			<input type="hidden" name="b[sessionId]" value="{$session_id|escape}" />
			<input type="image" name="validate_login" value="Validate Login" src="admin_images/loading.gif" style="margin: auto;" />
			<div class="medium_font">Validating Login Credentials...</div>
		</form>
	</div>
</body>
</html>