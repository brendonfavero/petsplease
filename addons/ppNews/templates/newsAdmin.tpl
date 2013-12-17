<link type="text/css" rel="stylesheet" href="jquery-ui/jquery-ui.min.css" />
<script type="text/javascript" src="/ps/jquery-1.9.1.min.js" ></script>
<script>  jQuery.noConflict();	var $J = jQuery; </script>
<script type="text/javascript" src="/js/jquery-ui.min.js" ></script>
<script type="text/javascript" src="scripts/aAdmin.js"></script>
<script type="text/javascript" src="scripts/tiny_mce/jquery.tinymce.js" ></script>

{literal}
	<style>
        .topIcons { display:none; }
		#sideMenu { z-index:1000; }
		#aHeader h2 {
			margin:8px 0px;
		}
		#aHeader button.add {
			float:right;	
		}
		#news {
			margin-top:10px;	
			max-width:1050px;
		}
		#news .controls {
			float:right;	
		}
		#news .controls .ui-button {
			float:right;
			border:1px solid #666;
			margin:4px 0px 4px 8px;
		}
		#news .controls .update {
			border-color:#393;	
		}
		#news .controls .delete {
			border-color:#900;	
		}
		#news .control {
			float:right;
			padding:7px 10px 5px 5px;
		}
		#news div.toolbox {
			float:right;
			margin-top:10px;
		}
		#news div.toolbox a {
			margin:3px;
		}	
		#news li input {
			border:0px;
			margin:-3px 1px 1px -4px;
			padding:2px 0px 2px 4px;
			background:none;
			min-width:
		}
		#news li.ui-news-selected input {			

		}
		#news li.ui-news-selected input:hover {
			margin:-3px 0 0 -4px;
			background:none;
			-webkit-box-shadow: rgba(0, 0, 0, 0.199219) 0px 1px 4px inset;
			border: 1px solid #AAA;
			border-radius: 3px 3px 3px 3px;
			-moz-border-radius: 3px 3px 3px 3px;
			-moz-box-shadow: 0 1px 4px rgba(0,0,0,0.2) inset;
		}
		#statusSaving, #statusDeleting, #statusCreating {
			color: #fff;
			display:none;
			padding:3px 18px 0px 0px;
			margin-right:5px;
			/* - {/literal} */
			background: url( '{$pathAddon}/images/icons/working.gif' ) no-repeat right 2px transparent;
			/* - {literal} - */
		}

		.stats {
			padding:0px 0px 5px 5px;	
		}
		.stat {
			font-size:10px;
			float:left;
			margin-right:15px;
			padding-top:17px;
		}
		.stats .title {
			font-weight:bold;
			float:left;
			margin-right:5px;
		}
		.stats .content {
			font-style:italic;
			float:left;
		}
    </style>
{/literal}	
<div id="aHeader">
	<button class="add">Add New Article</button>
	<h2>articles</h2>
</div>
<div id="news">
	<div class="toolbox">
        <div id="statusSaving"  > Saving </div>
        <div id="statusDeleting"> Deleting </div>
        <div id="statusCreating"> Creating </div>
    	<!-- <a href="#new"><img src="{$pathAddon}/images/icons/medium/Add.png" /></a> -->
    </div>
    <ul id="articleBar">  
       	{foreach from=$news item=article}
        	<li id="article-{$article.id}"><a href="#news-{$article.id}">{$article.label}</a></li>

        {/foreach}
	</ul>
    
    {foreach from=$news item=article}
        <div id="news-{$article.id}">
            <div class="controls">
            	<button class="update">Save Changes</button>
                <button class="delete">Delete</button>
                <div class="control">
                	<input name="status" type="checkbox"  autocomplete="off" {if $article.status == 1}checked='true' {/if} /> Enabled
                </div>
                
            </div>
            <div class="stats">
                <div class="stat created">
                    <div class="title">Created On:</div>
                    <div class="content">{$article.created|date_format:"%A, %B %e, %Y at %T"}</div>
                </div>
                <div class="stat modified">
                    <div class="title">Last Modified On:</div>
                    <div class="content">{$article.modified|date_format:"%A, %B %e, %Y at %T"}</div>
                </div>
            </div>
            <br clear="all" />
            
            <textarea autocomplete="off" style="width:100%;height:400px;">{$article.data}</textarea>            
            
         </div>

    {/foreach}
