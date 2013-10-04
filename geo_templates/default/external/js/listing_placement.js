/*
 * JS for listing detail collection page.
 * 
 * NOTE: This has been "partially" converted to use jQuery.
 * 
 * 7.2.1-16-g2bb821a
 */


Event.observe(window,'load',function () {
	geoListing.getLength(null, $('main_description'));
	geoListing.init();
} );

var geoListing = {
	
	inAdmin : false,
	
	combinedDefaultSerial : '',
	
	_onComplete : [],
	_onStart : [],
	
	_loading : false,
	_loadingQueue : false,
	
	_loadQueue : [],
	
	onComplete : function (callback) {
		if (typeof callback !== 'function') {
			jQuery.error('Invalid callback specified, not a function.');
			return;
		}
		geoListing._onComplete[geoListing._onComplete.length] = callback;
	},
	
	onStart : function (callback) {
		if (typeof callback !== 'function') {
			jQuery.error('Invalid callback specified, not a function.');
			return;
		}
		geoListing._onStart[geoListing._onStart.length] = callback;
	},
	
	init : function () {
		//start up the tag autofill
		geoListing.initTagAutofill();
		//watch the auction type and buy now only fields for changes
		jQuery('#buy_now_only').click(geoListing.auctionTypeChange);
		jQuery('#auction_type').change(geoListing.auctionTypeChange);
		
		//make sure everything is shown/hidden correctly
		geoListing.auctionTypeChange();
		
		if (jQuery('.combined_update_fields').length && jQuery('#combined_form').length) {
			//Now for combined steps...
			//first, store the default serialized form...
			geoListing.combinedDefaultSerial = jQuery('#combined_form').serialize();
			
			//now watch any selects for changes to the value
			jQuery('.combined_update_fields select')
				.unbind('.combined')
				.on('change.combined', function () {
					geoListing.combinedUpdate(jQuery(this).closest('.combined_step_section').attr('id'));
				});
			jQuery('.combined_update_fields input[type=radio]')
				.unbind('.combined')
				.on('click.combined',function() {
					geoListing.combinedUpdate(jQuery(this).closest('.combined_step_section').attr('id'));
				});
		}
	},
	
	popQueue : function () {
		//calling this method is how to say "loading is complete, so do next load in the queue".
		//console.log('poping queue');
		geoListing._loading = false;
		if (!geoListing._loadQueue.length) {
			//nothing on the queue
			return false;
		}
		//get the oldest one off of the array
		var section_changed_id = geoListing._loadQueue.shift();
		geoListing._loadingQueue = true;
		geoListing.combinedUpdate(section_changed_id);
		geoListing._loadingQueue = false;
	},
	
	combinedUpdate : function (section_changed_id) {
		if (geoListing._loading) {
			//already loading in progress!  Queue it up...
			//console.log('queueing up a change...');
			geoListing._loadQueue[geoListing._loadQueue.length] = section_changed_id;
			return;
		}
		//console.log('updating combined results');
		geoListing._loading = true;
		
		var combinedForm = jQuery('#combined_form');
		
		if (typeof geoWysiwyg !== 'undefined') {
			//close any wysiwyg editors...  Need to unload tiny for serialize to
			//work properly
			geoWysiwyg.removeTiny();
		}
		
		var formData = combinedForm.serialize();
		
		if (formData == geoListing.combinedDefaultSerial && !geoListing._loadingQueue) {
			//no changes to the form, nothing to update
			return geoListing.popQueue();
		}
		if (section_changed_id) {
			//see if that section currently has errors, if it does not have any
			//errors then we set it in the form URL so it does not get updated
			if (jQuery('#'+section_changed_id).find('.field_error_row').length==0) {
				//no errors in the section, so do not need to update the contents
				formData = formData+'&ajax_section_changed='+section_changed_id;
			}
		}
		
		if (typeof geoUH !== 'undefined') {
			//let geouh destroy itself...
			geoUH.destroy();
		}
		
		//Trigger any "onstart" actions
		jQuery.each(geoListing._onStart, function() {this();});
		
		//Add overlay / loading graphic
		jQuery('.combined_loading_overlay').each(function () {
			jQuery(this).width(jQuery(this).closest('.combined_step_section').width())
				.height(jQuery(this).closest('.combined_step_section').height())
				.fadeTo('fast',0.5);
			if (jQuery(this).closest('.combined_step_section').prop('id')==section_changed_id) {
				jQuery(this).find('img').show();
			} else {
				jQuery(this).find('img').hide();
			}
		});
		
		jQuery.post(combinedForm.attr('action'), formData, 'json').done(function (data) {
			if (data.sections) {
				//insert data into each section
				jQuery.each(data.sections, function (section_name, section_contents) {
					if (section_name) {
						var sectionBox = jQuery('#combined_'+section_name+'.combined_step_section');
						sectionBox.html(section_contents);
						gjUtil.leveledFields.init(sectionBox);
					}
				});
				geoUtil.init();
				geoListing.init();
				if (typeof geoWysiwyg !== 'undefined') {
					//close any wysiwyg editors...
					geoWysiwyg.restoreTiny();
				}
				jQuery.each(geoListing._onComplete, function() {this();});
			}
			geoListing.combinedDefaultSerial = jQuery('#combined_form').serialize();
			jQuery('.combined_loading_overlay').hide();
			geoListing.popQueue();
		});
	},
	
	checkLength : function( e , target )
	{
		var selection = '';
		var cur_len; 
		var keynum;
		
		if(window.event) { // IE
			keynum = e.keyCode
			selection = document.selection.createRange().text; // check for selection
		} else if(e.which) { // Netscape/Firefox/Opera
			keynum = e.which
			selection = target.value.substring(target.selectionStart,target.selectionEnd); // check for selection
		}
		e.modifiers
		cur_len = target.value.length;
	
		if ( keynum != '8' && keynum != undefined && selection == '' ) { // 8 == backspace
			if ( cur_len == max_length )
				return false;
			else if ( cur_len > max_length ) {
				target.value = e.target.value.substr(0,max_length);
				return false;
			}
			return true;
		}
		else
			return true;
	},
	
	getLength : function ( e , target )
	{
		//TODO: Convert to jquery
		target = $(target);
		if (!target) {
			//could not find element on page
			return;
		}
		var char_remain = $('chars_remaining');
		if (!char_remain){
			//could not find text to update
			return;
		}
		var cur_len = (target.value).length;
	
		if ( cur_len > max_length ) { // double check they didnt paste something huge into the textarea
			target.value = target.value.substr(0,max_length);
			char_remain.update('0');
			return false;
		}
		char_remain.update(''+(max_length - cur_len));
		return true;
	},
	
	auctionTypeChange : function ()
	{
		var auction_type_value = jQuery('#auction_type').val();
		
		var is_standard = (auction_type_value=='1');
		var is_dutch = (auction_type_value=='2');
		var is_reverse = (auction_type_value=='3');
		
		var buy_now = jQuery('#buy_now_only');
		
		var is_bno = (is_standard && ((buy_now.attr('type')=='checkbox' && buy_now.prop('checked'))
				|| (buy_now.attr('type')=='hidden' && buy_now.val()==1)));
		
		//go through each thing that needs to be shown/hidden, and figure out
		//if it should show/hide based on stuff above...
		
		if (is_bno) {
			//hide min row and reserve row
			jQuery('#min_row,#res_row').hide('fast');
			//set values for min and reserve to blank
			jQuery('#minimum').val('');
			jQuery('#reserve').val('');
			
			//show the applies box
			jQuery('#price_applies_box').show('fast');
			if (jQuery('#price_applies').prop('disabled')) {
				//make it not disabled
				jQuery('#price_applies').prop('disabled',false)
					.val('item');
			}
		} else {
			//show min and reserve row
			jQuery('#min_row,#res_row').show('fast');
			
			if (is_dutch) {
				jQuery('#price_applies_box').hide('fast');
			} else {
				jQuery('#price_applies_box').show('fast');
			}
			jQuery('#price_applies').prop('disabled',true)
				.val('lot');
		}
		if (is_reverse) {
			jQuery('#maximum_label').show('fast');
			jQuery('#minimum_label').hide('fast');
		} else {
			jQuery('#maximum_label').hide('fast');
			jQuery('#minimum_label').show('fast');
		}
		
		if (is_dutch || (is_reverse && !jQuery('#buy_now_row').hasClass('reverse_buy_now'))) {
			//if dutch, or if reverse but no fancy class on container, hide buy now row
			jQuery('#buy_now_row').hide('fast');
		} else {
			jQuery('#buy_now_row').show('fast');
		}
		
		if (!is_standard) {
			jQuery('#buy_now_only_row').hide('fast');
			if (jQuery('#buy_now_only').attr('type')=='checkbox') {
				jQuery('#buy_now_only').prop('checked',false);
			}
		} else {
			jQuery('#buy_now_only_row').show('fast');
		}
	},
	
	initTagAutofill : function ()
	{
		if (!jQuery('#listingTags').length) {
			//no input found for listing tags
			return;
		}
		//TODO: Convert to use jQuery!!
		var pre = (geoListing.inAdmin)? '../' : '';
		new Ajax.Autocompleter('listingTags', 'listingTags_choices', pre+'AJAX.php?controller=ListingTagAutocomplete&action=getSuggestions', {
			paramName : 'tags'
		});
	}
};