{* 7.0.3-958-g258b9c3 *}
{$adminMessages}
<form action="index.php?page=addon_mobile_api_iphone_field_overrides" method="post">

<fieldset>
<legend>Overridable Fields</legend>
<p>You can use these to make some of the fields in your personalized app display data different from their original functionality, such as showing listing ID instead of price, or end time instead of start time</p>

{foreach $overridables as $fieldName => $existingValue}
	<p>
		Replace <strong>{$fieldName}{if $fieldName == 'id'} (Seller Username){/if}</strong> field with value from
		<select name="override[{$fieldName}]">
			<option value="">{$fieldName} (no override)</option>
			{foreach $injectables as $in}
				<option value="{$in}" {if $in == $existingValue}selected="selected"{/if}>{$in}</option>
			{/foreach}
		</select>
	</p>
	
{/foreach}

</fieldset>


<fieldset>
	<legend>Hide Categories</legend>
	<p>This is a comma-separated list of category ID numbers for categories that will <strong>not</strong> appear in API search results. Listings in these categories, as well as subcategories and their listings, will also be hidden from the API.</p>
	<textarea name="hiddenCategories">{$hiddenCategories}</textarea>
</fieldset>   

<div class="center"><input type="submit" name="auto_save" value="Submit" /></div>
</form>