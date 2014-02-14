<script type="text/javascript" src="/js/jquery-jcarousellite.js"></script>
<script type="text/javascript" src="/js/fancybox/jquery.fancybox.js"></script>

<link rel="stylesheet" href="/js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />

<style>
	.competitiontitle {
		font-size:20px; 
		font-weight:bold; 
		margin-bottom:20px; 
		margin-left:1%;
		text-align:center;
		color:#2e3192;		
	}
	
</style>

<h1 class="title">Pet of the Week competition.</h1>

<p>Each week a pet photo will be chosen from those submitted to become Pet Of The Week, they will be posted on this website, Facebook, Google + and Instagram. Please take a look at some that have already appeared on Facebook.
<br/><br/>
If you would like your pet to be Pet Of The Week and appear on  Pets Please website and  Social media please send your photo’s to <a href="mailto:natasha@petsplease.com.au">natasha@petsplease.com.au</a> All Pet of the week winners will receive a prize.</p>

<h1 class="title">Current Pet of the Week</h1>
{foreach $current as $c}
	<div class="content_box_1" style="margin:auto; text-align:center;" id="petoftheweek">
		
		<img src="{$c.full_url}">
		<br/>
		<p style="font-weight:bold; font-size:15px">{$c.petname}</p>
		<p style="font-weight:bold; font-size:15px">Submitted By: {$c.sender_name}</p>
	</div>
{/foreach}
<h1 style="margin-top:20px" class="title">Past Pets of the Week</h1>

<div id="pastpets" class="content_box_1 petcarousel" style="text-align:center; ">
	
	<ul>
		{foreach $competitions as $competition}
				<li><a class="popupimage" href="{$competition.full_url}"><img src="{$competition.thumb_url}"></a>
				<br/>
				{$competition.petname}
				<br/>
				Submitted By: {$competition.sender_name}
			</li>
		{/foreach}
	</ul>
	<button style="float:left" class="prev"><<<</button>
    <button style="float:right" class="next">>>></button>
</div>

<script>
	jQuery(".petcarousel").jCarouselLite({
		btnNext: ".next",
		btnPrev: ".prev",
		auto: 3000,
   	    speed: 1000
	});
	
	jQuery(".popupimage").fancybox({
		'transitionIn'	:	'elastic',
		'transitionOut'	:	'elastic',
		'speedIn'		:	600, 
		'speedOut'		:	200, 
		'overlayShow'	:	false});
</script>
