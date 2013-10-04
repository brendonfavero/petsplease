function security_image_loader()
{
	toggleImageType();
	
	$('imageType_system').observe('click',toggleImageType);
	$('imageType_recaptcha').observe('click',toggleImageType);
	
	//char color ranges
	if ( $('rmin').value == '0' && $('rmax').value == '150' &&
		 $('gmin').value == '0' && $('gmax').value == '150' &&
		 $('bmin').value == '0' && $('bmax').value == '150' &&
		 !$('error_secure_font_color')){
		$('secure_font_color_adv').hide();
	} else {
		$('secure_font_color_adv_link').hide();
	}
	//chars allowed
	if ( $('allowedChars').value == '2346789ABCDEFGHJKLMNPRTWXYZ' && !$('error_allowedChars')){
		$('char_advanced_link_hide').hide();
		$('char_advanced_box').hide();
	} else {
		$('char_advanced_link_show').hide();
	}
	//distort
	if ( $('distort').value == '.5' && !$('error_distort')){
		$('distort_adv').hide();
	} else {
		$('distort_adv_link').hide();
	}
	//img url
	if ( $('refreshUrl').value == 'addons/security_image/reload.gif' && !$('error_refreshUrl')){
		$('secure_refresh_adv').hide();
	} else {
		$('secure_refresh_adv_link').hide();
	}
	//grid
	if ( $('numGrid').value == '8' && !$('error_numGrid')){
		$('numGrid_adv').hide();
	} else {
		$('numGrid_adv_link').hide();
	}
	//lines
	if ( $('lines').value == '3' && !$('error_lines')){
		$('lines_adv').hide();
	} else {
		$('lines_adv_link').hide();
	}
	//noise
	if ( $('numNoise').value == '150' && !$('error_numNoise')){
		$('numNoise_adv').hide();
	} else {
		$('numNoise_adv_link').hide();
	}
	
	
	//hide dev section
	if ( $('secure_dev_adv')){
		$('secure_dev_adv').hide();
	}
	
	return true;
}

var toggleImageType = function () {
	$$('.built_in_images').invoke(($('imageType_system').checked)? 'show':'hide');
	$$('.reCAPTCHA_images').invoke(($('imageType_recaptcha').checked)? 'show':'hide');
};


function advFormDefault()
{
	$('allowedChars').value = '2346789ABCDEFGHJKLMNPRTWXYZ';
	$('lines').value = '3';
	$('numGrid').value = '8';
	$('numNoise').value = '150';
	$('distort').value = '.5';
	$('refreshUrl').value = 'addons/security_image/reload.gif';
	
	$('rmin').value = $('gmin').value = $('bmin').value = 0;
	$('rmax').value = $('gmax').value = $('bmax').value = 150;
}

var imageUrl = null;

function changeSecurityImage()
{
	var a = new Date();
	var load_image = new Image();
	var new_image = new Image();
	load_image.src = '../addons/security_image/loader.gif';
	var x = $('addon_security_image').offsetWidth + 'px';
	var y = $('addon_security_image').offsetHeight + 'px';

	$('addon_security_image').style.width = x;
	$('addon_security_image').style.height = y;

	secure_image = $('addon_security_image').getElementsByTagName('img')[0];
	if (imageUrl == null) {
		imageUrl = secure_image.src;
	}
	secure_image.src = load_image.src;
	
	new_image.src = imageUrl+'&time='+a.getTime();

	Event.observe(new_image, 'load', function(){
		setTimeout(	function(){
			secure_image.src = new_image.src;
			} , 250);
	});		
}

Event.observe (window,'load',security_image_loader);
