//7.2beta3-41-gca71517

var geoDesignManage = {
	popupButtonObserve : function () {
		var box = $(this.identify()+'_box');
		if (!box) {
			//oops
			alert('Error loading tool, cannot find box with ID: '+this.identify()+'_box');
			return;
		}
		geoDesignManage.popupBox(box,this);
	},
	
	popupBox : function (box, button) {
		box = $(box);
		button = $(button);
		box.clonePosition(button,{
			setWidth: false,
			setHeight: false,
			offsetTop: 20
		});
		box.show().setOpacity(0.9);
		//lets make it movable on the page
		new Draggable(box, {handle: 'templateToolTitlebar'});
	},
	
	insertTag : function (tag) {
		if (!tag) {
			//oops, problem!
			return;
		}
		if ($('designTab') && $('designTab').hasClassName('activeTab')) {
			//insert into tinymce editor
			var ed = tinyMCE.activeEditor;
			if (!ed) {
				//editor not initialized
				return;
			}
			ed.focus();
			ed.selection.setContent(tag);
		} else if (geoDesignManage.useCodeMirror && geoDesignManage.codeMirror) {
			geoDesignManage.codeMirror.replaceSelection(tag);
		} else {
			var contents = $('tplContents');
			if (!contents) {
				//could not find contents textarea!?
				return;
			}
			if (contents.setSelectionRange){
				var start = contents.selectionStart;
				var end = contents.selectionEnd;
				contents.value = contents.value.substring(0, start)+ tag+ contents.value.substring(end, contents.value.length);
				
				//reset position to be right after inserted text
				contents.selectionEnd = start+tag.length;
				contents.selectionStart = start+tag.length;
		    } else if (document.selection && document.selection.createRange) {
		    	contents.focus();
		    	document.selection.createRange().text=tag;
		    } else {
		    	//some weird browser, just dump it at the end
		    	contents.value += tag;
		    }
			Form.Element.focus('tplContents');
		}
	},
	
	restrictedClick : function (event) {
		event.stop();
		var message = 'This is a restricted folder, you cannot access it from the template manager.';
		
		var elem = this;
		if (!elem.hasClassName('fileEntry')) {
			//this is a link, we need to look at the styles on the TR, not the link
			elem = elem.up().up().up();
		}
		
		if (elem.hasClassName('restrictedTset')) {
			message = 'Template set currently not being worked on.  You can change what template sets are being worked on at <a href="index.php?page=design_sets">Design > Template Sets</a>.';
		}
		if (elem.hasClassName('hiddenTset')) {
			message = 'Template set name starts with an underscore "_" which means it is effectively ignored by the template system.'
				+'<br /><br />The only way to work on or use this template set, is to re-name it via FTP to not start with an underscore "_".';
		}
		if (elem.hasClassName('restrictedAttachments')) {
			message = 'This is the attachments directory, where the files related to "templates to page" and "modules to template" are held.'
				+'<br /><br />The system will automatically keep track and update these files for you. In order to prevent corruption you will not be able to edit these files from the design manager.';
		}
		if (elem.hasClassName('restrictedTset_file')) {
			//special case: only FILE that shows as restricted, is the t_sets.php file
			message = 'This is the configuration file used to store what template sets are currently activated.'
				+'<br /><br />The system will automatically keep track and update this file for you. In order to prevent corruption you will not be able to edit this file from the design manager.';
		}
		if (elem.hasClassName('tempTset')) {
			message = 'This is a temporary folder, used by the system during complex operations such as template set uploads.';
		}
		geoUtil.addMessage(message);
	},
	
	selectedFiles : [],
	
	updateSelections : function () {
		//do stuff
		geoDesignManage.selectedFiles = [];
		$$('input.fileListCheckbox').each(function (element){
			var rowElem = element.up().up();
			if (rowElem.checked && geoDesignManage.isRestrictedFolder(element)) {
				//oops!  this is a restricted one, should not be checked, so force it
				rowElem.checked=false;
			}
			if (element.checked) {
				rowElem.addClassName('rowSelected');
				geoDesignManage.selectedFiles[geoDesignManage.selectedFiles.length] = element;
			} else {
				rowElem.removeClassName('rowSelected');
			}
		});
		
		geoDesignManage.updateSelectionActions();
	},
	
	updateSelectionActions : function () {
		var enabledActions = {
			edit : 0,
			cut : 0,
			copy : 0,
			paste : 0,
			rename : 0,
			make_copy : 0,
			download : 0,
			del : 0
		};
		var allIsWritable = 1;
		var folderCount = 0;
		var fileCount = 0;
		
		var listData = geoDesignManage.listData;
		
		if (listData.t_set!='n/a' && geoDesignManage.clipBoard.files.length>0 && listData.is_writable) {
			if (listData.t_type && listData.t_type==geoDesignManage.clipBoard.tType 
					&& geoDesignManage.clipBoard.tType == 'main_page' || geoDesignManage.clipBoard.tType == 'external') {
				if (listData.currentLocation != geoDesignManage.clipBoard.from) {
					//enable paste
					enabledActions.paste = 1;
				}
			} else if (listData.t_set != geoDesignManage.clipBoard.tSet) {
				//enable paste
				enabledActions.paste = 1;
			}
		}
		
		for (var i=0; i<geoDesignManage.selectedFiles.length; i++) {
			var selected = geoDesignManage.selectedFiles[i].getValue();
			
			var selectedData = listData.files[selected];
			
			if (selectedData.readonly || !listData.is_writable) {
				allIsWritable = 0;
			}
			
			if (allIsWritable) {
				if ( listData.t_set!='n/a' && listData.t_type) {
					enabledActions.cut = 1;
					enabledActions.del = 1;
				}
			} else {
				enabledActions.cut = 0;
				enabledActions.del = 0;
			}
			
			//at least 1 selected, so enable 'clipboard' operations
			if (listData.t_set!='n/a' && listData.t_type) {
				if (listData.t_type == 'main_page' || listData.t_type == 'external' || listData.canEditSystemTemplates) {
					enabledActions.copy = 1;
				}
			}
			
			if (geoDesignManage.selectedFiles.length == 1) {
				//only one selected, enable certain actions
				if (selectedData.type != 'php') {
					if (allIsWritable && listData.t_set!='n/a' && listData.t_type) {
						enabledActions.rename = 1;
						enabledActions.make_copy = 1;
					}
					
					if (!selectedData.is_dir) {
						enabledActions.download = 1;
						var allowedEditTypes = ['tpl','css','js','html','htm','txt'];
						if (allowedEditTypes.indexOf(selectedData.fileType) != -1) {
							enabledActions.edit = 1;
						}
					}
				}
			}
			
			//add to counters
			if (selectedData.is_dir) {
				folderCount++;
			} else {
				fileCount++;
			}
		}
		Object.keys(enabledActions).each(function (linkyId) {
			if ($('designSelected_'+linkyId)) {
				if (enabledActions[linkyId]) {
					$('designSelected_'+linkyId).addClassName('active');
				} else {
					$('designSelected_'+linkyId).removeClassName('active');
				}
			}
		});
		
		//update selected text
		if ($('selectedFolderCountSpan')) {
			$('selectedFolderCountSpan').update(folderCount);
			$('selectedFileCountSpan').update(fileCount);
		}
		//update preview box
		geoDesignManage.updatePreview();
	},
	
	clipBoard : {
		action : 'copy',
		from : '',
		tType : '',
		tSet : '',
		files : []
	},
	
	copyFiles : function (event) {
		event.stop();
		if (!this.hasClassName('active')) {
			//not currently active, don't bother proceeding
			return;
		}
		
		//set clipboard action to copy
		var isCut = (this.identify() == 'designSelected_cut');
		
		if (isCut && geoDesignManage.listData.t_set == 'default') {
			//force it to be copy, can't cut from efault
			isCut = false;
		}
		
		geoDesignManage.clipBoard.action = (isCut)? 'cut' : 'copy';
		
		var addLocal = false;
		//set the "from" location
		if (geoDesignManage.listData.t_type == 'main_page' || geoDesignManage.listData.t_type == 'external') {
			geoDesignManage.clipBoard.from = geoDesignManage.listData.currentLocation;
			//hide box about copying from system/module/addon templates
			$('systemCopyWarningBox').hide();
		} else {
			//can't just copy from and to anywhere...
			geoDesignManage.clipBoard.from = geoDesignManage.listData.t_set+'/'+geoDesignManage.listData.t_type+'/';
			addLocal = true;
			//show box about copying from system/module/addon templates
			$('systemCopyWarningBox').show()
				.select('span.opyReplace').each (function (elem) {
					elem.update((isCut)? 'ut':'opy');
				});
		}
		geoDesignManage.clipBoard.tType = geoDesignManage.listData.t_type;
		geoDesignManage.clipBoard.tSet = geoDesignManage.listData.t_set;
		
		//clear out any current files in clipboard, just in case there are some lingering
		geoDesignManage.clipBoard.files = [];
		$('designClipboard_files').update('');
		
		$('clipTypeSpan').update(geoDesignManage.clipBoard.action+' from <em>'+geoDesignManage.clipBoard.from+'</em>');
		
		//clear any cutFileRow classes from tr's to reset it
		$$('tr.cutFileRow').each(function (element) {
			element.removeClassName('cutFileRow');
		});
		
		//get all files currently selected
		geoDesignManage.selectedFiles.each(function (element){
			var index = element.value;
			var fileData = geoDesignManage.listData.files[index];
			if (!fileData) {
				//shouldn't happen, just sanity check
				return;
			}
			var filename = fileData.filename;
			if (fileData.is_dir) {
				filename += '/';
			}
			if (addLocal) {
				//one of the ones that must be copied to a specific spot
				filename = geoDesignManage.listData.t_localFile+filename;
			}
			
			$('designClipboard_files').insert(filename+'<br />');
			
			geoDesignManage.clipBoard.files[geoDesignManage.clipBoard.files.length] = filename;
			
			if (isCut) {
				//add class to cut
				element.up().up().addClassName('cutFileRow');
			}
		});
		
		//if count of selected files is 0, clear out files displayed, and hide the clip-board box
		if (geoDesignManage.clipBoard.files.length == 0) {
			$('designClipboard').hide();
		} else {
			$('designClipboard').show();
		}
	},
	
	toggleAllFiles : function (checked) {
		$$('input.fileListCheckbox').each(function (element) {
			if (geoDesignManage.isRestrictedFolder(element.up().up())) {
				//do not change this one, in fact, make sure it is unchecked
				element.checked = false;
				return;
			}
			
			element.checked = checked;
		});
	},
	
	isRestrictedFolder : function (element) {
		var isRestricted = false;
		if (element.hasClassName('restrictedAttachments')) {
			//attachments folder, cannot edit
			isRestricted = true;
		}
		if (element.hasClassName('restrictedTset')) {
			//TSET folder, cannot edit
			isRestricted = true;
		}
		if (element.hasClassName('hiddenTset')) {
			//TSET hidden folder, cannot edit
			isRestricted = true;
		}
		if (element.hasClassName('tempTset')) {
			//temp folder, cannot edit
			isRestricted = true;
		}
		if (element.hasClassName('restrictedTset_file')) {
			//t_sets.php file, special case, only "file" that shows as restricted
			isRestricted = true;
		}
		return isRestricted;
	},
	listData : {},
	
	/**
	 * a#id : baseUrl
	 */
	linkies : {
		refreshListLink : 'index.php?page=design_manage&location=',
		newFolderLinky : 'index.php?page=design_new_folder&location=',
		newFileLinky : 'index.php?page=design_new_file&location=',
		uploadFileLinky : 'index.php?page=design_upload_file&location='
	},
	
	editClicked : function (event) {
		event.stop();
		if (!this.hasClassName('active')) {
			//not currently active, don't bother proceeding
			return;
		}
		if (geoDesignManage.selectedFiles.length != 1) {
			//more than 1 selection
			return;
		}
		//figure out what file we are editing
		
		//get the only thing in the selected array
		index = geoDesignManage.selectedFiles[0].getValue();
		var filename = geoDesignManage.listData.files[index].filename;
		
		var url = 'index.php?page=design_edit_file&location='+escape(geoDesignManage.listData.currentLocation)+'&file='+escape(geoDesignManage.listData.currentLocation+filename);
		window.location = url;
	},
	
	downloadClicked : function (event) {
		event.stop();
		if (!this.hasClassName('active')) {
			//not currently active, don't bother proceeding
			return;
		}
		if (geoDesignManage.selectedFiles.length != 1) {
			//more than 1 selection
			return;
		}
		//figure out what file we are editing
		
		//get the only thing in the selected array
		index = geoDesignManage.selectedFiles[0].getValue();
		var filename = geoDesignManage.listData.currentLocation+geoDesignManage.listData.files[index].filename;
		
		var downloadForm = $('downloadForm');
		downloadForm.action = 'index.php?page=design_download_file&location='+escape(geoDesignManage.listData.currentLocation);
		$('downloadFileInput').setValue(filename);
		downloadForm.submit();
	},
	
	pasteClicked : function (event) {
		event.stop();
		if (!this.hasClassName('active')) {
			//not currently active, don't bother proceeding
			return;
		}
		//easier var to work with
		var cb = geoDesignManage.clipBoard;
		
		if (cb.files.length == 0) {
			//nothing to paste
			return;
		}
		
		
		var url='index.php?page=design_copy_files&actionType='+cb.action;
		
		url += '&location='+escape(geoDesignManage.listData.currentLocation);
		url += '&fromFolder='+escape(cb.from);
		if (cb.tType == 'main_page' || cb.tType == 'external') {
			//to folder will be current location
			url += '&toFolder='+escape(geoDesignManage.listData.currentLocation);
		} else {
			//to folder will be t_set + t_type
			url += '&toFolder='+escape(geoDesignManage.listData.t_set+'/'+cb.tType+'/');
		}
		
		cb.files.each(function (filename) {
			url += '&files[]='+escape(filename);
		});
		
		jQuery(document).gjLightbox('get',url);
	},
	
	renameClicked : function (event) {
		event.stop();
		
		if (geoDesignManage.selectedFiles.length != 1) {
			//more/less than 1 selection
			return;
		}
		//figure out what file we are editing
		
		//get the only thing in the selected array
		index = geoDesignManage.selectedFiles[0].getValue();
		var filename = geoDesignManage.listData.files[index].filename;
		
		var defaults = this.identify().substring(15);
		
		var url = 'index.php?page=design_rename_file&defaults='+defaults+'&location='+escape(geoDesignManage.listData.currentLocation)+'&file='+escape(geoDesignManage.listData.currentLocation+filename);
		jQuery(document).gjLightbox('get',url);
	},
	
	deleteClicked : function (event) {
		event.stop();
		
		if (geoDesignManage.selectedFiles.length == 0) {
			//more than 1 selection
			return;
		}
		//figure out what file we are editing
		var url = 'index.php?page=design_delete_files&location='+escape(geoDesignManage.listData.currentLocation);
		
		geoDesignManage.selectedFiles.each(function (element) {
			index = element.getValue();
			var filename=geoDesignManage.listData.files[index].filename;
			url += '&files[]='+escape(geoDesignManage.listData.currentLocation+filename);
		});
		
		jQuery(document).gjLightbox('get',url);
	},
	cleanEditorContents : '',
	editorPreContents : '',
	editorPostContents : '',
	wysiwygLoading : false,
	switchingTabs : false,
	
	parseContentsPrePost : function () {
		//get everything up to <body ... > and store it in pre
		var parsed = {
			'pre' : '',
			'post' : '',
			'contents' : ''
				
		};
		parsed.contents = $('tplContents').getValue();
		var findIndex = parsed.contents.search(/<body( [^>]*>|>)/i); 
		if (findIndex != -1) {
			//move the index over to the end of the <body> tag
			findIndex += parsed.contents.substring(findIndex).indexOf('>')+1;
			//set the pre and the new contents
			parsed.pre = parsed.contents.substring(0,findIndex)+"\n";
			
			parsed.contents = parsed.contents.substring(findIndex);
		}
		
		findIndex = parsed.contents.search(/<\/body( [^>]*>|>)/i);
		if (findIndex != -1) {
			parsed.post = "\n"+parsed.contents.substring(findIndex);
			
			parsed.contents = parsed.contents.substring(0,findIndex);
		}
		return parsed;
	},
	
	readOnly : false,
	
	editorDesignTabClick : function (action) {
		var isTabCallback = (typeof action != 'undefined');
		
		if (!isTabCallback) {
			//This function was called directly instead of by callback, so do a few
			//checks
			if (!$('designTab') || $('designTab').hasClassName('activeTab')) {
				//nothing to do, it's already active tab.
				
				return;
			}
			if (geoDesignManage.switchingTabs) {
				//don't do anything while in middle of switching tabs
				return;
			}
		}
		
		geoDesignManage.switchingTabs = true;
		
		if (geoDesignManage.codeMirror) {
			geoDesignManage.codeMirror.toTextArea();
			geoDesignManage.codeMirror = null;
		}
		
		//save the "un-cleaned" state of things with the editor
		geoDesignManage.cleanEditorContents = $('tplContents').getValue();
		
		var parsed = geoDesignManage.parseContentsPrePost();
		
		var contents = parsed.contents;
		var pre = parsed.pre;
		var post = parsed.post;
		
		
		geoDesignManage.readOnly = $('tplContents').getAttribute('readonly');
		
		
		var wysiwygSafe = true; 
		
		if (geoDesignManage.cleanEditorContents.indexOf('{*CODE_ONLY*}') != -1) {
			//if it has {*CODE_ONLY*} in there
			wysiwygSafe = false;
		}
		
		//see if contents has any head, body, or html tags in it
		if (contents.search(/<\/?(body|head|html)( [^>]*>|>)/i) != -1) {
			//there was a body, a head, or an html open/close tag in the main contents,
			//it is not safe to use the wysiwyg editor.
			wysiwygSafe = false;
		}
		
		if (!wysiwygSafe) {
			geoUtil.addMessage('It is not safe to edit this template with the WYSIWYG editor, as it contains parts that may be corrupted by the editor.');
			geoDesignManage.switchingTabs = false;
			geoDesignManage.editorCodeTabClick();
			geoDesignManage.cleanEditorContents = '';
			return;
		}
		
		$('tplContentsPre').setValue(pre);
		$('tplContentsPost').setValue(post);
		$('tplContents').setValue(contents);
		
		$('editorContents').show();
		//hide the tools box
		$('editTemplateButtons').hide();
		$('editNotes').hide();
		
		if (!isTabCallback) {
			$('designTab').addClassName('activeTab');
			$('codeTab').removeClassName('activeTab');
		}
		
		//turn on WYSIWYG editor
		geoWysiwyg.loadTiny();
		document.cookie = 'tinyMCE=on';
		if (tinyMCE.activeEditor) {
			//already active editor, so the close stuff won't happen on it's own
			geoDesignManage.switchingTabs = false;
		} else {
			//load editor
			tinyMCE.execCommand('mceAddControl', false, 'tplContents');
			//the rest happens in the setup callback...
		}
	},
	
	editorCodeTabClick : function (action) {
		var isTabCallback = (typeof action != 'undefined');
		
		if (!isTabCallback) {
			if ($('codeTab').hasClassName('activeTab')) {
				//nothing to do, it's already active tab.
				return;
			}
			if (geoDesignManage.switchingTabs) {
				return;
			}
		}
		
		geoDesignManage.switchingTabs = true;
		
		$('editorContents').show();
		
		var restoreOriginal = false;
		
		//show the tools box
		$('editTemplateButtons').show();
		$('editNotes').show();
		
		if (!isTabCallback) {
			$('codeTab').addClassName('activeTab');
		}
		if ($('designTab')) {
			if (tinyMCE.activeEditor && !tinyMCE.activeEditor.isDirty()) {
				//not dirty, meaning no changes made to contents, so restore un-cleaned
				
				restoreOriginal = true;
			}
			//turn off WYSIWYG editor
			document.cookie = 'tinyMCE=off';
			tinyMCE.execCommand('mceRemoveControl', false, 'tplContents');
			if (!isTabCallback) {
				$('designTab').removeClassName('activeTab');
			}
		}
		
		
		if (restoreOriginal) {
			$('tplContents').setValue(geoDesignManage.cleanEditorContents);
		} else if ($('tplContentsPre').getValue() || $('tplContentsPost').getValue()) {
			//re-assemple contents from pre and post
			var contents = $('tplContentsPre').getValue() + $('tplContents').getValue() + $('tplContentsPost').getValue();
			$('tplContents').setValue(contents);
		}
		//reset the pre and post
		$('tplContentsPre').setValue('');
		$('tplContentsPost').setValue('');
		if (geoDesignManage.useCodeMirror && !geoDesignManage.codeMirror) {
			geoDesignManage.initCodeMirror();
		}
		geoDesignManage.switchingTabs = false;
	},
	
	attachmentsTabClicked : function ()
	{
		$('editorContents').hide();
	},
	
	attachedToTabClicked : function ()
	{
		if ($('attachedToTab').hasClassName('activeTab')) {
			//nothing to do, it's already active tab.
			return;
		}
		if (geoDesignManage.switchingTabs) {
			return;
		}
		
		geoDesignManage.switchingTabs = true;
		
		$('attachedToTabContents').show();
		$('attachedToTab').addClassName('activeTab');
		
		$('editorContents').hide();
		$('codeTab').removeClassName('activeTab');
		if ($('designTab')) {
			$('designTab').removeClassName('activeTab');
		}
		
		if ($('attachmentsTab')) {
			$('attachmentsTab').removeClassName('activeTab');
			$('attachmentsTabContents').hide();
		}
		
		geoDesignManage.switchingTabs = false;
	},
	
	initEditor : function () {
		if (!$('codeTab')) {
			//nothing to init
			return;
		}
		
		//We are letting geoTabs do normal tab-related work, but augmented some
		//to prevent errors due to slow-loading tab contents.
		
		//set up tab callbacks, since it only called as callback, if that
		//tab does not exist, no errors
		geoTabs.tabCallbacks.designTab = geoDesignManage.editorDesignTabClick;
		geoTabs.tabCallbacks.codeTab = geoDesignManage.editorCodeTabClick;
		
		var closeEditorTab = function () { $('editorContents').hide(); };
		geoTabs.tabCallbacks.attachmentsTab = closeEditorTab;
		geoTabs.tabCallbacks.attachedToTab = closeEditorTab;
		
		//make sure not switching tabs before allowing changing tabs...
		geoDesignManage._oldTabPrecheck = geoTabs.precheck;
		geoTabs.precheck = function (elem) {
			return (geoDesignManage._oldTabPrecheck(elem) && !geoDesignManage.switchingTabs);
		};
		
		if ($('designTab') && geoUtil.getCookie('tinyMCE') != 'off') {
			//show design tab by default
			
			if ($('tplContents').getValue().strip() != $('contentsUntouched').getValue().strip()) {
				//this should be fun, they hit refresh, which cleared the pre and post values
				var refreshedData = geoDesignManage.parseContentsPrePost();
				var refreshedContents = refreshedData.contents;
				
				$('tplContents').setValue($('contentsUntouched').getValue());
				//now have it pars pre and post
				var contentData = geoDesignManage.parseContentsPrePost();
				//now put it all back together
				$('tplContents').setValue(contentData.pre + refreshedContents + contentData.post);
			}
			
			geoDesignManage.editorDesignTabClick();
		} else {
			if ($('tplContents').getValue().strip() != $('contentsUntouched').getValue().strip()) {
				$('tplContents').setValue($('contentsUntouched').getValue());
			}
			geoDesignManage.editorCodeTabClick();
		}
	},
	
	initEditorButtons : function (ed) {
		//add custom download button
		ed.addButton('geoDownload', {
			title: 'Download File',
			image : 'admin_images/icons/download.png',
			onclick : function () {
				geoDesignManage.popupBox('downloadTemplate_box', 'popupButtonHook');
			}
		});
		
		ed.addButton('geoUpload', {
			title: 'Upload File',
			image : 'admin_images/icons/upload.png',
			onclick : function () {
				geoDesignManage.popupBox('uploadTemplate_box', 'popupButtonHook');
			}
		});
		
		ed.addButton('geoSave', {
			title: 'Save Changes',
			image : 'admin_images/icons/save.png',
			onclick : function () {
				if ($('fileEditForm')) {
					$('fileEditForm').submit();
				}
			}
		});
		
		ed.addButton('geoRestore', {
			title: 'Restore Default Contents',
			image: 'admin_images/icons/restore.png',
			onclick : function () {
				geoDesignManage.popupBox('restoreDefault_box', 'popupButtonHook');
			}
		});
		
		ed.addButton('geoTags', {
			title: 'Insert Tag',
			image : 'admin_images/icons/insert-tag.png',
			onclick : function () {
				geoDesignManage.popupBox('insertTag_box', 'popupButtonHook');
			}
		});
		
		ed.onInit.add(function(ed) {
			//editor is done, mark it as not dirty
			ed.startContent = ed.getContent({format : 'raw', no_events : 1});
		});
		var pre = $('tplContentsPre').getValue();
		if (pre) {
			//parse pre for <link ... stylesheet tags
			var styleSheets = ed.settings.content_css.split(',');
			var foundTags = pre.match(/<link[^>]* type=('|")text\/css('|")[^>]*>/ig);
			if (foundTags!==null) {
				foundTags.each(function (tag) {
					//trim off everything around the href value, we're just after the css url
					tag = tag.replace(/^<link[^>]* href=('|")/i,'');
					//replace {external ...} with external location
					var externals = tag.match(/\{external[^}]*\}/ig);
					var external = '';
					if (externals !== null) {
						//assume there is only one
						external = externals[0];
						
						external = external.replace(/^\{[^}]* file=('|")/i,'')
							.replace(/('|").*$/,'');
						
						tag = 'get_external.php?file='+escape(external);
					} else {
						
						tag = tag.replace(/('|").*$/,'');
						if (external) {
							//convert to get_external.php?file=/file/
							external = external.replace(/^\{[^}]* file=('|")/i,'');
							
							
							//put the original {external ...} tag back in there.
							tag = tag.replace('{external}',external);
						}
					}
					
					if (styleSheets.indexOf(tag) == -1) {
						//css url not in list yet, so add it
						styleSheets[styleSheets.length] = tag;
					}
				});
				if (styleSheets) {
					//put the list of css files back together again.
					ed.settings.content_css = styleSheets.toString();
				}
			}
		}
		
		
			
		//convert {external ...} in main parts
		ed.onBeforeSetContent.add(function (ed, o) {
			//convert {external file=""}
			
			var foundTags = o.content.match(/\{external [^}]*\}/ig);
			if (foundTags!==null) {
				foundTags.each(function (tag) {
					//trim off everything around the href value, we're just after the css url
					var replacement = tag.replace(/^\{[^}]* file=('|")/i,'')
						.replace(/('|").*$/,'');
					replacement = 'get_external.php?file='+escape(replacement)+'&endExternal';
					o.content = o.content.replace(tag, replacement);
				});
			}
		});
		ed.onGetContent.add(function (ed, o) {
			//replace get_external.php?file= with {external ...}
			var foundTags = o.content.match(/get_external\.php\?file=.+?\&(amp\;)?endExternal/ig);
			if (foundTags!==null) {
				foundTags.each(function (tag) {
					//trim off everything around the href value, we're just after the css url
					var replacement = tag.replace(/^get_external\.php\?file=/i,'')
						.replace(/&(amp\;)?endExternal$/,'');
					replacement = '{external file=\''+replacement+'\'}';
					o.content = o.content.replace(tag, replacement);
				});
			}
		});
		if (!geoDesignManage.wysiwygLoading && geoDesignManage.readOnly) {
			//the design click will be called twice, first time it goes through
			//here if it is readonly, second time it does not go through here.
			geoDesignManage.wysiwygLoading = true;
			
			//set as readonly if it is readonly
			
			ed.settings.readonly = 1;
			
			geoDesignManage.switchingTabs = false;
			//un-load, then re-load
			geoDesignManage.editorCodeTabClick();
			geoDesignManage.editorDesignTabClick();
			
			//make it so it is not loaded any more...
			geoDesignManage.wysiwygLoading = false;
		}
		geoDesignManage.switchingTabs = false;
	},
	fileListLink_click : function (event) {
		event.stop();
		if (this.up().up().up().hasClassName('cutFileRow')) {
			geoUtil.addMessage('This folder is currently cut!  You must clear the file clipboard before you can enter the directory.');
			return;
		}
		
		geoDesignManage.fileListLink(this.href,true);
	},
	
	initManage : function () {
		if (typeof fileListData != 'undefined' && fileListData) {
			geoDesignManage.listData = fileListData;
			fileListData = false;
		}
		geoDesignManage.initEditor();
		//in case any are starting out checked:
		geoDesignManage.updateSelections();
		
		//watch the column sort headers for clicks
		$$('a.fileListLink').each (function (element) {
			var parent = element.up().up().up();
			if (geoDesignManage.isRestrictedFolder(parent)) {
				//restricted folder, cannot visit link
				return;
			}
			element.observe('click', geoDesignManage.fileListLink_click);
		});
		
		//make clicking on any rows check the box and highlight the row
		$$('table.fileListTable tr.fileEntry').each(function (element) {
			if (geoDesignManage.isRestrictedFolder(element)) {
				return;
			}
			
			//watch it for clicking, to auto-select the box thingy
			var currentCol = element.down();
			while (currentCol) {
				currentCol.observe('click', function (event) {
					var checkElem = $(this.up().identify()+'_checkbox');
					//event.stop();
					if (!checkElem) {
						//nothing to do
						
						return;
					}
					
					if (this.hasClassName('checkboxColumn')) {
						if (!event.findElement('.fileListCheckbox')) {
							checkElem.checked = !checkElem.checked;
						}
					} else {
						//un-select all checkboxes
						geoDesignManage.toggleAllFiles(false);
						
						//now select ours
						checkElem.checked = true;
					}
					geoDesignManage.updateSelections();
				});
				
				currentCol = currentCol.next();
			}
		});
		
		//disable any columns that are disabled t-sets
		$$('tr.restrictedTset', 'tr.hiddenTset', 'tr.tempTset', 'tr.restrictedAttachments', 'tr.restrictedTset_file').each(function (element) {
			element.stopObserving()
				.addClassName('disabled')
				.observe('click', geoDesignManage.restrictedClick);
			element.select('a').each(function (linkElem) {
				//stop clicks on links too
				//also set disabled class so it over-rides other css
				linkElem.stopObserving()
					.observe('click',geoDesignManage.restrictedClick);
			});
			//fade the images
			element.select('img').each(function (imgElem) {
				imgElem.setOpacity(0.3);
			});
		});
		if ($('newFolderLinky')) {
			//disable/enable any nav links
			if (!geoDesignManage.listData.canCreateFolder) {
				//cannot create folder...
				$('newFolderLinky').setOpacity(0.3);
			} else {
				$('newFolderLinky').setOpacity(1.0);
			}
			if (!geoDesignManage.listData.canCreateFile) {
				//cannot create file...
				$('newFileLinky').setOpacity(0.3);
			} else {
				$('newFileLinky').setOpacity(1.0);
			}
			
			if (!geoDesignManage.listData.canUploadFile) {
				$('uploadFileLinky').setOpacity(0.3);
			} else {
				$('uploadFileLinky').setOpacity(1.0);
			}
			
			if (geoDesignManage.listData) {
				var t_set = geoDesignManage.listData.t_set;
			}
			
			//watch the toggle thingy
			$('fileListCheckAllToggle').observe('click',function (event) {
				geoDesignManage.toggleAllFiles(this.checked);
				geoDesignManage.updateSelections();
			});
			
			//watch copy button
			$('designSelected_copy').observe('click',geoDesignManage.copyFiles);
			//watch cut button
			$('designSelected_cut').observe('click',geoDesignManage.copyFiles);
			//watch clear clipboard button
			$('clearClipboardButton').observe('click',function () {
				geoDesignManage.clipBoard.files = [];
				$('designClipboard').hide();
				$$('tr.cutFileRow').each(function (element) {
					element.removeClassName('cutFileRow');
				});
			});
			//watch edit button
			$('designSelected_edit').observe('click', geoDesignManage.editClicked);
			//watch rename button
			$('designSelected_rename').observe('click', geoDesignManage.renameClicked);
			//watch make copy button
			$('designSelected_make_copy').observe('click', geoDesignManage.renameClicked);
			//watch delete button
			$('designSelected_del').observe('click', geoDesignManage.deleteClicked);
			//watch download button
			$('designSelected_download').observe('click',geoDesignManage.downloadClicked);
			//watch paste button
			$('designSelected_paste').observe('click',geoDesignManage.pasteClicked);
			if (geoDesignManage.clipBoard.action == 'cut' && geoDesignManage.clipBoard.files.length
					&& geoDesignManage.clipBoard.tSet == geoDesignManage.listData.t_set
					&& geoDesignManage.clipBoard.tType == geoDesignManage.listData.t_type) {
				//there are cut files
				var baseDir = geoDesignManage.clipBoard.from.sub(geoDesignManage.clipBoard.tSet+'/'+geoDesignManage.clipBoard.tType+'/','');
				
				var fileKeys = Object.keys(geoDesignManage.listData.files);
				
				geoDesignManage.clipBoard.files.each (function (filename){
					//get rid of ending slashy
					filename = filename.sub(/\/$/,'');
					
					var local = baseDir+filename.sub(/[^\/]+$/,'');
					
					if (local == geoDesignManage.listData.t_localFile) {
						var rowFound = false;
						
						fileKeys.each(function (key){
							if (geoDesignManage.listData.files[key].filename==filename) {
								$('fileListRow_'+key).addClassName('cutFileRow');
							}
						});
					}
				});
			}
		}
		
		if ($('refreshListLink')) {
			//update all the data on the page
			
			//update links
			Object.keys(geoDesignManage.linkies).each(function (linkyId) {
				var linkyUrl = geoDesignManage.linkies[linkyId];
				$(linkyId).href = linkyUrl + geoDesignManage.listData.currentLocation;
			});
			
			if ($('totalSizeSpan')) {
				$('totalSizeSpan').update(geoDesignManage.listData.totalSize);
				$('folderCountSpan').update(geoDesignManage.listData.folderCount);
				$('fileCountSpan').update(geoDesignManage.listData.fileCount);
			}
			
			if ($('templateSetSpan')) {
				$('templateSetSpan').update(t_set);
			}
			if ($('breadcrumb') && geoDesignManage.listData.locationParts) {
				//populate breadcrumb
				var bcrumb = $('breadcrumb');
				//add the first entry in there
				bcrumb.update (
					new Element('li', {'class' : 'current'}).update('Current Folder')
				);
				
				var allData = Object.values(geoDesignManage.listData.locationParts);
				allData.each (function (data) {
					var location = data.location;
					if (data.showLink) {
						location = new Element('a', {
							href : 'index.php?page=design_manage&location='+data.fullPath
						}).update(location)
							.observe('click', geoDesignManage.fileListLink_click);
					}
					var newLi = new Element('li', {'title' : data.title}).update(location);
					if (data.endPath) {
						newLi.addClassName('current2');
					}
					
					bcrumb.insert(newLi);
				});
			}
			
			var currentLocation = geoDesignManage.listData.currentLocation;
			if (currentLocation.length==0) {
				currentLocation = 'Template Sets';
			}
			//$('currentLocationSpan').update(currentLocation)
			//	.href = 'index.php?page=design_manage&location='+geoDesignManage.listData.currentLocation;
		}
		if ($('tsetJumpInside')) {
			//watch nav links
			
			if (t_set != 'n/a') {
				//inside a template set
				$('tset_jump_box').hide();
				
				//update link href's
				$('navLink_main_page').href = 'index.php?page=design_manage&location='+t_set+'/main_page/';
				$('navLink_system').href = 'index.php?page=design_manage&location='+t_set+'/system/';
				$('navLink_external').href = 'index.php?page=design_manage&location='+t_set+'/external/';
				$('navLink_module').href = 'index.php?page=design_manage&location='+t_set+'/module/';
				$('navLink_addon').href = 'index.php?page=design_manage&location='+t_set+'/addon/';
				
				//update text
				var titleText = 'Folders in:<div class="foldersInTsetName">'+t_set+'</div>';
				//version safe for title attrib.
				var titleTitleText = 'Folders in: '+t_set+':';
				$('insideJumpTitle').update('<span title="'+titleTitleText+'">'+titleText+'</span>');
				
				//hide ones that do not exist
				if (geoDesignManage.listData.main_page_exists==1) {
					$('navLink_main_page').up().show();
					if (geoDesignManage.listData.t_type=='main_page') {
						$('navLink_main_page').up().addClassName('current');
					} else {
						$('navLink_main_page').up().removeClassName('current');
					}
					$('navLink_main_page').select('img')[0].writeAttribute({
						src : 'admin_images/icons/folder-'+((geoDesignManage.listData.t_type=='main_page')? 'open':'closed')+'.png'
					});
				} else {
					$('navLink_main_page').up().hide();
				}
				if (geoDesignManage.listData.system_exists==1) {
					$('navLink_system').up().show();
					if (geoDesignManage.listData.t_type=='system') {
						$('navLink_system').up().addClassName('current');
					} else {
						$('navLink_system').up().removeClassName('current');
					}
					$('navLink_system').select('img')[0].writeAttribute({
						src : 'admin_images/icons/folder-'+((geoDesignManage.listData.t_type=='system')? 'open':'closed')+'.png'
					});
				} else {
					$('navLink_system').up().hide();
				}
				if (geoDesignManage.listData.external_exists==1) {
					$('navLink_external').up().show();
					if (geoDesignManage.listData.t_type=='external') {
						$('navLink_external').up().addClassName('current');
					} else {
						$('navLink_external').up().removeClassName('current');
					}
					$('navLink_external').select('img')[0].writeAttribute({
						src : 'admin_images/icons/folder-'+((geoDesignManage.listData.t_type=='external')? 'open':'closed')+'.png'
					});
				} else {
					$('navLink_external').up().hide();
				}
				
				if (geoDesignManage.listData.module_exists==1) {
					$('navLink_module').up().show();
					if (geoDesignManage.listData.t_type=='module') {
						$('navLink_module').up().addClassName('current');
					} else {
						$('navLink_module').up().removeClassName('current');
					}
					$('navLink_module').select('img')[0].writeAttribute({
						src : 'admin_images/icons/folder-'+((geoDesignManage.listData.t_type=='module')? 'open':'closed')+'.png'
					});
				} else {
					$('navLink_module').up().hide();
				}
				if (geoDesignManage.listData.addon_exists==1) {
					$('navLink_addon').up().show();
					if (geoDesignManage.listData.t_type=='addon') {
						$('navLink_addon').up().addClassName('current');
					} else {
						$('navLink_addon').up().removeClassName('current');
					}
					$('navLink_addon').select('img')[0].writeAttribute({
						src : 'admin_images/icons/folder-'+((geoDesignManage.listData.t_type=='addon')? 'open':'closed')+'.png'
					});
				} else {
					$('navLink_addon').up().hide();
				}
				//show the list
				$('tsetJumpInside').show();
			} else {
				//at all template sets level
				$('tsetJumpInside').hide();
				
				if ($('tset_jump_box') && !$('tset_jump_box').visible() && geoDesignManage.listData.tsetListJumpHtml) {
					$('tset_jump_box').update(geoDesignManage.listData.tsetListJumpHtml)
						.show()
						.select('a').each(function (element) {
							element.observe('click',geoDesignManage.fileListLink_click);
						});
				}
			}
		}
		
		$$('.tset_active').each(function (elem) {
			elem.observe('click',geoDesignManage.tset_active_click);
		});
	},
	
	initCodeMirror : function () {
		geoDesignManage.codeMirror = CodeMirror.fromTextArea($('tplContents'),
				{
					lineNumbers: true,
					mode: geoDesignManage.codeMirrorMode,
					indentWithTabs: true,
					indentUnit: 4,
					readOnly: geoDesignManage.readOnly,
					theme: geoDesignManage.codeMirrorTheme,
					smartIndent: geoDesignManage.codeMirrorSmartIndent
				});
	},
	
	tset_active_click : function () {
		var langSelect = this.up('tr').down('select');
		
		if (langSelect) {
			langSelect[(this.checked)? 'show':'hide']();
		}
	},
	
	previewFilename : '',
	previewRequest : null,
	previewLarge : '',
	previewFileType : '',
	tinyCurrentlyLoaded : false,
	tinyCurrentlyLoadedLarge : false,
	codeMirrorMode : 'text/html',
	codeMirrorTheme : 'default',
	codeMirrorSmartIndent : true,
	useCodeMirror : false,
	codeMirror : null,
	
	previewResponse : function (response) {
		var data = response.responseJSON;
		if (!data) {
			//some error?
			geoUtil.addError('Invalid response for previewing file!');
			
			return;
		}
		if (data.file && data.file!=geoDesignManage.previewFilename) {
			//must have already requested this then moved on
			
			return;
		}
		$('designPreviewLoading').hide();
		if (data.error) {
			geoUtil.addError(data.error);
			return;
		}
		
		geoDesignManage.previewFileType = data.fileType;
		//store it so we can display it in larger box
		geoDesignManage.previewLarge = data.previewFull;
		
		$('designPreviewMain').update(data.contents);
		
		$('mainPreviewWindow').update(data.preview);

		$('mainPreviewWindowLargeContents').update(geoDesignManage.previewLarge);
		
		$('designPreviewMain').show();
		
		
		$('designEmptyPreview').hide();
		
		//watch preview box for clicks
		$('mainPreviewWindow').observe('click',function () {
			jQuery(document).gjLightbox('open',jQuery('#mainPreviewWindowLarge'));
		});
	},
	updatePreview : function () {
		if (!$('designPreviewMain')) {
			return;
		}
		var selectedCount = geoDesignManage.selectedFiles.length;
		if (selectedCount == 1) {
			//Get preview box
			var index = geoDesignManage.selectedFiles[0].getValue();
			var filename=geoDesignManage.listData.files[index].filename;
			
			filename = geoDesignManage.listData.currentLocation+filename;
			
			if (geoDesignManage.listData.files[index].is_dir) {
				filename += '/';
			}
			
			if (geoDesignManage.previewFilename == filename) {
				//already previewing this file
				return;
			}
			var href = 'index.php?page=design_preview_file&json=1&file='+escape(filename);
			geoDesignManage.previewFilename = filename;
			new Ajax.Request(href, {
				onComplete : geoDesignManage.previewResponse
			});
			
			$('designPreviewLoading').show();
			
		} else {
			geoDesignManage.previewFilename = '';
			$('designPreviewMain').hide();
			$('designEmptyPreviewLabel').update(((selectedCount>0)? 'Multiple':'None')+' Selected');
			$('designEmptyPreview').show();
		}
	},
	
	listRefreshed : function (response) {
		response.responseText.evalScripts();
		geoDesignManage.initManage();
		//stop race condition, where it polls in between the line that
		//sets windows.location.hash and the next line, causing a double-refresh...
		geoDesignManage.hashChanging = true;
		
		var hashParams = window.location.hash.replace(/^#/,'').toQueryParams();
		
		//so that we can more easily add additional aprams that are tracked via hash,
		//go ahead and use query param structure for it.
		
		hashParams.location = geoDesignManage.listData.currentLocation;
		
		//set the hash, to allow backwards and forwards in browser windows
		window.location.hash = Object.toQueryString(hashParams);
		geoDesignManage.recentHash = window.location.hash;
		
		geoDesignManage.hashChanging = false;
		
		$('refreshFilelistBox').hide();
		$('designFileList').setOpacity(1.0)
			.stopObserving();
	},
	
	centerRefreshBox : function () {
		var oWidth = $('refreshFilelistBox').up().getDimensions().width;
		var iWidth = $('refreshFilelistBox').getDimensions().width;
		if (iWidth < 100) {
			//not loaded or something, pretend width is 100
			iWidth = 110;
		}
		if (oWidth < 500) {
			//not fully loaded or something, pretend width is 500
			oWidth = 500;
		}
		var left = Math.floor((oWidth - iWidth)/2);
		if (left < 10) {
			//force it to be at least 10 over
			left = 10;
		}
		$('refreshFilelistBox').setStyle({left: left+'px'});
	},
	
	changeModeSubmit : function (event) {
		event.stop();
		
		this.request({
			onComplete: function (response) {
				var data = response.responseJSON;
				
				var extra = '<br /><br />Please wait while the page <a href="#" onclick="geoUtil.refreshPage()">re-loads</a>.';
				var isOK = true;
				if (!data) {
					geoUtil.addError('Server error, please try again.'+extra);
				} else if (data.error) {
					geoUtil.addError(data.error+extra);
				} else if (data.message) {
					geoUtil.addMessage(data.message+extra);
				}
				//wait 2 seconds, then re-load the page
				setTimeout(geoUtil.refreshPage, 2000);
			}
		});
		
		jQuery(document).gjLightbox('close');
	},
	
	fileListLink : function (href, forceRefresh) {
		if (!forceRefresh) {
			//not forcing a refresh, see if where we are going is where we already are...
			var currentLocation = 'index.php?page=design_manage&location='+geoDesignManage.listData.currentLocation;
			if (href == currentLocation) {
				//same location, not forcing refresh, so ignore this request.
				return;
			}
		}
		
		new Ajax.Updater('designFileList', href, {
			onComplete : geoDesignManage.listRefreshed
		});
		
		$('refreshFilelistBox').show();
		$('designFileList').setOpacity(0.2)
			.observe('click', function (event) { event.stop();});
		geoDesignManage.centerRefreshBox();
	},
	
	setListData : function (listData) {
		geoDesignManage.listData = listData;
	},
	recentHash : '',
	hashChanging : false,
	pollHash : function () {
		if (geoDesignManage.hashChanging || window.location.hash == geoDesignManage.recentHash) {
			//nothing changed since last we checked
			return;
		}
		geoDesignManage.recentHash = window.location.hash;
		
		var hashParams = window.location.hash.replace(/^#/,'').toQueryParams();
		
		if (typeof hashParams.location != 'undefined') {
			geoDesignManage.fileListLink('index.php?page=design_manage&location='+escape(hashParams.location),false);
		}
	}
};

Event.observe(window,'load',function () {
	//init the file list
	geoDesignManage.initManage();
	
	if ($('designMainWindow')) {
		//this is on manage page
		if (!window.location.hash) {
			//no hash set, set it to location
			var hashParams = {
				'location' : geoDesignManage.listData.currentLocation
			};
			window.location.hash = Object.toQueryString(hashParams);
		}
		
		//see if hash has changed every second or so
		setInterval(geoDesignManage.pollHash,1000);
	}
	
	//watch the template buttons
	$$('img.autoTemplateButton').each (function (element){
		element.observe('click', geoDesignManage.popupButtonObserve);
	});
	
	//watch the close link and cancel button for clicks
	$$('.closeBoxButton, .cancelButton').each (function (element) {
		element.observe('click',function () {
			var theBox = this.up();
			while (theBox && !theBox.hasClassName('templateTool') && theBox.up()) {
				theBox = theBox.up();
			}
			
			if (theBox && theBox.hasClassName('templateTool')) {
				//we got the right one!  woot!  Hide the box...
				theBox.hide();
			}
		});
	});
	
	//stuff for inserting tags
	if ($('subTplSelect')) {
		//sub templates insert
		$('subTplInsertButton').observe('click', function () {
			var tplFile = $('subTplSelect').getValue();
			if (tplFile=='none') {
				alert('Select a sub-template to insert the tag for!');
				return;
			}
			var tag = '{include file=\''+tplFile+'\'}';
			geoDesignManage.insertTag(tag);
		});
		//modules insert
		$('moduleInsertButton').observe('click', function () {
			var module = $('moduleTagSelect').getValue();
			if (module=='none') {
				alert('Select a module to insert the tag for!');
				return;
			}
			var tag = '{module tag=\''+module+'\'}';
			geoDesignManage.insertTag(tag);
		});
		if ($('addonTagSelect')) {
			//modules insert
			$('addonInsertButton').observe('click', function () {
				var tagInfo = $('addonTagSelect').getValue();
				
				if (tagInfo=='none') {
					alert('Select an addon tag to insert!');
					return;
				}
				tagInfo = tagInfo.split('.');
				
				var tag = '{addon author=\''+tagInfo[1]+'\' addon=\''+tagInfo[0]+'\' tag=\''+tagInfo[2]+'\'}';
				geoDesignManage.insertTag(tag);
			});
		}
		//special tags insert
		$$('.specialInsertButton').each(function(elem) {
			elem.observe('click', function () {
				var tag = this.previous().getValue();
				if (tag=='none') {
					alert('Select a special tag to insert!');
					return;
				}
				
				geoDesignManage.insertTag(tag);
			});
		});
		
		//external tags insert
		if ($('externalInsertButton')) {
			$('externalInsertButton').observe('click', function () {
				var tagInfo = $('externalTagSelect').getValue();
				
				if (tagInfo=='none') {
					alert('Select an external file to insert!');
					return;
				}
				
				var tag = '{external file=\''+tagInfo+'\'}';
				geoDesignManage.insertTag(tag);
			});
		}
	}
	//make download template button work
	if ($('downloadTplForm')) {
		$('downloadTplForm').observe('submit', function (event) {
			//get the current contents of the template and shove them into the "download" form.
			if ($('downloadSaveChanges').checked) {
				var contents = $('tplContents').getValue();
				if ($('designTab') && $('designTab').hasClassName('activeTab')) {
					//using tinymce editor
					contents = $('tplContentsPre').getValue() + tinyMCE.activeEditor.getContent() + $('tplContentsPost').getValue();
				}
				if (!contents) {
					alert('Cannot save/download an empty file!');
					event.stop();
					return;
				}
				$('downloadContentsInput').setValue(contents);
			}
		});
	}
});
