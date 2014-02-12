<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" ></script>
<script>  jQuery.noConflict();	var $J = jQuery; </script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.15/jquery-ui.min.js" ></script>
<script type="text/javascript" src="{$pathAddon}/scripts/aAdmin.js"></script>
<script type="text/javascript" src="{$pathAddon}/lib/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="{$pathAddon}/lib/ckeditor/adapters/jquery.js"></script>
<script type="text/javascript" src="{$pathAddon}/scripts/plupload/plupload.full.js"></script>

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
		
		
		div.fieldset {
			margin:15px 0px 5px 0px;
			padding:5px;
			border:1px solid #AAA;
			background:#FFF;
			border-radius:5px;
			-moz-border-radius:5px;
			-webkit-border-radius:5px;	
		}
		div.fieldset div.legend {
			float:left;
			margin-top:-14px;
			height:16px;
			line-height:16px;
			margin-left:-10px;
			background:#DDD;
			border:1px solid #AAA;
			border-radius:3px;
			-moz-border-radius:3px;
			-webkit-border-radius:3px;	
			padding:0px 10px;	
			-moz-box-shadow: 1px 1px 3px #ccc;
		  -webkit-box-shadow: 1px 1px 3px #ccc;
		  box-shadow: 1px 1px 3px #ccc;
		}
	
    div.fieldset.half {
      float:left;
      margin-right: 2%;
      width:45%;
    }
		/* new */
		
		 #ampse_container {
			padding-left:200px;    
			font-family:Arial;
		}
			
			#ampse_categories {
				float:left;
				margin-left:-200px; 
			}
				#ampse_categories h2 {
					text-align:center;
					color:#444;
					margin:0px;
					padding:5px;
					/* text-shadow: #AAA 1px 1px; */
				}
				#ampse_categories .toolbox {
				  clear:both;
				  border-top:1px solid #CCC;	
				  margin-left:-5px;
				}
				#ampse_categories .toolbox button {
					width:48px;
					border:none;
					background:none;
				}
				#ampse_categories .toolbox button:hover {
					width:48px;
					border:none;
					background:#BBB;
					cursor:pointer;
				}
				#ampse_categories .status > div{
				  text-align:center;
				  font-family:"Monaco", "Courier New", Courier, monospace;
				  font-size:10px;
				  padding:2px;
				}
				#ampse_categories ul {
					list-style:none;  
					padding:0px;
					margin:0;
					width:200px;
				}
				#ampse_categories ul li {
					font-size:1em;
					padding:5px 4px;
					cursor:pointer;
					border:1px solid #FFF;
					border-right:none;
				}
				#ampse_categories ul li.ui-state-active {
					border:1px solid #CCC;
					border-right:none;	
				}
				#ampse_categories ul li.ui-state.hightlight {
					border:1px solid #C60;
					background:#FC6;	
				}
			
				#ampse_categories ul li:first-child {
					border-top:none;
				}
				#ampse_categories ul span.current {
					display:none;  
				}
				#ampse_categories ul li.current {
					background:#DDD;
					color:#333;
					font-weight:bold;
					-webkit-border-top-left-radius: 5px;
					-webkit-border-bottom-left-radius: 5px;
					-moz-border-radius-topleft: 5px;
					-moz-border-radius-bottomleft: 5px;
					border-top-left-radius: 5px;
					border-bottom-left-radius: 5px;
				}
				#ampse_categories ul li.current span.current {
					float:right;
					font-size:28px;
					color:#FFF;
					font-weight:bold;
					display:block;
					line-height:6px;
				}
			#ampse_articles {
					border:4px solid #DDD;
				-moz-border-radius:5px;
				-webkit-border-radius:5px;
				min-height:400px;
				background:#F5F5F5;
			}
			#ampse_articles h2 {
					background:#DDD;
					text-align:center;
					color:#666;
					margin:0px;
					padding:5px;
					text-shadow: #333 1px 1px;
					border-bottom:1px solid #AAA;
				}
				#ampse_articles h2 .toolbox {
				  float:right;	
				}
			#ampse_articles div.article {
				border:1px solid #f4cd92;
				background-color:#FFF;
				height:30px;
				padding-left:40px;
				margin:4px 4px 8px 4px;
				-moz-border-radius:5px;
				-webkit-border-radius:5px;
			}
			#ampse_articles div.article.pending {
			  background-color: #f2e7d6;
			}
			#ampse_articles div.article.highlight {
				background:#39F;
				border-color:#C00;
			}
				#ampse_articles div.article h3 {
				   margin:2px 0px 2px 0px;
				   padding:0px;
				   height:1.2em;
				   overflow:hidden;	
				   font-size:1em;
				}
				#ampse_articles div.article .preview {
					float:right;
					color:#777;
					font-size:15px;	
					text-decoration:none;
					margin:5px;
				}
				#ampse_articles div.article .handle {
					border-right:1px solid #FF9900;
					background: #eed4ae;
					color:#e1ba82;
					width:30px;
					margin-left:-40px;
					height:30px;
					float:left;
					font-size:26px;
					color:d78203;
					text-shadow:#e09422 -1px 1px;
					text-align:center;
					cursor:move;
				}
				#ampse_articles div.article .created, #ampse_articles div.article .published {
				    float:left; 
					font-size:10px;
					 font-family:"Monaco", "Courier New", Courier, monospace;
					 margin-right:10px;
					 margin-left:5px;
				}

		form .formTile {
			width:204px;
			float:left;	
			text-align:right;
			line-height:1.9em;
			height:30px;
		}
		form .formTile label {
			margin-left:5px;	
			font-weight:bold;
		}
		form .formTile input[type=text] {
			width:120px;
			border:1px solid #AAA;
			border-radius:3px;
			-moz-border-radius:3px;
			-webkit-border-radius:3px;	
		}
		form .formTile input[name="publish"] {
			width: 65px;
		}
		form .formRow {
			padding:5px;
			border:1px solid #999;	
			border-radius:5px;
			-moz-border-radius:5px;
			-webkit-border-radius:5px;	
			font-size:1.5em;
			margin:5px 0px;
		}
    form .formColumn {
      width:48%;
      padding:1%;
      float:left;
    }
		form .formRow input[type=text] {
			background:none;
			border:none;
			width:75%;
			color:#444;
			margin-left:20px;
			margin-bottom:1px;
		}
		form .formRow input[type=text]:focus {
			outline:none;
			border-bottom:1px solid #AAA;
			margin-bottom:0px;
			background:#F9F9F9;	
		}
		form textarea.mce {
			width:100%;
			height:300px;	
		}
		#fileList {
      list-style:none;
      margin:0; padding:0;
    }
    #fileList li {
      display:block;
      margin:5px; padding:8px;
      background:#EEE;
      clear:left;
    }
    #fileList li .fileRemove {
      color: #777;
      font-weight:bold;
      float:right;
      display:block;
      cursor: pointer;
    }
    #fileList li .fileRemove:hover {
      color:#333;
    }
		#newsAddEditDialog .instructions {
			padding:5px 0px; 
			font-size:.8em;	
		}
		.code {
			font-family: Monaco, "Courier New", Courier, monospace	;
		}
		.ui-datepicker-calendar {
			background: #FFF;	
		}

		.ui-dialog-buttonset {
  position:absolute;
  bottom:20px;
  right:0;
  
}

