{* 7.2beta3-95-g23b3a2d *}
{*
	This is the JS to insert into the head of the page, can't put this in the
	JS file because a lot of it is dynamic
*}
<script type="text/javascript">
//<![CDATA[
	var swfu; 
	
	geoUHLogo.init = function () {
		//only define width/height in one place, so only 1 place to change:
		geoUHLogo.movieWidth = 96;
		geoUHLogo.movieHeight = 24;
		//update max upload size
		geoUHLogo.file_size_limit = '{$maximum_upload_size} B';
		geoUHLogo.currentUploadSpot = {$adlogofreeSlot};

		geoUHLogo.startingPostParams = {ldelim}
			'classified_session': '{$classified_session|escape_js}',
			'user_agent': '{$user_agent|escape_js}',
			'uploadSlot': '{$adlogofreeSlot}',
			'userId' : '{$userId}',
			'adminId' : '{$adminId}'
		};
		//location of flash file
		geoUHLogo.flashUrl = "{if $in_admin}../{/if}classes/swfupload/swfupload.swf";
		geoUHLogo.ajaxUrl = "{if $in_admin}../{/if}AJAX.php";
		
		//un-comment to turn debug on for image uploader
		//geoUHLogo.debug=true;
		
		geoUHLogo.userId = {$userId};
		geoUHLogo.adminId = {$adminId};
		
		//set the location of the select file image
		geoUHLogo.flashButtonImage = '{if $in_admin}../{/if}{external file="images/buttons/select_file.png"}';

		//set the text as it is from text in admin
		geoUHLogo.text = {ldelim}
			sessionError : '{$messages.500663|escape_js}',
			uploadStarting : '{$messages.500664|escape_js}',
			progressFileQueued1 : '{$messages.500665|escape_js}',
			progressFileQueued2 : '{$messages.500666|escape_js}',
			processingImage : '{$messages.500667|escape_js}',
			progressOf : '{$messages.500668|escape_js}',
			progressBytesUploaded : '{$messages.500669|escape_js}',
			editImagePosition : '{$messages.500670|escape_js}',
			keepSameImage : '{$messages.500671|escape_js}',
			apply : '{$messages.500672|escape_js}',
			newImageUpload : '{$messages.500673|escape_js}',
			newImage : '{$messages.500674|escape_js}',
			noFileSelected : '{$messages.500675|escape_js}',
			upload : '{$messages.500676|escape_js}',
			uploadError : '{$messages.500677|escape_js}',
			generalServerError : '{$messages.500678|escape_js}',
			uploadNotFinished : '{$messages.500758|escape_js}',
			editNotFinished : '{$messages.500759|escape_js}',
			fileTooBig : '{$messages.500818|escape_js}'
		};
		//set text for close button
		geoUtil.text.messageClose = '{$messages.500703|escape_js}';
		
		//initialize the upload button
		geoUHLogo.initSwfu();
		
		$('adlogoloadingBox').show();
	};
	jQuery(function () {
		//register callbacks
		if (gjUtil) {
			//register the show and hide callbacks for image upload box with the
			//lightupbox, so it shows and hides cleanly
			gjUtil.lightbox.onOpen(geoUHLogo.tempHideUpload);
			gjUtil.lightbox.onClose(geoUHLogo.tempShowUpload);
		}
		//register the fix position callback for after show/hide instruction
		//animation is done, so that the image upload box gets moved to correct
		//place after instructions are shown/hidden
		geoUtil.instrBtns.registerCallbacks (geoUHLogo.tempHideUpload, geoUHLogo.fixUploadPosition);
	});
	jQuery(window).load(function () {
		//Wait until window is done loading!  This should give enough time to the
		//preview window to show lightbox, which shoul trigger the _delayInit to
		//let us know NOT to start init right away.
		if (!geoUHLogo._delayInit) {
			//initialize now
			geoUHLogo.init();
		} else if (gjUtil) {
			//initialize when closing lightbox
			gjUtil.lightbox.onClose(geoUHLogo.init);
		}
	});
//]]>
</script>