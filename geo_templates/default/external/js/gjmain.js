// 7.2.1-4-g0cb89a6

//Main jQuery based JS, this is where "new" JS goes, or any of the existing JS
//that has been converted to work with jQuery instead of Prototype.

//NOTE:  you don't have to customize this JS file to change vars, instead add some
//JS to your own custom JS file (or in script tags on a template), most of
//the plugins were written to allow some custimization to how they work.


/*
 * This is the main namespace for generic JS utilities.  Note: gj is short for
 * "Geodesic jQuery"..  Anything with the "old" prefix of "geo" should be considered
 * deprecated, to be converted to use jQuery in future version.
 */
var gjUtil = {
	runHeartbeat : false,
	inAdmin : false,
	
	/**
	 * This one is for stuff to do when DOM is done loading
	 */
	ready : function () {
		if (typeof console !== 'undefined' && typeof console.log !== 'undefined') {
			jQuery.error = console.log;
		}
		if (typeof window.IN_ADMIN !== 'undefined') {
			if (window.IN_ADMIN) {
				gjUtil.inAdmin = true;
			}
		}
		
		//call back to run the heartbeat
		if (gjUtil.runHeartbeat && !gjUtil.inAdmin) {
			//ping cron.php
			jQuery.get('cron.php?action=cron');
			//then set runHeartbeat to false, keep it from running again
			gjUtil.runHeartbeat=false;
		}
		
		//Browsing: make sort dropdown work:
		jQuery('select.browse_sort_dropdown').change(gjUtil.browseSortChange);
		
		jQuery('.openLightboxLink').click(function () {
			jQuery(document).gjLightbox('get',jQuery(this).attr('href'));
			return false;
		});
		
		gjUtil.lightbox.initClick();
		
		gjUtil.leveledFields.init(jQuery(document));
		
		//advanced search
		gjUtil.searchCategory.init();
		
		//fade images (or dives with whatever in them) in and out
		jQuery('.gj_image_fade').gjImageFade();
		
		//Make the social hovers work
		gjUtil.initSocialHovers();
	},
	
	/**
	 * This is for things to do when window is done loading (all images loaded,
	 * NOT just the DOM loaded
	 */
	load : function () {
		//initialize gallery / carousel stuff
		gjUtil.initGallery();
		
		//init image gallery
		gjUtil.initImgGallery();
		
		//Sometimes the carousel row width is off, if loaded on document ready, so
		//init this on page load not on dom ready.
		gjUtil.initCarousel();
	},
	/**
	 * Initializes the listing gallery.  (Just makes sure the height on all the
	 * gallery items match up to make it all lined up)
	 */
	initGallery : function () {
		//Find all galleries that use columns/rows, and make sure each gallery
		//entry matches in height.  Done this way because heights need to match
		//for each gallery, but not across different galleries that might be on
		//the page.
		jQuery('.listing_set.gallery').has('.gallery_row').each(function(){
			jQuery(this).find('.article_inner').gj('setMaxHeight');
		});
	},
	/**
	 * Initializes the listing gallery carousel
	 */
	initCarousel : function () {
		//init the simple carousel on any elements with CSS class of "gj_simple_carousel"
		jQuery('.gj_simple_carousel .listing_set.gallery').gjSimpleCarousel();
		
		if (jQuery('.gj_carousel_keySlide').length) {
			//there are carousels to move back and forth, so register back/forth arrows
			jQuery(document).bind('keypress', function (e){
				if (e.keyCode==37) {
					//slide to the left
					jQuery('.gj_carousel_keySlide').gjSimpleCarousel('slide',{where:'left'});
				} else if (e.keyCode==39) {
					//slide to the right
					jQuery('.gj_carousel_keySlide').gjSimpleCarousel('slide',{where:'right'});
				}
			});
		}
	},
	
	/**
	 * Initializes the image gallery, the gallery for showing images on an individual
	 * listing.
	 */
	initImgGallery : function () {
		//large image block links...  Let's just stick this in here
		jQuery('.largeImageBlockLink').click(function () {
			//set top offset to 5 px up
			var topOffset = jQuery(this.hash).offset().top - 5;
			jQuery('html, body').animate({
				scrollTop : topOffset
			}, 2000);
			return false;
		});
		
		//This is actually the "gallery view" for images in one single listing.
		jQuery('.galleryContainer, .filmstrip_container').each(function(){
			$this = jQuery(this);
			var bigImg = $this.find('.bigLeadImage, .filmstrip_main_img');
			var bigDesc = $this.find('.imageTitle');
			
			$this.find('.thumb').each(function (){
				jQuery(this).click(function(){
					var txtNode = jQuery(this).next();
					var bigClass = txtNode.prop('id');
					var txt = txtNode.html();
					
					//hide everything
					bigImg.find('a:visible').hide();
					//then show just what we want
					bigImg.find('a.'+bigClass).show();
					//then shove in the p contents
					bigDesc.html(txt);
				});
			});
			
			//make sure the width of the outer is set to max height...
			$this.find('.galleryBigImage').width($this.find('.galleryBigImage img').gj('getMaxWidth'));
			
			if ($this.find('.filmstrip_strip_container').length) {
				//specific to filmstrip: make hover over arrows smooth scroll...
				
				//first, make the height so that the stuff inside actually shows
				var tallestThumb=90;
				
				$this.find('.filmstrip_entry').each(function() {
					tallestThumb = Math.max(tallestThumb, jQuery(this).outerHeight());
				});
				
				//calculate amount of buffer needed for the overall width, NOT
				//including any extra stuff possibly added by the image caption.
				//first figure out the buffer surounding the main div
				var mainFilmBuffer = $this.find('.filmstrip_main_img').outerWidth(true)-$this.find('.filmstrip_main_img').innerWidth();
				//now add any buffer for the image itself
				mainFilmBuffer = mainFilmBuffer + $this.find('.filmstrip_main_img img').outerWidth(true)-$this.find('.filmstrip_main_img img').innerWidth();
				
				//now set the main container to match width plus the buffer, so that
				//long captions do not push it really wide
				$this.find('.filmstrip_main').width($this.find('.filmstrip_main img').gj('getMaxWidth')+mainFilmBuffer);
				
				$this.find('.filmstrip_strip_container').height(tallestThumb);
				
				//Set min width/height on big image according to largest dimensions
				//so that the big img doesn't jump around.
				var tallestBig=0, maxWidth=0;
				$this.find('.filmstrip_main_img a').each(function(){
					tallestBig = Math.max(tallestBig, jQuery(this).outerHeight());
					maxWidth = Math.max(maxWidth, jQuery(this).outerWidth());
				});
				$this.find('.filmstrip_main_img').css({
					'min-height':tallestBig+'px',
					'min-width':maxWidth+'px'
					});
				
				//now get the infernal hovers to work
				//first figure out how wide we are total...
				var innerWidth = $this.find('.filmstrip_strip').outerWidth();
				
				//and the width of the surrounding part
				var windowWidth = $this.find('.filmstrip_strip_container').innerWidth();
				
				var hideScroll = function (elem) {
					elem.css({'opacity':'0.2', cursor:'default'})
						.addClass('no_hover');
				};
				var showScroll = function (elem) {
					elem.css({'opacity':'1', cursor:'pointer'})
						.removeClass('no_hover');
				};
				
				var leftB = $this.find('.filmstripLeftScrollButton');
				var rightB = $this.find('.filmstripRightScrollButton');
				
				if (innerWidth<windowWidth) {
					//all the images fit inside the window, no scrolling needed...
					hideScroll(leftB);
					hideScroll(rightB);
				} else {
					//set up hover effects
					
					var overflow = innerWidth-windowWidth;
					
					//function to update whether buttons show or not
					var updateB = function (filmstrip) {
						if (typeof filmstrip == 'undefined') {
							var filmstrip = jQuery(this).closest('.filmstrip_container').find('.filmstrip_strip');
						}
						var d = filmstrip.position().left;
						
						if (d==0) {
							//all the way to left...
							hideScroll(leftB);
							showScroll(rightB);
						} else if ((d*-1) == overflow) {
							//all the way to the right...
							showScroll(leftB);
							hideScroll(rightB);
						} else {
							//somewhere in middle
							showScroll(leftB);
							showScroll(rightB);
						}
					};
					
					//go ahead and updateB now
					updateB($this.find('.filmstrip_strip'));
					
					//goal:  umm, how about 100px / second...
					var speed = 100;
					
					leftB.hover(function () {
						//find the part that gets moved around, relative to this button
						var filmstrip = jQuery(this).closest('.filmstrip_container').find('.filmstrip_strip');
						//now figure out the distance (d) from left
						var d = filmstrip.position().left;
						if (d==0) {
							//already full left
							return;
						}
						
						//note: d is negative, need it to be positive, thus the -1000
						var duration = (d * -1000) / speed;
						filmstrip.filter(':not(:animated)')
							.animate({left:'0px'}, {
								'duration':duration,
								'complete': updateB
							});
					}, function () {
						jQuery(this).closest('.filmstrip_container').find('.filmstrip_strip').stop(true,false);
						updateB(jQuery(this).closest('.filmstrip_container').find('.filmstrip_strip'));
					});
					
					rightB.hover(function () {
						//find the part that gets moved around, relative to this button
						var filmstrip = jQuery(this).closest('.filmstrip_container').find('.filmstrip_strip');
						//now figure out the distance (d) from right
						var d = (-1*overflow) - filmstrip.position().left;
						if (d==0) {
							//already full right to left
							return;
						}
						var duration = (d * -1000) / speed;
						
						filmstrip.filter(':not(:animated)')
							.animate({left:'-'+overflow+'px'}, {
								'duration':duration,
								'complete': updateB
							});
					}, function () {
						jQuery(this).closest('.filmstrip_container').find('.filmstrip_strip').stop(true,false);
						updateB(jQuery(this).closest('.filmstrip_container').find('.filmstrip_strip'));
					});					
				}
			}
		});
	},
	
	/**
	 * Makes the fancy social hovers work.  Adapted from something found on:
	 * 
	 * http://www.marcofolio.net/css/display_social_icons_in_a_beautiful_way_using_css3.html
	 */
	initSocialHovers : function () {
		// Hide all the tooltips
		jQuery("#social_hovers li a strong").css({opacity: 0});
		
		jQuery("#social_hovers li").hover(function() { // Mouse over
			jQuery(this)
				.stop().fadeTo(500, 1)
				.siblings().stop().fadeTo(500, 0.2);
			
			jQuery(this).find('a strong')
				.stop()
				.animate({ opacity: 1, top: '-10px' }, 300);
		}, function() { // Mouse out
			jQuery(this)
				.stop().fadeTo(500, 1)
				.siblings().stop().fadeTo(500, 1);
			
			jQuery(this).find('a strong')
				.stop()
				.animate({ opacity: 0, top: '-1px' }, 300);
		});
	},
	
	/**
	 * Observer for when the browse sort-by dropdown changes.
	 */
	browseSortChange : function () {
		jQuery(this).find('option:selected').each(function(){
			//start from hidden a tag href.. Needed to force IE to take base
			//location into effect.  Trying to use just relative URL will
			//not work in IE when URL is re-written already.
			var href = jQuery(this).parent('select').prev('a').get(0).href;
			
			if (href.indexOf('?')==-1) {
				//this is re-written, add ? to end
				href += '?c=';
			}
			href += jQuery(this).val();
			window.location.href=href;
		});
	},
	
	searchCategory : {
		_onComplete : [],
		
		init : function () {
			jQuery('#adv_searchCat').on('change', gjUtil.searchCategory.categoryChange);
			
			gjUtil.searchCategory.onComplete(function () {
				//function to call when new results are loaded...
				//be sure to initialize the stuff for leveled fields.
				gjUtil.leveledFields.init(jQuery('#catQuestions'));
				
				//update the calendar stuff
				geoUtil.initCalendars();
			});
			gjUtil.searchCategory.categoryChange();
		},
		
		
		
		onComplete : function (callback) {
			if (typeof callback !== 'function') {
				jQuery.error('Invalid callback specified, not a function.');
				return;
			}
			gjUtil.searchCategory._onComplete[gjUtil.searchCategory._onComplete.length] = callback;
		},
		
		categoryChange : function () {
			//use reference to ID instead of this, that way can use this method
			//directly
			var catId = jQuery('#adv_searchCat').val();
			
			if (!catId) {
				//empty the contents of the search field thingy
				gjUtil.searchCategory.emptyCatFields();
				return;
			}
			
			var url = 'AJAX.php?controller=AdvancedSearch&action=getCatFields&catId='+catId;
			jQuery('#catQuestions').load(url,function (resultTxt) {
				if (!resultTxt.length) {
					//empty results, close it
					gjUtil.searchCategory.emptyCatFields();
					return;
				}
				jQuery(this).show('slow');
				//do anything for onload
				jQuery.each(gjUtil.searchCategory._onComplete, function() {this();});
			});
		},
		
		emptyCatFields : function () {
			if (jQuery('#catQuestions').is(':empty')) {
				//already empty, no work to do
				return;
			}
			//hide it and empty it
			jQuery('#catQuestions').hide().empty();
		}
	},
	
	/**
	 * Shortcuts for doing things with the gjLightbox plugin
	 */
	lightbox : {
		/**
		 * Shortcut to initialize the lightbox.  This is shortcut for doing:
		 * jQuery(document).gjLightbox();
		 */
		init : function () {
			jQuery(document).gjLightbox();
		},
		
		/**
		 * Starts watching all of the common classes that do stuff with the lightbox,
		 * like lightUpLink and such.
		 */
		initClick : function () {
			jQuery('.lightUpImg').gjLightbox('clickLinkImg');
			jQuery('.lightUpLink').gjLightbox('clickLink');
			jQuery('.lightUpDisabled').gjLightbox('clickDisabled');
		},
		
		/**
		 * Easy way to close the lightbox if it's open.  This is a shortcut for:
		 * jQuery(document).gjLightbox('close');
		 */
		close : function () {
			jQuery(document).gjLightbox('close');
		},
		
		/**
		 * Add a callback to be called at the time the lightbox is opened.  Note
		 * that this happens when the lightbox is going from "closed" state to
		 * "open" state, typically you would use this to hide things that
		 * don't work well with overlays.
		 * 
		 * @param callback
		 */
		onOpen : function (callback) {
			if (typeof callback !== 'function') {
				jQuery.error('Not a valid callback function.');
				return;
			}
			//first need to make sure lightbox is initialized
			jQuery(document).gjLightbox();
			//get the data
			var data = jQuery(document).data('gjLightbox');
			if (!data) {
				//not initialized or something went wrong
				jQuery.error('Could not retrieve data, so not able to set next image ID');
				return false;
			}
			data.onOpen[data.onOpen.length] = callback;
		},
		
		/**
		 * Add a callback to be called at the time the lightbox is closed.  Note
		 * that the precise time this happens is when the "fadeOut" animation is
		 * complete for the lightbox.  This is best place to show things that
		 * may have been hidden by an onOpen callback.
		 * 
		 * @param callback
		 */
		onClose : function (callback) {
			if (typeof callback !== 'function') {
				jQuery.error('Not a valid callback function.');
				return;
			}
			//first need to make sure lightbox is initialized
			jQuery(document).gjLightbox();
			//get the data
			var data = jQuery(document).data('gjLightbox');
			if (!data) {
				//not initialized or something went wrong
				jQuery.error('Could not retrieve data, so not able to set next image ID');
				return false;
			}
			data.onClose[data.onClose.length] = callback;
		},
		
		/**
		 * Add a callback to be called at the time the lightbox is showing
		 * new contents, at the point that the contents are done being inserted
		 * into the document DOM.  This is best place to add any new "observers"
		 * on any contents that may have been loaded into the lightbox.
		 * 
		 * @param callback
		 */
		onComplete : function (callback) {
			if (typeof callback !== 'function') {
				jQuery.error('Not a valid callback function.');
				return;
			}
			//first need to make sure lightbox is initialized
			jQuery(document).gjLightbox();
			//get the data
			var data = jQuery(document).data('gjLightbox');
			if (!data) {
				//not initialized or something went wrong
				jQuery.error('Could not retrieve data, so not able to set next image ID');
				return false;
			}
			data.onComplete[data.onComplete.length] = callback;
		},
		
		/**
		 * Gets the jQuery selection of current contents of the lightbox.  Note that
		 * this returns a jQuery('...') object, not the element itself.
		 * 
		 * @returns jQuery selection of the lightbox
		 */
		contents : function () {
			//first need to make sure lightbox is initialized
			jQuery(document).gjLightbox();
			//get the data
			var data = jQuery(document).data('gjLightbox');
			if (!data) {
				//not initialized or something went wrong
				return null;
			}
			return data.box;
		},
		
		/**
		 * Used by the slideshow to set the next image ID
		 */
		setNextImgId : function (id) {
			//first need to make sure lightbox is initialized
			jQuery(document).gjLightbox();
			//get the data
			var data = jQuery(document).data('gjLightbox');
			if (!data) {
				//not initialized or something went wrong
				jQuery.error('Could not retrieve data, so not able to set next image ID');
				return false;
			}
			data.nextImageId = id;
			return true;
		}
	},
	/**
	 * Used for multi-level (leveled) fields, at this point it is simple, might
	 * want to convert it to plugin if it ever gets more complicated.
	 */
	leveledFields : {
		/**
		 * If this is set to true, when someone clicks on a multi-level field value,
		 * it will scroll down to have that value as the first one in the box.  If
		 * this is false, it will ONLY scroll to the value when the page is first loading,
		 * to make sure the "current selected" value is within the scroll box.
		 */
		alwaysScrollToValueOnClick : false,
		
		/**
		 * Initializes any leveledField selections on the page for the given parent
		 * jQuery selection passed in
		 */
		init : function (parent) {
			//watch for clicks
			parent.find('.leveled_value')
				.click(gjUtil.leveledFields.valueClick);
			
			//similate click if a radio is checked already
			var currentSelected = parent.find('input.leveled_radio:checked')
				.closest('.leveled_value');
			
			//do the main part for selecting that option
			currentSelected.each(function () {
				//NOTE: We use "each" so that it runs the function once per selected
				//value, it can break things if pass in a selector with multiple selections
				//in it!
				gjUtil.leveledFields.selectValue(jQuery(this),true);
			});
			
			//watch the pagination
			parent.find('.leveled_pagination a').click(function () {
				var url = this.href;
				url = url.replace(/&selected=[0-9]+/g, '');
				//have to populate selected value...
				//now add correct selected
				var selected = 0;
				var currentChecked = jQuery(this).closest('.leveled_level_box').find(':checked');
				if (currentChecked.length) {
					selected = currentChecked.val();
				}
				url = url + '&selected='+selected;
				jQuery(this).closest('.leveled_level_box').load(url, function () {
					//init contents
					gjUtil.leveledFields.init(jQuery(this));
				});
				//note: do NOT close boxes "after" because the "selected" value is maintained
				//when doing pagination.
				return false;
			});
			
			parent.find('.leveled_clear').click(gjUtil.leveledFields.clearClick);
		},
		
		clearClick : function () {
			var container = jQuery(this).closest('.leveled_level_box')
			
			//Un-check the currently checked radio.. hopefully this works
			container.find('input.leveled_radio:checked')
				.attr('checked',false);
			
			//remove the selected CSS from any that have it
			container.find('.leveled_value.selected_value').removeClass('selected_value');
			
			//clear out other children
			gjUtil.leveledFields.closeAfter(container);
			return false;
		},
		
		/**
		 * Function to use for click observer on individual value
		 */
		valueClick : function () {
			var valueBox = jQuery(this);
			
			return gjUtil.leveledFields.selectValue(valueBox, false);
		},
		
		/**
		 * Function that does the "work" for selecting a specific value, just pass
		 * in the jQuery object with the .leveled_value in question as the selection
		 * 
		 * 
		 */
		selectValue : function (valueBox, scrollToValue) {
			var radio = valueBox.find('input.leveled_radio');
			var valuesBox = valueBox.closest('.leveled_values');
			
			if (!valueBox.length || !radio.length || !valuesBox.length) {
				//something wrong...
				return;
			}
			
			//remove the selected value class from the old selection
			valuesBox.find('.leveled_value.selected_value').removeClass('selected_value');
			
			//we'll use this in a sec... whether it was already checked or not
			var alreadyActive = radio.prop('checked');
			
			//make the radio option clicked
			radio.prop('checked',true);
			
			if (scrollToValue || gjUtil.leveledFields.alwaysScrollToValueOnClick) {
				//figure how much it should be scrolled
				var offset = valueBox.position().top + valuesBox.scrollTop();
				
				//scroll to the offset
				valuesBox.animate({
					scrollTop: offset+'px' 
				}, 'fast');
			}
			
			//set some CSS on the value box...
			valueBox.addClass('selected_value');
			
			//see if we need to populate the next
			var container = valuesBox.closest('.leveled_level_box');
			var next = container.next('.leveled_level_box');
			
			var isCat = container.closest('.leveled_cat').length;
			
			if (!alreadyActive && isCat && !next.length) {
				//create the next box dynamically
				var level = container.closest('div').find('.leveled_level_box').length;
				
				jQuery('<div/>').hide()
					.append(jQuery('<ul class="leveled_values leveled_cat"><li class="leveled_value_empty"></li></ul>'))
					.addClass('leveled_level_box')
					.addClass('leveled_cat_'+level)
					.insertAfter(container);
				next = container.next('.leveled_level_box');
			}
			
			if (next.is(':empty') || next.find('li.leveled_value_empty').length || !alreadyActive) {
				//next box is empty so populate it
				var loadNextUrl = 'AJAX.php?controller=LeveledFields&action=getLevel&parent='+radio.val();
				if (gjUtil.inAdmin) {
					loadNextUrl = '../'+loadNextUrl+'&inAdmin=1';
				}
				if (container.find('.leveled_clear').length) {
					//let it know to populate the clear selection link
					loadNextUrl = loadNextUrl+'&showClearSelection=1';
				}
				if (isCat) {
					//this is actually a category
					loadNextUrl = loadNextUrl+'&cat=1';
				}
				if (jQuery('#listing_types_allowed').length) {
					loadNextUrl = loadNextUrl+'&listing_types_allowed='+jQuery('#listing_types_allowed').val();
				}
				next.load(loadNextUrl, function (responseTxt) {
						if (responseTxt.length) {
							jQuery(this).show('slow');
							gjUtil.leveledFields.init(jQuery(this));
						} else {
							//no values...
							jQuery(this).hide('slow');
						}
						if (jQuery(this).closest('.combined_update_fields').length) {
							//this is on a combined step, so update things
							geoListing.combinedUpdate(jQuery(this).closest('.combined_step_section').attr('id'));
						}
					});
				gjUtil.leveledFields.closeAfter(next);
			}
		},
		
		/**
		 * Closes any boxes after the one selected, emptying any that are not "always open"
		 * @param elem
		 */
		closeAfter : function (elem) {
			var next = elem.next('.leveled_level_box');
			if (next.length) {
				if (next.find('li.leveled_value_empty').length==0) {
					next.hide('slow', function () {jQuery(this).empty();});
				}
				gjUtil.leveledFields.closeAfter(next);
			}
		}
	}
};

