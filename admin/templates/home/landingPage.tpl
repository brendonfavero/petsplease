{* 6.0.7-60-g4a6c66e *}

<fieldset>
	<legend>Admin Landing Page</legend>
	<div class="medium_font">
		<form action='' id="landingPageForm" method='post'>
			<div>
				<strong>After admin logs in:</strong>
				<select name="landingPage" id="landingPageSelect" onchange="$('landingPageForm').submit()" style="font-size: 8pt;">
					<option value='0'>Show last page viewed</option>
					<option value='home'{if $landingPage == 'home'} selected="selected"{/if}>Display Home Page (this page)</option>
				</select>
				<input type="hidden" name="auto_save" value="1" />
				<input type="submit" name="auto_save" value="Apply" style="font-size: 8pt;" />
			</div>
		</form>
	</div>
</fieldset>