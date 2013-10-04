// 7.1.2-74-gc0f16fc

//This file allows offsite_video video ajax to work during listing process.

var geoVidProcess = {
	text : {
		addButton : 'Add This Video',
		editButton : 'Apply Changes'
	},
	
	currentSlot : 0,
	
	ajaxUrl : 'AJAX.php',
	
	adminId : 0,
	userId : 0,
	draggable : null,
	
	//If you are constantly getting the server error message, change this to
	//true and it will display additional debug information about what is wrong.
	debug : false,
	
	//scroll setting for sortable (dragging image boxes around), if it causes
	//problems on your layout you can change it to null to disable.
	scrollSetting : window,
	
	//if this is true, that means something is happening, so no actions can take
	//place.  Be careful of deadlock/race conditions on this!
	_inTransition : '',
	
	init : function () {
		//init everything here
		
		$$('div.offsite_video_action_buttons').invoke('update');
		
		if (geoVidProcess.currentSlot) {
			//insert the add button
			
			for (var i=1; i<=geoVidProcess.currentSlot; i++) {
				var buttonContainer = $('offsite_videoButtons_'+i);
				if (buttonContainer) {
					var msg = (i==geoVidProcess.currentSlot)? geoVidProcess.text.addButton : geoVidProcess.text.editButton;
					var addButton = new Element('a', {'class': 'button', 'href': '#'})
						.update(msg);
					//watch the button like a hawk!
					addButton.observe ('click', geoVidProcess.buttonClick);
					
					buttonContainer.update(addButton);
				}
			}
		}
		
		//keep watch on delete buttons
		$$('.delete_offsite_video').each(function (elem) {
			elem.observe('click', geoVidProcess.deleteVideo);
		});
		
		//transfer the offsite video slot sortable class to the parent...
		$$('.offsite_video_slot').each (function (elem) {
			var isSortable = elem.down().hasClassName ('offsite_video_is_sortable');
			elem[(isSortable)?'addClassName':'removeClassName']('offsite_video_slot_sortable');
		});
		
		//do the draggable bit
		geoVidProcess.initSortableSlots();
	},
	
	buttonClick : function (event) {
		event.stop();
		
		if (geoVidProcess.doingSomething('')) return;
		
		geoVidProcess.doingSomething('applyChanges');
		
		//make the animation show
		this.up().previous().show();
		
		//trick is, we are sending ALL fields for all the offsite_video videos, so user
		//can actually enter in all of the fields before submitting.  It will skip
		//processing on any that have not changed.
		
		var params = Form.serialize($('offsite_videos_outer'),true);
		
		params.adminId = geoVidProcess.adminId;
		params.userId = geoVidProcess.userId;
		
		new Ajax.Request(geoVidProcess.ajaxUrl+'?controller=OffsiteVideos&action=uploadVideo', {
			method: 'post',
			parameters: params,
			onSuccess: geoVidProcess.uploadResponse
		});
	},
	
	deleteVideo : function (event) {
		event.stop();
		
		if (geoVidProcess.doingSomething('')) return;
		
		//figure out for which slot
		var deleteVideoSlot = this.identify().replace('deleteYoutube_','');
		
		//TODO: add a confirmation for delete
		
		//send an ajax call to delete the image.
		geoVidProcess.doingSomething('deleteVideo');
		var params = {
			videoSlot : deleteVideoSlot,
			userId : geoVidProcess.userId,
			adminId : geoVidProcess.adminId
		}
		
		new Ajax.Request(geoVidProcess.ajaxUrl+"?controller=OffsiteVideos&action=deleteVideo", {
			method: 'post',
			parameters: params,
			onSuccess: geoVidProcess.deleteSuccess
		});
	},
	
	deleteSuccess : function (transport) {
		var data = transport.responseJSON;
		
		if (data) {
			geoVidProcess.processResponse(data);
		}
		geoVidProcess.doNothing();
	},
	
	uploadResponse : function (transport) {
		var data = transport.responseJSON;
		
		if (data) {
			geoVidProcess.processResponse(data);
		}
		//not doing anything any more
		geoVidProcess.doNothing();
	},
	
	initSortableSlots : function () {
		//NOTE: If element is already sortable, it destroys it first automatically
		//for us!  So we can call geoVidProcess whenever we need the sortable items to be
		//re-done, no need to call destroy ourselves		
		
		if (!$('offsite_videos_outer')) {
			//oops, not found...
			return;
		}
		
		Sortable.create('offsite_videos_outer', {
			tag: 'div',
			only: 'offsite_video_slot_sortable',
			overlap: 'horizontal',
			treeTag: 'div',
			constraint: '',
			handle: 'offsite_video_box_title',
			scroll : geoVidProcess.scrollSetting,
			revert : true,
			//hoverclass: 'dragHover',
			onUpdate: function () {
				new Ajax.Request(geoVidProcess.ajaxUrl+"?controller=OffsiteVideos&action=sortVideos", {
					method: 'post',
					parameters: {
						'videoSlots': Sortable.serialize('offsite_videos_outer'),
						'userId' : geoVidProcess.userId,
						'adminId' : geoVidProcess.adminId
					},
					onSuccess: geoVidProcess.sortableResponseSuccess
				});
			}
		});
		
		geoVidProcess.dragableObserver.init();
	},
	sortableResponseSuccess : function (transport) {
		var response = transport.responseJSON;
		if (response) {
			geoVidProcess.processResponse(response);
		}
		geoVidProcess.doNothing();
	},
	
	processResponse : function (data) {
		if (data.error) {
			geoUtil.addError(data.error);
		}
		if (data.errorSession) {
			geoUtil.addError(data.errorSession);
		}
		
		if (data.msg){
			geoUtil.addMessage(data.msg);
		}
		
		if (data.changed_slots) {
			data.changed_slots.each (function (item) {
				var itemBox = $('offsite_video_slot_'+item.slotNum);
				if (itemBox) {
					itemBox.update()
						.insert(item.contents);
				}
			});
			
			geoVidProcess.currentSlot = data.edit_slot;
			geoVidProcess.init();
		}
		
		if (data.upload_slots_html) {
			$('offsite_videos_outer').update()
				.insert(data.upload_slots_html);
			
			geoVidProcess.currentSlot = data.edit_slot;
			geoVidProcess.init();
		}
		$$('.offsite_video_loading_container').invoke('hide');
	},
	
	dragableObserver : {
		_doFade: false,
		
		_plopVideo : function (draggable) {
			//move the "move image here" image to the right place, and make it visible.
			var plopVideo = $('plopDropVideoHere');
			if (!plopVideo) {
				//nothing can be done, can't find the plog image!
				return;
			}
			
			//figure out choords to move it to
			var d = draggable.currentDelta();
			var now = draggable.element.positionedOffset();
			
			var ptop = now.top - d[1];
			var pleft = now.left - d[0];
			
			plopVideo.setStyle({left: pleft+'px', top: ptop+'px'});
			plopVideo.show();
		},
		
		onStart: function (eventName, draggable, event) {
			if (!draggable.element.hasClassName('offsite_video_slot_sortable')) {
				//ignore this one
				return;
			}
			if (geoVidProcess.doingSomething('')) {
				//what the... already doing something
				event.stop();
				return;
			}
			
			
			//simple hack to make the box centered vertically on the mouse,
			//so people don't get as confused...
			draggable.options.snap = function (x,y, draggable) {
				return [x, y-75];
			};
			//start out with the plopHere box visible instead of waiting for change
			geoVidProcess.dragableObserver._plopVideo(draggable);
		},
		
		currentOrder : null,
		
		onDrag: function (eventName, draggable, event) {
			if (!draggable.element.hasClassName('offsite_video_slot_sortable')) {
				//ignore this one
				return;
			}
			//don't do this every single time it's moved, only when the move
			//causes the position to change
			var newOrder = Sortable.serialize('offsite_videos_outer');
			if (newOrder == geoVidProcess.dragableObserver.currentOrder) {
				//did not change order, nothing to do
				return;
			}
			geoVidProcess.dragableObserver.currentOrder = newOrder;
			
			//move the "move image here" image to the right place, and make it visible.
			var plopVideo = $('plopDropVideoHere');
			if (!plopVideo) {
				//nothing can be done, can't find the plog image!
				return;
			}
			
			geoVidProcess.dragableObserver._plopVideo(draggable);
		},
		
		onEnd: function (eventName, draggable, event) {
			if (!draggable.element.hasClassName('offsite_video_slot_sortable')) {
				//ignore this one
				return;
			}
			//move it to where it goes
			
			var plopVideo = $('plopDropVideoHere');
			if (plopVideo) plopVideo.hide();
		},
		_alreadyInited: false,
		
		init: function() {
			if (geoVidProcess.dragableObserver._alreadyInited) {
				//already initialized
				return;
			}
			geoVidProcess.dragableObserver._alreadyInited = true;
			Draggables.addObserver(geoVidProcess.dragableObserver);
		}
	},
	
	doingSomething : function (notThis) {
		return (geoVidProcess._inTransition == notThis)? false: true;
	},
	
	doSomething : function (what) {
		//sanity check
		if (geoVidProcess.doingSomething('')) {
			//already doing something, stop that!
			if (geoVidProcess.debug) alert('Attempting to do something '+what+' when already '+geoVidProcess._inTransition);
			return false;
		}
		geoVidProcess._inTransition = what;
		return true;
	},
	doNothing : function () {
		geoVidProcess._inTransition = '';
	}
};