.ui-button.ui-widget.ui-state-default.ui-corner-all.ui-button-text-only {
  width: 75%;
  font-size: 15px;
  height: 50px;
  float:right;
}
		
    </style>
{/literal}	

<div id="ampse_container">

	<div id="ampse_categories">
		<h2>CATEGORIES</h2>
        <ul>
        	<li>Unfiled<input type="hidden" name="id" value="0" /></li>
        	{foreach from=$categories item=category}
            	<li>{$category.label}<input type="hidden" name="id" value="{$category.id}" /></li>
            {/foreach}
        </ul>
        <div class="toolbox">
        	<button name="add">Add</button><button name="edit">Edit</button><button name="delete">Delete</button><button name="order">Sort</button>
        </div>
        <div class="status">
        	
        </div>
	</div>
    
    
	<div id="ampse_articles">
     	<h2><span class="toolbox"><button name="add">Add</button></span>ARTICLES</h2>
    	         

    	<div id="ampse_articlesStatus">
    	
        </div>
        
        <div class="content">
        
        </div>
       
    </div>
	
    
    
	
</div>

{* templates *}
<div style="display:none;">
	<div id="tabContentTemplate">
            <div class="controls">
            	<button class="update">Save Changes</button>
                <button class="delete">Delete</button>
                 <div class="control">
                	<input name="status" type="checkbox"  autocomplete="off" {if $tab.status == 1}checked='true' {/if} /> Enabled
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
<div style="display:none;">
	<div id="newsAddEditDialog" title="Format Article">
    	<form>               
            <div class="formRow">
                Heading <input name="heading" type="text" />
            </div>
                
            
            
            <textarea class="mce" name="raw"></textarea>
            <div class="insructions">
            	<p>The first portion of the article will be used automatically for preview. To specifcy an text block to use, surround it with the text <span class="code">[PREVIEW]</span> and <span class="code">[/PREVIEW]</span>. For example, the text "We have posted [PREVIEW]a new news article for you![/PREVIEW] Check it out!" will place "a new news article for you!" as the preview/leading text. If <span class="code">[PREVIEW]</span> is omitted, the begging of the article will be assumed.</p>
                <p>The first image found will be used as the preview image. If you require a different image article be used, add the word "preview" to it's <i>Stylesheet Classes</i> option under the advanced tab of the image's properties. If you require that the image be used for preview, but NOT displayed in the article, add this text to the end of the <i>Style</i> option: <span class="code">display:none;</span></p>
                <p>You can also use the thumbnail image by appending <span class="code">_tn</span> to the file name. (e.g. image_name.jpg becomes images_name_tn.jpg ) </p>
                <p><a href="/ampse/training/newsAdmin.mov" target="training" onclick="alert('Upload pending');return false;">Or watch a video tutorial of how to operate this section</a></p>
            </div>
            
      
            <div class="fieldset half">
            	<div class="legend">Options</div><br clear="left" />
                <div class="formTile">
                	<label>Publish <input type="text" name="publish" class="datePicker" /><input name="published" type="hidden" class="datePickerAlt" /></label> at 
					
					<select name="publishedtime">
						{foreach from=$times item=time key=val}
							<option value="{$val}">{$time}</option>
						{/foreach}
					</select>					
                </div>
                <div class="formTile">
                	<label>Enabled <input type="radio" name="status" value="1" /></label>
                    <label>Disabled <input type="radio" name="status" value="0" /></label>
                </div>
				<div class="formTile">
                	Comments? 
                    <label>Yes <input type="radio" name="comments" value="1" /></label>
                    <label>No <input type="radio" name="comments" value="0" /></label>
                     
                </div>
				<div class="formTile">
                	Locale
					<select name="locale">
						<option value="0" selected="selected">ALL</option>
						<option value="1">AU</option>
						<option value="2">NZ</option>
					</select>
                </div>
                <br clear="all" />
            </div>
            <input type="hidden" name="id" />          
        </form>
    </div>
