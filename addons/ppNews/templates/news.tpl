{literal}
<style>
/* >> The Magnificent CLEARFIX: Updated to prevent margin-collapsing on child elements << j.mp/bestclearfix */
.clearfix:before, .clearfix:after {
  content: "\0020"; display: block; height: 0; visibility: hidden;
}

.clearfix:after { clear: both; }
/* Fix clearfix: blueprintcss.lighthouseapp.com/projects/15318/tickets/5-extra-margin-padding-bottom-of-page */
.clearfix { zoom: 1; }
	#news a {
		text-decoration:underline;
	}
	
	#news { background:none; }
	#news h2 {
		margin:0px;
		padding:0px;
	}
	#news div.published {
		font-size: .8em;
		margin-bottom:10px;
		padding:1px;
	}
	#news div.article ul {
		margin-left:10px;
	}
	html div.psContentBox div.news {
		padding:0px;

	}
	html div.psContentBox div.news ul {
		list-style-image:none;
		list-style:none;
		margin:10px 0px 0px 0px;
		padding:0px;
	}
	html div.psContentBox div.news li{
		padding:5px;
		font-size:1.1em;
		background:#F9F9F9;
		margin-top:2px;
		cursor:pointer;
	}
	html div.psContentBox div.news li:hover{
		background:#FCC;
	}
	html div.psContentBox div.news li.selected{
		background:#FFF;
		margin-left:-22px;
		color:#000;
		border-left:1px solid #FFF;
		z-index:3;
		border-top:1px solid #AAA;
		border-bottom:1px solid #AAA;
	}
	#news {
	   min-height:600px;
	}
	div.paginate {
		text-align:center;
		padding:10px 2x;
	}
	div.readmore {
		margin-top:10px;
		float:right;
		font-weight:bold;
		color:#666;
	}
	div.readmore a {
		color:#888;
	}
  #news div.sponsor {
    float:right;
    margin-right:10px;
    text-align:center;
  }
	#news div.comments {
		padding-top:25px;
	}
	#news div.comment {
		background:#EEE;
		border:1px solid #AAA;
		padding:5px;
		margin-bottom:15px;
	}
	#news div.own {
		background: #D2DCE5;
	}
	#news div.comment h6 {
		margin:0px;
		padding:0px;
	}
	#news div.admin h6 {
		text-align:right;
	}
	#news div.comment div.timestamp {
		font-size:.8em;
	}
	#news div.admin div.timestamp {
		text-align:right;
	}
	#news div.comments h2 {
		margin-top:20px;
	}
	#news div.comment .delete {
		font-weight:bold;
		color: #FF0000;
	}
	#news div.compose {
		border:1px dashed #AAA;
		background:#FFE;
		padding:6px;
	}
	#news div.compose textarea {
		width:100%;
		height:100px;
	}
	#news div.compose input {
		float:right;
	}
	div.fb {
		padding:5px;
	}
  .attachments .file {
    margin:4px 0px;
    background: url( '/images/pdf.jpg' ) no-repeat top left;
    padding-left:54px;
    line-height:48px;
  }
</style>
{/literal}
<!-- ---------->

<div class="innerColumn left newsLeft">
    
    <div id = "browsing_search">
    		<h4><span class="title">Browse Categories</span></h4>
        <div class="newscats">
            <ul class="categories">
       			 {foreach from=$categories item=category}
           			 <li class="{if $category.id == $currentCategory}selected{/if}"><a href="/index.php?a=ap&addon=ppNews&page=news&category={$category.hash}">{$category.label}</a></li>
        		{/foreach}
		</ul>
        <br />

		</div>
        	<h4 >
    			<span class="title">News Search</span>
    		</h4>
    		 <div class="news_search">
    		 
	    	<form action="/index.php?a=ap&addon=ppNews&page=news" method="get">
	    		<input type="hidden" name="a" value="ap" />
	    		<input type="hidden" name="addon" value="ppNews" />
	    		<input type="hidden" name="page" value="news" />

			    <input type="text" placeholder="Search" name="search" value="{$searchQuery}" style="width:206px; margin:10px 0 13px" />
			    <button>Search</button>
			</form>
			
		</div>
       
    	
	    
	</div>
	{addon addon="ppAds" tag="adspot" aid=4}

    
</div>

