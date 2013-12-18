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
	#news h2 a {
		color:#222;
		font-weight:normal;
		margin-bottom:0px;
		padding-bottom:0px;
		font-size:1.2em;
		text-transform:uppercase;
		text-decoration:none;
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
	#news div.article img{
		padding:2px;
		border:1px solid #CCC;

		margin-right:5px;
		margin-bottom:5px;
		float:left;
	}
	#news div.article {
		margin:2px;
		margin-top:5px;
		background: none;
		border: 1px dotted #DEDEDE;
		padding:10px;
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

<div class="innerColumn right listingRight">
    
    <div class="psContentBox">
    	{if $currentCategory == 49 }
    		<h4>
            	<a class="title" href="/news">Stallions News Search</a>
        	</h4>
        	 <div class="psContentBoxBody">
	    	<form action="/news/1/" method="get">
	    		<!--<input type="hidden" name="a" value="ap" />
	    		<input type="hidden" name="addon" value="ampseCustom" />
	    		<input type="hidden" name="page" value="news" />-->
				<input type="hidden" name="searchcat" value="49" />
			    <input type="text" placeholder="Search" name="search" value="{$searchQuery}" style="width:206px; margin:10px 0 13px" />
			    <button>Search</button>
			</form>
		</div>
        	
        {else}
        	<h4 >
    			<span class="title">News Search</span>
    		</h4>
    		 <div class="psContentBoxBody">
	    	<form action="/news/1/" method="get">
	    		<!--<input type="hidden" name="a" value="ap" />
	    		<input type="hidden" name="addon" value="ampseCustom" />
	    		<input type="hidden" name="page" value="news" />-->

			    <input type="text" placeholder="Search" name="search" value="{$searchQuery}" style="width:206px; margin:10px 0 13px" />
			    <button>Search</button>
			</form>
		</div>
        {/if}
       
    	
	    
	</div>

    <div class="psContentBox">
        <h4 class="title">
            <span class="psContentBoxTick"></span>
            <span class="title">Categories</span></h4>
        <div class="psContentBoxBody news">
            <ul class="categories">
       			 {foreach from=$categories item=category}
           			 <li class="{if $category.id == $currentCategory}selected{/if}"><a href="/news/{$category.hash}">{$category.label}</a></li>
        		{/foreach}
		</ul>
        <br />

		</div>

    </div>
    {if $mode == 'article'}
    <div class="fb">
        <g:plusone href="http://myurl.com"></g:plusone>
        <br /><br />
       
    </div>
     {/if}
</div>

<div class="innerColumn center listingLeft">
    <div class="psContentBox">
        <h2 class="title">
            <span class="psContentBoxTick"></span>
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

                <div class="sponsor">
                	Brought to you by 
                	<br>
                	<a href="http://www.horseware.com/ice-vibe/">
                		<img src="/images/promo/icevibe.gif" width="140" />
                	</a>
                </div>

                <div class="comments">

                    {if $data.comments && is_array($data.comments) && count($data.comments) > 0 }
                   		 <h2>Comments</h2>
                        {foreach from=$data.comments item=comment}
                            <div class="comment {if $comment.user_id == $userId} own{/if}{if $comment.user_id == 1} admin{/if}">
                            	<h6 >{$comment.user_name} wrote:</h6>
                           		<div class="timestamp">{$comment.created|date_format:"%A, %B %e, %Y"}</div>
                                <div class="wrote">{$comment.comment}</div>
                                {if $userId == 1}<a class="delete" onclick="return confirm('You sure? This is NOT reversable.') ;" href="{$url}?delComment={$comment.id}">DELETE COMMENT</a>{/if}
                            </div>

                        {/foreach}
                    {else}
                        <div class="commentsNote">Be the first to comment on this article</div>
                    {/if}

                    {if $userId > 0}
                        {if $data.comments > 0 || $userID == 1 }

                            <div class="compose">
                                Write a comment: <br />
                                <form method="post">
                                    <textarea name="comment"></textarea>
                                    <input type="submit" name="submit" value="Comment" />
                                </form>
                                <br clear="both" />
                            </div>
                        {else}
                            <div class="commentsNote">Commenting not available on this article</div>
                        {/if}
                    {else}
                        <div class="commentsNote">You must be <a href="/login">logged in</a> to place comments</div>
                    {/if}
                </div>
                {literal}
                <link href="/ps/css/jquery.lightbox-0.5.css" type="text/css" rel="stylesheet" />
                <script src="/ps/jquery.lightbox-0.5.pack.js" type="text/javascript" ></script>
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
                        <h2><a href="/news/{$article.hash}">{$article.heading}</a></h2>
                        <div class="published">Published on {$article.published|date_format:"%d.%m.%Y"}{if $mode == 'home'} in <a href="/news/{$article.category_hash}">{$article.category_label}</a>{/if}</div>

                        <div class="preview">
                        	{if strlen($article.thumb	) > 0 }<img src="{$article.thumb}" />{/if} {$article.preview} <div class="readmore"><a href="/news/{$article.hash}">Read Article</a></div> <br clear="left" />
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
</div>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
  {lang: 'en-GB'}
</script>
