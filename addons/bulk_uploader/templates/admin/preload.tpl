<div>{$adminMsgs}</div>
<fieldset>
	<legend>Input Source File</legend>
	<div>
		<form action='' method='POST' enctype='multipart/form-data'>
			<div class='row_color1'>
				<div class='leftColumn'>
					Upload a CSV file:
				</div>
				<div class='rightColumn'>
					<input type='file' id='file_name' name='csvfile' />
				</div>
				<div class='clearColumn'></div>
			</div>
			<div class='row_color2'>
				<div class='leftColumn'>
					Delimiter:
				</div>
				<div class='rightColumn'>
					<input type='text' name='delimiter' size='5' value=','> e.g.,&nbsp;&nbsp; <b>, (comma)</b>  or <b>. (dot)</b>  or <b>| (pipe)</b> ...etc
				</div>
				<div class='clearColumn'></div>
			</div>
			
			<div class='row_color1'>
				<div class='leftColumn'>
					Encapsulation:
				</div>
				<div class='rightColumn'>
					<input type='text' name='encapsulation' size='5' value='\"'> e.g.,&nbsp;&nbsp; <b>\"</b>
				</div>
				<div class='clearColumn'></div>
			</div>
			<div class='row_color2' style='margin: 0 auto; text-align: center;'>
				<input type='submit' name='auto_save' value='Start' />
			</div>
		</form>
	</div>
</fieldset>