<div class="innerColumn center newsRight">
    <div class="psContentBox">
        <h2 class="title">
            PetsPlease News and Advice
        </h2>
        <div class="psContentBoxBody psListingDisplay" id="news">
            <div class="inner" id="news">
            {if $messages}
            	 <div style="margin:5px; background:#F0E7DB; padding:5px;">
                    {foreach from=$messages item=message}
                        <div class="message">{$message}</div>
                    {/foreach}
                </div>
            {/if}


            {if $mode == 'article' }
            	<h2><a href="/news/{$data.hash}">{$data.heading}</a></h2>
                <div class="published">Published on {$data.published|date_format:"%A, %B %e, %Y"} in <a href="/news/{$data.category_hash}">{$data.category_label}</a></div>
                {$data.article}
                <br clear="all" />
                {if strlen( $data.files ) > 0 }
                <div class="attachments">
                  <h3>Attachments</h3>
                  {foreach from=$fileList item=file}
                    <div class="file"><a href="/addons/ppNews/pdfs/{$file}" target="hz_attach">{$file}</a></div>
                  {/foreach}
                </div>
                <br clear="all" />
                {/if}               

                   <!-- Go to www.addthis.com/dashboard to customize your tools -->
				   <div class="addthis_sharing_toolbox"></div>
               
                {literal}
                <script>
					(function($) {
						$(document).ready(function() {
							$( '#news img' ).each( function() {
								var $img = $( this ),
								  	href = $( this ).attr( 'src' );

                // Check if img element is already inside of a link
                if ( !$img.closest( 'a' ).length ) {
								  console.log( $img.closest( 'a' ) );
                  href = href.indexOf( '_fs.' ) > 0 ? href : href.replace( /\.(?!.*\..*)/, "_fs." );
								  $img.wrap( '<a></a>' ).parent().attr( 'href', href.replace( '_tn_fs', '_fs' )).lightBox();
                }
							});
						});
					})(jQuery);
				</script>
                {/literal}
            {/if}

             {if $mode == 'category' || $mode == 'home' || $mode == 'search' }
             	{foreach from=$data item=article}
                    <div class="article">
                    	<h3><a class="articlecat" href="/news/{$article.category_hash}">{$article.category_label}</a></h3>
                        
                    	<div class="articlethumb">
                    		{if strlen($article.thumb	) > 0 }<img src="{$article.thumb}" />{/if} 
                    	</div>
                    	<h2><a href="/news/{$article.hash}">{$article.heading}</a></h2>
                        <div class="published">Published on {$article.published|date_format:"%d.%m.%Y"}{if $mode == 'home'} {/if}</div>

                        <div class="articlepreview">
                        	{$article.preview} <div class="readmore"><a href="/index.php?a=ap&addon=ppNews&page=news&article={$article.hash}">Read Article</a></div> 
                        </div>
                        <br clear="all" />
                        
                    </div>                    
                    
                    <div>&nbsp;</div>
                {/foreach}
             {/if}


             {if $totalRows > 1}
	            
	            {if $currentCategory == 49 }
	            	{assign var=searchcat value=49}
	            {else}
	            	{assign var=somevar value=someval}
	            {/if}
	            
                <br clear="all" />
            	<div class="paginate">Page
     	        
     	        	{if $pagination.page > 1}
		     	        {if $mode == "search"}
							&nbsp;<a href="{$url}/1?search={$searchQuery}&searchcat={$searchcat}">1</a>&nbsp;
						{else}
		               		 &nbsp;<a href="{$url}/1">1</a>&nbsp;
		               	{/if}
	            	{/if}

	            	{if $pagination.page >= 7} ... {/if}

	                {foreach from=$pagination.range item=p}
	                	{if $p > 1 && $p != $pagination.page && $p < $pagination.total}
							{if $mode == "search"}
								&nbsp;<a href="{$url}/{$p}?search={$searchQuery}&searchcat={$searchcat}">{$p}</a>&nbsp;
							{else}
	                       		 &nbsp;<a href="{$url}/{$p}">{$p}</a>&nbsp;
	                       	 {/if}
                        {/if}
                        {if $p == $pagination.page}
                        	<strong>&nbsp;{$p}&nbsp;</strong>
                    	{/if}

	                {/foreach}

	                {if $pagination.page <= $pagination.ellipse_upper} ... {/if}

	                {if $pagination.page < $pagination.total}
						{if $mode == "search"}
							&nbsp;<a href="{$url}/{$pagination.total}?search={$searchQuery}&searchcat={$searchcat}">{$pagination.total}</a>&nbsp;
						{else}
		               		 &nbsp;<a href="{$url}/{$pagination.total}">{$pagination.total}</a>&nbsp;
		               	 {/if}
		            {/if}
	            </div>
             {/if}

            &nbsp;
           </div>
           <br clear="all" />
       </div>
        <br clear="all" />
    </div>
    
    
    <!-- end content box -->
    <div style="text-align:center;font-style:italic; margin-top:25px">
		<strong style="font-weight:bold">Disclaimer:</strong> Every effort has been made to make the Site as accurate as possible. 
		You acknowledge that any reliance upon any advice, opinion, statement, advertisement, or other information displayed or distributed through the Site is at Your sole risk and We are not responsible or labile for any loss or damage that results from the use of the information on the Site. 
		We reserve the right in Our sole discretion and without notice to You to correct any errors or omissions in any portion of the Site. 
	</div>
</div>

