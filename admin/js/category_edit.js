// 6.0.7-3-gce41f93

Event.observe(window, 'load', function () {
	if ($('which_header_html')) {
		$('which_header_html').observe('change', function () {
			var which_html = this.getValue();
			$$('div.header_html').each(function (elem) {
				elem[((which_html=='cat'||which_html=='cat+default')? 'appear':'fade')]();
			});
		});
	}
});