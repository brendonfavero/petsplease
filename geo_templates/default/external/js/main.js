// 7.2beta3-35-gb6c27fa

//OLD Main javascript used on most pages of the software.

//EVERYTHING IN THIS FILE IS DEPRECATED!!!
//We will be converting existing JS to use jQuery instead of Prototype.
//As we do, the JS will be moved into gjmain.js.  The below can be used for now
//but note that anything custom that you make use anything in this file, will
//need to be converted at some point in the future.


//NOTE:  you don't have to customize this JS file to change vars, instead add some
//JS to your own custom JS file (or in script tags on a template) that works like this:

/*
//change default duration for how long effects take
Event.observe(window,'load',function() {
	//change duration to last 1.5 seconds instead default of .3 seconds
	geoEffect.defaultDuration = 1.5;
});


 */

var geoUtil = {
	defaultParams : {duration: .8},
	inAdmin : false,
	runHeartbeat : false,
	
	init : function () {
		//A function that starts up all the common stuff.
		//This will be loaded after the page is done loading, 
		//no need for Event.observer(window,'load',...)
		
		if ($('extraQuestionName') && $('extraQuestionValue')) {
			//make sure extra question labels height match up with values height
			var values = $('extraQuestionValue').select('li');
			$('extraQuestionName').select('li').each(function(element, index) {
				if (element.getHeight()==this[index].getHeight()) {
					return;
				}
				//Dirty hack to hold over until we re-do the whole question/answer thing...
				//figure out which one is taller, then set that as height to both.
				var max=Math.max(element.getHeight(), this[index].getHeight());
				
				this[index].setStyle({ height : max+'px' });
				element.setStyle({ height : max+'px' });
			}, values);
		}
		
		//init the tag autocomplete, do it here so it is only done once
		geoUtil.initTagSearch();
		
		//init the instruction buttons
		geoUtil.instrBtns.init();
		
		//calendar view
		geoUtil.initCalendars();
		
		//tabs
		geoTabs.init();
	},
	//namespace for instruction button actions for show/hide instructions on
	//media collection step during listing process
	instrBtns : {
		_animating : false,
		
		//used to initialize any "instructions" buttons on the page, specifically
		//used during listing placement on media collection page
		init : function () {
			$$('.show_instructions_button').each (function (elem) {
				var descriptionBox = $(elem.identify()+'_box');
				if (!descriptionBox) {
					//no description box found for this one
					return;
				}
				descriptionBox.hide();
				
				elem.observe('click', geoUtil.instrBtns.buttonClicked);
			});
		},
		buttonClicked : function (action) {
			action.stop();
			if (geoUtil.instrBtns._animating) {
				//do not do more than one animation at once
				return;
			}
			
			if (geoEffect.useEffect()) {
				geoUtil.instrBtns.startUpAction();
				var params = {
						duration : geoEffect.defaultDuration,
						afterFinish : geoUtil.instrBtns.finishAction
						};
				
				Effect.toggle(this.identify()+'_box', 'blind', params);
			} else {
				geoUtil.instrBtns.startUpAction ();
				$(this.identify()+'_box').toggle();
				geoUtil.instrBtns.finishAction ();
			}
		},
		_callbacks_start : new Array(),
		
		_callbacks_end : new Array(),
		
		registerCallbacks : function (callbackStart, callbackEnd) {
			if (typeof callbackStart == 'function') {
				var index = geoUtil.instrBtns._callbacks_start.size();
				geoUtil.instrBtns._callbacks_start[index] = callbackStart;
			}
			if (typeof callbackEnd == 'function') {
				var index = geoUtil.instrBtns._callbacks_end.size();
				geoUtil.instrBtns._callbacks_end[index] = callbackEnd;
			}
		},
		startUpAction : function () {
			//mark as in middle of animating show/hide instructions
			geoUtil.instrBtns._animating = true;
			//process any pre-animation callbacks
			geoUtil.instrBtns._callbacks_start.each(function(f) {f();});
			return true;
		},
		finishAction : function () {
			//process any post-animation callbacks
			geoUtil.instrBtns._callbacks_end.each(function(f) {f();});
			//no longer in middle of animating show/hide instructions
			geoUtil.instrBtns._animating = false;
		}
	},
	initCalendars : function () {
		if (typeof Calendar != 'undefined') {
			//only do this if Calendar exists...
			$$('input.dateInput').each(function (elem) {
				var params = {dateField : elem.identify()};
				var calButton = elem.identify()+'CalButton';
				if ($(calButton)) {
					params.triggerElement = calButton;
				}
				Calendar.setup(params);
				if ($(calButton)) {
					$(calButton).setStyle({ cursor: 'pointer' });
				}
				//remove the class name to prevent re-adding each time
				elem.removeClassName('dateInput')
					.writeAttribute({'placeholder':geoUtil._dateDefaultText});
			});
		}
	},
	_dateDefaultText : 'YYYY-MM-DD',
	
	
	pageDimensions : function () {
		//gets the page dimensions
		//first figure out main width and height:
		var scrollDim = {width: 0, height: 0};
		
		var elem = $$('body')[0];
		
		if (window.innerHeight && window.scrollMaxY) {
			scrollDim.width = window.innerWidth + window.scrollMaxX;
			scrollDim.height = window.innerHeight + window.scrollMaxY;
		} else if (document.body.scrollHeight > document.body.offsetHeight) {
			//works in all but explorer mac
			scrollDim.width = document.body.scrollWidth;
			scrollDim.height = document.body.scrollHeight;
		} else {
			scrollDim.width = document.body.offsetWidth;
			scrollDim.height = document.body.offsetHeight;
		}
		
		var windowDim = document.viewport.getDimensions();
		//return which ever calculation came up with larger dimension
		scrollDim.width = Math.max(scrollDim.width, windowDim.width);
		scrollDim.height = Math.max(scrollDim.height, windowDim.height);
		
		return scrollDim;
	},
	
	text : {
		//text used, usually over-written by admin text set in in-line JS
		messageClose : '[close]',
		messageMove : '[move]'
	},
	
	addError: function (errorMsg) {
		geoUtil._highlightColor = '#ff9999';
		geoUtil._autoHideMessage = false;
		if (geoUtil.inAdmin) {
			errorMsg = '<span style="color: red;">Error:</span> '+errorMsg;
		}
		geoUtil.addMessage(errorMsg);
	},
	
	_messageTimeout : null,
	_messageMadeDragable : false,
	_highlightColor : '#ffff99',
	_autoHideMessage : true,
	_messageBoxInit : false,
	_initMessageBox : function () {
		if (geoUtil._messageBoxInit) {
			return;
		}
		geoUtil._messageBoxInit = true;
		var messageBox = new Element('div', {
			'id' : 'messageBox',
			'style' : 'display: none;'
		});
		
		var closeButton = new Element ('div');
		closeButton.addClassName('messageBoxButtons')
			.addClassName('closeMessage')
			.update(geoUtil.text.messageClose)
			.observe('click', geoUtil.closeMessage);
		
		var moveButton = new Element ('div', {'id' : 'moveMessageButton'});
		moveButton.addClassName('messageBoxButtons')
			.addClassName('moveMessage')
			.update(geoUtil.text.messageMove);
		if (!geoUtil.inAdmin) {
			moveButton.setOpacity(0);//make it hard to see
		}
		
		var messageText = new Element ('div', {'id' : 'messageTxt'});
		
		//add it all into messageBox
		messageBox.insert(closeButton)
			.insert(moveButton)
			.insert(messageText);
		//insert it into the body
		geoUtil.insertInBody(messageBox);
	},
	
	addMessage : function (msgText) {
		geoUtil._initMessageBox();
		var messageBox = $('messageBox');
		var messageText = $('messageTxt');
		if (!messageBox || !messageText) {
			//can't insert the message if no message box
			alert(msgText);
			return;
		}
		messageText.update(msgText);
		if (!geoUtil._messageMadeDragable) {
			geoUtil._messageMadeDragable = true;
			new Draggable(messageBox, {
				zindex: 1002,
				handle: 'moveMessageButton',
				onStart: function () {
					if (geoUtil._messageTimeout) {
						clearTimeout(geoUtil._messageTimeout);
						geoUtil._messageTimeout = null;
					}
				}
			});
			messageBox.makePositioned();
		}
		
		if (!messageBox.visible()) {
			messageBox.show();
		}
		//move it to middle
		geoEffect.moveToMiddle(messageBox);
		
		//highlight it
		new Effect.Highlight(messageBox, {
			startcolor: geoUtil._highlightColor,
			restorecolor: '#ffffff'
		});
		geoUtil._highlightColor = '#ffff99';//restore to default in case it was changed
		
		if (geoUtil._messageTimeout) {
			//stop it from happening, as we're re-doing it
			clearTimeout(geoUtil._messageTimeout);
		}
		//make it fade out after 10 seconds
		if (geoUtil._autoHideMessage) {
			geoUtil._messageTimeout = setTimeout("new Effect.Fade('messageBox', geoUtil.defaultParams);geoUtil._messageTimeout = null;", 10000);
		}
		//reset auto hide setting for next message
		geoUtil._autoHideMessage = true;
	},
	
	closeMessage : function () {
		if (geoUtil._messageTimeout) {
			//stop it from happening, as we're re-doing it
			clearTimeout(geoUtil._messageTimeout);
			geoUtil._messageTimeout = null;
		}
		new Effect.Fade('messageBox', geoUtil.defaultParams);
	},
	
	insertInBody : function (element) {
		$$('body')[0].insert(element);
	},
	
	getCookie : function (sName) {
		var aCookie = document.cookie.split('; ');
		for (var i=0; i < aCookie.length; i++) {
			var aCrumb = aCookie[i].split('=');
			if (sName == aCrumb[0]) {
				return unescape(aCrumb[1]);
			}
		}
		return null;
	},
	
	/*
	 * Simple function to re-load the page, it should work regardless of whether
	 * there is a hash or not, and will not prompt the user if the current page
	 * is the result of a POST.
	 */
	refreshPage : function () {
		//add refresh=# to the query params, to "force" a refresh of the page even
		//when there is a hash on the page
		var params = location.href.toQueryParams();
		params.refresh = (params.refresh)? params.refresh*1+1 : 1;
		
		//now re-construct the URL with the refresh=# added in the URL
		var href = location.protocol+'//'+location.hostname+location.pathname+'?'+Object.toQueryString(params);
		var hash = location.hash.replace(/^#/,'');
		if (hash) {
			//add the hash back
			href += '#'+hash;
		}
		//alert('href: '+href);
		//use replace so it doesn't result in history entry, it acts like a refresh
		location.replace(href);
	},
	
	/**
	 * Handles taking user to next page automatically when logging in or
	 * registering
	 * 
	 * Note: Uses prototype!
	 * 
	 * @param string form ID of form to submit
	 * @param string replaceTxt
	 */
	autoSubmitForm : function (form, replaceTxt) {
		var delay = 2000; //time to wait after the page is done loading
		
		Event.observe(window, 'load', function () {
			setTimeout(function () {
				//2 seconds after page is done loading, auto submit the form.
				myForm = $(form);
				if (myForm){
					if (replaceTxt) {
						window.location.replace(replaceTxt);
					}
					myForm.submit();
				}
			}, delay);
		});
	},
	
	initTagSearch : function () {
		$$('input.tagSearchField').each (function (element) {
			//use CSS class so that multiple input fields can be used on same page
			var inputField = element.identify();
			//The choices div MUST be next field after the input field
			var choicesDiv = element.next().identify();
			new Ajax.Autocompleter(inputField, choicesDiv, 'AJAX.php?controller=ListingTagAutocomplete&action=getSuggestions', {
				paramName : 'tags',
				parameters : 'showCounts=1'//{showCounts : '1'}
			});
		});
	},
	
	/**
	 *
	 *  MD5 (Message-Digest Algorithm)
	 *  http://www.webtoolkit.info/
	 *
	 */
	md5 : function (string) {

		function RotateLeft(lValue, iShiftBits) {
			return (lValue<<iShiftBits) | (lValue>>>(32-iShiftBits));
		}

		function AddUnsigned(lX,lY) {
			var lX4,lY4,lX8,lY8,lResult;
			lX8 = (lX & 0x80000000);
			lY8 = (lY & 0x80000000);
			lX4 = (lX & 0x40000000);
			lY4 = (lY & 0x40000000);
			lResult = (lX & 0x3FFFFFFF)+(lY & 0x3FFFFFFF);
			if (lX4 & lY4) {
				return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
			}
			if (lX4 | lY4) {
				if (lResult & 0x40000000) {
					return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
				} else {
					return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
				}
			} else {
				return (lResult ^ lX8 ^ lY8);
			}
	 	}

	 	function F(x,y,z) { return (x & y) | ((~x) & z); }
	 	function G(x,y,z) { return (x & z) | (y & (~z)); }
	 	function H(x,y,z) { return (x ^ y ^ z); }
		function I(x,y,z) { return (y ^ (x | (~z))); }

		function FF(a,b,c,d,x,s,ac) {
			a = AddUnsigned(a, AddUnsigned(AddUnsigned(F(b, c, d), x), ac));
			return AddUnsigned(RotateLeft(a, s), b);
		};

		function GG(a,b,c,d,x,s,ac) {
			a = AddUnsigned(a, AddUnsigned(AddUnsigned(G(b, c, d), x), ac));
			return AddUnsigned(RotateLeft(a, s), b);
		};

		function HH(a,b,c,d,x,s,ac) {
			a = AddUnsigned(a, AddUnsigned(AddUnsigned(H(b, c, d), x), ac));
			return AddUnsigned(RotateLeft(a, s), b);
		};

		function II(a,b,c,d,x,s,ac) {
			a = AddUnsigned(a, AddUnsigned(AddUnsigned(I(b, c, d), x), ac));
			return AddUnsigned(RotateLeft(a, s), b);
		};

		function ConvertToWordArray(string) {
			var lWordCount;
			var lMessageLength = string.length;
			var lNumberOfWords_temp1=lMessageLength + 8;
			var lNumberOfWords_temp2=(lNumberOfWords_temp1-(lNumberOfWords_temp1 % 64))/64;
			var lNumberOfWords = (lNumberOfWords_temp2+1)*16;
			var lWordArray=Array(lNumberOfWords-1);
			var lBytePosition = 0;
			var lByteCount = 0;
			while ( lByteCount < lMessageLength ) {
				lWordCount = (lByteCount-(lByteCount % 4))/4;
				lBytePosition = (lByteCount % 4)*8;
				lWordArray[lWordCount] = (lWordArray[lWordCount] | (string.charCodeAt(lByteCount)<<lBytePosition));
				lByteCount++;
			}
			lWordCount = (lByteCount-(lByteCount % 4))/4;
			lBytePosition = (lByteCount % 4)*8;
			lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80<<lBytePosition);
			lWordArray[lNumberOfWords-2] = lMessageLength<<3;
			lWordArray[lNumberOfWords-1] = lMessageLength>>>29;
			return lWordArray;
		};

		function WordToHex(lValue) {
			var WordToHexValue="",WordToHexValue_temp="",lByte,lCount;
			for (lCount = 0;lCount<=3;lCount++) {
				lByte = (lValue>>>(lCount*8)) & 255;
				WordToHexValue_temp = "0" + lByte.toString(16);
				WordToHexValue = WordToHexValue + WordToHexValue_temp.substr(WordToHexValue_temp.length-2,2);
			}
			return WordToHexValue;
		};

		function Utf8Encode(string) {
			string = string.replace(/\r\n/g,"\n");
			var utftext = "";

			for (var n = 0; n < string.length; n++) {

				var c = string.charCodeAt(n);

				if (c < 128) {
					utftext += String.fromCharCode(c);
				}
				else if((c > 127) && (c < 2048)) {
					utftext += String.fromCharCode((c >> 6) | 192);
					utftext += String.fromCharCode((c & 63) | 128);
				}
				else {
					utftext += String.fromCharCode((c >> 12) | 224);
					utftext += String.fromCharCode(((c >> 6) & 63) | 128);
					utftext += String.fromCharCode((c & 63) | 128);
				}

			}

			return utftext;
		};

		var x=Array();
		var k,AA,BB,CC,DD,a,b,c,d;
		var S11=7, S12=12, S13=17, S14=22;
		var S21=5, S22=9 , S23=14, S24=20;
		var S31=4, S32=11, S33=16, S34=23;
		var S41=6, S42=10, S43=15, S44=21;

		string = Utf8Encode(string);

		x = ConvertToWordArray(string);

		a = 0x67452301; b = 0xEFCDAB89; c = 0x98BADCFE; d = 0x10325476;

		for (k=0;k<x.length;k+=16) {
			AA=a; BB=b; CC=c; DD=d;
			a=FF(a,b,c,d,x[k+0], S11,0xD76AA478);
			d=FF(d,a,b,c,x[k+1], S12,0xE8C7B756);
			c=FF(c,d,a,b,x[k+2], S13,0x242070DB);
			b=FF(b,c,d,a,x[k+3], S14,0xC1BDCEEE);
			a=FF(a,b,c,d,x[k+4], S11,0xF57C0FAF);
			d=FF(d,a,b,c,x[k+5], S12,0x4787C62A);
			c=FF(c,d,a,b,x[k+6], S13,0xA8304613);
			b=FF(b,c,d,a,x[k+7], S14,0xFD469501);
			a=FF(a,b,c,d,x[k+8], S11,0x698098D8);
			d=FF(d,a,b,c,x[k+9], S12,0x8B44F7AF);
			c=FF(c,d,a,b,x[k+10],S13,0xFFFF5BB1);
			b=FF(b,c,d,a,x[k+11],S14,0x895CD7BE);
			a=FF(a,b,c,d,x[k+12],S11,0x6B901122);
			d=FF(d,a,b,c,x[k+13],S12,0xFD987193);
			c=FF(c,d,a,b,x[k+14],S13,0xA679438E);
			b=FF(b,c,d,a,x[k+15],S14,0x49B40821);
			a=GG(a,b,c,d,x[k+1], S21,0xF61E2562);
			d=GG(d,a,b,c,x[k+6], S22,0xC040B340);
			c=GG(c,d,a,b,x[k+11],S23,0x265E5A51);
			b=GG(b,c,d,a,x[k+0], S24,0xE9B6C7AA);
			a=GG(a,b,c,d,x[k+5], S21,0xD62F105D);
			d=GG(d,a,b,c,x[k+10],S22,0x2441453);
			c=GG(c,d,a,b,x[k+15],S23,0xD8A1E681);
			b=GG(b,c,d,a,x[k+4], S24,0xE7D3FBC8);
			a=GG(a,b,c,d,x[k+9], S21,0x21E1CDE6);
			d=GG(d,a,b,c,x[k+14],S22,0xC33707D6);
			c=GG(c,d,a,b,x[k+3], S23,0xF4D50D87);
			b=GG(b,c,d,a,x[k+8], S24,0x455A14ED);
			a=GG(a,b,c,d,x[k+13],S21,0xA9E3E905);
			d=GG(d,a,b,c,x[k+2], S22,0xFCEFA3F8);
			c=GG(c,d,a,b,x[k+7], S23,0x676F02D9);
			b=GG(b,c,d,a,x[k+12],S24,0x8D2A4C8A);
			a=HH(a,b,c,d,x[k+5], S31,0xFFFA3942);
			d=HH(d,a,b,c,x[k+8], S32,0x8771F681);
			c=HH(c,d,a,b,x[k+11],S33,0x6D9D6122);
			b=HH(b,c,d,a,x[k+14],S34,0xFDE5380C);
			a=HH(a,b,c,d,x[k+1], S31,0xA4BEEA44);
			d=HH(d,a,b,c,x[k+4], S32,0x4BDECFA9);
			c=HH(c,d,a,b,x[k+7], S33,0xF6BB4B60);
			b=HH(b,c,d,a,x[k+10],S34,0xBEBFBC70);
			a=HH(a,b,c,d,x[k+13],S31,0x289B7EC6);
			d=HH(d,a,b,c,x[k+0], S32,0xEAA127FA);
			c=HH(c,d,a,b,x[k+3], S33,0xD4EF3085);
			b=HH(b,c,d,a,x[k+6], S34,0x4881D05);
			a=HH(a,b,c,d,x[k+9], S31,0xD9D4D039);
			d=HH(d,a,b,c,x[k+12],S32,0xE6DB99E5);
			c=HH(c,d,a,b,x[k+15],S33,0x1FA27CF8);
			b=HH(b,c,d,a,x[k+2], S34,0xC4AC5665);
			a=II(a,b,c,d,x[k+0], S41,0xF4292244);
			d=II(d,a,b,c,x[k+7], S42,0x432AFF97);
			c=II(c,d,a,b,x[k+14],S43,0xAB9423A7);
			b=II(b,c,d,a,x[k+5], S44,0xFC93A039);
			a=II(a,b,c,d,x[k+12],S41,0x655B59C3);
			d=II(d,a,b,c,x[k+3], S42,0x8F0CCC92);
			c=II(c,d,a,b,x[k+10],S43,0xFFEFF47D);
			b=II(b,c,d,a,x[k+1], S44,0x85845DD1);
			a=II(a,b,c,d,x[k+8], S41,0x6FA87E4F);
			d=II(d,a,b,c,x[k+15],S42,0xFE2CE6E0);
			c=II(c,d,a,b,x[k+6], S43,0xA3014314);
			b=II(b,c,d,a,x[k+13],S44,0x4E0811A1);
			a=II(a,b,c,d,x[k+4], S41,0xF7537E82);
			d=II(d,a,b,c,x[k+11],S42,0xBD3AF235);
			c=II(c,d,a,b,x[k+2], S43,0x2AD7D2BB);
			b=II(b,c,d,a,x[k+9], S44,0xEB86D391);
			a=AddUnsigned(a,AA);
			b=AddUnsigned(b,BB);
			c=AddUnsigned(c,CC);
			d=AddUnsigned(d,DD);
		}

		var temp = WordToHex(a)+WordToHex(b)+WordToHex(c)+WordToHex(d);

		return temp.toLowerCase();
	}
};


