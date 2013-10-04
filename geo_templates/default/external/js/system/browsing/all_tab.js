// 6.0.7-3-gce41f93

/*
 * This file sets up the fancy stuff for the "all" tab when browsing, so that
 * we don't have to duplicate exact same stuff, instead use custom stuff
 * so that all tab just shows both sections.
 *
 */

Event.observe(window,'load',function () {
	if ($('allTab')) {
		geoTabs.tabCallbacks.allTab = function () {
			$('classifiedsTabContents').show();
			$('auctionsTabContents').show();
		};
		
		if ($('allTab').hasClassName('activeTab')) {
			//it is currently active...  Just in case tab init has already happened
			geoTabs.tabCallbacks.allTab();
		}
	}
});
