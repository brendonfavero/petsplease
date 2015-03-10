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

<h1 class="title">Dog Clicker Program</h1>

<p>{$content}</p>

<h1 class="title">Links</h1>
{foreach $links as $link}
		<a href="{$link.url}">Week {$link.week}</a><br/>
{/foreach}

<h1 class="title">Dogs</h1>
{foreach $dogs as $dog}
	<div class="content_box_1" style="margin:auto; text-align:center;" id="petoftheweek">
		
		<img src="{$dog.full_url}">
		<br/>
		<p style="font-weight:bold; font-size:15px">{$dog.dogname} {$dog.age}</p>
		<p style="font-weight:bold; font-size:15px">Trainer: {$dog.trainer}</p>
		<p style="font-weight:bold; font-size:15px">Comments: {$dog.comments}</p>
	</div>
{/foreach}

