{* 7.0.1-18-g337b864 *}

{literal}
<script type="text/javascript">
	//<![CDATA[
	var geoPaypalInplace = {
		onComplete : function (transport, element) {
			var data = transport.responseJSON;
			if (!data) {
				geoUtil.addError('Server Error, re-load page');
				return;
			}
			if (data.error) {
				geoUtil.addError(data.error);
			} else if (data.message) {
				geoUtil.addMessage(data.message);
			}

			if (data.email && data.email.length > 0) {
				element.update(data.email);
			} else {
				element.update('');
			}

			new Effect.Highlight(element, {startcolor: this.options.highlightColor});
		}
	};
	
	Event.observe(window, 'load', function () {
		var paypalInplace = new Ajax.InPlaceEditor ($('paypal_id'), 'AJAX.php?controller=UserDetailChange&action=edit',
			{
				cancelControl:'button',
				onComplete: geoPaypalInplace.onComplete,
				{/literal}
				okText: '{$messages.500216|escape_js}',
				cancelText: '{$messages.500816|escape_js}',
				savingText: '{$messages.500817|escape_js}',
				clickToEditText: '{$messages.500215|escape_js}'
				{literal}
			});
		if ('{/literal}{$paypal_id}{literal}'=='') {
			//it is blank!
			paypalInplace.enterEditMode('click');
		}
	});
	//]]>
</script>

{/literal}

<h1 class="subtitle">{$messages.500204}</h1>
<div class="row_even">
	<div id='update_response'>
		<label class="field_label">{$messages.500205}</label>
		<div class="inline"><div id="paypal_id" class="field" style="min-width: 250px;">{$paypal_id}</div></div>
	</div>
</div>