var geoEffect = {
	defaultDuration : .5,
	
	useEffect : function () {
		if (typeof Scriptaculous == 'undefined') {
			return false;
		}
		return true;
	},
	
	/**
	 * Use just like you would Effect.move() except that if scriptaculous is
	 * not present, it doesn't animate, or if element is currently hidden, it
	 * just moves it there by setting attribute.
	 */
	move : function (element, params) {
		element = $(element);
		if (element.visible() && geoEffect.useEffect()) {
			if (!params.duration) params.duration = geoEffect.defaultDuration;
			
			new Effect.Move(element, params);
		} else {
			element.setStyle({left: params.x+'px', top: params.y+'px'});
		}
	},
	
	show : function (element, effect, params) {
		element = $(element);
		if (element.visible() && element.getStyle('opacity') != 0.1) {
			//either the element needs to be hidden, or opacity set to 0.1
			return;
		}
		if (typeof effect == 'undefined') {
			effect = 'appear';
		}
		if (geoEffect.useEffect()) {
			if (typeof params == 'undefined') {
				params = {};
			}
			if (!params.duration) {
				params.duration = geoEffect.defaultDuration;
			}
			switch (effect) {
				case 'appear' :
					//break ommited on purpose
				default: 
					new Effect.Appear (element, params);
					break;
			}
		} else {
			if (element.visible()) {
				//if element is visible, then it must just be opaque
				element.setOpacity(1.0);
			} else {
				element.show();
			}
		}
	},
	
	hide : function (element, effect, params) {
		element = $(element);
		if (!element.visible()) {
			//already hidden
			return;
		}
		if (typeof effect == 'undefined') {
			effect = 'fade';
		}
		if (typeof params == 'undefined') {
			params = {};
		}
		if (geoEffect.useEffect()) {
			if (!params.duration) {
				params.duration = geoEffect.defaultDuration;
			}
			switch (effect) {
				case 'fade' :
					//break ommited on purpose
				default: 
					new Effect.Fade (element, params);
					break;
			}
		} else {
			element.hide();
			if (typeof params.afterFinish != 'undefined') {
				//it's finished, so call whatever is meant to be called
				params.afterFinish();
			}
		}
	},
	
	morphSize : function (element, width, height, params) {
		element = $(element);
		if (!element) return;
		
		if (geoEffect.useEffect()) {
			if (typeof params == 'undefined') {
				params = {};
			}
			if (!params.duration) {
				params.duration = geoEffect.defaultDuration;
			}
			params.style = 'width: '+width+'px; height: '+height+'px;';
			
			new Effect.Morph(element, params);
		} else {
			//use prototype to go to that element
			element.setStyle({
				width: width+'px',
				height: height+'px'
			});
		}
	},
	
	/**
	 * Ensures that given element is positioned vertically around the middle of
	 * the current viewport even if scrolled down some.
	 * 
	 * @param element
	 */
	moveToMiddle : function (element) {
		element = $(element);
		if (!element) {
			//not valid element
			return;
		}
		//make sure it's absolutized
		element.absolutize();
		
		//figure out mid point taking into account scrolled down amount
		var offset = document.viewport.getScrollOffsets();
		
		//figure out different dimensions we're working with
		var elemDim = element.getDimensions();
		var viewDim = document.viewport.getDimensions();
		
		if (viewDim.width == 0 && viewDim.height == 0) {
			//viewport dimensions were not able to be retrieved, so pretend it is
			//800x600 just so it's not off in the corner
			viewDim.width = 800;
			viewDim.height = 600;
		}
		
		//if the difference is > 0 use it, otherwise just start out at scrolled offset
		if ((viewDim.width-elemDim.width) > 0)
			offset.left += Math.floor((viewDim.width-elemDim.width)/2);
		
		if ((viewDim.height-elemDim.height) > 0)
			offset.top += Math.floor((viewDim.height-elemDim.height)/2);
		
		//move into place
		geoEffect.move(element, {x: offset.left, y: offset.top, mode: 'absolute'});
	},
	
	scrollTo : function (element, params) {
		element = $(element);
		if (!element) return;
		
		if (geoEffect.useEffect()) {
			if (typeof params == 'undefined') {
				params = {};
			}
			if (!params.duration) {
				params.duration = geoEffect.defaultDuration;
			}
			new Effect.ScrollTo(element, params);
			
		} else {
			//use prototype to go to that element
			element.scrollTo();
		}
	}
};


