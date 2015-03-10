{$messages}

<div id="sample">
	{literal}
   <script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script> <script type="text/javascript">
//<![CDATA[
  bkLib.onDomLoaded(function() {
        new nicEditor({buttonList : ['fontSize','bold','italic','underline','strikeThrough','subscript','superscript','left','right','center','link','unlink','html']}).panelInstance('area4');
  });
  //]]>
  </script>
  {/literal}
  <form method="post" enctype="multipart/form-data" action="?page=addon_content_settings&mc=addon_cat_ppDogClicker" id="change_detail_form">
      <textarea style="width:400px;height:400px" cols="50" id="area4" name="content">{$competition}</textarea>
      <input type="submit" name="auto_save" value="Save" onclick="nicEditors.findEditor('area4').saveContent();"/>
  </form>
  <br/><br/>
  
  
</div>