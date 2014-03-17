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
	  .block {
		padding:10px 0px;  
	  }
	  
    </style>
{/literal}
	These are the default settings to use when specific tag information cannot be obtained.<br />
    <form method="post" autocomplete="false">
    
   		 <div class="block">
        <strong>Site Name:</strong><br />
            <input type="text" name="siteName" style="width:100%" value="{$siteName}" class="ps_input" /></div>
            
    	<div class="block">
        <strong>Title:</strong><br />
            <input type="text" name="title" style="width:100%" value="{$title}" class="ps_input" /></div>
            
        <div class="block">
        <strong>Description:</strong><br />
             <textarea name="description" id='description' style="width:100%;" rows="4">{$description}</textarea></div>
             
         <div class="block">    
        <strong>Keywords:</strong><br />
             <textarea name="keywords" id='keywords' style="width:100%;" rows="4">{$keywords}</textarea></div>
        <div class="block">
        <strong>Auto-description length:</strong>
        	  <input name="descLength"  value="{$descLength}" style="width:50px;" type="text" /> Number of characters of listing description to add to listing's meta description)</div>
              
        <div class="block">
        	<strong>Auto-title length:</strong>
        	<input name="titleLength"  value="{$titleLength}" style="width:50px;" type="text" /> Number of characters to limit the entire generated title to 
        </div>
        
              
        <div class="block">
        	<strong>Auto-generate category titles</strong>
        	<input name="autoCategory"  value="on" type="checkbox" {if $autoCategory != ""}checked="checked"{/if} /> Auto-magically generates category titles - CATEGORY NAMEs - PARENT CATEGORY - SITE NAME
        </div>
        
    
        
        
        
        <input type="submit" name="save" value="Save Changes" class="ps_button"  />
    </form>




