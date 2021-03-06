// 7.2beta3-95-g23b3a2d

//This does the fancy stuff for image uploads

//use geoUHExtra as namespace (geo upload handler)
var geoUHExtra = {
	
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
		if (!geoUHExtra.doingSomething('initSwfu')) {
			//init swfu over
			geoUHExtra.doNothing();
		}
		if (geoUHExtra._initRun) {
			return;
		}
		geoUHExtra._initRun = true;
		
		var newImageBox = $('adnewImageBox');
		if (!newImageBox) {
			//new image box not on page
			return;
		}
		
		if ($('adlegacyUploadContainer').visible()) {
			//if we had un-hidden the old form because page was taking too long,
			//hide it again
			$('adlegacyUploadContainer').hide();
		}
		//hide the loading box if it's there...
		$('adloadingBox').hide();
		
		//hide the legacy
		if ($('adlegacyUploadBox')) {
			$('adlegacyUploadBox').hide();
		}
		
		//show the normal
		$('adstandardUploadBox').show();
		//hide it
		geoUHExtra.hideImageUploadBox(true);
		
		if (geoUHExtra.currentUploadSpot) {
			//now figure out where it goes..
			geoUHExtra.moveTo(geoUHExtra.currentUploadSpot);
		} else {
			//no spots open!  oh well, it's already hidden...
		}
		
		//make draggable
		geoUHExtra.initSortableSlots();
		
		//what out for clicks on update button
		$('aduploadButton').observe('click', geoUHExtra.uploadButtonClick);
		//watch out for cancel clicks too
		$('adcancelUploadButton').observe('click',geoUHExtra.cancelEditClick);
		
		//show the instructions
		if ($('adimage_upload_instructions')) {
			$('adimage_upload_instructions').show();
		}
		if ($('adimage_upload_instructions_legacy')) {
			//hide legacy button
			$('adimage_upload_instructions_legacy').hide();
		}
		
		//Find the "next" form, and add a listener to it.
		var formElem = $('adimageUploadNextFormElement');
		if (formElem) {
			formElem = formElem.up('form');
			if (formElem && formElem.match('form')) {
				//this is the form element, add a listener
				formElem.observe('submit', geoUHExtra.uploadFormSubmit);
			}
		}
	},
	
	doingSomething : function (notThis) {
		return (geoUHExtra._inTransition == notThis)? false: true;
	},
	
	doSomething : function (what) {
		//sanity check
		if (geoUHExtra.doingSomething('')) {
			//already doing something, stop that!
			if (geoUHExtra.debug) alert('Attempting to do something '+what+' when already '+geoUHExtra._inTransition);
			return false;
		}
		geoUHExtra._inTransition = what;
		return true;
	},
	doNothing : function () {
		geoUHExtra._inTransition = '';
	},
	//initializes the swfu movie.  Will be called multiple times in a page load.
	initSwfu : function () {
		if (geoUHExtra.doingSomething('')) return;
		
		if (geoUHExtra.swfu) {
			//why is this being called?
			if (geoUHExtra.debug) {
				alert('called initSwfu but swfu is already inited!');
			}
			return;
		}
		geoUHExtra.doSomething('initSwfu');
		geoUHExtra.swfu = new SWFUpload({
			upload_url : geoUHExtra.ajaxUrl+"?controller=UploadAdImage&action=uploadImage", 
			file_size_limit : geoUHExtra.file_size_limit,
			file_queue_limit : 1,
	
			// Let the script know the classified session ID:
			post_params: geoUHExtra.startingPostParams,
	
			// Button settings
			button_placeholder_id: 'adspanButtonPlaceHolder',
			button_image_url : geoUHExtra.flashButtonImage,
			/*
			button_text : "<span class='selectButton'><b>Select File</b></span>",
			button_text_style : ".selectButton {ldelim} color: #0000FF; font-size: 10px }",
			*/
			button_width : geoUHExtra.movieWidth,
			button_height : geoUHExtra.movieHeight,
			button_cursor : SWFUpload.CURSOR.HAND,
			button_action : SWFUpload.BUTTON_ACTION.SELECT_FILES,
			button_disabled : false,//start out disabled
			
			// Event Handler Settings - these functions in handlers.js
			swfupload_loaded_handler : geoUHExtra.initUploads,
			upload_success_handler : geoUHExtra.uploadSuccess,
			upload_error_handler : geoUHExtra.uploadError,
			upload_start_handler : geoUHExtra.uploadStart,
			upload_progress_handler : geoUHExtra.uploadProgress,
			file_dialog_start_handler : geoUHExtra.diagStart,
			file_dialog_complete_handler : geoUHExtra.diagComplete,
			file_queued_handler : geoUHExtra.fileQueued,
			file_queue_error_handler : geoUHExtra.fileQueuedError,
			
			
			// Flash Settings
			flash_url : geoUHExtra.flashUrl,	// Relative to main file
			
			// Debug Setting, use same as geoUHExtra.debug
			debug: geoUHExtra.debug
		});
		
		//reset vars
		geoUHExtra._currentButtonDisabled = true;
		geoUHExtra.currentFileId = 0;
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
			geoUHExtra.initSortableSlots();
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
		geoUHExtra.initSortableSlots();
	},

	dragableObserver : {
		_doFade: false,
		
		_plopImage : function (draggable) {
			//move the "move image here" image to the right place, and make it visible.
			var plopImage = $('adplopDropImageHere');
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
			if (geoUHExtra.doingSomething('')) {
				//what the... already doing something
				event.stop();
				return;
			}
			
			
			//simple hack to make the box centered vertically on the mouse,
			//so people don't get as confused...
			draggable.options.snap = function (x,y, draggable) {
				return [x, y-75];
			};
			geoUHExtra.tempHideUpload();
			//start out with the plopHere box visible instead of waiting for change
			geoUHExtra.dragableObserver._plopImage(draggable);
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
			if (newOrder == geoUHExtra.dragableObserver.currentOrder) {
				//did not change order, nothing to do
				return;
			}
			geoUHExtra.dragableObserver.currentOrder = newOrder;
			
			//move the "move image here" image to the right place, and make it visible.
			var plopImage = $('adplopDropImageHere');
			if (!plopImage) {
				//nothing can be done, can't find the plog image!
				return;
			}
			
			geoUHExtra.dragableObserver._plopImage(draggable);
		},
		
		onEnd: function (eventName, draggable, event) {
			if (!draggable.element.hasClassName('imageBox')) {
				//ignore this one
				return;
			}
			//move it to where it goes
			
			geoUHExtra.tempShowUpload();
			var plopImage = $('adplopDropImageHere');
			if (plopImage) plopImage.hide();
		},
		_alreadyInited: false,
		
		init: function() {
			if (geoUHExtra.dragableObserver._alreadyInited) {
				//already initialized
				return;
			}
			geoUHExtra.dragableObserver._alreadyInited = true;
			Draggables.addObserver(geoUHExtra.dragableObserver);
		}
	},

	initSortableSlots : function () {
		//NOTE: If element is already sortable, it destroys it first automatically
		//for us!  So we can call geoUHExtra whenever we need the sortable items to be
		//re-done
		
		//Sortable.destroy('imagesCapturedBox');
		
		Sortable.create('imagesCapturedBox', {
			tag: 'div',
			only: 'imageBox',
			overlap: 'horizontal',
			treeTag: 'div',
			constraint: '',
			handle: 'imageBoxTitleHandle',
			scroll : geoUHExtra.scrollSetting,
			//hoverclass: 'dragHover',
			onUpdate: function () {
				new Ajax.Request(geoUHExtra.ajaxUrl+"?controller=UploadAdImage&action=sortImages", {
					method: 'post',
					parameters: {
						'imageSlots': Sortable.serialize('imagesCapturedBox'),
						'userId' : geoUHExtra.userId,
						'adminId' : geoUHExtra.adminId
					},
					onSuccess: geoUHExtra.sortableResponseSuccess
				});
				//hide new image box to not show till it's done
				geoUHExtra.hideImageUploadBox();
			}
		});
		
		geoUHExtra.dragableObserver.init();
		
		//re-init the image clicks
		if (typeof gjUtil != 'undefined') {
			gjUtil.lightbox.initClick();
		}
	},
	
	moveImageUploaderBox : function (data) {
		var newImageBox = $('adnewImageBox');
		
		if (geoUHExtra.currentEditPosition) {
			//used to be edit, but we're moving it to new position, so it's not
			//edit no more!
			geoUHExtra.cancelEdit(true);
		}
		if (!data.error && (!data.nextUploadSlot || data.nextUploadSlot > data.maxSlots)) {
			//hide the whole thing
			if (geoUHExtra.imageBoxVisible) {
				geoUHExtra.hideImageUploadBox();
			}
			geoUHExtra.currentUploadSpot = 0;
		} else {
			//make sure it is not hidden
			if (data.nextUploadSlot) {
				geoUHExtra.currentUploadSpot = data.nextUploadSlot;
			}
			geoUHExtra.moveTo(geoUHExtra.currentUploadSpot);
		}
		//set the new upload slot
		geoUHExtra.startingPostParams.uploadSlot = geoUHExtra.currentUploadSpot;
		if (geoUHExtra.swfu != null) {
			try {
				geoUHExtra.swfu.addPostParam('uploadSlot', geoUHExtra.currentUploadSpot);
			} catch (ex) {
				if (geoUHExtra.debug) {
					alert ('DEBUG caught exception 392: '+ex);
				}
			}
		}
	},
	//can't use .hide() on image uploader, that will break opera since button is
	//in there.  Instead we destroy it and re-create it later.
	hideImageUploadBox : function (force) {
		var newImageBox = $('adnewImageBox');
		if (!newImageBox || (force != true && !geoUHExtra.imageBoxVisible)) {
			return false;
		}
		
		//cancel any animations
		if (geoUHExtra.fadingInAnimation != null && typeof geoUHExtra.fadingInAnimation.cancel != 'undefined') {
			geoUHExtra.fadingInAnimation.cancel();
			geoUHExtra.fadingInAnimation = null;
		}
		
		//destroy SWFU object
		var destroyResult = null;
		if (geoUHExtra.swfu == null) {
			//already destroyed, just hide the surrounding box!
			destroyResult = true;
		} else {
			try {
				destroyResult = geoUHExtra.swfu.destroy();
			} catch (ex) {
				if (geoUHExtra.debug) {
					alert('destroy failed, exception caught:'+ex);
				}
				destroyResult = false;
			}
		}
		if (!destroyResult) {
			//destroy left swfu in an inconsistent state, tell user and re-load page
			alert(geoUHExtra.text.sessionError+' Internal Page Error, page needs to re-load. (null pointer to cheese)');
			window.location.reload();
			return false;
		}
		//just to be sure, clear out the container with the starting button
		$('adstartingButtonContainer').update('').hide();
		//reset parameters
		//geoUHExtra.swfu should have been destroyed above, so set the var to null
		geoUHExtra.swfu = null;
		if (!geoUHExtra.doingSomething('initSwfu')) {
			//we are hiding before init is finished loading, since we just destroyed it,
			//chances are it isn't initializing any more...
			//alert('that was close!');
			geoUHExtra.doNothing();
		}
		geoUHExtra._currentButtonDisabled = true;
		if (geoUHExtra.currentEditPosition) {
			//reset the box
			geoUHExtra.resetUploadBox();
		}
		//re-insert the span so it has something to attach to when fading in the box.
		$('adselectFileButtonBox').update('<span id="adspanButtonPlaceHolder"></span>');
		//now that button is gone, hide the box
		newImageBox.hide();
		
		geoUHExtra.imageBoxVisible = false;
		return true;
	},
	
	destroy : function () {
		if (geoUHExtra.swfu == null) {
			//already destroyed, just hide the surrounding box!
			destroyResult = true;
		} else {
			try {
				destroyResult = geoUHExtra.swfu.destroy();
			} catch (ex) {
				if (geoUHExtra.debug) {
					alert('destroy failed, exception caught:'+ex);
				}
				destroyResult = false;
			}
		}
		geoUHExtra.swfu = null;
		geoUHExtra.doNothing();
		geoUHExtra._initRun = false;
	},
	
	//if this is false, it will not add an effect when fading in, it will just appear.
	fadeInImageBoxParams : {duration: .8, from: 0.0, to: 1.0},
	
	fadingInAnimation : null,
	
	showImageUploadBox : function () {
		var newImageBox = $('adnewImageBox');
		if (!newImageBox || geoUHExtra.imageBoxVisible) {
			return false;
		}
		
		//if this is the first time, clear all the stuff that was not cleared before...
		
		//we CAN fade it in if we start from something not 0 as starting point
		if (geoUHExtra.fadeInImageBoxParams) {
			if (!geoUHExtra.doingSomething('')) {
				//not doing anything except uploading, so finished with upload
				geoUHExtra.doSomething('appear');
			}
			if (typeof geoUHExtra.fadeInImageBoxParams.afterFinish != 'function') {
				//init what to do once the appear is finished.
				geoUHExtra.fadeInImageBoxParams.afterFinish = function () {
					//once it's done fading in, init the movie param
					if (!geoUHExtra.doingSomething('appear')) {
						//not doing anything except appearing, so finished with appear
						geoUHExtra.doNothing();
					}
					if (!geoUHExtra.swfu) {
						//this must be done once the surrounding box is visible.
						geoUHExtra.initSwfu();
					}
					if ($('adnewImageBox').visible()) {
						//just in case there is some race condition, reset as
						//visible if it's visible.
						geoUHExtra.imageBoxVisible = true;
					}
					//I wonder if we can do this?
					geoUHExtra.fadingInAnimation = null;
				};
			}
			geoUHExtra.fadingInAnimation = newImageBox.appear(geoUHExtra.fadeInImageBoxParams);
			//new Effect.Appear (newImageBox, geoUHExtra.fadeInImageBoxParams);
		} else {
			newImageBox.show();
			if (!geoUHExtra.swfu) {
				geoUHExtra.initSwfu();
			}
		}
		geoUHExtra.imageBoxVisible = true;
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
		if (geoUHExtra.imageBoxVisible) {
			geoUHExtra._doTempShow = true;
			if (geoUHExtra.currentEditPosition) {
				geoUHExtra.cancelEdit(true);
				if (geoUHExtra.currentUploadSpot) {
					geoUHExtra.moveTo(geoUHExtra.currentUploadSpot);
				} else {
					//there are no open upload slots, so don't fade back in
					geoUHExtra._doTempShow = false;
				}
			}
			geoUHExtra.hideImageUploadBox();
		} else {
			//let init know to delay the display, for times that this is called
			//before the uploader is even initialized...  In such cases, will want
			//to delay initialization
			geoUHExtra._delayInit=true; 
		}
	},
	/**
	 * Shows the upload box after it has been hidden using tempHideUpload, but
	 * only if there is an empty spot to show it in.
	 */
	tempShowUpload : function () {
		//Fade it back in
		if (geoUHExtra._doTempShow && !geoUHExtra.imageBoxVisible) {
			geoUHExtra.cancelEdit(true);
			geoUHExtra._doTempShow = false;
			geoUHExtra.showImageUploadBox();
		}
	},
	
	/**
	 * Fixes the position of the image upload box
	 */
	fixUploadPosition : function () {
		if (geoUHExtra.currentEditPosition) {
			geoUHExtra.moveTo(geoUHExtra.currentEditPosition);
		} else if (geoUHExtra.currentUploadSpot) {
			geoUHExtra.moveTo(geoUHExtra.currentUploadSpot);
		}
	},
	
	/**
	 * Moves new/edit image box to the given image slot, uses a move animation if
	 * the box is already visible, or just moves it then uses a fade in animation
	 * if the box starts out hidden.
	 */
	moveTo : function (position) {
		var newImageBox = $('adnewImageBox');
		
		var moveTo = $('adimageSlot_'+position);
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
		
		if (!geoUHExtra.imageBoxVisible) {
			//just move it to where it goes and fade it in
			newImageBox.setStyle({left: moveX+'px', top: moveY+'px'});
			geoUHExtra.showImageUploadBox();
		} else {
			//move it!  Wheeeeeeeeeeee!  Speedything goes in, speedything comes out!
			
			new Effect.Move(newImageBox, {x: moveX, y: moveY, mode: 'absolute'});
		}
	},
	
	currentFileId : 0,
	
	editSuccess : function (transport) {
		if (!geoUHExtra.doingSomething('upload')) {
			//not doing anything except uploading, so finished with upload
			geoUHExtra.doNothing();
		}
		var response = transport.responseJSON;
		if (response) {
			geoUHExtra.processResponse(response);
		} else {
			//some type of server error
			geoUHExtra.throwServerError(transport);
		}
	},
	
	deleteSuccess : function (transport) {
		if (!geoUHExtra.doingSomething('deleteImage')) {
			geoUHExtra.doNothing();
		}
		var response = transport.responseJSON;
		if (response) {
			geoUHExtra.processResponse(response);
		} else {
			//some type of server error
			geoUHExtra.throwServerError(transport);
		}
	},
	
	sortableResponseSuccess : function (transport) {
		var response = transport.responseJSON;
		if (response) {
			geoUHExtra.processResponse(response);
		} else {
			//some type of server error
			geoUHExtra.throwServerError(transport);
		}
	},
	
	processResponse : function (data) {
		//reset stuff in image box
		geoUHExtra.resetUploadBox();
		
		var msg = '';
		
		if (data.imagesDisplay && data.uploadSlot && $('adimageSlot_'+data.uploadSlot)) {
			if (data.imagesDisplay == 'get') {
				//did not pass back what is to be displayed, must go get the box
				var params = {
					uploadSlot : data.uploadSlot,
					editImage : data.editImage,
					editImageSlot : geoUHExtra.currentEditPosition,
					userId : geoUHExtra.userId,
					adminId : geoUHExtra.adminId
				};
				new Ajax.Request(geoUHExtra.ajaxUrl+"?controller=UploadAdImage&action=imageSlotContents", {
					method: 'post',
					parameters: params,
					onSuccess: geoUHExtra.getSlotResponse
				});
			} else {
				geoUHExtra.updateSlotContents(data);
			}
		} else if (data.uploadImageBox) {
			//update all the slots
			$('adimagesCapturedBox').update(data.uploadImageBox);
			//reset draggables
			geoUHExtra.initSortableSlots();
		}
		
		if (data.errorSession) {
			//error requiring to re-load the page
			alert(geoUHExtra.text.sessionError+data.errorSession);
			window.location.reload();
		}
		
		if (data.error) {
			geoUHExtra.addError(data.error);
		}
		
		if (data.msg) {
			msg += data.msg;
		}
		
		if (msg && !data.error) {
			geoUtil.addMessage(msg);
		}
		
		//move it where it goes
		geoUHExtra.moveImageUploaderBox(data);
	},
	getSlotResponse : function (transport) {
		var response = transport.responseJSON;
		if (response) {
			geoUHExtra.updateSlotContents(response);
		} else {
			//some type of server error
			geoUHExtra.throwServerError(transport);
		}
	},
	updateSlotContents : function (data) {
		if (typeof data.uploadSlot == 'undefined' || !data.uploadSlot) {
			//nothing to do, some kind of error
			geoUHExtra.throwServerError('Invalid data, data: '+data);
			return;
		}
		//shove it in there
		
		//there is an image box to add in there
		var newBox = new Element('div',{'class': 'innerImageBox'}).hide();
		
		//shove the contents into our new div
		newBox.insert(data.imagesDisplay);
		
		//now shove the new box into the right spot
		var rightSpot = $('adimageSlot_'+data.uploadSlot);
		rightSpot.update(newBox);
		
		//make it sortable
		geoUHExtra.makeSortable(rightSpot);
		
		//show it
		//new Effect.Appear(newBox, geoUHExtra.defaultParams); //appear goes too slow
		newBox.show();
	},
	
	uploadSuccess : function (file, server_data, receivedResponse) {
		//alert("The file " + file.name + " has been delivered to the server. The server responded with: " + server_data);
		//$('SWFUpload_Console').insert(server_data);
		if (!geoUHExtra.doingSomething('upload')) {
			//not doing anything except uploading, so finished with upload
			geoUHExtra.doNothing();
		}
		if (server_data.isJSON()) {
			var data = server_data.evalJSON(true);
			geoUHExtra.processResponse(data);
		} else {
			//throw an error, something went wrong with processing...
			geoUHExtra.throwServerError(server_data);
			
		}
		//$('imagesCapturedBox').update(server_data);
		return true;
	},
	
	uploadError : function () {
		//throw a generic error
		if (!geoUHExtra.doingSomething('upload')) {
			//not doing anything except uploading, so finished with upload
			geoUHExtra.doNothing();
		}
		geoUHExtra.throwServerError('uploadError Triggered');
	},

	uploadStart : function () {
		//triggered once upload has started, this is NOT the click event!
		
		//alert ('starting upload!');
		$('aduploadButton').disable();
		//turn on the animation thingy and make the scroll thingy big
		$('aduploadBar').setStyle({width: '100%'});
		$('adnewImageProgress').update (geoUHExtra.text.uploadStarting);
		geoUHExtra.startAnimationBar();
		geoUHExtra.setButtonDisabled(true);
	},

	diagComplete : function (numSelected, numQueued, totalQueued) {
		if ((numSelected > 0 && numQueued > 0) || geoUHExtra.currentEditPosition) {
			//either there are images selected, or this is an edit, either case
			//turn on the upload (or apply) button.
			$('aduploadButton').enable();
			//bring attention to upload button
			new Effect.Pulsate('uploadButton', {pulses: 3, duration: .8});
		} else {
			//they canceled selection of image, turn off the upload button.
			$('aduploadButton').disable();
		}
	},
	
	clearQueue : function () {
		var stats = null;
		try {
			//put this in try block, as it will fail if the movie is currently
			//not visible
			stats = geoUHExtra.swfu.getStats();
		} catch (ex) {
			stats = false;
		}
		
		while (stats && stats.files_queued > 0) {
			try {
				//put this in try block, as it can fail sometimes if button
				//is hidden
				geoUHExtra.swfu.cancelUpload(undefined, false);
				stats = geoUHExtra.swfu.getStats();
			} catch (ex) {
				stats = false;
			}
		}
	},
	
	diagStart : function () {
		//clear the queue, we only have 1 in the queue at once
		geoUHExtra.clearQueue();
	},

	fileQueued : function (file) {
		geoUHExtra.currentFileId = file.id;
		$('adnewImageProgress').update(geoUHExtra.text.progressFileQueued1+file.name+geoUHExtra.text.progressFileQueued2);
	},
	
	fileQueuedError : function (file, error_code, message) {
		if (geoUHExtra.debug) {
			alert ('File que error, message: '+message);
		}
		if (error_code == SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT) {
			//file too big
			
			//make a pretty looking max file size
			var maxSize = geoUHExtra.file_size_limit.replace(' B', '')*1;
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
			
			geoUHExtra.addError(geoUHExtra.text.fileTooBig + maxSize);
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
		$('aduploadBar').setStyle({width: percent+'%'});
		if (percent == 100) {
			//at 100 percent...
			$('adnewImageProgress').update (geoUHExtra.text.processingImage);
		} else {
			$('adnewImageProgress').update (''+complete+geoUHExtra.text.progressOf+total+geoUHExtra.text.progressBytesUploaded);
		}
	},
	
	uploadButtonClick : function (junk) {
		//don't do upload if still in middle of something
		if (geoUHExtra.doingSomething('')) return;
		
		geoUHExtra.doingSomething('upload');
		if (geoUHExtra.currentEditPosition) {
			//editing, not doing something new
			var stats = null;
			try {
				stats = geoUHExtra.swfu.getStats();
			} catch (ex) {
				stats = false;
			}
			if (stats && stats.files_queued > 0) {
				//there is an upload to process...
				//treat it like a new image upload
				try {
					geoUHExtra.swfu.addFileParam (geoUHExtra.currentFileId, 'imageTitle', $('adfileTitle').getValue());
					geoUHExtra.swfu.addFileParam (geoUHExtra.currentFileId, 'editImage', 1);
					geoUHExtra.swfu.addFileParam (geoUHExtra.currentFileId, 'editImageSlot', geoUHExtra.currentEditPosition);
					
					geoUHExtra.swfu.startUpload();
				} catch (ex) {
					if (geoUHExtra.debug) {
						alert ('DEBUG caught exception 742: '+ex);
					}
				}
			} else {
				//send an ajax call w/o the image data
				
				var params = {
					imageTitle : $('adfileTitle').getValue(),
					editImage : 1,
					editImageSlot : geoUHExtra.currentEditPosition,
					userId : geoUHExtra.userId,
					adminId : geoUHExtra.adminId
				};
				
				new Ajax.Request(geoUHExtra.ajaxUrl+"?controller=UploadAdImage&action=uploadImage", {
					method: 'post',
					parameters: params,
					onSuccess: geoUHExtra.editSuccess
				});
				//manually make the stuff happen for when uploading image
				geoUHExtra.uploadStart();
			}
			
			//hide cancel button
			$('adcancelUploadButton').hide();
		} else {
			if ($('aduploadButton').disabled || !geoUHExtra.currentFileId) {
				//it's disabled, don't do a thing
				geoUHExtra.doNothing();
				return;
			}
			try {
				geoUHExtra.swfu.addFileParam (geoUHExtra.currentFileId, 'imageTitle', $('adfileTitle').getValue());
				geoUHExtra.swfu.startUpload();
			} catch (ex) {
				if (geoUHExtra.debug) {
					alert ('DEBUG caught exception 777: '+ex);
				}
			}
		}
	},
	
	uploadFormSubmit : function(action) {
		//see if there are pending photos needing uploading or not
		if (!$('aduploadButton').disabled) {
			//upload button active, must be pending something
			var text = geoUHExtra.text.uploadNotFinished;
			if (geoUHExtra.currentEditPosition) {
				text = geoUHExtra.text.editNotFinished;
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
		var animation = $('adbarAnimation');
		if (!animation) {
			//could not find animation image, can't do anything
			return;
		}
		animation.show();
	},
	stopAnimationBar : function () {
		var animation = $('adbarAnimation');
		if (!animation) {
			//could not find animation image, can't do anything
			return;
		}
		animation.hide();
	},
	
	currentEditPosition : 0,
	//triggered from button click
	editImage : function (position) {
		if (geoUHExtra.doingSomething('')) return;
		
		if (!$('adimageSlot_'+position)) {
			return;
		}
		geoUHExtra.cancelEdit(true);
		
		//hide it so it doesn't do moving effect, that tends to be confusing.
		geoUHExtra.hideImageUploadBox();
		
		//change new image box into edit image box
		geoUHExtra.currentEditPosition = position;
		
		geoUHExtra.changeToEdit();
		
		//now move it there!  remember we hid it already above so this will
		//make it just appear in place.
		geoUHExtra.moveTo(position);
	},
	
	changeToEdit : function () {
		var newImageBox = $('adnewImageBox');
		if (!newImageBox) {
			return;
		}
		var position = geoUHExtra.currentEditPosition;
		
		//first, change title
		$('adimageUploadTitle').update(geoUHExtra.text.editImagePosition+position);
		
		//now, get the contents of that image thingy
		$('adimagePreview').update($('adimagePreview_'+position).down())
			.removeClassName('emptyPreview');
		
		//set the title
		$('adfileTitle').value = $('adimageTitle_'+position).value;
		
		//set the progress bar text
		$('adnewImageProgress').update(geoUHExtra.text.keepSameImage);
		
		//update upload button
		$('aduploadButton').enable();
		$('aduploadButton').value = geoUHExtra.text.apply;
		
		//enable the cancel button
		$('adcancelUploadButton').show();
		
		//hide the edit and delete buttons underneith
		geoUHExtra.hideEditDeleteButtons(position);
		
		//kill anything in the queue
		geoUHExtra.clearQueue();
	},
	
	cancelEditClick : function (action) {
		if (geoUHExtra.doingSomething('')) {
			//action.stop();
			return;
		}
		geoUHExtra.cancelEdit(false);
	},
	
	cancelEdit : function (skipMove) {
		var newImageBox = $('adnewImageBox');
		if (!newImageBox) {
			return;
		}
		if (!geoUHExtra.currentEditPosition) {
			//not editing!
			return;
		}
		var position = geoUHExtra.currentEditPosition;
		
		//first, change title
		$('adimageUploadTitle').update(geoUHExtra.text.newImageUpload);
		
		//now, get the contents of that image thingy
		$('adimagePreview_'+position).update($('adimagePreview').down());
		
		$('adimagePreview').update(geoUHExtra.text.newImage)
			.addClassName('emptyPreview');
		
		//set the title
		$('adfileTitle').value = "";
		
		//set the progress bar text
		$('adnewImageProgress').update(geoUHExtra.text.noFileSelected);
		
		//update upload button
		$('aduploadButton').disable();
		$('aduploadButton').value = geoUHExtra.text.upload;
		
		//hide the cancel button
		$('adcancelUploadButton').hide();
		
		//show the edit and delete buttons underneith
		geoUHExtra.showEditDeleteButtons(position);
		
		//kill anything in the queue
		geoUHExtra.clearQueue();
		//reset the edit ID
		geoUHExtra.currentEditPosition = 0;
		if (skipMove == true) {
			
		} else {
			//move back to position
			
			//hide it first, don't do the moving animation as that tends to
			//be confusing.
			geoUHExtra.hideImageUploadBox();
			if (geoUHExtra.currentUploadSpot) {
				//we've go somewhere to go!  Places to be!  People to meet!
				geoUHExtra.moveTo(geoUHExtra.currentUploadSpot);
			}
		}
	},
	
	showEditDeleteButtons : function (position) {
		var editButton = $('adeditImage_'+position);
		var deleteButton = $('addeleteImage_'+position);
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
		var editButton = $('adeditImage_'+position);
		var deleteButton = $('addeleteImage_'+position);
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
		if (geoUHExtra.currentEditPosition) {
			//cancel the edit
			geoUHExtra.cancelEdit(true);
			geoUHExtra.hideImageUploadBox();
		}
		$('adnewImageProgress').update(geoUHExtra.text.noFileSelected);
		$('aduploadBar').setStyle({width: '1%'});
		$('adfileTitle').value = '';
		$('aduploadButton').disable();
		//kill the animation bar
		geoUHExtra.stopAnimationBar();
		
		geoUHExtra.clearQueue();
		geoUHExtra.setButtonDisabled(false);
	},
	
	deleteImage : function (deleteImageSlot) {
		if (geoUHExtra.doingSomething('')) return;
		
		//TODO: add a confirmation for delete
		
		//send an ajax call to delete the image.
		geoUHExtra.doingSomething('deleteImage');
		var params = {
			imageSlot : deleteImageSlot,
			userId : geoUHExtra.userId,
			adminId : geoUHExtra.adminId
		}
		
		new Ajax.Request(geoUHExtra.ajaxUrl+"?controller=UploadAdImage&action=deleteImage", {
			method: 'post',
			parameters: params,
			onSuccess: geoUHExtra.deleteSuccess
		});
	},
	addError: function (errorMsg) {
		var msg = geoUHExtra.text.uploadError+errorMsg;
		geoUtil.addError(msg);
	},
	
	throwServerError : function (debugInfo, skipReset) {
		var msg = geoUHExtra.text.generalServerError;
		//rest of text does not need to be changeble, it is only for debug
		if (geoUHExtra.debug) {
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
		
		geoUHExtra.addError(msg);
		if (skipReset == true) {
			//do not reset the upload box
			
		} else {
			//reset the upload box
			geoUHExtra.resetUploadBox();
		}
	},
	
	//This needs to be the SAME as what param is set to in swfu init call!
	_currentButtonDisabled : true,
	
	//use this instead of method of same name in swfu object, to avoid script
	//from stop running when button is already that way...
	setButtonDisabled : function (setting) {
		if (setting != geoUHExtra._currentButtonDisabled && geoUHExtra.swfu != null) {
			try {
				geoUHExtra.swfu.setButtonDisabled(setting);
				geoUHExtra._currentButtonDisabled = setting;
			} catch (ex) {
				if (geoUHExtra.debug) {
					alert ('DEBUG caught exception 1082: '+ex);
				}
			}
		}
	}
};
