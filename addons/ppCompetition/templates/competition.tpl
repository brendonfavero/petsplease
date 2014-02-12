<script type="text/javascript" src="/js/jquery-jcarousellite.js"></script>

<h1 class="title">Pet of the Week competition.</h1>

<p>Each week a pet photo will be chosen from those submitted to become Pet Of The Week, they will be posted on this website, Facebook, Google + and Instagram. Please take a look at some that have already appeared on Facebook.
<br/><br/>
If you would like your pet to be Pet Of The Week and appear on  Pets Please website and  Social media please send your photo’s to <a href="mailto:natasha@petsplease.com.au">natasha@petsplease.com.au</a> All Pet of the week winners will receive a prize.</p>

{foreach $current as $c}
	<div style="margin:0 20%; text-align:center;" id="petoftheweek">
		<h1 style="font-size:16px; font-weight:bold">Current Pet of the Week</h1>
		<img src="{$c.full_url}">
		<br/>
		<p style="font-weight:bold; font-size:15px">{$c.petname}</p>
	</div>
{/foreach}

<div id="pastpets" class="petcarousel" style="padding-top:15px; text-align:center; ">
	<h1 style="font-size:16px; font-weight:bold">Past Pets of the Week</h1>
	<ul>
		{foreach $competitions as $competition}
				<li><img src="{$competition.thumb_url}">
				<br/>
				{$competition.petname}
			</li>
		{/foreach}
	</ul>
	<button class="prev">Prev</button>
    <button class="next">Next</button>
</div>

<script>
	jQuery(".petcarousel").jCarouselLite({
		btnNext: ".next",
		btnPrev: ".prev",
		auto: 3000,
   	    speed: 1000
	});
</script>
