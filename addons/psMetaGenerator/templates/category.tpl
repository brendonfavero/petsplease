{* $Rev: 14561 $ *}
{* 
Note to template designers:
	You can over-ride an addon's smarty template by placing
	an identically named template file in your template set,
	under addons/ in a sub-directory named the same as the folder
	name for the addon.
	For example, to over-ride this template, you would create
	a file at:
	my_template_set/addons/example/hello_world_admin.tpl

 *}
 <link type="text/css" rel="stylesheet" href="/ps/custom-theme/jquery-ui-1.8.custom.css" />
<script type="text/javascript" src="/ps/jquery-1.9.1.min.js" ></script>
<script>  jQuery.noConflict();	var $J = jQuery; </script>
<script type="text/javascript" src="/ps/jquery-ui-1.9.2.custom.min.js" ></script>
<script type="text/javascript" src="{$pathAddon}/scripts/tiny_mce/jquery.tinymce.js" ></script>
<div>{$status}</div>
{literal}
	<style>
      .ps_input {
         border:1px solid #CCC;
          background:#FFF;
          padding:4px;
          color:#000;
          font-size:12px;
          font-family:Verdana, Geneva, sans-serif;
      }
      .ps_button {
          border-top:1px solid #060;
          border-left:1px solid #060;
          border-right:2px solid #030;
          border-bottom:2px solid #030;
          background:#DDD;
          color:#000;
          font-size:12px;
          padding:1px 4px 2px;
          font-family:Verdana, Geneva, sans-serif;
      }
	  .simpleSmall {
			font-size:10px; 
			color:#666;  
	  		height:15px;
			overflow:hidden;
	  }	
	  
    </style>
{/literal}
{if $edit}
<h3 style="margin-bottom:3px;">{$form.category_name}</h3>
<div class="simpleSmall">{$form.description}</div>
    <form method="post" enctype="multipart/form-data" >
        <strong>Title:</strong><br />
            <input type="input" name="title" style="width:100%" value="{$form.title}" class="ps_input" /><br />
        <strong>Description:</strong><br />
             <textarea name="descr" id='descr' style="width:100%;" rows="4">{$form.descr}</textarea><br />
        <strong>Keywords:</strong><br />
             <textarea name="keywords" id='keywords' style="width:100%;" rows="4">{$form.keywords}</textarea><br />
        <strong>Leading Content:</strong><br />
             <textarea name="extra" id='extra' style="width:100%;" rows="8">{$form.extra}</textarea><br />
        <input type="hidden" name="category_id" value="{$form.category_id}" />
        <input type="submit" name="save" value="Save Changes" class="ps_button"  />
        <input type="submit" name="reset" value="Reset" class="ps_button"/>
    </form>
{/if}

{foreach from=$pages item=page}
    <div style="border: 1px dashed green; padding: 4px 10px; height:31px;clear:both;margin-bottom:7px;">
		<div style="float:right; margin-top:-8px; margin-left:10px; margin-right:-15px;">
                     <form method="post"><input type="hidden" name="category_id" value="{$page.category_id}" /> <input type="submit" name="open" value="Edit" class="ps_button"/></form>
        </div>		

        <div style="float:right; text-align:right;">
            <div class="simpleSmall" >
                {if $page.title != ""}
                    Title is set
                {else}    
                   <span style="color:#900;">No title set</span>
                {/if}
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {if $page.descr != ""}
                    Description is set
                {else}    
                   <span style="color:#900;">No description set</span>
                {/if}
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {if $page.keywords != ""}
                    Keywords is set
                {else}    
                    <span style="color:#900;">No keywords set</span>
                {/if}
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {if $page.extra != ""}
                    Leading Content is set
                {else}    
                    <span style="color:#900;">No leading content set</span>
                {/if}
             </div>
        
        </div>	
        
         <strong>{$page.category_name}</strong> <br />
         <div class="simpleSmall">
         {if $page.description != ""}
         	{$page.description}
         {else}
         	{$page.admin_label}
          {/if}
         </div>
         
   </div>
{/foreach}

<script type="text/javascript">//<![CATA[
var svrStatus = '{$status}';
var svrUrl = '{$url}';
var svrAddonUrl = '{$pathAddon}';
//{literal}
$J(document).ready(function() {
	
		$J('#extra').tinymce({
				// General options
				script_url: svrAddonUrl + '/scripts/tiny_mce/tiny_mce_gzip.php',
				theme : "advanced",
				plugins : "safari,style,layer,table,save,advhr,advimage,advlink,inlinepopups,insertdatetime,media,searchreplace,contextmenu,paste,xhtmlxtras,template",
			
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

}); // end document.ready

//{/literal}
//]]></script>