/**
 * Function to...  load a popup?  Don't use this, it will be removed in a future
 * release, replaced by new lightbox or you could just use window.open(this.href)
 * 
 * @param string fileName
 * @deprecated Will be removed in future release.
 */
var win = function (fileName) 
{
	var myFloater = window.open('','myWindow','scrollbars=yes,resizable=yes,status=no,width=300,height=300');
	myFloater.location.href = fileName;
	if (window.focus) myFloater.focus();
};

/**
 * Opens a popup, this one is actually still used a lot of places, so can't be
 * removed quite yet.  Do NOT use for new stuff though.
 * 
 * @param string fileName
 * @param int width
 * @param int height
 */
var winimage = function (fileName,width,height) 
{
	var myFloater = window.open('','myWindow','scrollbars=yes,resizable=yes,status=no,width=' + width + ',height=' + height);
	myFloater.location.href = fileName;
};

/**
 * NO LONGER USED!!!  Do NOT use lightUpBox, use the new jQuery version of things!
 * This is now just a shell that hopefully will make it easier for people to transition
 * to use the "new" way of doing things in jQuery.
 */
var lightUpBox = {
	/**
	 * Set this to true, and the main functions will "call" the "new way" of
	 * doing everything.  This is only meant as a tool to help developers transition
	 * code to use the new way, it should not be used as a long-term solution to
	 * make old code work.
	 * 
	 * It will add log entries to let you know what you need to be calling "instead"
	 * 
	 * Note that even if it does "try" to call the new way, if one of the parameters
	 * passed in uses somethign specific to prototype, it may not work anyways
	 */
	legacyCompat : false,
	startSlideshow : function () {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: jQuery(document).gjLightbox(\'startSlideshow\');');
		if (lightUpBox.legacyCompat) {
			jQuery(document).gjLightbox('startSlideshow');
		}
	},
	stopSlideshow : function () {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: jQuery(document).gjLightbox(\'stopSlideshow\');');
		if (lightUpBox.legacyCompat) {
			jQuery(document).gjLightbox('stopSlideshow');
		}
	},
	registerHideCallback : function (callback) {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: gjUtil.lightbox.onOpen(callback);');
		if (lightUpBox.legacyCompat) {
			gjUtil.lightbox.onOpen(callback);
		}
	},
	registerShowCallback : function (callback) {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: gjUtil.lightbox.onClose(callback);');
		if (lightUpBox.legacyCompat) {
			gjUtil.lightbox.onClose(callback);
		}
	},
	registerOnCompleteCallback : function (callback) {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: jQuery(document).gjLightbox(\'onComplete\', callback);');
		if (lightUpBox.legacyCompat) {
			gjUtil.lightbox.onComplete(callback);
		}
	},
	openBox : function (contents) {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: jQuery(document).gjLightbox(\'open\',contents);');
		if (lightUpBox.legacyCompat) {
			jQuery(document).gjLightbox('open',contents);
		}
	},
	closeBox : function (event) {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: jQuery(document).gjLightbox(\'close\');');
		if (lightUpBox.legacyCompat) {
			jQuery(document).gjLightbox('close');
		}
	},
	lightUpImage : function (event) {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: jQuery(document).gjLightbox(\'getImg\',this.href);return false;');
		if (lightUpBox.legacyCompat) {
			jQuery(document).gjLightbox('getImg',this.href);
			return false;
		}
	},
	lightUpLink : function (event) {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: jQuery(document).gjLightbox(\'get\',this.href);return false;');
		if (lightUpBox.legacyCompat) {
			jQuery(document).gjLightbox('get',this.href);
			return false;
		}
	},
	lightUpLinkManual : function (url) {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: jQuery(document).gjLightbox(\'get\',url);');
		if (lightUpBox.legacyCompat) {
			jQuery(document).gjLightbox('get',url);
		}
	},
	lightUpLinkManualPost : function (url, params) {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: jQuery(document).gjLightbox(\'post\',url,params);');
		if (lightUpBox.legacyCompat) {
			jQuery(document).gjLightbox('post',url,params);
		}
	},
	initBox : function () {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: jQuery(document).gjLightbox();');
		if (lightUpBox.legacyCompat) {
			jQuery(document).gjLightbox();
		}
	},
	init : function () {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: gjUtil.lightbox.initClick();');
		if (lightUpBox.legacyCompat) {
			gjUtil.lightbox.initClick();
		}
	},
	addImageObserver : function (element) {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: jQuery(\'#element_id\').gjLightbox(\'clickLinkImg\');');
		//Not able to call automatically, if element is extended by Prototype
	},
	addLinkObserver : function (element) {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: jQuery(\'#element_id\').gjLightbox(\'clickLink\');');
		//Not able to call automatically, if element is extended by Prototype
	},
	addDisabledObserver : function (element) {
		jQuery.error('Error: Function no longer exists, replaced by jQuery plugin.  Use this instead: jQuery(\'#element_id\').gjLightbox(\'clickDisabled\');');
		//Not able to call automatically, if element is extended by Prototype
	}
};

