{* 7.2.4-4-g7132b5f *}
{* Note: ALL of a user's registration data is available in these default_pages templates as the $user array *}
<h2>Questions? Comments? We want to hear from YOU!</h2>
<h3>Contact us using one of these methods, or with the form below</h3>
{if $user.email}<p>Email: {$user.email}</p>{/if}
{if $user.phone}<p>Phone: {$user.phone}</p>{/if}
{if $user.phone2}<p>Phone 2: {$user.phone2}</p>{/if}
{if $user.fax}<p>Fax: {$user.fax}</p>{/if}

<hr style="width: 75%;" />

<form action="" method="post">

	<table style="width: 100%; text-align: left;"> {* using a table here, because it's easier for users to edit that way *}
		<tr>
			<td style="width: 50%; text-align: right;">Your Name: </td>
			<td><input type="text" name="contact[name]" /></td>
		</tr>
		<tr>
			<td style="width: 50%; text-align: right;">Your Email Address: </td>
			<td><input type="text" name="contact[email]" /></td>
		</tr>
		<tr>
			<td style="width: 50%; text-align: right;">Subject: </td>
			<td><input type="text" name="contact[subject]" /></td>
		</tr>
		{* 
			extra fields may be added to this form, as follows:
			<tr>
				<td style="width: 50%; text-align: right;">Phone Number: </td>
				<td><input type="text" name="contact[extra][phone_number]" /></td>
			</tr>
		 *}
		<tr>
			<td style="width: 50%; text-align: right;">Message: </td>
			{* note: textarea does not work well for this field, since it's being edited by end-users in TinyMCE *}
			<td><input type="text" name="contact[message]" size="70" /></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;"><input type="submit" value="Submit" /></td>
		</tr>
	
	
	</table>

</form>

