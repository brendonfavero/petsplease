{* 7.1beta1-1199-gde0dfe9 *}

{* JavaScript needed by the Sharing addon, done in a .tpl file so we can use things like {$classifieds_file_name} *}

{literal}
	<script type="text/javascript">

		chosenListing = '';
		chosenMethod = '';
		
		var getMethodsForListing = function (listingId) {
			
			$('share_methods_box').hide(); //hide method selection box until its new data has been populated
			$('share_options_box').hide(); //also hide the options box
			
			if(listingId == '') {
				//top (blank) option selected -- clear everything and exit
				$('share_methods').update('');
				$('share_options').update('');
				return true;
			}
			
			new Ajax.Request({/literal}'{$classifieds_file_name}?a=ap&addon=sharing&page=ajax&function=getMethodsForListing'{literal}, {
				method: 'post',
				parameters: {
					listing: listingId 
				},
				onSuccess: function(returned) {
					//returned.responseText is the returned value
					shareMethodsHtml = returned.responseText;
					if(shareMethodsHtml != '') {
						$('share_methods').update(shareMethodsHtml);
						$('share_methods_box').show();
						
						//if the "options" box is showing, hide it.
						//user has selected a new listing and must re-select from valid methods
						$('share_options_box').hide();
						$('share_options').update('');
						chosenListing = listingId;
					}
				}
			});
		}

		var getOptionsForMethod = function (methodName) {
			$('share_options_box').hide(); //hide method selection box until its new data has been populated
			new Ajax.Request({/literal}'{$classifieds_file_name}?a=ap&addon=sharing&page=ajax&function=getOptionsForMethod'{literal}, {
				method: 'post',
				parameters: {
					method: methodName,
					listing: chosenListing
				},
				onSuccess: function(returned) {
					//returned.responseText is the returned value
					shareOptionsHtml = returned.responseText;
					chosenMethod = methodName;
					if(shareOptionsHtml != '') {
						$('share_options').update(shareOptionsHtml);
						$('share_options_box').show();
					}
				}
			});
		}


		Event.observe(window,'load', function() {
			
			//observe the final form, and be ready to reroute it to ajax
			Event.observe('options_form','submit', function(event) {
				event.stop();

				$('options_form').request({
					parameters: {
						chosenMethod: chosenMethod,
						listing: chosenListing
					},
					onSuccess: function(returned) {
						optionsResult = returned.responseText;
						
						jQuery(document).gjLightbox('open',optionsResult);
					}
				});
				
			});
		});
		
		if($('listing_select')) {
			Event.observe('listing_select','change', function() {
				getMethodsForListing($('listing_select').options[this.selectedIndex].value);
			});
		} else {
			//autoload the chosen listing
			Event.observe(window, 'load', function() {
				getMethodsForListing($('listingToShare').value);
			});
		}


	</script>
{/literal}