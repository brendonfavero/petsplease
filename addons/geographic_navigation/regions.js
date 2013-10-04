// 6.0.7-3-gce41f93


var addonNavigation = {
	init : function () {
		$$('.addonNavigation_regionSelect').each (function(element) {
			element.stopObserving('change');
			element.observe('change', addonNavigation.selectObserve);
		});
		
		$$('.geographic_navigation_changeLink').each (function (element) {
			element.observe('click', addonNavigation.changeRegionClick);
		});
	},
	
	selectObserve : function (action) {
		var element = this;
		if (element.getValue() == 0 && element.previous()) {
			//selected "select", pretend they just did the previous one, otherwise
			//all of them get cleared.
			element = element.previous();
		}
		new Ajax.Updater(this.up(), 'AJAX.php?controller=addon_geographic_navigation&action=selectRegion',{
			parameters: {region_id : element.getValue(), fieldName : element.name},
			evalScripts : true
		});
	},
	
	navChangeBox : null,
	
	navChangeOn : null,
	
	animParams : {duration: .3},
	
	getParams : {},
	
	initNavChangeBox : function ()
	{
		if (addonNavigation.navChangeBox) {
			//already init
			return;
		}
		var box = new Element ('div', {
			'id' : 'geoNavChangeBox'
		});
		
		geoUtil.insertInBody(box);
		
		box.absolutize()
			.hide()
			.update(new Element('div'));
		
		addonNavigation.navChangeBox = box;
	},
	
	updateChangeBox : function (content) {
		addonNavigation.initNavChangeBox();
		
		addonNavigation.navChangeBox.down().update(content);
		
		return addonNavigation.navChangeBox;
	},
	
	changeRegionClick : function (action) {
		action.stop();
		
		addonNavigation.initNavChangeBox();
		
		if (addonNavigation.navChangeOn == this.identify()) {
			addonNavigation.closeChangeBox();
			return;
		}
		var offsetHeight = 30;
		addonNavigation.updateChangeBox('Loading...')
			.clonePosition(this, {
				setWidth: false,
				setHeight: false,
				offsetTop: offsetHeight
			})
			.setStyle({width: '', height: ''})
			.appear(addonNavigation.animParams);
		
		addonNavigation.navChangeOn = this.identify();
		
		new Ajax.Request ('AJAX.php?controller=addon_geographic_navigation&action=chooseRegionBox', {
			onSuccess : addonNavigation.handleChangeBox,
			parameters : addonNavigation.getParams
		});
	},
	
	changeChooseRegion : function (action) {
		var regionId = 0;
		if (this.hasClassName('narrowRegionLink')) {
			//linky
			regionId = this.next().value;
			
		} else {
			regionId = this.value;
		}
		var params = addonNavigation.getParams;
		params.region = regionId;
		
		new Ajax.Request ('AJAX.php?controller=addon_geographic_navigation&action=chooseRegionBox', {
			onSuccess : addonNavigation.handleChangeBox,
			parameters : params
		});
	},
	
	changeDblClick : function (action) {
		action.stop();
		addonNavigation.closeChangeBox();
		window.location = this.next().next().value;
	},
	
	handleChangeBox : function (transport) {
		addonNavigation.updateChangeBox(transport.responseText);
		
		//watch stuff inside the box
		addonNavigation.navChangeBox.select('.chooseNavCancel').each(function (elem) {
			elem.observe('click', addonNavigation.closeChangeBox);
		});
		addonNavigation.navChangeBox.select('.narrowRegionLink').each(function (elem) {
			elem.observe('click', addonNavigation.changeChooseRegion)
				.observe('dblclick', addonNavigation.changeDblClick);
		});
		addonNavigation.navChangeBox.select('.narrowRegionSelect').each(function (elem) {
			elem.observe('change', addonNavigation.changeChooseRegion);
		});
	},
	
	closeChangeBox : function () {
		addonNavigation.navChangeBox.fade(addonNavigation.animParams);
		addonNavigation.navChangeOn = null;
	}
	
};

Event.observe(window, 'load', addonNavigation.init);
