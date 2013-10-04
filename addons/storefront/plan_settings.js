//for use by storefront item settings
function toggleSubscriptionDisplay() {
	var displaySetting=false;
	var subCheck = $('storefront_subscription[enabled]');
	
	
	if (subCheck && subCheck.checked && $('storefrontChoices')){
		$('storefrontChoices').show();
		checkSubscriptionPeriods();
	} else if ($('storefrontChoices')) {
		$('storefrontChoices').hide();
		if ($('needsPeriodError')) {
			$('needsPeriodError').hide();
		}
	}
}

function checkSubscriptionPeriods() {
	var parent = $('storefrontChoices');
	if (!parent){
		return;
	}
	var fieldsToUse = $$('input.storefrontPeriod');
	
	for(i=0;i<fieldsToUse.length;i++) {
		if(fieldsToUse[i].checked) {
			$('needsPeriodError').hide();
			return;
		}
	}
	$('needsPeriodError').show();
	return;
}
//This is sooo awesome!
toggleSubscriptionDisplay();