/**
 * Used for having tabbed contents on the front side OR the admin side.  See below for usage.
 * DEPRECATED:  Will be converting this to use jQuery at some point.  Along with everything
 * else in this file for that matter...
 */
/*
NOTE:  For tab ID's, can be whatever you want.  The "content divs" must use same
exact ID, but with "Content" added to end, as demonstrated in sample below.

Note 2:  If needed, this will work with multiple sets of tabbed contents, the
ul just needs to be right above the content divs for that set of tabs.

Using tabs example:

<ul class="tabList">
	<li id="firstTab">Tab 1</li>
	<li id="secondTab">Tab 2</li>
	<li id="funny" class="activeTab">Funny Tab</li>
</ul>

<div class="tabContents" id="firstTabContents">
	Tab 1 contents.
</div>

<div class="tabContents" id="secondTabContents">
	Tab 2 contents!
</div>

<div class="tabContents" id="funnyContents">
	Funny Tab!  Insert funny joke here:  ____
</div>


------
Possible AJAX loading alternate:  If need a loading image, have something like this (alternate
 to the way the divs are above, this one is stripped down for brevity):
<div class="tabContents">
	<div id="loadingImg"><img ...></div>
	<div id="firstTabContents">...</div>
	...
</div>

Then using a callback (see below), would show the loading div and make an ajax
call to populate that tab.
*/

