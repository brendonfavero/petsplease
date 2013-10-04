// 7.2beta3-95-g23b3a2d

//This does the fancy stuff for image uploads

//use geoUH as namespace (geo upload handler)
var geoUH = {
	
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
		if (!geoUH.doingSomething('initSwfu')) {
			//init swfu over
			geoUH.doNothing();
		}
		if (geoUH._initRun) {
			return;
		}
		geoUH._initRun = true;
		
		var newImageBox = $('newImageBox');
		if (!newImageBox) {
			//new image box not on page
			return;
		}
		
		if ($('legacyUploadContainer').visible()) {
			//if we had un-hidden the old form because page was taking too long,
			//hide it again
			$('legacyUploadContainer').hide();
		}
		//hide the loading box if it's there...
		$('loadingBox').hide();
		
		//hide the legacy
		if ($('legacyUploadBox')) {
			$('legacyUploadBox').hide();
		}
		
		//show the normal
		$('standardUploadBox').show();
		//hide it
		geoUH.hideImageUploadBox(true);
		
		if (geoUH.currentUploadSpot) {
			//now figure out where it goes..
			geoUH.moveTo(geoUH.currentUploadSpot);
		} else {
			//no spots open!  oh well, it's already hidden...
		}
		
		//make draggable
		geoUH.initSortableSlots();
		
		//what out for clicks on update button
		$('uploadButton').observe('click', geoUH.uploadButtonClick);
		//watch out for cancel clicks too
		$('cancelUploadButton').observe('click',geoUH.cancelEditClick);
		
		//show the instructions
		if ($('image_upload_instructions')) {
			$('image_upload_instructions').show();
		}
		if ($('image_upload_instructions_legacy')) {
			//hide legacy button
			$('image_upload_instructions_legacy').hide();
		}
		
		//Find the "next" form, and add a listener to it.
		var formElem = $('imageUploadNextFormElement');
		if (formElem) {
			formElem = formElem.up('form');
			if (formElem && formElem.match('form')) {
				//this is the form element, add a listener
				formElem.observe('submit', geoUH.uploadFormSubmit);
			}
		}
	},
	
	doingSomething : function (notThis) {
		return (geoUH._inTransition == notThis)? false: true;
	},
	
	doSomething : function (what) {
		//sanity check
		if (geoUH.doingSomething('')) {
			//already doing something, stop that!
			if (geoUH.debug) alert('Attempting to do something '+what+' when already '+geoUH._inTransition);
			return false;
		}
		geoUH._inTransition = what;
		return true;
	},
	doNothing : function () {
		geoUH._inTransition = '';
	},
	//initializes the swfu movie.  Will be called multiple times in a page load.
	initSwfu : function () {
		if (geoUH.doingSomething('')) return;
		
		if (geoUH.swfu) {
			//why is this being called?
			if (geoUH.debug) {
				alert('called initSwfu but swfu is already inited!');
			}
			return;
		}
		geoUH.doSomething('initSwfu');
		geoUH.swfu = new SWFUpload({
			upload_url : geoUH.ajaxUrl+"?controller=UploadImage&action=uploadImage", 
			file_size_limit : geoUH.file_size_limit,
			file_queue_limit : 1,
	
			// Let the script know the classified session ID:
			post_params: geoUH.startingPostParams,
	
			// Button settings
			button_placeholder_id: 'spanButtonPlaceHolder',
			button_image_url : geoUH.flashButtonImage,
			/*
			button_text : "<span class='selectButton'><b>Select File</b></span>",
			button_text_style : ".selectButton {ldelim} color: #0000FF; font-size: 10px }",
			*/
			button_width : geoUH.movieWidth,
			button_height : geoUH.movieHeight,
			button_cursor : SWFUpload.CURSOR.HAND,
			button_action : SWFUpload.BUTTON_ACTION.SELECT_FILES,
			button_disabled : false,//start out disabled
			
			// Event Handler Settings - these functions in handlers.js
			swfupload_loaded_handler : geoUH.initUploads,
			upload_success_handler : geoUH.uploadSuccess,
			upload_error_handler : geoUH.uploadError,
			upload_start_handler : geoUH.uploadStart,
			upload_progress_handler : geoUH.uploadProgress,
			file_dialog_start_handler : geoUH.diagStart,
			file_dialog_complete_handler : geoUH.diagComplete,
			file_queued_handler : geoUH.fileQueued,
			file_queue_error_handler : geoUH.fileQueuedError,
			
			
			// Flash Settings
			flash_url : geoUH.flashUrl,	// Relative to main file
			
			// Debug Setting, use same as geoUH.debug
			debug: geoUH.debug
		});
		
		//reset vars
		geoUH._currentButtonDisabled = true;
		geoUH.currentFileId = 0;
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
			geoUH.initSortableSlots();
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
		geoUH.initSortableSlots();
	},

	dragableObserver : {
		_doFade: false,
		
		_plopImage : function (draggable) {
			//move the "move image here" image to the right place, and make it visible.
			var plopImage = $('plopDropImageHere');
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
			if (geoUH.doingSomething('')) {
				//what the... already doing something
				event.stop();
				return;
			}
			
			
			//simple hack to make the box centered vertically on the mouse,
			//so people don't get as confused...
			draggable.options.snap = function (x,y, draggable) {
				return [x, y-75];
			};
			geoUH.tempHideUpload();
			//start out with the plopHere box visible instead of waiting for change
			geoUH.dragableObserver._plopImage(draggable);
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
			if (newOrder == geoUH.dragableObserver.currentOrder) {
				//did not change order, nothing to do
				return;
			}
			geoUH.dragableObserver.currentOrder = newOrder;
			
			//move the "move image here" image to the right place, and make it visible.
			var plopImage = $('plopDropImageHere');
			if (!plopImage) {
				//nothing can be done, can't find the plog image!
				return;
			}
			
			geoUH.dragableObserver._plopImage(draggable);
		},
		
		onEnd: function (eventName, draggable, event) {
			if (!draggable.element.hasClassName('imageBox')) {
				//ignore this one
				return;
			}
			//move it to where it goes
			
			geoUH.tempShowUpload();
			var plopImage = $('plopDropImageHere');
			if (plopImage) plopImage.hide();
		},
		_alreadyInited: false,
		
		init: function() {
			if (geoUH.dragableObserver._alreadyInited) {
				//already initialized
				return;
			}
			geoUH.dragableObserver._alreadyInited = true;
			Draggables.addObserver(geoUH.dragableObserver);
		}
	},

	initSortableSlots : function () {
		//NOTE: If element is already sortable, it destroys it first automatically
		//for us!  So we can call geoUH whenever we need the sortable items to be
		//re-done
		
		//Sortable.destroy('imagesCapturedBox');
		
		Sortable.create('imagesCapturedBox', {
			tag: 'div',
			only: 'imageBox',
			overlap: 'horizontal',
			treeTag: 'div',
			constraint: '',
			handle: 'imageBoxTitleHandle',
			scroll : geoUH.scrollSetting,
			//hoverclass: 'dragHover',
			onUpdate: function () {
				new Ajax.Request(geoUH.ajaxUrl+"?controller=UploadImage&action=sortImages", {
					method: 'post',
					parameters: {
						'imageSlots': Sortable.serialize('imagesCapturedBox'),
						'userId' : geoUH.userId,
						'adminId' : geoUH.adminId
					},
					onSuccess: geoUH.sortableResponseSuccess
				});
				//hide new image box to not show till it's done
				geoUH.hideImageUploadBox();
			}
		});
		
		geoUH.dragableObserver.init();
		
		//re-init the image clicks
		if (typeof gjUtil != 'undefined') {
			gjUtil.lightbox.initClick();
		}
	},
	
	moveImageUploaderBox : function (data) {
		var newImageBox = $('newImageBox');
		
		if (geoUH.currentEditPosition) {
			//used to be edit, but we're moving it to new position, so it's not
			//edit no more!
			geoUH.cancelEdit(true);
		}
		if (!data.error && (!data.nextUploadSlot || data.nextUploadSlot > data.maxSlots)) {
			//hide the whole thing
			if (geoUH.imageBoxVisible) {
				geoUH.hideImageUploadBox();
			}
			geoUH.currentUploadSpot = 0;
		} else {
			//make sure it is not hidden
			if (data.nextUploadSlot) {
				geoUH.currentUploadSpot = data.nextUploadSlot;
			}
			geoUH.moveTo(geoUH.currentUploadSpot);
		}
		//set the new upload slot
		geoUH.startingPostParams.uploadSlot = geoUH.currentUploadSpot;
		if (geoUH.swfu != null) {
			try {
				geoUH.swfu.addPostParam('uploadSlot', geoUH.currentUploadSpot);
			} catch (ex) {
				if (geoUH.debug) {
					alert ('DEBUG caught exception 392: '+ex);
				}
			}
		}
	},
	//can't use .hide() on image uploader, that will break opera since button is
	//in there.  Instead we destroy it and re-create it later.
	hideImageUploadBox : function (force) {
		var newImageBox = $('newImageBox');
		if (!newImageBox || (force != true && !geoUH.imageBoxVisible)) {
			return false;
		}
		
		//cancel any animations
		if (geoUH.fadingInAnimation != null && typeof geoUH.fadingInAnimation.cancel != 'undefined') {
			geoUH.fadingInAnimation.cancel();
			geoUH.fadingInAnimation = null;
		}
		
		//destroy SWFU object
		var destroyResult = null;
		if (geoUH.swfu == null) {
			//already destroyed, just hide the surrounding box!
			destroyResult = true;
		} else {
			try {
				destroyResult = geoUH.swfu.destroy();
			} catch (ex) {
				if (geoUH.debug) {
					alert('destroy failed, exception caught:'+ex);
				}
				destroyResult = false;
			}
		}
		if (!destroyResult) {
			//destroy left swfu in an inconsistent state, tell user and re-load page
			alert(geoUH.text.sessionError+' Internal Page Error, page needs to re-load. (null pointer to cheese)');
			window.location.reload();
			return false;
		}
		//just to be sure, clear out the container with the starting button
		$('startingButtonContainer').update('').hide();
		//reset parameters
		//geoUH.swfu should have been destroyed above, so set the var to null
		geoUH.swfu = null;
		if (!geoUH.doingSomething('initSwfu')) {
			//we are hiding before init is finished loading, since we just destroyed it,
			//chances are it isn't initializing any more...
			//alert('that was close!');
			geoUH.doNothing();
		}
		geoUH._currentButtonDisabled = true;
		if (geoUH.currentEditPosition) {
			//reset the box
			geoUH.resetUploadBox();
		}
		//re-insert the span so it has something to attach to when fading in the box.
		$('selectFileButtonBox').update('<span id="spanButtonPlaceHolder"></span>');
		//now that button is gone, hide the box
		newImageBox.hide();
		
		geoUH.imageBoxVisible = false;
		return true;
	},
	
	destroy : function () {
		if (geoUH.swfu == null) {
			//already destroyed, just hide the surrounding box!
			destroyResult = true;
		} else {
			try {
				destroyResult = geoUH.swfu.destroy();
			} catch (ex) {
				if (geoUH.debug) {
					alert('destroy failed, exception caught:'+ex);
				}
				destroyResult = false;
			}
		}
		geoUH.swfu = null;
		geoUH.doNothing();
		geoUH._initRun = false;
	},
	
	//if this is false, it will not add an effect when fading in, it will just appear.
	fadeInImageBoxParams : {duration: .8, from: 0.0, to: 1.0},
	
	fadingInAnimation : null,
	
	showImageUploadBox : function () {
		var newImageBox = $('newImageBox');
		if (!newImageBox || geoUH.imageBoxVisible) {
			return false;
		}
		
		//if this is the first time, clear all the stuff that was not cleared before...
		
		//we CAN fade it in if we start from something not 0 as starting point
		if (geoUH.fadeInImageBoxParams) {
			if (!geoUH.doingSomething('')) {
				//not doing anything except uploading, so finished with upload
				geoUH.doSomething('appear');
			}
			if (typeof geoUH.fadeInImageBoxParams.afterFinish != 'function') {
				//init what to do once the appear is finished.
				geoUH.fadeInImageBoxParams.afterFinish = function () {
					//once it's done fading in, init the movie param
					if (!geoUH.doingSomething('appear')) {
						//not doing anything except appearing, so finished with appear
						geoUH.doNothing();
					}
					if (!geoUH.swfu) {
						//this must be done once the surrounding box is visible.
						geoUH.initSwfu();
					}
					if ($('newImageBox').visible()) {
						//just in case there is some race condition, reset as
						//visible if it's visible.
						geoUH.imageBoxVisible = true;
					}
					//I wonder if we can do this?
					geoUH.fadingInAnimation = null;
				};
			}
			geoUH.fadingInAnimation = newImageBox.appear(geoUH.fadeInImageBoxParams);
			//new Effect.Appear (newImageBox, geoUH.fadeInImageBoxParams);
		} else {
			newImageBox.show();
			if (!geoUH.swfu) {
				geoUH.initSwfu();
			}
		}
		geoUH.imageBoxVisible = true;
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
		if (geoUH.imageBoxVisible) {
			geoUH._doTempShow = true;
			if (geoUH.currentEditPosition) {
				geoUH.cancelEdit(true);
				if (geoUH.currentUploadSpot) {
					geoUH.moveTo(geoUH.currentUploadSpot);
				} else {
					//there are no open upload slots, so don't fade back in
					geoUH._doTempShow = false;
				}
			}
			geoUH.hideImageUploadBox();
		} else {
			//let init know to delay the display, for times that this is called
			//before the uploader is even initialized...  In such cases, will want
			//to delay initialization
			geoUH._delayInit=true; 
		}
	},
	/**
	 * Shows the upload box after it has been hidden using tempHideUpload, but
	 * only if there is an empty spot to show it in.
	 */
	tempShowUpload : function () {
		//Fade it back in
		if (geoUH._doTempShow && !geoUH.imageBoxVisible) {
			geoUH.cancelEdit(true);
			geoUH._doTempShow = false;
			geoUH.showImageUploadBox();
		}
	},
	
	/**
	 * Fixes the position of the image upload box
	 */
	fixUploadPosition : function () {
		if (geoUH.currentEditPosition) {
			geoUH.moveTo(geoUH.currentEditPosition);
		} else if (geoUH.currentUploadSpot) {
			geoUH.moveTo(geoUH.currentUploadSpot);
		}
	},
	
	/**
	 * Moves new/edit image box to the given image slot, uses a move animation if
	 * the box is already visible, or just moves it then uses a fade in animation
	 * if the box starts out hidden.
	 */
	moveTo : function (position) {
		var newImageBox = $('newImageBox');
		
		var moveTo = $('imageSlot_'+position);
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
		
		if (!geoUH.imageBoxVisible) {
			//just move it to where it goes and fade it in
			newImageBox.setStyle({left: moveX+'px', top: moveY+'px'});
			geoUH.showImageUploadBox();
		} else {
			//move it!  Wheeeeeeeeeeee!  Speedything goes in, speedything comes out!
			
			new Effect.Move(newImageBox, {x: moveX, y: moveY, mode: 'absolute'});
		}
	},
	
	currentFileId : 0,
	
	editSuccess : function (transport) {
		if (!geoUH.doingSomething('upload')) {
			//not doing anything except uploading, so finished with upload
			geoUH.doNothing();
		}
		var response = transport.responseJSON;
		if (response) {
			geoUH.processResponse(response);
		} else {
			//some type of server error
			geoUH.throwServerError(transport);
		}
	},
	
	deleteSuccess : function (transport) {
		if (!geoUH.doingSomething('deleteImage')) {
			geoUH.doNothing();
		}
		var response = transport.responseJSON;
		if (response) {
			geoUH.processResponse(response);
		} else {
			//some type of server error
			geoUH.throwServerError(transport);
		}
	},
	
	sortableResponseSuccess : function (transport) {
		var response = transport.responseJSON;
		if (response) {
			geoUH.processResponse(response);
		} else {
			//some type of server error
			geoUH.throwServerError(transport);
		}
	},
	
	processResponse : function (data) {
		//reset stuff in image box
		geoUH.resetUploadBox();
		
		var msg = '';
		
		if (data.imagesDisplay && data.uploadSlot && $('imageSlot_'+data.uploadSlot)) {
			if (data.imagesDisplay == 'get') {
				//did not pass back what is to be displayed, must go get the box
				var params = {
					uploadSlot : data.uploadSlot,
					editImage : data.editImage,
					editImageSlot : geoUH.currentEditPosition,
					userId : geoUH.userId,
					adminId : geoUH.adminId
				};
				new Ajax.Request(geoUH.ajaxUrl+"?controller=UploadImage&action=imageSlotContents", {
					method: 'post',
					parameters: params,
					onSuccess: geoUH.getSlotResponse
				});
			} else {
				geoUH.updateSlotContents(data);
			}
		} else if (data.uploadImageBox) {
			//update all the slots
			$('imagesCapturedBox').update(data.uploadImageBox);
			//reset draggables
			geoUH.initSortableSlots();
		}
		
		if (data.errorSession) {
			//error requiring to re-load the page
			alert(geoUH.text.sessionError+data.errorSession);
			window.location.reload();
		}
		
		if (data.error) {
			geoUH.addError(data.error);
		}
		
		if (data.msg) {
			msg += data.msg;
		}
		
		if (msg && !data.error) {
			geoUtil.addMessage(msg);
		}
		
		//move it where it goes
		geoUH.moveImageUploaderBox(data);
	},
	getSlotResponse : function (transport) {
		var response = transport.responseJSON;
		if (response) {
			geoUH.updateSlotContents(response);
		} else {
			//some type of server error
			geoUH.throwServerError(transport);
		}
	},
	updateSlotContents : function (data) {
		if (typeof data.uploadSlot == 'undefined' || !data.uploadSlot) {
			//nothing to do, some kind of error
			geoUH.throwServerError('Invalid data, data: '+data);
			return;
		}
		//shove it in there
		
		//there is an image box to add in there
		var newBox = new Element('div',{'class': 'innerImageBox'}).hide();
		
		//shove the contents into our new div
		newBox.insert(data.imagesDisplay);
		
		//now shove the new box into the right spot
		var rightSpot = $('imageSlot_'+data.uploadSlot);
		rightSpot.update(newBox);
		
		//make it sortable
		geoUH.makeSortable(rightSpot);
		
		//show it
		//new Effect.Appear(newBox, geoUH.defaultParams); //appear goes too slow
		newBox.show();
	},
	
	uploadSuccess : function (file, server_data, receivedResponse) {
		//alert("The file " + file.name + " has been delivered to the server. The server responded with: " + server_data);
		//$('SWFUpload_Console').insert(server_data);
		if (!geoUH.doingSomething('upload')) {
			//not doing anything except uploading, so finished with upload
			geoUH.doNothing();
		}
		if (server_data.isJSON()) {
			var data = server_data.evalJSON(true);
			geoUH.processResponse(data);
		} else {
			//throw an error, something went wrong with processing...
			geoUH.throwServerError(server_data);
			
		}
		//$('imagesCapturedBox').update(server_data);
		return true;
	},
	
	uploadError : function () {
		//throw a generic error
		if (!geoUH.doingSomething('upload')) {
			//not doing anything except uploading, so finished with upload
			geoUH.doNothing();
		}
		geoUH.throwServerError('uploadError Triggered');
	},

	uploadStart : function () {
		//triggered once upload has started, this is NOT the click event!
		
		//alert ('starting upload!');
		$('uploadButton').disable();
		//turn on the animation thingy and make the scroll thingy big
		$('uploadBar').setStyle({width: '100%'});
		$('newImageProgress').update (geoUH.text.uploadStarting);
		geoUH.startAnimationBar();
		geoUH.setButtonDisabled(true);
	},

	diagComplete : function (numSelected, numQueued, totalQueued) {
		if ((numSelected > 0 && numQueued > 0) || geoUH.currentEditPosition) {
			//either there are images selected, or this is an edit, either case
			//turn on the upload (or apply) button.
			$('uploadButton').enable();
			//bring attention to upload button
			new Effect.Pulsate('uploadButton', {pulses: 3, duration: .8});
		} else {
			//they canceled selection of image, turn off the upload button.
			$('uploadButton').disable();
		}
	},
	
	clearQueue : function () {
		var stats = null;
		try {
			//put this in try block, as it will fail if the movie is currently
			//not visible
			stats = geoUH.swfu.getStats();
		} catch (ex) {
			stats = false;
		}
		
		while (stats && stats.files_queued > 0) {
			try {
				//put this in try block, as it can fail sometimes if button
				//is hidden
				geoUH.swfu.cancelUpload(undefined, false);
				stats = geoUH.swfu.getStats();
			} catch (ex) {
				stats = false;
			}
		}
	},
	
	diagStart : function () {
		//clear the queue, we only have 1 in the queue at once
		geoUH.clearQueue();
	},

	fileQueued : function (file) {
		geoUH.currentFileId = file.id;
		$('newImageProgress').update(geoUH.text.progressFileQueued1+file.name+geoUH.text.progressFileQueued2);
	},
	
	fileQueuedError : function (file, error_code, message) {
		if (geoUH.debug) {
			alert ('File que error, message: '+message);
		}
		if (error_code == SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT) {
			//file too big
			
			//make a pretty looking max file size
			var maxSize = geoUH.file_size_limit.replace(' B', '')*1;
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
			
			geoUH.addError(geoUH.text.fileTooBig + maxSize);
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
		$('uploadBar').setStyle({width: percent+'%'});
		if (percent == 100) {
			//at 100 percent...
			$('newImageProgress').update (geoUH.text.processingImage);
		} else {
			$('newImageProgress').update (''+complete+geoUH.text.progressOf+total+geoUH.text.progressBytesUploaded);
		}
	},
	
	uploadButtonClick : function (junk) {
		//don't do upload if still in middle of something
		if (geoUH.doingSomething('')) return;
		
		geoUH.doingSomething('upload');
		if (geoUH.currentEditPosition) {
			//editing, not doing something new
			var stats = null;
			try {
				stats = geoUH.swfu.getStats();
			} catch (ex) {
				stats = false;
			}
			if (stats && stats.files_queued > 0) {
				//there is an upload to process...
				//treat it like a new image upload
				try {
					geoUH.swfu.addFileParam (geoUH.currentFileId, 'imageTitle', $('fileTitle').getValue());
					geoUH.swfu.addFileParam (geoUH.currentFileId, 'editImage', 1);
					geoUH.swfu.addFileParam (geoUH.currentFileId, 'editImageSlot', geoUH.currentEditPosition);
					
					geoUH.swfu.startUpload();
				} catch (ex) {
					if (geoUH.debug) {
						alert ('DEBUG caught exception 742: '+ex);
					}
				}
			} else {
				//send an ajax call w/o the image data
				
				var params = {
					imageTitle : $('fileTitle').getValue(),
					editImage : 1,
					editImageSlot : geoUH.currentEditPosition,
					userId : geoUH.userId,
					adminId : geoUH.adminId
				};
				
				new Ajax.Request(geoUH.ajaxUrl+"?controller=UploadImage&action=uploadImage", {
					method: 'post',
					parameters: params,
					onSuccess: geoUH.editSuccess
				});
				//manually make the stuff happen for when uploading image
				geoUH.uploadStart();
			}
			
			//hide cancel button
			$('cancelUploadButton').hide();
		} else {
			if ($('uploadButton').disabled || !geoUH.currentFileId) {
				//it's disabled, don't do a thing
				geoUH.doNothing();
				return;
			}
			try {
				geoUH.swfu.addFileParam (geoUH.currentFileId, 'imageTitle', $('fileTitle').getValue());
				geoUH.swfu.startUpload();
			} catch (ex) {
				if (geoUH.debug) {
					alert ('DEBUG caught exception 777: '+ex);
				}
			}
		}
	},
	
	uploadFormSubmit : function(action) {
		//see if there are pending photos needing uploading or not
		if (!$('uploadButton').disabled) {
			//upload button active, must be pending something
			var text = geoUH.text.uploadNotFinished;
			if (geoUH.currentEditPosition) {
				text = geoUH.text.editNotFinished;
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
		var animation = $('barAnimation');
		if (!animation) {
			//could not find animation image, can't do anything
			return;
		}
		animation.show();
	},
	stopAnimationBar : function () {
		var animation = $('barAnimation');
		if (!animation) {
			//could not find animation image, can't do anything
			return;
		}
		animation.hide();
	},
	
	currentEditPosition : 0,
	//triggered from button click
	editImage : function (position) {
		if (geoUH.doingSomething('')) return;
		
		if (!$('imageSlot_'+position)) {
			return;
		}
		geoUH.cancelEdit(true);
		
		//hide it so it doesn't do moving effect, that tends to be confusing.
		geoUH.hideImageUploadBox();
		
		//change new image box into edit image box
		geoUH.currentEditPosition = position;
		
		geoUH.changeToEdit();
		
		//now move it there!  remember we hid it already above so this will
		//make it just appear in place.
		geoUH.moveTo(position);
	},
	
	changeToEdit : function () {
		var newImageBox = $('newImageBox');
		if (!newImageBox) {
			return;
		}
		var position = geoUH.currentEditPosition;
		
		//first, change title
		$('imageUploadTitle').update(geoUH.text.editImagePosition+position);
		
		//now, get the contents of that image thingy
		$('imagePreview').update($('imagePreview_'+position).down())
			.removeClassName('emptyPreview');
		
		//set the title
		$('fileTitle').value = $('imageTitle_'+position).value;
		
		//set the progress bar text
		$('newImageProgress').update(geoUH.text.keepSameImage);
		
		//update upload button
		$('uploadButton').enable();
		$('uploadButton').value = geoUH.text.apply;
		
		//enable the cancel button
		$('cancelUploadButton').show();
		
		//hide the edit and delete buttons underneith
		geoUH.hideEditDeleteButtons(position);
		
		//kill anything in the queue
		geoUH.clearQueue();
	},
	
	cancelEditClick : function (action) {
		if (geoUH.doingSomething('')) {
			//action.stop();
			return;
		}
		geoUH.cancelEdit(false);
	},
	
	cancelEdit : function (skipMove) {
		var newImageBox = $('newImageBox');
		if (!newImageBox) {
			return;
		}
		if (!geoUH.currentEditPosition) {
			//not editing!
			return;
		}
		var position = geoUH.currentEditPosition;
		
		//first, change title
		$('imageUploadTitle').update(geoUH.text.newImageUpload);
		
		//now, get the contents of that image thingy
		$('imagePreview_'+position).update($('imagePreview').down());
		
		$('imagePreview').update(geoUH.text.newImage)
			.addClassName('emptyPreview');
		
		//set the title
		$('fileTitle').value = "";
		
		//set the progress bar text
		$('newImageProgress').update(geoUH.text.noFileSelected);
		
		//update upload button
		$('uploadButton').disable();
		$('uploadButton').value = geoUH.text.upload;
		
		//hide the cancel button
		$('cancelUploadButton').hide();
		
		//show the edit and delete buttons underneith
		geoUH.showEditDeleteButtons(position);
		
		//kill anything in the queue
		geoUH.clearQueue();
		//reset the edit ID
		geoUH.currentEditPosition = 0;
		if (skipMove == true) {
			
		} else {
			//move back to position
			
			//hide it first, don't do the moving animation as that tends to
			//be confusing.
			geoUH.hideImageUploadBox();
			if (geoUH.currentUploadSpot) {
				//we've go somewhere to go!  Places to be!  People to meet!
				geoUH.moveTo(geoUH.currentUploadSpot);
			}
		}
	},
	
	showEditDeleteButtons : function (position) {
		var editButton = $('editImage_'+position);
		var deleteButton = $('deleteImage_'+position);
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
		var editButton = $('editImage_'+position);
		var deleteButton = $('deleteImage_'+position);
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
		if (geoUH.currentEditPosition) {
			//cancel the edit
			geoUH.cancelEdit(true);
			geoUH.hideImageUploadBox();
		}
		$('newImageProgress').update(geoUH.text.noFileSelected);
		$('uploadBar').setStyle({width: '1%'});
		$('fileTitle').value = '';
		$('uploadButton').disable();
		//kill the animation bar
		geoUH.stopAnimationBar();
		
		geoUH.clearQueue();
		geoUH.setButtonDisabled(false);
	},
	
	deleteImage : function (deleteImageSlot) {
		if (geoUH.doingSomething('')) return;
		
		//TODO: add a confirmation for delete
		
		//send an ajax call to delete the image.
		geoUH.doingSomething('deleteImage');
		var params = {
			imageSlot : deleteImageSlot,
			userId : geoUH.userId,
			adminId : geoUH.adminId
		}
		
		new Ajax.Request(geoUH.ajaxUrl+"?controller=UploadImage&action=deleteImage", {
			method: 'post',
			parameters: params,
			onSuccess: geoUH.deleteSuccess
		});
	},
	addError: function (errorMsg) {
		var msg = geoUH.text.uploadError+errorMsg;
		geoUtil.addError(msg);
	},
	
	throwServerError : function (debugInfo, skipReset) {
		var msg = geoUH.text.generalServerError;
		//rest of text does not need to be changeble, it is only for debug
		if (geoUH.debug) {
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
		
		geoUH.addError(msg);
		if (skipReset == true) {
			//do not reset the upload box
			
		} else {
			//reset the upload box
			geoUH.resetUploadBox();
		}
	},
	
	//This needs to be the SAME as what param is set to in swfu init call!
	_currentButtonDisabled : true,
	
	//use this instead of method of same name in swfu object, to avoid script
	//from stop running when button is already that way...
	setButtonDisabled : function (setting) {
		if (setting != geoUH._currentButtonDisabled && geoUH.swfu != null) {
			try {
				geoUH.swfu.setButtonDisabled(setting);
				geoUH._currentButtonDisabled = setting;
			} catch (ex) {
				if (geoUH.debug) {
					alert ('DEBUG caught exception 1082: '+ex);
				}
			}
		}
	}
};
