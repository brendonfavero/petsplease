{literal}
<style>
	strong {
		font-size:17px;
		font-weight:bold;
		font-family:georgia;
	}	
	em {
		float:right;
		font-style:italic;
	}
	blockquote {
		font-size:16px;
		font-family:georgia;
		position:relative;
	}
	blockquote:before {
		display: block;
		content: "\201C";
		font-size: 80px;
		position: absolute;
		left: -28px;
		top: -28px;
		color: #7a7a7a;
	}
</style>
{/literal}

<div class="content_box">
To submit a testimonial or feedback on Pets Please please complete our <a href="/testimonialform">testimonial and feedback form.</a> 
<br/><br/>
<h1 class="title">Customer Feedback</h1>
<table style="margin:auto; margin-top:20px; position:relative">
{foreach $testimonials as $testimonial}
	<tr>			
		<td style="width:600px;">
			<strong>{$testimonial.title}</strong><br/>
			<br/>
			<blockquote>{$testimonial.description}<br/>
			<em>{$testimonial.from_name}</em>
			</blockquote>
		</td>
	</tr>
{/foreach}
</table>
</div>