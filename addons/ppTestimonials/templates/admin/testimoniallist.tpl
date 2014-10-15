{$messages}

<a href="?page=addon_testimonials_settings&mc=addon_cat_ppTestimonials&edit_id=new">New Testimonial</a><br>
<br>

<table id="testimoniallist">
	<tr>
		<th>Testimonial</th>
		<th>Action</th>
	</tr>

	{foreach $testimonials as $testimonial}
		<tr>			
			<td>{$testimonial.description}<br/>
				{$testimonial.from}
			</td>
			<td>
				<a href="?page=addon_testimonials_settings&mc=addon_cat_ppTestimonial&edit_id={$testimonial.id}">Edit</a>
			</td>
		</tr>
	{/foreach}
</table>