/*

Callbacks:  It is possible to add a callback, by adding to geoTabs.tabCallbacks.
 the index would be the id if the tab, and the value should be the function to
 call when that tab is clicked (after normal stuff is done).
 
Callback example: (this snippet would be done inside JS script run when window
 is done loading):

//funny is ID for the "Funny Tab" in the tab example.
geoTabs.tabCallbacks.funny = function () {
	alert ('Funny tab clicked!');
	//Note:  Might show "loading" image here, and possibly make ajax call that
	//would populate the tab's contents.  Text search (in admin) uses this.
};

 */
var geoTabs = {
	tabCallbacks : {},
	debug : false,
	ignoreActiveCookie : false,
	
	precheck : function (elem) {
		if (elem.hasClassName('activeTab')) {
			//has active tab, don't allow to proceed
			return false;
		}
		return true;
	},
	
	init : function () {
		//look for tab menu items
		
		$$('ul.tabList').each(function (parent_elem) {
			
			var cookie = 'activeTab';
			var activeTab = null;
			parent_elem.select('li').each (function(elem) {
				elem.observe('click', geoTabs.tabClick);
				if (!elem.hasClassName('activeTab')) {
					//hide the contents
					if (!$(elem.identify()+'Contents')) {
						alert ('Page did not finish loading, or tabs may be set up incorrectly!  There is no content div with ID of '+elem.identify()+'Contents');
						return;
					}
					$(elem.identify()+'Contents').hide();
				} else {
					//has activeTab class, must be "default" active tab
					activeTab = elem.identify();
					//remove class name so it can "activate" the tab without failing pre-checks
					elem.removeClassName('activeTab');
				}
				cookie += '::'+elem.identify();
			});
			
			if (!geoTabs.ignoreActiveCookie && !parent_elem.hasClassName('ignoreActiveCookie')) {
				cookie = geoTabs.tabCookieName(cookie);
				var activeCookie = geoUtil.getCookie(cookie);
				if (activeCookie && $(activeCookie)) {
					activeTab = activeCookie;
				}
			}
			
			if (activeTab && $(activeTab)) {
				geoTabs.activateTab(activeTab);
			}
			
		});
	},
	
	tabCookieName : function (tabs) {
		return 'tab_'+geoUtil.md5(tabs);
	},
	
	activateTab : function (tab, action) {
		tab = $(tab);
		if (!geoTabs.precheck(tab)) {
			//pre-checks failed, do not proceed.
			//NOTE:  can over-write the precheck if needed, normally it just checks
			//to make sure that tab is not already the current active tab.
			return;
		}
		var cookie = 'activeTab';
		tab.up('ul').select('li').each(function (elem) {
			cookie += '::'+elem.identify();
			elem.removeClassName('activeTab');
			if (!$(elem.identify()+'Contents') && (geoUtil.inAdmin||geoTabs.debug)) {
				alert ('Tabs set up incorrectly!  There is no element with ID '+elem.identify()+'Contents');
			}
			$(elem.identify()+'Contents').hide();
		});
		
		tab.addClassName('activeTab');
		$(tab.identify()+'Contents').show();
		
		if (typeof geoTabs.tabCallbacks[tab.identify()] == 'function') {
			geoTabs.tabCallbacks[tab.identify()](action);
		}
		if (!geoTabs.ignoreActiveCookie && !tab.up().hasClassName('ignoreActiveCookie')) {
			cookie = geoTabs.tabCookieName(cookie);
			//save the cookie, let expire at session end (when browser closes)
			document.cookie = cookie+'='+tab.identify()+'; path=/';
		}
	},
	
	tabClick : function (action) {
		geoTabs.activateTab(this, action);
	}
};