</div>




{$status}

<script>

var svrStatus = '{$status}';
var svrUrl = '{$url}';
var svrAddonUrl = '{$pathAddon}';
	
// {literal}	
	ampse.articles = { 
		messages:{
			deleteWarning: "Are you sure you want to delete this article?"		
		}
	 }; 
	ampse.categories = {
		messages: {
			deleteWarning: "Are you sure you want to delete this category?",	
		}
	};
				 
				 
	(function($){ 
	
		
		var $categories, $categoriesToolbox, $categoriesStatus,
			$articlesStatus, $articles;
			
		
		$(document).ready(function() {
			// generate cache
			$categories = $('#ampse_categories ul');
			$categoriesToolbox = $('#ampse_categories .toolbox');
			$categoriesStatus = $('#ampse_categories .status');
			$articles = $('#ampse_articles .content');
			$articlesStatus = $('#ampse_articlesStatus');
			ampse.categories.initEvents();
			ampse.articles.initEvents();
			
			$categories.find('li:first').click();
		});
		ampse.app = {};
		ampse.app.com = function(data, callback, type) {
			if ( type === undefined ){
				 type = 'json';
			}
			$.post( svrUrl, data, callback, type );	
		}
		
		ampse.categories.initEvents = function initEvents() {
			// category buttons
			$categoriesToolbox.find('button[name=add]').click( ampse.categories.add )
				.end().find('button[name=edit]').click( ampse.categories.edit )
				.end().find('button[name=delete]').click( ampse.categories.remove )
				.end().find('button[name=order]').click( ampse.categories.order )
				.end().find('button[name=saveOrder]').click( ampse.categories.saveOrder );
			
			// category click/open	
			$categories.delegate('li', 'click', ampse.articles.list );		
			$categories.find('li').droppable({
				hoverClass: "ui-state-active",
				drop: ampse.categories.drop
			});
		};
		
		ampse.categories.add = function() {
			var name = prompt('What should it be called?');
			if( !name || name == "") {
			   ampse.categories.status("Sorry, empty names not allowed.");
			   return false;
			}
			$.post( svrUrl, { category_action: "add", label: name }, 
				function(data) {
					if( data.result && parseInt(data.result) > 0){
						$('<li>' + name + '<input type="hidden" name="id" value="' + data.result + '" /></li>')
							.hide().appendTo($categories).slideDown();
						
					}
				}, 'json');
		};
		
		ampse.categories.drop =  function( event, ui ) {
			var $this = $(this);
			$this.addClass( "ui-state-highlight" , 500	);
			setTimeout( function() { $this.removeClass( "ui-state-highlight", 500 ); }, 1000 );
			var move = function move($elem) {
				var id = $elem.find('input[name="id"]').val(),
					category = $this.find('input[name="id"]').val();
				ampse.app.com( { article_action: "move", id: id, category: category }, function(data) {
					if( data.result ){
						$elem.remove();	
					}else {
						$elem.slideDown();
						ampse.articles.status("Error moving article.");	
					}
				});
			}
			$( ui.draggable ).closest('.article').slideUp('fast', function() {				
				move($(this));
			});
			
		}
		ampse.categories.edit = function() {
			var $li = $categories.find('li.current');
				label = $li.clone().find('span.current').remove().end().text(),
				id = $li.find('input[name=id]').val(),
				newName = prompt('What should it be called?', label);
		
			if( newName && newName.length > 0) {
				$.post( svrUrl, { category_action: "edit", label: newName, id: id }, 
				function(data) {
					if( data.result ){
						$li.html(newName + '<input type="hidden" name="id" value="' + id + '" /');
						ampse.categories.status(name + ' saved.');						
					}else {
						ampse.categories.error(data);
					}
				}, 'json');
			}else {
				ampse.categories.status( "Sorry, empty names not allowed." );	
			}
			
		};
		
		ampse.categories.remove = function() {
			var $li = $categories.find('li.current'),
				id = $li.find('input[name=id]').val(),
				sure = false;
			if( id ) {
				sure = confirm('You sure?');
			} else {
			    ampse.categories.status('You gotta select one first..');
		    }
		
			if( sure ) {
				$.post( svrUrl, { category_action: "delete", id: id }, 
				function(data) {
					if( data.result ){
						$li.slideUp( function() { $li.remove(); } );
					}
				}, 'json');
			}
		};
		
		ampse.categories.order = function() {
			$categories.sortable({ containment: "parent", click: function(e) { 
				e.stopPropagation();
			 }});
			$(this).prop('name', 'saveOrder').text('Save')
				.unbind('click').click( ampse.categories.saveOrder );
		};
		
		ampse.categories.error = function(data) {
			if( data.messages ) {
				$.each( data.messages, function() {
					ampse.categories.status(this);	
				});
			}else {
				ampse.categories.status("Error");	
			}
		}
		
		ampse.categories.saveOrder = function() {
			var order = $categories.find('li').map(function() { return $(this).find('input[name=id]').val() }).get().join(',');
			$.post( svrUrl, { category_action: "reorder", order: order }, 
				function(data) {
					if( data.result ){
						ampse.categories.status('Order Updated.');
					}else {
					   ampse.categories.error(data);	
					}
				}, 'json');
			$categories.sortable('destroy');
			$(this).prop('name', 'order').text('Sort')
				.unbind('click').click( ampse.categories.order );;	
		}
		/** 
		* displays status message for the category box
		*/
		ampse.categories.status = function(message) {
			var $div = $('<div>' +  message + '</div>').hide()
				.appendTo( $categoriesStatus )
				.slideDown();
			setTimeout( function() { $div.slideUp(function() { $(this).remove(); delete $div; }); }, 5000 );
			return message;
		}
		
		ampse.articles.initEvents = function() {
			$('#ampse_articles .toolbox button[name=add]').click( ampse.articles.add );
      $( '#newsAddEditDialog form' ).submit( function() { return false; });
      		$articles.delegate( "div.article", "dblclick", ampse.articles.edit );
			$articles.delegate( "a.editbutton", "click", ampse.articles.edit );
      ampse.articles.files.init();
		}
		
		ampse.articles.list = function(id) {	
			if( id > 0 ) {
				// console.log(id);
				var $li =  $('#ampse_categories input[name="id"][value="' + id + '"]').closest('li');
			}else {
				// console.log('omg', id);
				var $li = this.tagName !== "LI" ? $('#ampse_categories li.current') : $(this);
				id = $li.find('input[name=id]').val();
			}
				
			$li.addClass('current').siblings('.current').removeClass('current');					
			
			// retreive categories contents and display
			$.post( svrUrl, { article_action: "get", category: id }, 
				function(data) {
					if( data.result && data.result.length) {
						// render articles
						var articles = $.map( data.result, function(row, i) { 
							return ampse.articles.make(row); 
						});
						/*var articles = [];
						$.each( data.result, function(i,row) {							
							articles.push(ampse.articles.make(row));
						});*/
						$articles.html(articles.join('')).find('.handle');
						ampse.articles.makeDraggable( $articles );
					}else {
						$articles.html('');
						ampse.articles.status('No articles in ' + $li.text().replace(/[^-a-zA-Z0-9_]/, '') );	
					}
				}, 'json' );
				

		};
		
		/** 
		* displays status message for the category box
		*/
		ampse.articles.status = function(message) {
			var $div = $('<div>' +  message + '</div>').hide()
				.appendTo( $articlesStatus )
				.slideDown();
			setTimeout( function() { $div.slideUp(function() { $(this).remove(); delete $div; }); }, 5000 );
			return message;
		}
		
		ampse.articles.add = function() {
			var category = $categories.find('li.current input[name="id"]').val() || 0;
			ampse.app.com( { article_action: "add", category: category }, function(data) {
				if( data.result && data.result.length ) {
					var $a = $( ampse.articles.make( data.result[0] ) )
							.addClass('highlight', 1000)
							.prependTo( $articles );
					ampse.articles.makeDraggable( $a );
					setTimeout(function() { $a.removeClass('highlight', 4000); }, 3000);
				}else {
					ampse.articles.status('Problem with article add');	
				}
			});
		}

    ampse.articles.files = (function() {
      var $fl;

      return {
        clear: function() {
         $fl.empty();
        },

        add: function( file ){
          $fl.append( '<li><span class="fileRemove">X</span><a target="hz_attach" href="/ampse/pdfs/' + file + '" data-href="' + file  + '">' + file + '</a></li>' );
        },

        remove: function( $li, id ) {
          var file = $li.find('a').prop( 'data-href' );
		    	ampse.app.com({ removeFile: file, id: id }, function(data) {
            if ( data && data.result === true ) {
              $li.remove();
            }else {
              alert( 'Error:' + ( data ? data.result : "Comm error - maybe you got logged out?" ) );
            }
          });
        },

        populate: function( filesJSON ) {
          $fl.empty();
          var files = filesJSON.length > 0 ? $.parseJSON( filesJSON ) : [];
          $.each( files, function(i,v) {
            ampse.articles.files.add( v );
          });
        },

        init: function() {
          $fl = $('#newsAddEditDialog').find( '#fileList' );
          $fl.click( function(e) {
            var $target = $(e.target),
                id = $target.closest('form').find( 'input[name="id"]' ).val();
            if ( /span/i.test( e.target.nodeName ) && $target.is( '.fileRemove' ) ) {
              ampse.articles.files.remove( $target.closest( 'li' ), id );
            }
          });

          // setup uploader
          	var uploader = new plupload.Uploader({
              runtimes : 'html5,flash,silverlight',
              browse_button : 'filePick',
              container : 'fileContainer',
              max_file_size : '10mb',
              url : '/admin/index.php?page=addon_ampseCustom_news&mc=addon_cat_ampseCustom&fileUpload=1&id=0',
              flash_swf_url : svrAddonUrl + '/scripts/plupload/plupload.flash.swf',
              silverlight_xap_url : svrAddonUrl + '/scripts/plupload/plupload.silverlight.xap',
              filters : [
                {title : "PDF", extensions : "pdf"}
              ]
            });
            ampse.articles.uploader = uploader;

            uploader.bind('Init', function(up, params) {
              $fl.closest('#fileContainer').prop('title', 'Using: ' + params.runtime );
            });
            

            $('#fileUpload').click(function(e) {
              uploader.start();
              e.preventDefault();
            });


            uploader.init();

            uploader.bind('FilesAdded', function(up, files) {
              $.each(files, function(i, file) {
                $('#filePending').append(
                  '<div id="' + file.id + '">' +
                  file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' +
                '</div>');
              });

              up.refresh(); // Reposition Flash/Silverlight
            });

            uploader.bind('UploadProgress', function(up, file) {
              $('#' + file.id + " b").html(file.percent + "%");
            });

            uploader.bind('Error', function(up, err) {
              $('#filePending').append("<div>Error: " + err.code +
                ", Message: " + err.message +
                (err.file ? ", File: " + err.file.name : "") +
                "</div>"
              );

              up.refresh(); // Reposition Flash/Silverlight
            });

            uploader.bind('FileUploaded', function(up, file, res ) {
              console.dir( res );
              var response = $.parseJSON( res.response );
              // it was successful and has a filename even!
              if ( response && response.result &&  response.file.filename ) {
                ampse.articles.files.add( response.file.filename );
              }
              $('#' + file.id).remove();
            });

        }
      }
    })();

		ampse.articles.edit = function(e) {
			var $dialog = $('#newsAddEditDialog'),
				$row = $(this),
				id = $row.find('input[name="id"]').val();

      // change the id in the uploader
      ampse.articles.uploader.settings.url = ampse.articles.uploader.settings.url.replace( /id=(\d+)(.*)?$/, "id=" + id + "$2" );
      
			if( !$dialog.is('.ui-dialog') ) {
				$dialog.dialog({
					autoOpen: false,
					modal: true,
					width: $(window).width() * .9,
					height: $(window).height() * .93,
					buttons: {
						"Save Changes, Open Next" : function(e) { 	e.openNext = true;
																	ampse.articles.save.call(this, e);
						},
					    "Save Changes" : ampse.articles.save,
						//"Clear Form"   : ampse.articles.clearForm,
						"Delete Article" : ampse.articles.remove
					},
					open: function() {
						$(document.body).css('overflow', 'hidden');	

					},
					close: function() {
						$(document.body).css('overflow', 'auto');	
					}
				})
				.find('.datePicker').each(function() {
					var $this = $(this);
					$this.datepicker({ dateFormat: 'dd/mm/yy' , 
														  showButtonPanel: true,
														  altField: $this.siblings('.datePickerAlt'),
														  altFormat: "@" });
				}).end()
				.find('textarea.mce').ckeditor({
					filebrowserUploadUrl : svrUrl,
					'stylesCombo_stylesSet' :'my_styles',
					
				});
			}
			
			
			// set time DD to current
		
			
			// results
			ampse.app.com({ article_action: "get", id: id }, function(data) {
				if( data.result && data.result.length ) {
          var article = data.result[0];
					ampse.articles.clearForm(true, $dialog);
					$dialog.find('form').populate( article );
					$dialog.find('input[name="publish"]').datepicker('setDate', $.datepicker.formatDate( "dd/mm/yy", new Date( article.published * 1000 ) ));
					if ( !article.publishedtime || !(+article.publishedtime) ) {
						$dialog.find( 'select[name="publishedtime"]' ).val( ampse.util.fourDigitTime( 15 ) );
					}
          // file list
          ampse.articles.files.populate( article.files );
         	} else {
					ampse.articles.status("Failed to retreive article - try logging out and back in");
					$dialog.dialog('close');	
				}
			});
			
			$dialog.dialog('open').data('row', id);
      ampse.articles.uploader.refresh();
		}
		
		ampse.articles.save = function (e) {
			var $dialog = $(this).closest('.ui-dialog-content');
			
			// console.log($dialog);
			// todo validation
			$dialog.find('.datePickerAlt').each(function() {
				this.value = this.value / 1000;
			});
			ampse.app.com( $dialog.find('form').serializeObject({ article_action: "save" }), 
				function(data) {
					if( data.result ) {
						ampse.articles.list();
						ampse.articles.clearForm(true, $dialog);
						$dialog.dialog('close');
						if( e.openNext ) {
						   $articles.find('input[name="id"][value="' + $dialog.data('row') + '"]').closest('div.article').next().dblclick();
						}
					}
				}
			);
			
		}
		
		ampse.articles.makeDraggable = function ($items) {
			$items.find('.handle').draggable({ revert: "invalid" , helper: "clone"})
		}
		
		ampse.articles.make = function ( row ) {
			var	publishedClass = "pending",
				published = "pending";
				
			if(  row.published ){
				published = new Date(row.published * 1000).toDateString();
			}
			
			if ( row.status && row.status !== "0" ) {
			  publishedClass = "";
			}
			
			
			
			var article = "<div class='article " + publishedClass + "'> \
								<div class='preview'><a href='/news/" + row.hash + "' target='preview'>Preview</a></div> \
								<div class='handle'>&#926;</div> \
								<h3> " + row.heading + "</h3> \
								<div class='created'>Created: " +  new Date(row.created * 1000).toDateString() + "</div> \
								<div class='published'>Published: " + published + "</div> \
								<input type='hidden' name='id' value='" + row.id + "' /> \
							</div>";
			return article;
		}
		ampse.articles.clearForm = function ( byPass, $dialog ) {
			if( $dialog == undefined ) {
				$dialog = $(this).closest('.ui-dialog-content');
			}
			var pass = byPass === true ? true : confirm('sure?');
			if( pass ) {
				$dialog.find('form')[0].reset();
				$dialog.find('textarea.mce').val('');	
			}
			
		}
		
		ampse.articles.remove = function() {
			if( !confirm("Sure?") ) {
				return false;	
			}
			var $dialog = $(this).closest('.ui-dialog-content').dialog('close'),
				id = $dialog.find('input[name="id"]').val();
			ampse.app.com( {article_action: "remove", id: id }, function() { 
				$articles.find('input[name="id"][value="' + id + '"]').closest('.article').slideUp('slow', function() { $(this).remove() });
			});
		}

$(function() {
});

		
	})(jQuery);
	
	CKEDITOR.addStylesSet( 'my_styles',
[
    // Block Styles
    { name : 'Image Left', element : 'img', attributes : { 'class' : 'image_left' }},
    { name : 'Image Right' , element : 'img',attributes : { 'class' : 'image_right' } },

  
]);



	
	//config.stylesCombo_stylesSet = 'my_styles';
	
	
 
// {/literal}
</script>
