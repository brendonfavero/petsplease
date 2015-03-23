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
<br/>
<div style="text-align:center">
{foreach $links as $link}
	{if $link.link != ''}
		<a href="{$link.link}">Week {$link.week}</a><br/>
	{/if}
{/foreach}
</div>
<br/>
{foreach $dogs as $dog}
	<div class="dogclickerbox">
		
		<img src="{$dog.full_url}">
		<br/>
		<p style="font-weight:bold; font-size:15px">{$dog.dogname} {$dog.age}</p>
		<p style="font-weight:bold; font-size:15px">Trainer: {$dog.trainer}</p>
		<p style="font-weight:bold; font-size:15px">{$dog.comments}</p>
	</div>
{/foreach}

