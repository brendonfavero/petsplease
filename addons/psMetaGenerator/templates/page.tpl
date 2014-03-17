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
	  .heading {
			font-size:14px;
			padding:5px 0px;
	  }
	  .simpleSmall span.set {
		 cursor:pointer;  
	  }
	  
    </style>
{/literal}
{if $edit}
<h3 style="margin-bottom:3px;">{$form.name}</h3>
<div class="simpleSmall">{$form.description}</div>
    <form method="post" enctype="multipart/form-data" >
        <strong>Title:</strong><br />
            <input type="input" name="title" style="width:100%" value="{$form.title}" class="ps_input" /><br />
        <strong>Description:</strong><br />
             <textarea name="descr" id='descr' style="width:100%;" rows="4">{$form.descr}</textarea><br />
        <strong>Keywords:</strong><br />
             <textarea name="keywords" id='keywords' style="width:100%;" rows="4">{$form.keywords}</textarea><br />
        <input type="hidden" name="page_id" value="{$form.page_id}" />
        <input type="hidden" name="addonTag" value="{$form.addonTag}" />
        <input type="submit" name="save" value="Save Changes" class="ps_button"  />
        <input type="submit" name="reset" value="Reset" class="ps_button"/>
    </form>
{/if}
<div class="heading">
Pages Section: <select id="pages" onchange="window.location.href = 'index.php?page=addon_psMetaGenerator_page&mc=addon_cat_psMetaGenerator&section='+this.options[this.selectedIndex].value">
						<option value="1" {if $section == 1}selected="true"{/if}>Pages</option>
                        <option value="ap" {if $section == 'ap'}selected="true"{/if}>Addons</option>
						<option value="2" {if $section == 2}selected="true"{/if} >Purchase/Cart</option>
						<option value="3" {if $section == 3}selected="true"{/if} >Registration</option>
						<option value="4" {if $section == 4}selected="true"{/if} >User</option>
						<option value="5" {if $section == 5}selected="true"{/if} >User Auth / Region</option>
						<option value="7" {if $section == 7}selected="true"{/if} >Communication</option>
						<option value="8" {if $section == 8}selected="true"{/if} >Filters</option>
						<option value="9" {if $section == 9}selected="true"{/if} >Expired Listings</option>
						<option value="10" {if $section == 10}selected="true"{/if} >Listing Management</option>
						<option value="11" {if $section == 11}selected="true"{/if} >User Management</option>
						<option value="12" {if $section == 12}selected="true"{/if} >Extra Pages</option>
						<option value="13" {if $section == 13}selected="true"{/if} >Feedback</option>
						<option value="14" {if $section == 14}selected="true"{/if} >Bidding</option>
				  </select>
            </div> 
{foreach from=$pages item=page}
    <div style="border: 1px dashed green; padding: 4px 10px; height:31px;clear:both;margin-bottom:7px;">
		<div style="float:right; margin-top:-8px; margin-left:10px; margin-right:-15px;">
                     <form method="post"><input type="hidden" name="page_id" value="{$page.page_id}" /> <input type="hidden" name="addonTag" value="{$page.addonTag}" /><input type="submit" name="open" value="Edit" class="ps_button"/></form>
        </div>		

        <div style="float:right; text-align:right;">
            <div class="simpleSmall" >
                {if $page.title != ""}
                   <span class="set" title="{$page.title}"> Title is set</span>
                {else}    
                   <span style="color:#900;">No title set</span>
                {/if}
                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {if $page.descr != ""}
                    <span class="set"  title="{$page.descr}"> Description is set</span>
                {else}    
                   <span style="color:#900;" >No description set</span>
                {/if}
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {if $page.keywords != ""}
                     <span class="set" title="{$page.keywords}">Keywords is set</span>
                {else}    
                    <span style="color:#900;">No keywords set</span>
                {/if}
             </div>
        
        </div>	
        
          <strong>{$page.name}</strong> <br />
         <div class="simpleSmall">
         {if $page.description != ""}
         	{$page.description}
         {else}
         	{$page.admin_label}
          {/if}
         </div>
         
         
   </div>
{/foreach}
<script>
  document.getElementById('pages').
</script>


