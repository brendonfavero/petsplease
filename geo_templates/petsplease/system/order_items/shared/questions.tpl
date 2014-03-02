{* 6.0.7-3-gce41f93 *}
{if count($questions) > 0}
	{if $messages.131}
		<h2 class="title">
			{$messages.131}
		</h2>
	{/if}
	{if $messages.132}
		<p class="page_instructions">
			{$messages.132}
		</p>
	{/if}

	
	{foreach from=$questions key="display_order" item="order_questions"}
		{foreach from=$order_questions item="question"}
			{assign var="key" value=$question.key}

			

			<div>
					<label for="b[question_value][{$key}]" class="field_label">
						{if $key neq 191} {* don't show label for Dog - Dog or Litter *}
							{$question.name|fromDB}
						{else}
							&nbsp;
						{/if}
					</label>
				
				{if $question.type == 'none' || $question.type == 'url' || $question.type == 'number'}
					{* Question value will be from user input, which is already html escaped *}
					{if $key eq 173 or $key eq 174 or $key eq 180 or $key eq 181}
						<select name="b[question_value][{$key}]" id="b[question_value][{$key}]" class="field">
							{for $i=0 to 10}
								<option {if $session_variables.question_value.$key|strip == $i}selected{/if}>{$i}</option>
							{/for}
						</select>
					{else}
						<input class="field" type="text" name="b[question_value][{$key}]" id="b[question_value][{$key}]" value="{$session_variables.question_value.$key}" size="30" maxlength="255" />
					{/if}
				{elseif $question.type == 'date'}
					{* Question is a date *}
					<input class="field dateInput" type="text" name="b[question_value][{$key}]" id="b[question_value][{$key}]" value="{$session_variables.question_value.$key}" size="10" maxlength="10" />
				{elseif $question.type == 'textarea'}
					{* Question value will be from user input, which is already html escaped *}
					<textarea name="b[question_value][{$key}]" id="b[question_value][{$key}]" cols="60" rows="15" class="field"
						{if $field_config.textarea_wrap}style="white-space: pre;"{/if}>{$session_variables.question_value.$key}</textarea>
				{elseif $question.type == "check"}
					{* Question value will be from DB value, which is NOT already html escaped,
						DO NOT remove the HTML escape for this specific type of question! *}
					<input type="checkbox"  id="b[question_value][{$key}]"
						name="b[question_value][{$key}]"
						value="{$question.name|escape}"
						{if $session_variables.question_value.$key}checked="checked"{/if} />
				{else}
					{if $key eq 191} {* Dog - Is Dog or Litter *}
						<input type="radio" {if $session_variables.question_value.$key|strip == 0}checked{/if} name="b[question_value][{$key}]" value="0" id="islitter_single" />
						<label for="islitter_single">Dog</label>

						<input type="radio" {if $session_variables.question_value.$key|strip == 1}checked{/if} name="b[question_value][{$key}]" value="1" id="islitter_litter" />
						<label for="islitter_litter">Puppy or Litter</label>

					{else}
						{if count($question.choices)}
							<select class="field" name="b[question_value][{$key}]" id="b[question_value][{$key}]">
								{* ARDEX - IF SECOND BREED FIELD ALLOW BLANK OPTION *}
								{if $key eq 172 or $key eq 179} {* 172: Dog - Second Breed, 179: Cat - Second Breed *}
									<option value=""{if $session_variables.question_value.$key|strip == ''} selected="selected"{/if}></option>
								{/if}
								{* END ARDEX SPECIFIC CODE *}

								{foreach from=$question.choices item="choice"}
									<option{if $session_variables.question_value.$key|strip == $choice.value|strip} selected="selected"{/if}>
										{$choice.value}
									</option>
								{/foreach}
							</select>
						{/if}
						{if $question.other_box}
							&nbsp;{$messages.133}&nbsp;<input class="field" type="text" size="12" maxlength="50" name="b[question_value_other][{$key}]" id="b[question_value][{$key}]" value="{$session_variables.question_value_other.$key}" />
						{/if}
					{/if}
				{/if}
					{$question.help}
					<input type="hidden" name="b[question_display_order][{$key}]" value="{$display_order}" />
			</div>
			{if $key eq 192}
				Pet Shops are part of the market place and have the ability to accept direct payment through PayPal including credit cards, just by having a PayPal account.
				<br/><br/>
				PayPal is loved by buyers for it’s convenience and security, you get your payment fast and secure.
				<br/><br/>
				PayPal helps safeguard eligible sellers from losses due to customer claims, chargebacks and payment reversals. Please read the PayPal Seller Protection Policy.
				<br/><br/>
				Once the payment has been made, you will receive an email with all the sale details, all you need to do is sent the product to the customer, it is the easiest way to sell.
				<br/><br/>
			
			{/if}
		{/foreach}
	{/foreach}
{/if}