//For older scripts that still do things old way
var getCookie = geoUtil.getCookie;

/* Mini-object for handling loading/unloading wysiwyg's */
var geoWysiwyg = {
	editors : [],
	
	tinyLoaded : false,
	
	loadTiny : function () {
		//This meant to be over-written by admin/client side
		return false;
	},
	
	removeTiny : function () {
		if (!geoWysiwyg.tinyLoaded) {
			//nothing to do
			//alert('not loaded, nothing to do!');
			return;
		}
		for (var i = 0; i < geoWysiwyg.editors.length; i++) {
			var id = geoWysiwyg.editors[i].identify();
			if (tinyMCE.getInstanceById(id)) {
				tinyMCE.execCommand('mceRemoveControl',false,id);
			}
		}
		//reset things, so they can be re-init
		geoWysiwyg.tinyLoaded = false;
		geoWysiwyg.editors = [];
	},
	
	//This one used to re-load tiny after it has been removed
	restoreTiny : function () {
		geoWysiwyg.loadTiny();
		geoWysiwyg.editors = $$('.editor');
	},
	
	toggleTinyEditors : function () {
		if (geoWysiwyg.loadTiny()) {
			//tiny was loaded for first time, so toggling on
			document.cookie = 'tinyMCE=on';
			return;
		}
		for (var i = 0; i < geoWysiwyg.editors.length; i++) {
			var id = geoWysiwyg.editors[i].identify();
			if (!tinyMCE.getInstanceById(id)) {
				tinyMCE.execCommand('mceAddControl',false,id);
				document.cookie = 'tinyMCE=on';
			} else {
				tinyMCE.execCommand('mceRemoveControl',false,id);
				document.cookie = 'tinyMCE=off';
			}
		}
	}
};

