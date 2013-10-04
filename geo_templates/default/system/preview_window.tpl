{* 6.0.7-3-gce41f93 *}

{*
This is inserted into the head of the page for the preview window, it is meant to
stop clicks, since clicking on some of the links in preview window will result
in site errors since the listing does not exist yet (for example, contact seller)
*}

{literal}
<script type='text/javascript'>
//<![CDATA[
Event.observe(window, 'load', function () {
	//stop any clicks to prevent clicking on links, and set style to crosshair
	//so people don't wonder why it says they can click but it doesn't do anything.
	$$('body')[0].observe('click', function (event) {
		event.stop();
		//un-comment the following line to display a message when user clicks on something
		//alert('Links disabled in preview window.');
	}).setStyle({cursor: 'crosshair'});
	//also set the style for all the links to a crosshair
	$$('a').each(function (element) { element.setStyle({cursor: 'crosshair'})});
});
//]]>
</script>
{/literal}