{* 7.1beta4-102-gdaf7ef6 *}

<div class="offsite_videos_container">
	{foreach from=$offsite_videos item='video'}
		{if $video.video_type=='youtube'}
			<div class="offsite_video">
				<object width="480" height="390">
					<param name="movie" value="{$video.media_content_url|escape}" />
					<param name="allowFullScreen" value="true" />
					<embed src="{$video.media_content_url|escape}" type="{$video.media_content_type|escape}"
						width="480"
						height="390"
						allowfullscreen="true" />
				</object>
			</div>
		{/if}
		{* If any more video types are ever added, they would be added here. *}
	{/foreach}
</div>