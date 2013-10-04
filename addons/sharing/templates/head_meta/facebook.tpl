{* 7.1.2-8-g6e2718c *}
{* The meta tag(s) for listing stuff to make like work better on Facebook *}
<meta property="og:image" content="{$image_url}" />
{if $listing}
	<meta property="og:description" content="{$description_clean}" />
	<meta property="og:title" content="{$listing.title|fromDB|escape}" />
	<meta property="og:type" content="product" />
	<meta property="og:url" content="{$listing_url|escape}" />
{/if}

<script>
//<![CDATA[
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
//]]>
</script>