/* As the name implies, this is the "old" way of doing ajax.  New stuff uses
 * prototype's Ajax object directly to do anything.  This is scheduled to
 * eventually be re-coded so do not use for new code!
 * 
 * @deprecated Do not use this for new code, it is old and being phased out.
 */
var geoOldAjax = {
	sendReq : function (action, b) {
		if (b) {
			b = '&b='+b;
		} else {
			var b = '';
		}
		var url = '';
		if (action=='close'){
			//use different url for close/cron routines
			url = 'cron.php?action=cron';
		} else {
			//find the filename
			url = 'ajaxBackend.php?action='+action+b;
		}
		new Ajax.Request (url, {
			onSuccess : geoOldAjax.handleResponse
		});
	},
	handleResponse : function (transport) {
		var response = transport.responseText;
		var update = new Array();
		var sep = '|';
		if (response.indexOf('~~|~~') != -1){
			sep = '~~|~~';
		}
		if(response.indexOf(sep) != -1) {
			update = response.split(sep);
			
			for (var i=1; i<update.length; i++) {
				if ($(update[i])) {
					//replace contents of container with first part
					$(update[i]).update(update[0]);
				}
			}
			geoUtil.initCalendars();
		}
	}
};

//For customizations that still use sendReq straight up..  this will be removed eventually
var sendReq = geoOldAjax.sendReq;
