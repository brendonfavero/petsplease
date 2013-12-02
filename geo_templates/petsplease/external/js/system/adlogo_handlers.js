// 7.2beta3-95-g23b3a2d

//This does the fancy stuff for image uploads

//use geoUHLogo as namespace (geo upload handler)
var geoUHLogo = {
	
	//If you are constantly getting the server error message, change this to
	//true and it will display additional debug information about what is wrong.
	debug : false,
	
	//if this is true, that means something is happening, so no actions can take
	//place.  Be careful of deadlock/race conditions on this!
	_inTransition : '',
	
	//movie width and height, over-written in upload images head
	movieWidth : 96,
	movieHeight : 24,
	
	//starting out post params, this will be populated dynamically with stuff
	//like user agent and cookie session, in upload images header.
	startingPostParams : {},
	
	//The URL for the AJAX.php file
	ajaxUrl : "AJAX.php",
	
	//the flash URL, used when initing the swfu
	flashUrl : "classes/swfupload/swfupload.swf",
	
	flashButtonImage : "images/buttons/select_file.png",
	
	//the max file upload size, over-written in upload images head
	file_size_limit : '0',
	
	//These are text entries, the defaults are here but they are over-written
	//by upload images header(smarty file).
	text : {
		sessionError : 'Error: ',
		uploadStarting : 'Upload Starting...',
		progressFileQueued1 : 'Progress: <strong>',
		progressFileQueued2 : '</strong> queued',
		processingImage : 'Processing Image...',
		progressOf : ' of ',
		progressBytesUploaded : ' bytes uploaded.',
		editImagePosition : 'Edit Image for Position ',
		keepSameImage : 'Keep same image.',
		apply : 'Apply',
		newImageUpload : 'New Image Upload',
		newImage : 'New Image',
		noFileSelected : 'Progress: No File Selected',
		upload : 'Upload',
		uploadError : '<span style="color: red;">Error:</span> ',
		generalServerError : 'Server Error occurred while processing!  Please try again with a smaller file, and make sure the file is not corrupted.',
		uploadNotFinished : 'Be sure to click the Upload button after you select a file for upload.  You have selected a file, but have not uploaded it yet, do you still want to continue without finishing?',
		editNotFinished : 'Be sure to click the Apply button when you are finished editing a file slot.  You have started to edit a file slot, but have not applied the changes yet, do you still want to continue without finishing?',
		fileTooBig : 'The file you selected is too large, the max allowed file size is '
	},
	
	//scroll setting for sortable (dragging image boxes around), if it causes
	//problems on your layout you can change it to null to disable.
	scrollSetting : window,
	
	//set this to be for how long animations should take, in seconds (0 to not
	//animate)
	defaultParams : {duration: .8},
	currentUploadSpot : 0,
	userId : 0,
	adminId : 0,
	
	swfu : null,
	_initRun : false,
	
	imageBoxVisible : false,
	
	initUploads : function () {
		if (!geoUHLogo.doingSomething('initSwfu')) {
			//init swfu over
			geoUHLogo.doNothing();
		}
		if (geoUHLogo._initRun) {
			return;
		}
		geoUHLogo._initRun = true;
		
		var newImageBox = $('adlogonewImageBox');
		if (!newImageBox) {
			//new image box not on page
			return;
		}
		
		if ($('adlogolegacyUploadContainer').visible()) {
			//if we had un-hidden the old form because page was taking too long,
			//hide it again
			$('adlogolegacyUploadContainer').hide();
		}
		//hide the loading box if it's there...
		$('adlogoloadingBox').hide();
		
		//hide the legacy
		if ($('adlogolegacyUploadBox')) {
			$('adlogolegacyUploadBox').hide();
		}
		
		//show the normal
		$('adlogostandardUploadBox').show();
		//hide it
		geoUHLogo.hideImageUploadBox(true);
		
		if (geoUHLogo.currentUploadSpot) {
			//now figure out where it goes..
			geoUHLogo.moveTo(geoUHLogo.currentUploadSpot);
		} else {
			//no spots open!  oh well, it's already hidden...
		}
		
		//make draggable
		geoUHLogo.initSortableSlots();
		
		//what out for clicks on update button
		$('adlogouploadButton').observe('click', geoUHLogo.uploadButtonClick);
		//watch out for cancel clicks too
		$('adlogocancelUploadButton').observe('click',geoUHLogo.cancelEditClick);
		
		//show the instructions
		if ($('adlogo_upload_instructions')) {
			$('adlogo_upload_instructions').show();
		}
		if ($('adlogo_upload_instructions_legacy')) {
			//hide legacy button
			$('adlogo_upload_instructions_legacy').hide();
		}
		
		//Find the "next" form, and add a listener to it.
		var formElem = $('adlogoUploadNextFormElement');
		if (formElem) {
			formElem = formElem.up('form');
			if (formElem && formElem.match('form')) {
				//this is the form element, add a listener
				formElem.observe('submit', geoUHLogo.uploadFormSubmit);
			}
		}
	},
	
	doingSomething : function (notThis) {
		return (geoUHLogo._inTransition == notThis)? false: true;
	},
	
	doSomething : function (what) {
		//sanity check
		if (geoUHLogo.doingSomething('')) {
			//already doing something, stop that!
			if (geoUHLogo.debug) alert('Attempting to do something '+what+' when already '+geoUHLogo._inTransition);
			return false;
		}
		geoUHLogo._inTransition = what;
		return true;
	},
	doNothing : function () {
		geoUHLogo._inTransition = '';
	},
	//initializes the swfu movie.  Will be called multiple times in a page load.
	initSwfu : function () {
		if (geoUHLogo.doingSomething('')) return;
		
		if (geoUHLogo.swfu) {
			//why is this being called?
			if (geoUHLogo.debug) {
				alert('called initSwfu but swfu is already inited!');
			}
			return;
		}
		geoUHLogo.doSomething('initSwfu');
		geoUHLogo.swfu = new SWFUpload({
			upload_url : geoUHLogo.ajaxUrl+"?controller=UploadAdlogo&action=uploadImage", 
			file_size_limit : geoUHLogo.file_size_limit,
			file_queue_limit : 1,
	
			// Let the script know the classified session ID:
			post_params: geoUHLogo.startingPostParams,
	
			// Button settings
			button_placeholder_id: 'adlogospanButtonPlaceHolder',
			button_image_url : geoUHLogo.flashButtonImage,
			/*
			button_text : "<span class='selectButton'><b>Select File</b></span>",
			button_text_style : ".selectButton {ldelim} color: #0000FF; font-size: 10px }",
			*/
			button_width : geoUHLogo.movieWidth,
			button_height : geoUHLogo.movieHeight,
			button_cursor : SWFUpload.CURSOR.HAND,
			button_action : SWFUpload.BUTTON_ACTION.SELECT_FILES,
			button_disabled : false,//start out disabled
			
			// Event Handler Settings - these functions in handlers.js
			swfupload_loaded_handler : geoUHLogo.initUploads,
			upload_success_handler : geoUHLogo.uploadSuccess,
			upload_error_handler : geoUHLogo.uploadError,
			upload_start_handler : geoUHLogo.uploadStart,
			upload_progress_handler : geoUHLogo.uploadProgress,
			file_dialog_start_handler : geoUHLogo.diagStart,
			file_dialog_complete_handler : geoUHLogo.diagComplete,
			file_queued_handler : geoUHLogo.fileQueued,
			file_queue_error_handler : geoUHLogo.fileQueuedError,
			
			
			// Flash Settings
			flash_url : geoUHLogo.flashUrl,	// Relative to main file
			
			// Debug Setting, use same as geoUHLogo.debug
			debug: geoUHLogo.debug
		});
		
		//reset vars
		geoUHLogo._currentButtonDisabled = true;
		geoUHLogo.currentFileId = 0;
	},
	
	makeNotSortable : function (theBox) {
		//make sure it is extended element
		theBox = $(theBox);
		if (!theBox) {
			//not valid
			return;
		}
		//make sure that spot is NOT sortable...
		if (theBox.hasClassName('imageBox')) {
			//add the empty class name
			theBox.addClassName('imageBoxEmpty');
			//remove main one
			theBox.removeClassName('imageBox');
			
			//re-start the sortable
			geoUHLogo.initSortableSlots();
		}
	},

	makeSortable : function (theBox) {
		//make sure it is extended element
		theBox = $(theBox);
		if (!theBox) {
			//not valid
			return;
		}
		//make sure that spot is NOT sortable...
		if (theBox.hasClassName('imageBoxEmpty')) {
			//add the normal class name
			theBox.addClassName('imageBox');
			//remove main one
			theBox.removeClassName('imageBoxEmpty');
		}
		//re-start the sortable
		geoUHLogo.initSortableSlots();
	},

	dragableObserver : {
		_doFade: false,
		
		_plopImage : function (draggable) {
			//move the "move image here" image to the right place, and make it visible.
			var plopImage = $('adlogoplopDropImageHere');
			if (!plopImage) {
				//nothing can be done, can't find the plog image!
				return;
			}
			
			//figure out choords to move it to
			var d = draggable.currentDelta();
			var now = draggable.element.positionedOffset();
			
			var ptop = now.top - d[1];
			var pleft = now.left - d[0];
			
			plopImage.setStyle({left: pleft+'px', top: ptop+'px'});
			plopImage.show();
		},
		
		onStart: function (eventName, draggable, event) {
			if (!draggable.element.hasClassName('imageBox')) {
				//ignore this one
				return;
			}
			if (geoUHLogo.doingSomething('')) {
				//what the... already doing something
				event.stop();
				return;
			}
			
			
			//simple hack to make the box centered vertically on the mouse,
			//so people don't get as confused...
			draggable.options.snap = function (x,y, draggable) {
				return [x, y-75];
			};
			geoUHLogo.tempHideUpload();
			//start out with the plopHere box visible instead of waiting for change
			geoUHLogo.dragableObserver._plopImage(draggable);
		},
		
		currentOrder : null,
		
		onDrag: function (eventName, draggable, event) {
			if (!draggable.element.hasClassName('imageBox')) {
				//ignore this one
				return;
			}
			//don't do this every single time it's moved, only when the move
			//causes the position to change
			var newOrder = Sortable.serialize('imagesCapturedBox');
			if (newOrder == geoUHLogo.dragableObserver.currentOrder) {
				//did not change order, nothing to do
				return;
			}
			geoUHLogo.dragableObserver.currentOrder = newOrder;
			
			//move the "move image here" image to the right place, and make it visible.
			var plopImage = $('adlogoplopDropImageHere');
			if (!plopImage) {
				//nothing can be done, can't find the plog image!
				return;
			}
			
			geoUHLogo.dragableObserver._plopImage(draggable);
		},
		
		onEnd: function (eventName, draggable, event) {
			if (!draggable.element.hasClassName('imageBox')) {
				//ignore this one
				return;
			}
			//move it to where it goes
			
			geoUHLogo.tempShowUpload();
			var plopImage = $('adlogoplopDropImageHere');
			if (plopImage) plopImage.hide();
		},
		_alreadyInited: false,
		
		init: function() {
			if (geoUHLogo.dragableObserver._alreadyInited) {
				//already initialized
				return;
			}
			geoUHLogo.dragableObserver._alreadyInited = true;
			Draggables.addObserver(geoUHLogo.dragableObserver);
		}
	},

	initSortableSlots : function () {
		//NOTE: If element is already sortable, it destroys it first automatically
		//for us!  So we can call geoUHLogo whenever we need the sortable items to be
		//re-done
		
		//Sortable.destroy('imagesCapturedBox');
		
		Sortable.create('imagesCapturedBox', {
			tag: 'div',
			only: 'imageBox',
			overlap: 'horizontal',
			treeTag: 'div',
			constraint: '',
			handle: 'imageBoxTitleHandle',
			scroll : geoUHLogo.scrollSetting,
			//hoverclass: 'dragHover',
			onUpdate: function () {
				new Ajax.Request(geoUHLogo.ajaxUrl+"?controller=UploadAdlogo&action=sortImages", {
					method: 'post',
					parameters: {
						'imageSlots': Sortable.serialize('imagesCapturedBox'),
						'userId' : geoUHLogo.userId,
						'adminId' : geoUHLogo.adminId
					},
					onSuccess: geoUHLogo.sortableResponseSuccess
				});
				//hide new image box to not show till it's done
				geoUHLogo.hideImageUploadBox();
			}
		});
		
		geoUHLogo.dragableObserver.init();
		
		//re-init the image clicks
		if (typeof gjUtil != 'undefined') {
			gjUtil.lightbox.initClick();
		}
	},
	
	moveImageUploaderBox : function (data) {
		var newImageBox = $('adlogonewImageBox');
		
		if (geoUHLogo.currentEditPosition) {
			//used to be edit, but we're moving it to new position, so it's not
			//edit no more!
			geoUHLogo.cancelEdit(true);
		}
		if (!data.error && (!data.nextUploadSlot || data.nextUploadSlot > data.maxSlots)) {
			//hide the whole thing
			if (geoUHLogo.imageBoxVisible) {
				geoUHLogo.hideImageUploadBox();
			}
			geoUHLogo.currentUploadSpot = 0;
		} else {
			//make sure it is not hidden
			if (data.nextUploadSlot) {
				geoUHLogo.currentUploadSpot = data.nextUploadSlot;
			}
			geoUHLogo.moveTo(geoUHLogo.currentUploadSpot);
		}
		//set the new upload slot
		geoUHLogo.startingPostParams.uploadSlot = geoUHLogo.currentUploadSpot;
		if (geoUHLogo.swfu != null) {
			try {
				geoUHLogo.swfu.addPostParam('uploadSlot', geoUHLogo.currentUploadSpot);
			} catch (ex) {
				if (geoUHLogo.debug) {
					alert ('DEBUG caught exception 392: '+ex);
				}
			}
		}
	},
	//can't use .hide() on image uploader, that will break opera since button is
	//in there.  Instead we destroy it and re-create it later.
	hideImageUploadBox : function (force) {
		var newImageBox = $('adlogonewImageBox');
		if (!newImageBox || (force != true && !geoUHLogo.imageBoxVisible)) {
			return false;
		}
		
		//cancel any animations
		if (geoUHLogo.fadingInAnimation != null && typeof geoUHLogo.fadingInAnimation.cancel != 'undefined') {
			geoUHLogo.fadingInAnimation.cancel();
			geoUHLogo.fadingInAnimation = null;
		}
		
		//destroy SWFU object
		var destroyResult = null;
		if (geoUHLogo.swfu == null) {
			//already destroyed, just hide the surrounding box!
			destroyResult = true;
		} else {
			try {
				destroyResult = geoUHLogo.swfu.destroy();
			} catch (ex) {
				if (geoUHLogo.debug) {
					alert('destroy failed, exception caught:'+ex);
				}
				destroyResult = false;
			}
		}
		if (!destroyResult) {
			//destroy left swfu in an inconsistent state, tell user and re-load page
			alert(geoUHLogo.text.sessionError+' Internal Page Error, page needs to re-load. (null pointer to cheese)');
			window.location.reload();
			return false;
		}
		//just to be sure, clear out the container with the starting button
		$('adlogostartingButtonContainer').update('').hide();
		//reset parameters
		//geoUHLogo.swfu should have been destroyed above, so set the var to null
		geoUHLogo.swfu = null;
		if (!geoUHLogo.doingSomething('initSwfu')) {
			//we are hiding before init is finished loading, since we just destroyed it,
			//chances are it isn't initializing any more...
			//alert('that was close!');
			geoUHLogo.doNothing();
		}
		geoUHLogo._currentButtonDisabled = true;
		if (geoUHLogo.currentEditPosition) {
			//reset the box
			geoUHLogo.resetUploadBox();
		}
		//re-insert the span so it has something to attach to when fading in the box.
		$('adlogoselectFileButtonBox').update('<span id="adlogospanButtonPlaceHolder"></span>');
		//now that button is gone, hide the box
		newImageBox.hide();
		
		geoUHLogo.imageBoxVisible = false;
		return true;
	},
	
	destroy : function () {
		if (geoUHLogo.swfu == null) {
			//already destroyed, just hide the surrounding box!
			destroyResult = true;
		} else {
			try {
				destroyResult = geoUHLogo.swfu.destroy();
			} catch (ex) {
				if (geoUHLogo.debug) {
					alert('destroy failed, exception caught:'+ex);
				}
				destroyResult = false;
			}
		}
		geoUHLogo.swfu = null;
		geoUHLogo.doNothing();
		geoUHLogo._initRun = false;
	},
	
	//if this is false, it will not add an effect when fading in, it will just appear.
	fadeInImageBoxParams : {duration: .8, from: 0.0, to: 1.0},
	
	fadingInAnimation : null,
	
	showImageUploadBox : function () {
		var newImageBox = $('adlogonewImageBox');
		if (!newImageBox || geoUHLogo.imageBoxVisible) {
			return false;
		}
		
		//if this is the first time, clear all the stuff that was not cleared before...
		
		//we CAN fade it in if we start from something not 0 as starting point
		if (geoUHLogo.fadeInImageBoxParams) {
			if (!geoUHLogo.doingSomething('')) {
				//not doing anything except uploading, so finished with upload
				geoUHLogo.doSomething('appear');
			}
			if (typeof geoUHLogo.fadeInImageBoxParams.afterFinish != 'function') {
				//init what to do once the appear is finished.
				geoUHLogo.fadeInImageBoxParams.afterFinish = function () {
					//once it's done fading in, init the movie param
					if (!geoUHLogo.doingSomething('appear')) {
						//not doing anything except appearing, so finished with appear
						geoUHLogo.doNothing();
					}
					if (!geoUHLogo.swfu) {
						//this must be done once the surrounding box is visible.
						geoUHLogo.initSwfu();
					}
					if ($('adlogonewImageBox').visible()) {
						//just in case there is some race condition, reset as
						//visible if it's visible.
						geoUHLogo.imageBoxVisible = true;
					}
					//I wonder if we can do this?
					geoUHLogo.fadingInAnimation = null;
				};
			}
			geoUHLogo.fadingInAnimation = newImageBox.appear(geoUHLogo.fadeInImageBoxParams);
			//new Effect.Appear (newImageBox, geoUHLogo.fadeInImageBoxParams);
		} else {
			newImageBox.show();
			if (!geoUHLogo.swfu) {
				geoUHLogo.initSwfu();
			}
		}
		geoUHLogo.imageBoxVisible = true;
		return true;
	},
	_doTempShow : false,
	_delayInit : false,
	/**
	 * Temporarily hides the image upload box, with intent to show it again
	 * using tempShowUpload.  Cancels any pending stuff not applied yet in
	 * image upload box, I think...
	 */
	tempHideUpload : function () {
		//fade out the new image box
		if (geoUHLogo.imageBoxVisible) {
			geoUHLogo._doTempShow = true;
			if (geoUHLogo.currentEditPosition) {
				geoUHLogo.cancelEdit(true);
				if (geoUHLogo.currentUploadSpot) {
					geoUHLogo.moveTo(geoUHLogo.currentUploadSpot);
				} else {
					//there are no open upload slots, so don't fade back in
					geoUHLogo._doTempShow = false;
				}
			}
			geoUHLogo.hideImageUploadBox();
		} else {
			//let init know to delay the display, for times that this is called
			//before the uploader is even initialized...  In such cases, will want
			//to delay initialization
			geoUHLogo._delayInit=true; 
		}
	},
	/**
	 * Shows the upload box after it has been hidden using tempHideUpload, but
	 * only if there is an empty spot to show it in.
	 */
	tempShowUpload : function () {
		//Fade it back in
		if (geoUHLogo._doTempShow && !geoUHLogo.imageBoxVisible) {
			geoUHLogo.cancelEdit(true);
			geoUHLogo._doTempShow = false;
			geoUHLogo.showImageUploadBox();
		}
	},
	
	/**
	 * Fixes the position of the image upload box
	 */
	fixUploadPosition : function () {
		if (geoUHLogo.currentEditPosition) {
			geoUHLogo.moveTo(geoUHLogo.currentEditPosition);
		} else if (geoUHLogo.currentUploadSpot) {
			geoUHLogo.moveTo(geoUHLogo.currentUploadSpot);
		}
	},
	
	/**
	 * Moves new/edit image box to the given image slot, uses a move animation if
	 * the box is already visible, or just moves it then uses a fade in animation
	 * if the box starts out hidden.
	 */
	moveTo : function (position) {
		var newImageBox = $('adlogonewImageBox');
		
		var moveTo = $('adlogoSlot_'+position);
		if (!newImageBox || !moveTo) {
			//could not find new image box, or could not find slot we're supposed
			//to be moving to
			return;
		}
		var movePosition = moveTo.positionedOffset();
		//The old -10 adjustment no longer needed, guess Prototype's
		//positionedOffset is more accurate in new version of library.
		var moveX = (movePosition.left * 1);
		var moveY = (movePosition.top * 1);
		
		if (!geoUHLogo.imageBoxVisible) {
			//just move it to where it goes and fade it in
			newImageBox.setStyle({left: moveX+'px', top: moveY+'px'});
			geoUHLogo.showImageUploadBox();
		} else {
			//move it!  Wheeeeeeeeeeee!  Speedything goes in, speedything comes out!
			
			new Effect.Move(newImageBox, {x: moveX, y: moveY, mode: 'absolute'});
		}
	},
	
	currentFileId : 0,
	
	editSuccess : function (transport) {
		if (!geoUHLogo.doingSomething('upload')) {
			//not doing anything except uploading, so finished with upload
			geoUHLogo.doNothing();
		}
		var response = transport.responseJSON;
		if (response) {
			geoUHLogo.processResponse(response);
		} else {
			//some type of server error
			geoUHLogo.throwServerError(transport);
		}
	},
	
	deleteSuccess : function (transport) {
		if (!geoUHLogo.doingSomething('deleteImage')) {
			geoUHLogo.doNothing();
		}
		var response = transport.responseJSON;
		if (response) {
			geoUHLogo.processResponse(response);
		} else {
			//some type of server error
			geoUHLogo.throwServerError(transport);
		}
	},
	
	sortableResponseSuccess : function (transport) {
		var response = transport.responseJSON;
		if (response) {
			geoUHLogo.processResponse(response);
		} else {
			//some type of server error
			geoUHLogo.throwServerError(transport);
		}
	},
	
	processResponse : function (data) {
		//reset stuff in image box
		geoUHLogo.resetUploadBox();
		
		var msg = '';
		
		if (data.imagesDisplay && data.uploadSlot && $('adlogoSlot_'+data.uploadSlot)) {
			if (data.imagesDisplay == 'get') {
				//did not pass back what is to be displayed, must go get the box
				var params = {
					uploadSlot : data.uploadSlot,
					editImage : data.editImage,
					editImageSlot : geoUHLogo.currentEditPosition,
					userId : geoUHLogo.userId,
					adminId : geoUHLogo.adminId
				};
				new Ajax.Request(geoUHLogo.ajaxUrl+"?controller=UploadAdlogo&action=imageSlotContents", {
					method: 'post',
					parameters: params,
					onSuccess: geoUHLogo.getSlotResponse
				});
			} else {
				geoUHLogo.updateSlotContents(data);
			}
		} else if (data.uploadImageBox) {
			//update all the slots
			$('adlogoCapturedBox').update(data.uploadImageBox);
			//reset draggables
			geoUHLogo.initSortableSlots();
		}
		
		if (data.errorSession) {
			//error requiring to re-load the page
			alert(geoUHLogo.text.sessionError+data.errorSession);
			window.location.reload();
		}
		
		if (data.error) {
			geoUHLogo.addError(data.error);
		}
		
		if (data.msg) {
			msg += data.msg;
		}
		
		if (msg && !data.error) {
			geoUtil.addMessage(msg);
		}
		
		//move it where it goes
		geoUHLogo.moveImageUploaderBox(data);
	},
	getSlotResponse : function (transport) {
		var response = transport.responseJSON;
		if (response) {
			geoUHLogo.updateSlotContents(response);
		} else {
			//some type of server error
			geoUHLogo.throwServerError(transport);
		}
	},
	updateSlotContents : function (data) {
		if (typeof data.uploadSlot == 'undefined' || !data.uploadSlot) {
			//nothing to do, some kind of error
			geoUHLogo.throwServerError('Invalid data, data: '+data);
			return;
		}
		//shove it in there
		
		//there is an image box to add in there
		var newBox = new Element('div',{'class': 'innerImageBox'}).hide();
		
		//shove the contents into our new div
		newBox.insert(data.imagesDisplay);
		
		//now shove the new box into the right spot
		var rightSpot = $('adlogoSlot_'+data.uploadSlot);
		rightSpot.update(newBox);
		
		//make it sortable
		geoUHLogo.makeSortable(rightSpot);
		
		//show it
		//new Effect.Appear(newBox, geoUHLogo.defaultParams); //appear goes too slow
		newBox.show();
	},
	
	uploadSuccess : function (file, server_data, receivedResponse) {
		//alert("The file " + file.name + " has been delivered to the server. The server responded with: " + server_data);
		//$('SWFUpload_Console').insert(server_data);
		if (!geoUHLogo.doingSomething('upload')) {
			//not doing anything except uploading, so finished with upload
			geoUHLogo.doNothing();
		}
		if (server_data.isJSON()) {
			var data = server_data.evalJSON(true);
			geoUHLogo.processResponse(data);
		} else {
			//throw an error, something went wrong with processing...
			geoUHLogo.throwServerError(server_data);
			
		}
		//$('imagesCapturedBox').update(server_data);
		return true;
	},
	
	uploadError : function () {
		//throw a generic error
		if (!geoUHLogo.doingSomething('upload')) {
			//not doing anything except uploading, so finished with upload
			geoUHLogo.doNothing();
		}
		geoUHLogo.throwServerError('uploadError Triggered');
	},

	uploadStart : function () {
		//triggered once upload has started, this is NOT the click event!
		
		//alert ('starting upload!');
		$('adlogouploadButton').disable();
		//turn on the animation thingy and make the scroll thingy big
		$('adlogouploadBar').setStyle({width: '100%'});
		$('adlogonewImageProgress').update (geoUHLogo.text.uploadStarting);
		geoUHLogo.startAnimationBar();
		geoUHLogo.setButtonDisabled(true);
	},

	diagComplete : function (numSelected, numQueued, totalQueued) {
		if ((numSelected > 0 && numQueued > 0) || geoUHLogo.currentEditPosition) {
			//either there are images selected, or this is an edit, either case
			//turn on the upload (or apply) button.
			$('adlogouploadButton').enable();
			//bring attention to upload button
			new Effect.Pulsate('adlogouploadButton', {pulses: 3, duration: .8});
		} else {
			//they canceled selection of image, turn off the upload button.
			$('adlogouploadButton').disable();
		}
	},
	
	clearQueue : function () {
		var stats = null;
		try {
			//put this in try block, as it will fail if the movie is currently
			//not visible
			stats = geoUHLogo.swfu.getStats();
		} catch (ex) {
			stats = false;
		}
		
		while (stats && stats.files_queued > 0) {
			try {
				//put this in try block, as it can fail sometimes if button
				//is hidden
				geoUHLogo.swfu.cancelUpload(undefined, false);
				stats = geoUHLogo.swfu.getStats();
			} catch (ex) {
				stats = false;
			}
		}
	},
	
	diagStart : function () {
		//clear the queue, we only have 1 in the queue at once
		geoUHLogo.clearQueue();
	},

	fileQueued : function (file) {
		geoUHLogo.currentFileId = file.id;
		$('adlogonewImageProgress').update(geoUHLogo.text.progressFileQueued1+file.name+geoUHLogo.text.progressFileQueued2);
	},
	
	fileQueuedError : function (file, error_code, message) {
		if (geoUHLogo.debug) {
			alert ('File que error, message: '+message);
		}
		if (error_code == SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT) {
			//file too big
			
			//make a pretty looking max file size
			var maxSize = geoUHLogo.file_size_limit.replace(' B', '')*1;
			var ext = 'Bytes';
			if (maxSize > 1024) {
				//kb
				maxSize = maxSize/1024;
				ext = 'KB';
				if (maxSize > 1024) {
					//mb
					maxSize = maxSize/1024;
					ext = 'MB';
					if (maxSize > 1024) {
						//gb
						maxSize = maxSize/1024;
						ext = 'GB';
					}
				}
			}
			maxSize = maxSize.toFixed(2).toString();
			while (/[0-9]{4}(,|\.|$)/.test(maxSize)) {
				maxSize = maxSize.replace(/^([^,]+)([0-9]{3})(,|\.|$)/, '$1,$2$3');
			}
			maxSize += ext;
			
			geoUHLogo.addError(geoUHLogo.text.fileTooBig + maxSize);
		}
	},

	uploadProgress : function (file, complete, total) {
		//alert('complete: '+complete+' of '+total+' bytes done');//, percent is '+percent+'%');
		var percent = 0;
		if (complete && total) {
			//only figure out percent if complete and total aren't 0 since that would
			//be an error
			percent = (complete * 1)/(total * 1);
		}
		percent = (percent * 100);
		$('adlogouploadBar').setStyle({width: percent+'%'});
		if (percent == 100) {
			//at 100 percent...
			$('adlogonewImageProgress').update (geoUHLogo.text.processingImage);
		} else {
			$('adlogonewImageProgress').update (''+complete+geoUHLogo.text.progressOf+total+geoUHLogo.text.progressBytesUploaded);
		}
	},
	
	uploadButtonClick : function (junk) {
		//don't do upload if still in middle of something
		if (geoUHLogo.doingSomething('')) return;
		
		geoUHLogo.doingSomething('upload');
		if (geoUHLogo.currentEditPosition) {
			//editing, not doing something new
			var stats = null;
			try {
				stats = geoUHLogo.swfu.getStats();
			} catch (ex) {
				stats = false;
			}
			if (stats && stats.files_queued > 0) {
				//there is an upload to process...
				//treat it like a new image upload
				try {
					geoUHLogo.swfu.addFileParam (geoUHLogo.currentFileId, 'imageTitle', $('adlogofileTitle').getValue());
					geoUHLogo.swfu.addFileParam (geoUHLogo.currentFileId, 'editImage', 1);
					geoUHLogo.swfu.addFileParam (geoUHLogo.currentFileId, 'editImageSlot', geoUHLogo.currentEditPosition);
					
					geoUHLogo.swfu.startUpload();
				} catch (ex) {
					if (geoUHLogo.debug) {
						alert ('DEBUG caught exception 742: '+ex);
					}
				}
			} else {
				//send an ajax call w/o the image data
				
				var params = {
					imageTitle : $('adlogofileTitle').getValue(),
					editImage : 1,
					editImageSlot : geoUHLogo.currentEditPosition,
					userId : geoUHLogo.userId,
					adminId : geoUHLogo.adminId
				};
				
				new Ajax.Request(geoUHLogo.ajaxUrl+"?controller=UploadAdlogo&action=uploadImage", {
					method: 'post',
					parameters: params,
					onSuccess: geoUHLogo.editSuccess
				});
				//manually make the stuff happen for when uploading image
				geoUHLogo.uploadStart();
			}
			
			//hide cancel button
			$('adlogocancelUploadButton').hide();
		} else {
			if ($('adlogouploadButton').disabled || !geoUHLogo.currentFileId) {
				//it's disabled, don't do a thing
				geoUHLogo.doNothing();
				return;
			}
			try {
				geoUHLogo.swfu.addFileParam (geoUHLogo.currentFileId, 'imageTitle', $('adlogofileTitle').getValue());
				geoUHLogo.swfu.startUpload();
			} catch (ex) {
				if (geoUHLogo.debug) {
					alert ('DEBUG caught exception 777: '+ex);
				}
			}
		}
	},
	
	uploadFormSubmit : function(action) {
		//see if there are pending photos needing uploading or not
		if (!$('adlogouploadButton').disabled) {
			//upload button active, must be pending something
			var text = geoUHLogo.text.uploadNotFinished;
			if (geoUHLogo.currentEditPosition) {
				text = geoUHLogo.text.editNotFinished;
			}
			if (text == '') {
				//text empty, no confirmation to show
				return;
			}
			if (!confirm(text)) {
				action.stop();
			}
		}
	},
	
	startAnimationBar : function () {
		var animation = $('adlogobarAnimation');
		if (!animation) {
			//could not find animation image, can't do anything
			return;
		}
		animation.show();
	},
	stopAnimationBar : function () {
		var animation = $('adlogobarAnimation');
		if (!animation) {
			//could not find animation image, can't do anything
			return;
		}
		animation.hide();
	},
	
	currentEditPosition : 0,
	//triggered from button click
	editImage : function (position) {
		if (geoUHLogo.doingSomething('')) return;
		
		if (!$('adlogoSlot_'+position)) {
			return;
		}
		geoUHLogo.cancelEdit(true);
		
		//hide it so it doesn't do moving effect, that tends to be confusing.
		geoUHLogo.hideImageUploadBox();
		
		//change new image box into edit image box
		geoUHLogo.currentEditPosition = position;
		
		geoUHLogo.changeToEdit();
		
		//now move it there!  remember we hid it already above so this will
		//make it just appear in place.
		geoUHLogo.moveTo(position);
	},
	
	changeToEdit : function () {
		var newImageBox = $('adlogonewImageBox');
		if (!newImageBox) {
			return;
		}
		var position = geoUHLogo.currentEditPosition;
		
		//first, change title
		$('adlogoUploadTitle').update(geoUHLogo.text.editImagePosition+position);
		
		//now, get the contents of that image thingy
		$('adlogoPreview').update($('adlogoPreview_'+position).down())
			.removeClassName('emptyPreview');
		
		//set the title
		$('adfileTitle').value = $('adlogoTitle_'+position).value;
		
		//set the progress bar text
		$('adlogonewImageProgress').update(geoUHLogo.text.keepSameImage);
		
		//update upload button
		$('adlogouploadButton').enable();
		$('adlogouploadButton').value = geoUHLogo.text.apply;
		
		//enable the cancel button
		$('adlogocancelUploadButton').show();
		
		//hide the edit and delete buttons underneith
		geoUHLogo.hideEditDeleteButtons(position);
		
		//kill anything in the queue
		geoUHLogo.clearQueue();
	},
	
	cancelEditClick : function (action) {
		if (geoUHLogo.doingSomething('')) {
			//action.stop();
			return;
		}
		geoUHLogo.cancelEdit(false);
	},
	
	cancelEdit : function (skipMove) {
		var newImageBox = $('adlogonewImageBox');
		if (!newImageBox) {
			return;
		}
		if (!geoUHLogo.currentEditPosition) {
			//not editing!
			return;
		}
		var position = geoUHLogo.currentEditPosition;
		
		//first, change title
		$('adlogoUploadTitle').update(geoUHLogo.text.newImageUpload);
		
		//now, get the contents of that image thingy
		$('adlogoPreview_'+position).update($('adlogoPreview').down());
		
		$('adlogoPreview').update(geoUHLogo.text.newImage)
			.addClassName('emptyPreview');
		
		//set the title
		$('adlogofileTitle').value = "";
		
		//set the progress bar text
		$('adlogonewImageProgress').update(geoUHLogo.text.noFileSelected);
		
		//update upload button
		$('adlogouploadButton').disable();
		$('adlogouploadButton').value = geoUHLogo.text.upload;
		
		//hide the cancel button
		$('adlogocancelUploadButton').hide();
		
		//show the edit and delete buttons underneith
		geoUHLogo.showEditDeleteButtons(position);
		
		//kill anything in the queue
		geoUHLogo.clearQueue();
		//reset the edit ID
		geoUHLogo.currentEditPosition = 0;
		if (skipMove == true) {
			
		} else {
			//move back to position
			
			//hide it first, don't do the moving animation as that tends to
			//be confusing.
			geoUHLogo.hideImageUploadBox();
			if (geoUHLogo.currentUploadSpot) {
				//we've go somewhere to go!  Places to be!  People to meet!
				geoUHLogo.moveTo(geoUHLogo.currentUploadSpot);
			}
		}
	},
	
	showEditDeleteButtons : function (position) {
		var editButton = $('adlogoeditImage_'+position);
		var deleteButton = $('adlogodeleteImage_'+position);
		if (editButton.up() && editButton.up().hasClassName('imageBoxTitleButtons')) {
			editButton = editButton.up();
		}
		if (deleteButton.up() && deleteButton.up().hasClassName('imageBoxTitleButtons')) {
			deleteButton = deleteButton.up();
		}
		editButton.show();
		deleteButton.show();
	},
	
	hideEditDeleteButtons : function (position) {
		var editButton = $('adlogoeditImage_'+position);
		var deleteButton = $('adlogodeleteImage_'+position);
		if (editButton.up() && editButton.up().hasClassName('imageBoxTitleButtons')) {
			editButton = editButton.up();
		}
		if (deleteButton.up() && deleteButton.up().hasClassName('imageBoxTitleButtons')) {
			deleteButton = deleteButton.up();
		}
		editButton.hide();
		deleteButton.hide();
	},
	
	//resets all the stuff to default pre-upload new image status
	resetUploadBox : function () {
		if (geoUHLogo.currentEditPosition) {
			//cancel the edit
			geoUHLogo.cancelEdit(true);
			geoUHLogo.hideImageUploadBox();
		}
		$('adlogonewImageProgress').update(geoUHLogo.text.noFileSelected);
		$('adlogouploadBar').setStyle({width: '1%'});
		$('adlogofileTitle').value = '';
		$('adlogouploadButton').disable();
		//kill the animation bar
		geoUHLogo.stopAnimationBar();
		
		geoUHLogo.clearQueue();
		geoUHLogo.setButtonDisabled(false);
	},
	
	deleteImage : function (deleteImageSlot) {
		if (geoUHLogo.doingSomething('')) return;
		
		//TODO: add a confirmation for delete
		
		//send an ajax call to delete the image.
		geoUHLogo.doingSomething('deleteImage');
		var params = {
			imageSlot : deleteImageSlot,
			userId : geoUHLogo.userId,
			adminId : geoUHLogo.adminId
		}
		
		new Ajax.Request(geoUHLogo.ajaxUrl+"?controller=UploadAdlogo&action=deleteImage", {
			method: 'post',
			parameters: params,
			onSuccess: geoUHLogo.deleteSuccess
		});
	},
	addError: function (errorMsg) {
		var msg = geoUHLogo.text.uploadError+errorMsg;
		geoUtil.addError(msg);
	},
	
	throwServerError : function (debugInfo, skipReset) {
		var msg = geoUHLogo.text.generalServerError;
		//rest of text does not need to be changeble, it is only for debug
		if (geoUHLogo.debug) {
			//also insert debug
			msg += '<br /><br />DEBUG: ';
			if (debugInfo.responseText) {
				msg += 'responseText: <pre>'+debugInfo.responseText+'</pre>';
			} else if (debugInfo) {
				msg += debugInfo;
			} else {
				msg += 'no debug info provided.?.';
			}
		}
		
		geoUHLogo.addError(msg);
		if (skipReset == true) {
			//do not reset the upload box
			
		} else {
			//reset the upload box
			geoUHLogo.resetUploadBox();
		}
	},
	
	//This needs to be the SAME as what param is set to in swfu init call!
	_currentButtonDisabled : true,
	
	//use this instead of method of same name in swfu object, to avoid script
	//from stop running when button is already that way...
	setButtonDisabled : function (setting) {
		if (setting != geoUHLogo._currentButtonDisabled && geoUHLogo.swfu != null) {
			try {
				geoUHLogo.swfu.setButtonDisabled(setting);
				geoUHLogo._currentButtonDisabled = setting;
			} catch (ex) {
				if (geoUHLogo.debug) {
					alert ('DEBUG caught exception 1082: '+ex);
				}
			}
		}
	}
};
