{* 612208c *}
<div>
	<script type="text/javascript" src="http://widgets.twimg.com/j/2/widget.js"></script>
	<script type="text/javascript">
	new TWTR.Widget({ldelim}
	  version: 2,
	  type: 'profile',  
	  rpp: {$config.rpp}, //number of tweets to show
	  interval: {math equation="i * 1000" i=$config.interval}, //stored in seconds, but Twitter wants the value in ms 
	  width: {if $config.autowidth}'auto'{else}{$config.width}{/if},
	  height: {$config.height},
	  
	  theme: {ldelim}
	    shell: {ldelim}
	      background: '#{$config.shell}', 
	      color: '#{$config.heading}'  
	    },
	    tweets: {ldelim}
	      background: '#{$config.background}', 
	      color: '#{$config.text}', 
	      links: '#{$config.links}'
	    }
	  },
	  features: {ldelim}
	    scrollbar: {if $config.behavior == 'default'}false{else}true{/if},
	    loop: {if $config.behavior == 'default'}true{else}false{/if},
	    live: true, //no real reason to ever make this be false
	    hashtags: {if $config.hashtags}true{else}false{/if},
	    timestamp: {if $config.timestamps}true{else}false{/if},
	    avatars: {if $config.avatars}true{else}false{/if},
	    behavior: '{$config.behavior}'
	  }
	}).render().setUser('{$twitterName}').start();
	</script>
</div>