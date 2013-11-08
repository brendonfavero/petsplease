{* 6.0.7-3-gce41f93 *}
{if count($questions) > 0}
	{if $messages.131}
		<h1 class="subtitle">
			{$messages.131}
		</h1>
	{/if}
	{if $messages.132}
		<p class="page_instructions">
			{$messages.132}
		</p>
	{/if}

	
	{foreach from=$questions key="display_order" item="order_questions"}
		{foreach from=$order_questions item="question"}
			{assign var="key" value=$question.key}

			{if $key eq 181}{continue}{/if} {* Suppress female input - handled elsewhere *}

			{if $key eq 180}
				<label for="b[question_value][{$key}]" class="field_label">
					{$question.name|fromDB}
				</label>

				{continue}
			{/if}

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
					{if $key eq 173 or $key eq 174}
						<select name="b[question_value][{$key}]" id="b[question_value][{$key}]" class="field">
							{for $i=1 to 10}
								<option>{$i}</option>
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
						<input type="radio" name="b[question_value][{$key}]" value="0" id="islitter_single" />
						<label for="islitter_single">Dog</label>

						<input type="radio" name="b[question_value][{$key}]" value="1" id="islitter_litter" />
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
		{/foreach}
	{/foreach}
{/if}