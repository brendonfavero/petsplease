{* 34a4d9b *}

{$adminMsgs}

<fieldset>
	<legend>Data Import Information</legend>
	{if $alreadyExisting > 0}
		<div>
			<p class="page_note">Imported zip/postal code information was found in the database.</p>
			<strong>Last Import Performed:</strong> {if $lastRun}{$lastRun|date_format}{else}Never{/if}<br /><br />
			<strong>Existing Zip/Postal Data:</strong> {if $alreadyExisting}{$alreadyExisting} Entries{else} N/A{/if}<br /><br />
		</div>
	{else}
		<div>No zip/postal code information was found in the database. Be sure to <a href="index.php?page=insertZipData">import</a> some.</div>
	{/if}
</fieldset>

<fieldset>
	<legend>Settings</legend>
	<div>
		<form action="" method="post">
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Zipsearch Enabled</div>
				<div class="rightColumn">
					<label><input type="checkbox" name="enabled" value="1" {if $enabled == 1}checked="checked"{/if} /></label>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Distance Units</div>
				<div class="rightColumn">
					<label><input type="radio" name="units" value="M"{if $units=='M'} checked="checked"{/if} /> miles</label><br />
					<label><input type="radio" name="units" value="km"{if $units=='km'} checked="checked"{/if} /> kilometers</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="{cycle values='row_color1,row_color2'}">
				<div class="leftColumn">Search Method</div>
				<div class="rightColumn">
					<label><input type="radio" name="search_method" value="exact"{if $search_method=='exact'} checked="checked"{/if} /> Exact Match (US/Germany/similar)</label><br />
					<label><input type="radio" name="search_method" value="hierarchical"{if $search_method=='hierarchical'} checked="checked"{/if} /> Hierarchical Match (UK/Canada/similar)</label>
				</div>
				<div class="clearColumn"></div>
			</div>
			<div class="center">
				<input type="submit" name="auto_save" value="Save" />
			</div>
		</form>
	</div>
</fieldset>