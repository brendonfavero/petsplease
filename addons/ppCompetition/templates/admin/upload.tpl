
<style>
#change_detail_form div {
	margin-bottom: 7px;
}
#change_detail_form label {
	display: inline-block;
	width: 120px;
}
#change_detail_form textarea {
	vertical-align: top
}
</style>
<link rel="stylesheet" type="text/css" href="/js/jquery.datepick.css"> 
<script type="text/javascript" src="/js/jquery.datepick.js"></script>
<script type="text/javascript" src="/js/jquery.datepick.ext.js"></script>

<form method="post" enctype="multipart/form-data" action="?page=addon_Competition_settings&mc=addon_cat_ppCompetition" id="change_detail_form">
	
	<div>
		<label>ID</label>
		<span>
		{if $detail.id}
			{$detail.id}
		{else}
			New
		{/if}
		</span>
	</div>	

	<div>
		<label for="pet_name">Pet Name</label>
		<input type="text" name="d[name]" id="pet_name" value="{$detail.name}" />
	</div>

	<div>
		<label for="pet_week">Week</label>
		<input type="text" name="d[week]" class="selectWeekPicker" /><img src="/addons/ppCompetition/img/calendar-green.gif" alt="Popup" class="trigger datepick-trigger"> 
		Click on the week number.
	</div>
	
	<div style="margin-top:24px;">
		<label for="image">Upload Image</label>
	
			<input type="file" name="imagefile">
			<br>
	</div>
	
	<div style="margin-top:24px;">
		<label for="current">Current Week? </label>
	
			<input type="checkbox" checked="checked" name="formCurrent" value="Yes" />
			<br>
	</div>
	
	

	<input type="submit" name="auto_save" value="Save" />
	
	

</form>



<script type="text/javascript">
		jQuery(function() {
			jQuery('.selectWeekPicker').datepick({ 
			    renderer: jQuery.datepick.weekOfYearRenderer, 
			    firstDay: 1, showOtherMonths: true, rangeSelect: true, 
			    onShow: jQuery.datepick.selectWeek, showTrigger: '#calImg'});
		});
		
		</script>






