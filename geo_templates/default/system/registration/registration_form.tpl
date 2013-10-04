{* 7.1beta1-1211-gcedabce *}


<div class="content_box">
	<h1 class="title">{$messages.614}</h1>
	{$addons_top}
	<h1 class="subtitle">{$messages.239}</h1>
	<p class="page_instructions">{$messages.245}</p>
	
	<form action="{$registration_url}?b=1" method="post" id="registration_form">
		<div class="divider"></div>
		
		{foreach from=$fields item=f}
			<div class="{if $f.error}field_error_row{else}{cycle values='row_odd,row_even'}{/if}">
				{if $f.label}<label for="{$f.name}" class="{if $f.required}required{else}field_label{/if}">{$f.label|fromDB}{if $f.required} *{/if}</label>{/if}
				
				{if $f.type == "text"}
					<input type="text" id="{$f.name}" name="{$f.name}" value="{$f.value}" size="{if !$f.size or $f.size > 30}30{else}{$f.size}{/if}" maxlength="{if !$f.size}100{else}{$f.size}{/if}" class="field" />
				{elseif $f.type == "radio"}
					{foreach from=$f.options item=radio key=k name=radioLoop}
						<label><input id="{$f.name}" type="radio" name="{$f.name}" value="{$k}" {if $radio.checked}checked="checked"{/if} /> {$radio.text}</label>
					{/foreach}
				{else}
					{* no field type explicity set by PHP. Probably something like Regions that has its own HTML *}
					{$f.value}
				{/if}
				
				{if $f.error}
					<span class="error_message">{$f.error|fromDB}</span>
				{/if}
			</div>
		{/foreach}
		
		{if $optionalFieldInstructions}
			<h1 class="subtitle">{$optionalFieldInstructions}</h1>
		{/if}
		
		{foreach from=$optionals item=opt}
			<div class="{if $opt.error}field_error_row{else}{cycle values='row_odd,row_even'}{/if}">
				<label for="{$opt.name}" class="{if $opt.required}required{else}field_label{/if}">{$opt.label|fromDB}{if $opt.required} *{/if}</label>
				
				{if $opt.type == "filter"}
					{$opt.filter_html} <a href="{$registration_url}?b=5">{$messages.1528}</a>
				{elseif $opt.type == "text"}
					<input type="text" id="{$opt.name}" name="{$opt.name}" value="{$opt.value}" size="{if $opt.maxlen > 30}30{else}{$opt.maxlen}{/if}" maxlength="{if !$opt.maxlen}100{else}{$opt.maxlen}{/if}" class="field" />
				{elseif $opt.type == "area"}
					<textarea id="{$opt.name}" name="{$opt.name}" rows="8" cols="50" class="field">{$opt.value}</textarea>
				{elseif $opt.type == "select"}
					<select id="{$opt.name}" name="{$opt.name}">
						{foreach from=$opt.dropdown item=drop}
							<option value="{$drop.value}" {if $drop.selected}selected="selected"{/if}>{$drop.value}</option>
						{/foreach}
					</select>
					{if $opt.other_name}
						{* leading space left here intentionally *} {$messages.1261} <input type="text" name="{$opt.other_name}" {if $opt.other_value}value="{$opt.other_value}"{/if} size="15" maxlength="{if !$opt.maxlen}100{else}{$opt.maxlen}{/if}" class="field" />
					{/if}
				{/if}
				
				{if $opt.error}
					<span class="error_message">{$opt.error}</span>
				{/if}
			</div>
		{/foreach}
		
		{if $security_image}
			{$security_image}
		{/if}
		
		<h1 class="subtitle">{$messages.774}</h1>
		
		<div class="{if $username.error}field_error_row{else}{cycle values='row_odd,row_even'}{/if}">
			<label for="username" class="required">{$messages.762} *</label>
			<input type="text" id="username" name="c[username]" size="15" value="{$username.value}" maxlength="{if $username.maxlen}{$username.maxlen}{else}100{/if}" class="field" />
			
			{if $username.error}
				<span class="error_message">{$username.error}</span>
			{/if}
		</div>
		<div class="{if $password.error}field_error_row{else}{cycle values='row_odd,row_even'}{/if}">
			<label for="password" class="required">{$messages.763} *</label>
			<input type="password" id="password" name="c[password]" size="15" maxlength="{if $password.maxlen}{$password.maxlen}{else}100{/if}" class="field" />
			
			{if $password.error}
				<span class="error_message">{$password.error}</span>
			{/if}
		</div>	
		<div class="{if $password.error}field_error_row{else}{cycle values='row_odd,row_even'}{/if}">
			<label for="password_confirm" class="required">{$messages.764} *</label>
			<input type="password" id="password_confirm" name="c[password_confirm]" size="15" maxlength="{if $password.maxlen}{$password.maxlen}{else}100{/if}" class="field" />
		</div>
		
		{if is_array($eula)}
			{* user agreement field *}
			
			<div class="{if $eula.error}field_error_row{else}{cycle values='row_odd,row_even'}{/if}">
				<label for="agreement" class="required">{$messages.765} *</label>
				<label><input type="radio" id="agreement" name="c[agreement]" value="yes" {if $eula.checked == "yes"}checked="checked"{/if} /> {$messages.766}</label> 
				<label><input type="radio" name="c[agreement]" value="no" {if $eula.checked == "no"}checked="checked"{/if} /> {$messages.767}</label> 
				{if $eula.error}
					<span class="error_message">{$messages.782}</span>
				{/if}
				<div class="clr"></div>
				<br />
				{if $eula.type == "div"}
					<div class="usage_agreement">{$eula.text}</div> 
				{elseif $eula.type == "area"}
					<textarea name="registration_agreement" class="field usage_agreement" readonly="readonly" onfocus="this.blur();">{$eula.text}</textarea>
				{/if}
			</div>
		{/if}
		
		
		<div class="center">
			<input type="submit" name="submit" value="{$messages.278}" class="button" />
			
			<br /><br />
			<a href="{$registration_url}?b=4" class="cancel">{$messages.241}</a>
		</div>
		{$messages.244}
	</form>
</div>