</div>

<div class="instructions">
	<ul>
    	<li>To edit a article's label, double-click the article.</li>
        <li>Drag and drop the news to re-order them</li>
    </ul>
</div>

{* templates *}
<div style="display:none;">
	<div id="articleContentTemplate">
            <div class="controls">
            	<button class="update">Save Changes</button>
                <button class="delete">Delete</button>
                 <div class="control">
                	<input name="status" type="checkbox"  autocomplete="off" {if $article.status == 1}checked='true' {/if} /> Enabled
                </div>
            </div>
            <div class="stats">
                <div class="stat created">
                    <div class="title">Created On:</div>
                    <div class="content">Just created</div>
                </div>
                <div class="stat modified">
                    <div class="title">Last Modified On:</div>
                    <div class="content">Just created</div>
                </div>
            </div>
            <br clear="all" />
            
            <textarea style="width:100%;height:400px;"></textarea>
            
    </div>
</div>

{* dialogs *}
<div id="aLoading">
  <p align="center">Loading...</p>
</div>	



{$status}
<script>

var svrStatus = '{$status}';
var svrUrl = '{$url}';
var svrAddonUrl = '{$pathAddon}';
	
// {literal}	
	ampse.news = { 
					messages:{
						deleteWarning: "Are you sure you want to delete this article?"		
					}
				 }; 
				 
	
	$J(function() {
		ampse.generateCache(['news', 'articleBar', 'aLoading', 'statusSaving', 'statusDeleting', 'statusCreating']);
		ampse.news.createEvents();	
		ampse.cache.aLoading.dialog({
			autoOpen: false,
			hide: 'puff',
			modal:true
		});
		ampse.cache.articleBar
			.sortable({
				update: ampse.news.save	,
				items: 'li',
				containment: 'parent'
			});
		
		ampse.cache.news.find('textarea').tinymce({
				// General options
				script_url: svrAddonUrl + '/scripts/tiny_mce/tiny_mce_gzip.php',
				theme : "advanced",
				plugins : "safari,style,layer,articlele,save,advhr,advimage,advlink,inlinepopups,insertdatetime,media,searchreplace,contextmenu,paste,xhtmlxtras,template",
			
				// Theme options
				theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,|,undo,redo,|,link,unlink,image,cleanup,code,|,insertdate,inserttime",
				theme_advanced_buttons2 : "",
				theme_advanced_buttons3 : "",
				theme_advanced_buttons4 : "",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,
				theme_advanced_path : false,
			
				// Example content CSS (should be your site CSS)
				content_css : "/ps/global.css",

			});
		ampse.initStyles();
												

	});
	ampse.initStyles = function() {
		$J('button.add').button({ 
				icons: {
					primary: 'ui-icon-plusthick'
				}
			});
		$J('button.update').button({ 
				icons: {
					primary: 'ui-icon-check'
				}
			});
		$J('button.delete').button({ 
				icons: {
					primary: 'ui-icon-trash'
				}
			});
	};
	
	ampse.news.createEvents = function() {
		$J('#aHeader button.add').click( ampse.news.create );
		ampse.cache.articleBar.find('li a').on('dblclick', ampse.news.rename );
		ampse.cache.news.find('div.controls button.delete').on('click', ampse.news.delete );
		ampse.cache.news.find('div.controls button.update').on('click', ampse.news.update );
		ampse.cache.news.find('div.controls input[name=status]').on('click', ampse.news.status );
	};
	
	// new article
	ampse.news.create = function() {
		$J('#statusCreating').fadeIn('fast');
		$J.post( svrUrl, {create: 1},
			function(data) {
				var newarticle = $J('#articleContentTemplate').clone().prop('id', 'news-'+data); //clone from template and make new id
				ampse.cache.news.append(newarticle);
				newarticle.find('textarea').prop('id', 'mce-'+data);
				ampse.cache.news.news("add", "#news-"+data, "Untitled article").news('select',ampse.cache.news.news('length')-1); // add the article and select it
				ampse.cache.news.find('a[href=#news-'+data+']').parent().prop('id', 'article-'+data);  // alter the new article's li id attr
				tinyMCE.execCommand('mceAddControl', false, 'mce-'+data);
				$J('#statusCreating').fadeOut('slow');
			}, 'text');
		
	}; 	
	
	// save/update article
	ampse.news.update = function() {
		ampse.cache.statusSaving.fadeIn('fast');
		var $article = $J(this).closest('.ui-news-panel');
		var articleId = $article.prop('id').substring(5);
		var articleContent = $article.find('textarea').html();
		$J.post( svrUrl, {update: articleId, data: articleContent},
			function(data) {
				ampse.cache.statusSaving.fadeOut('slow');
				if(data.success) {
					$article.find('div.stat.modified .content').html(data.data.modified)
						.animate({opacity: .2}, 500, 'swing', function() { $J(this).animate({opacity: 1}, 1000) });
				}
				
				//animate modified
			}, 'json');		
	}
	
	// delete article
	ampse.news.delete = function() {		
		var articleIndex = ampse.cache.news.news('option', 'selected');
		var articleId = $J(this).closest('.ui-news-panel').prop('id').substring(5);
		
		if( confirm(ampse.news.messages.deleteWarning) ){
			$J('#statusDeleting').fadeIn('fast');
			$J.post( svrUrl, { delete: articleId },
				function(data) {
					$J('#statusDeleting').fadeOut('slow');
					if( data == 1) {
						ampse.cache.news.news('remove', articleIndex).news('select', articleIndex-1);
					}else {
						alert(data);	
					}					
				}, 'text');
		}
	}
	
	//rename article
	ampse.news.rename = function() {
		var $a = $J(this);
		var newLabel = prompt('article Label:', $a.text() );
		if( newLabel.length > 0 ) {
			var id = $a.parent().prop('id').substring(4);
			$J('#statusSaving').fadeIn('fast');
			$J.post( svrUrl, { update: id, label: newLabel},
				function(data) {
					$J('#statusSaving').fadeOut('slow');
					$a.html(newLabel);
				}, 'text');
		}
	}
	
	// save article order
	ampse.news.save = function() {
		$J('#statusSaving').fadeIn('fast');
		var idResult = ampse.cache.articleBar.sorarticlele('toArray');
		var ids = new Array();
		$J.each(idResult, 
			function(i,v) {
				ids.push( v.substring(4) );		  
			 })
		$J.post(svrUrl, {order: ids.join(',')},
			function(data) {
				if(data != 1) {
					alert(data);
				}
				$J('#statusSaving').fadeOut('slow');

			}, 'text');
		
		
	}
	
	// update status
	ampse.news.status = function() {
		ampse.cache.statusSaving.fadeIn('fast');
		var $article = $J(this).closest('.ui-news-panel');
		var articleId = $article.prop('id').substring(5);
		var checkBox = $J(this);
		var status = 0;
		if( checkBox.prop('checked') ) {
			status = 1;	
		}
		$J.post( svrUrl, {update: articleId, status: status},
			function(data) {
				ampse.cache.statusSaving.fadeOut('slow');
				if(data.success) {
					$article.find('div.stat.modified .content').html(data.data.modified);
					checkBox.parent().animate({opacity: .3}, 1000, 'swing', function() { $J(this).animate({opacity: 1}, 1000) });
				}else{
					checkBox.prop('checked', !checkBox.prop('checked'));
				}
				
				//animate modified
			}, 'json');		
	};
	
	
	
 
// {/literal}
</script>
