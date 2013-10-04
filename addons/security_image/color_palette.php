<?php
//addons/security_image/color_palette.php
/**************************************************************************
Addon Created by Geodesic Solutions, LLC
Copyright (c) 2001-2013 Geodesic Solutions, LLC
All rights reserved
http://geodesicsolutions.com
see license attached to distribution
**************************************************************************/
##########GIT Build Data##########
## 
## File Changed In GIT Commit:
## ##    6.0.7-2-gc953682
## 
##################################

//stand-alone script to display "filtered" color pallete 


if(isset($_GET['generate']))
{
	$rmin = $_GET['rmin'];
	$rmax = $_GET['rmax'];
	
	$gmin = $_GET['gmin'];
	$gmax = $_GET['gmax'];
	
	$bmin = $_GET['bmin'];
	$bmax = $_GET['bmax'];
	$size = $_GET['size'];
		
	echo 	"<img src='color_palette.php?display_palette=1&rmin=$rmin&rmax=$rmax&gmin=$gmin&gmax=$gmax&bmin=$bmin&bmax=$bmax&size=$size&cjax&t=".time()."'>";
	exit;
}


if (isset($_GET['display_palette'])){
	$is_errors = false;	
	$vars = $_GET;
	if (!is_numeric($vars['rmin']) || !is_numeric($vars['rmax']) || 
		!is_numeric($vars['gmin']) || !is_numeric($vars['gmax']) || 
		!is_numeric($vars['bmin']) || !is_numeric($vars['bmax']) ||
		!is_numeric($vars['size']) )
	{
		//one of the min/max values is not numeric
		//THERE IS ERROR!!!
		$is_errors = true;
		
	} elseif ($vars['rmin'] < 0 || $vars['rmin'] > $vars['rmax'] || $vars['rmax'] > 255 ||
		$vars['gmin'] < 0 || $vars['gmin'] > $vars['gmax'] || $vars['gmax'] > 255 ||
		$vars['bmin'] < 0 || $vars['bmin'] > $vars['bmax'] || $vars['bmax'] > 255 ||
		$vars['size'] < 0 || $vars['size'] > 100 )
	{
		//THERE IS ERROR!!!
		$is_errors = true;
	}
	
	if(isset($_GET['cjax']) && !$is_errors)
	{
		$rmin = $_GET['rmin'];
		$rmax = $_GET['rmax'];
		$gmin = $_GET['gmin'];
		$gmax = $_GET['gmax'];
		$bmin = $_GET['bmin'];
		$bmax = $_GET['bmax'];
		$size = $_GET['size'];
	}
	else
	{
		$rmin = 0;
		$rmax = 150;
		$gmin = 0;
		$gmax = 150;
		$bmin = 0;
		$bmax = 150;
		$size = 20;		
	}
	$palette = new palette($rmin, $rmax, $gmin, $gmax, $bmin, $bmax, $size);
	$palette->Create();
} else {
	require '../../classes/cjax/core/cjax_config.php';
	$CJAX->JSdir('../../classes/cjax/core/js/');
	$CJAX->init(true);//echoing
	
	$rmin = $CJAX->value('rmin');
	$rmax = $CJAX->value('rmax');
	$gmin = $CJAX->value('gmin');
	$gmax = $CJAX->value('gmax');
	$bmin = $CJAX->value('bmin');
	$bmax = $CJAX->value('bmax');
	$size = $CJAX->value('size');
	
	$response = $CJAX->call("?generate&rmin=$rmin&rmax=$rmax&gmin=$gmin&gmax=$gmax&bmin=$bmin&bmax=$bmax&size=$size","rgb_palette");
	echo "
	<h1>Color Range Palette Beta</h1>
	Select RGB Color Range:<br />
	Red: <input type='text' id='rmin' value='0'> - <input type='text' id='rmax' value='150'><br />
	Green: <input type='text' id='gmin' value='0'> - <input type='text' id='gmax' value='150'><br />
	Blue: <input type='text' id='bmin' value='0'> - <input type='text' id='bmax' value='150'><br />
	<br />
	Palette Size: (1-100) <input type='text' id='size' value='20'><br />
	Palette Type: <label>waves<input type='radio' name='type1' id='type' value='1'></label> <label>Ven<input type='radio' name='type3' id='type' value='3'><br /><br />
	<input type='submit' value='Generate Palette' $response/><br />
	Color palette:<br />
	<div id='rgb_palette'>
	<img src='color_palette.php?display_palette=1&rmin=0&rmax=150&&gmin=0&gmax=150&bmin=0&bmax=150&size=20'>
	</div>";
	
}
class palette {
	var $oImage;
	var $white;
	var $gray;
	var $black;
	var $transparent;
	var $mult = 15;
	var $ratio = 1; //set properly in constructer
	
	var $method = 1; //1=one way, 3 = original way
	var $debug = 0;
	
	var $width, $height, $starty, $ymult;
	
	var $rmin, $rmax, $gmin,$gmax,$bmin,$bmax, $size;
	function palette($rmin, $rmax, $gmin,$gmax,$bmin,$bmax, $size){
		$this->rmin = $rmin;
		$this->rmax = $rmax;
		$this->gmin = $gmin;
		$this->gmax = $gmax;
		$this->bmin = $bmin;
		$this->bmax = $bmax;
		$this->size = $size;
		
		$this->ratio = ($size/100);
		$this->starty = 0;
	}
	function createImage($width, $height)
	{
		if ($this->debug)echo 'w: '.$width.' h: '.$height.'<br />';
		if (function_exists("imagecreatetruecolor"))
		{
			$new_image = imagecreatetruecolor($width,$height);
		} elseif (function_exists('imagecreate'))
		{
			$new_image = imagecreate($width,$height);
		} else {
			$new_image = false;
		}
		// allocate white background colour
		$this->white = ImageColorAllocate($new_image, 255, 255, 255);
		$this->gray = ImageColorAllocate($new_image, 100, 100, 100);
		$this->black = ImageColorAllocate($new_image, 0, 0, 0);
		$this->transparent = ImageColorAllocate($new_image, 127, 127, 127);
		
		imagefill($new_image,0,0,$this->white);
		
		return $new_image;
	}
	
	function DrawPalette() 
	{
		//go through each x and y and calculate what values of RGB need to be there, and put them there.
		$a255 = (255 * $this->ratio); //actual value of adjusted 255
		$f255 = floor($a255); //floor of adjusted value for 255
		//height of triangle = ratio * (255^2 - (255/2)^2)^(1/2) = ratio * 220.836477965
		$h_triag = ($this->ratio * 220.836477965);
		if ($this->method == 1){
			$f255 == ($f255 * 2);
			$h_triag = ($h_triag * 2);
		}
		
		$rstart = array ($f255 , $f255);//red start goes in top left corner of triangle
		$m = ($this->method == 1)? 3: 2;
		$gstart = array (($m*$f255), $f255);//green start goes in top right corner of triangle
		$bstart = array (ceil((($gstart[0] - $rstart[0]) / 2)+$rstart[0]), ($rstart[1] + ($h_triag)));//blue start goes in bottom of triangle
		
		if ($this->debug)echo "Starts: r{$rstart[0]},{$rstart[1]} g{$gstart[0]},{$gstart[1]} b{$bstart[0]},{$bstart[1]}<br /><br />";
		for ($x = 0; $x <= $this->width; $x++){
			
			for ($y=0; $y<= $this->height; $y++){
				//get distance from each color starting point...
				$r = $this->getD($x, $y, $rstart[0], $rstart[1]) / $this->ratio;
				$g = $this->getD($x, $y, $gstart[0], $gstart[1]) / $this->ratio;
				$b = $this->getD($x, $y, $bstart[0], $bstart[1]) / $this->ratio;
				if ($this->debug)echo "x{$x}y{$y} - r{$r}g{$g}b{$b}<br />";
				//is rgb values within the specified values?
				$show = true;
				
				if ($this->method==1){
					$faults = 0;
					$r = $r-$this->rmin;
					$g = $g-$this->gmin;
					$b = $b-$this->bmin;
					
					$rmax = $this->rmax - $this->rmin;
					$gmax = $this->gmax - $this->gmin;
					$bmax = $this->bmax - $this->bmin;
					if ($r > $rmax || $rmax == 0){
						if ($rmax == 0){
							$r = $rmax;
						} elseif (fmod(floor($r/$rmax),2)){
							$r = ($rmax * ceil($r/$rmax)) - $r;
						} else {
							$r = fmod($r, $rmax);
						}
					}
					if ($g > $gmax || $gmax == 0){
						if ($gmax == 0){
							$g = $gmax;
						} elseif (fmod(floor($g/$gmax),2)){
							$g = ($gmax * ceil($g/$gmax)) - $g;
						} else {
							$g = fmod($g, $gmax);
						}
					}
					if ($b > $bmax || $bmax == 0){
						if ($bmax == 0){
							$b = $bmax;
						} elseif (fmod(floor($b/$bmax),2)){
							$b = ($bmax * ceil($b/$bmax)) - $b;
						} else {
							$b = fmod($b, $bmax);
						}
					}
					$r+=$this->rmin;
					$g+=$this->gmin;
					$b+=$this->bmin;
					
					if ($faults >= 3){
						$show = false;
					}
					
				}
				if ($this->method == 2){
					$faults = 0;
					if (($r > $this->rmax || $r < $this->rmin)){
						//r is nogo!
						$r=$this->rmin;
						$faults ++;
					}
					if (($g > $this->gmax || $g < $this->gmin)){
						//g is nogo!
						$g=$this->gmin;
						$faults ++;
					}
					if (($b > $this->bmax || $b < $this->bmin)){
						//b is nogo!
						$b=$this->bmin;
						$faults ++;
					}
					if ($faults >= 3){
						$show = false;
					}
				}
				if ($this->method == 3){
				
					if (($r > $this->rmax || $r < $this->rmin) && $r <= 255){
						//r is nogo!
						$show = false;
					}
					if (($g > $this->gmax || $g < $this->gmin) && $g <= 255){
						//g is nogo!
						$show = false;
					}
					if (($b > $this->bmax || $b < $this->bmin) && $b <= 255){
						//b is nogo!
						$show = false;
					}
					if ($r > 255 && $g > 255 && $b > 255){
						//all are out of range, don't render!
						$show = false;
					}
				}
				
				if ($show){
					$r = ($r > 255)? $this->rmin: floor($r);
					$g = ($g > 255)? $this->gmin: floor($g);
					$b = ($b > 255)? $this->bmin: floor($b);
					
					$color = imagecolorallocate($this->oImage, $r,$g,$b);
					imagesetpixel($this->oImage, $x, $y, $color);
				}
			}
		}
		
		/*
		$min = 255;
		if ($this->rmin < $min){
			$min = $this->rmin;
		}
		if ($this->bmin < $min){
			$min = $this->bmin;
		}
		if ($this->gmin < $min){
			$min = $this->gmin;
		}
		$max = 0;
		if ($this->rmax > $max){
			$max = $this->rmax;
		}
		if ($this->bmax > $max){
			$max = $this->bmax;
		}
		if ($this->gmax > $max){
			$max = $this->gmax;
		}
		
		for( $r = $min; $r <= $max; $r+=$this->mult)
		{
			$x = 0;
			for( $g = $min; $g <= $max; $g+=$this->mult)
			{
				for( $b = $min; $b <= $max; $b+=$this->mult)
				{
					if ($r <= $this->rmax && $r >= $this->rmin &&
						$g <= $this->gmax && $g >= $this->gmin &&
						$b <= $this->bmax && $b >= $this->bmin )
					{
						$color = imagecolorallocate($this->oImage, $r,$g,$b);
						//calculate position
						for ($y = $this->starty; $y < ($this->starty + $this->ymult); $y++){
							imagesetpixel($this->oImage, $x, $y, $color);
							if ($this->debug)echo "r{$r}g{$g}b{$b}x{$x}y{$y}<br />";
						}
						//smoosh x together:
						$x++;
					}
					//display images where they belong on the pallete:
					//$x++;
					
				}
				//$this->starty += $this->ymult;
			}
			$this->starty += $this->ymult;
			//echo 'Aye<br />';
				
		}
		//die ('end');
*/
	}		
	
	function Create($sFilename = '') 
	{
		// check for existence of GD JPEG library
		if (!function_exists('imagejpeg') && !function_exists('imagegif') && !function_exists('imagepng') && !function_exists('imagewbmp')) {
			return false;
		}
		//height & width of entire image = ratio * 2*255 + ( 255^2 - (255/2)^2 )^(1/2) which roughly = 730.836477965 * ratio
		$this->width = 3 * 255 * $this->ratio;
		$this->height = ceil(730.836477965 * $this->ratio);
		
		if ($this->method == 1){
			$this->width = $this->width * 2;
			$this->height = $this->height * 2;
		}
		
		/*
		$this->width = ceil((255/$this->mult) * (255/$this->mult) * (255/$this->mult));
		$this->width = ceil(($this->width * 2) / 27);
		
		
		$this->height = 200;//45;
		$this->ymult = floor($this->height / 18);
		*/
		$this->oImage =& $this->createImage($this->width,$this->height);
		$this->DrawPalette();
		// Make the image
		// Apply transparencies
		imagecolortransparent($this->oImage,$this->transparent);
		
		if (function_exists("imagepng")) {
			//prefer using png, it's most high tech
			if (!$this->debug)header("Content-type: image/png");
			imagepng($this->oImage);
		} elseif (function_exists("imagegif")) {
			if (!$this->debug)header("Content-type: image/gif");
			imagegif($this->oImage);
		} elseif (function_exists("imagejpeg")) {
			if (!$this->debug)header("Content-type: image/jpeg");
			imagejpeg($this->oImage);
		} elseif (function_exists("imagewbmp")) {
			if (!$this->debug)header("Content-type: image/vnd.wap.wbmp");
			imagewbmp($this->oImage);
		}
	
		// free memory used in creating image
		imagedestroy($this->oImage);
		return true;
	}
	
	
	
	function loadImageFromFile($filename){
		$load_functions = array (
			'imagecreatefromgif',
			'imagecreatefromjpeg',
			'imagecreatefrompng',
			'imagecreatefromwbmp'
		);
		$img_resource = false;
		foreach ($load_functions as $func_name){
			if (function_exists($func_name)){
				$img_resource = $func_name($filename);
				if ($img_resource){
					break; //finally got the img resource.
				}
			}
		}
		return $img_resource;
	}
	function GetColor($low=0, $high=255)
	{
	
		$r = rand($low, $high);
		$g = rand($low, $high);
		$b = rand($low, $high);
		$color = imagecolorallocate($this->oImage, $r,$g,$b);
		return $color;
	}
	
	function getD($x1, $y1, $x2, $y2){
		//((x1 - x2)^2 + (y1-y2)^2)^(1/2)
		//good old pathagerous, helps in so many ways on this script...
		//I thought my math teacher was lieing when she said I would use this some day...
		
		$c = sqrt(pow(($x1 - $x2),2) + pow(($y1 - $y2),2));
		return $c;
	}
	function pow(){
		
	}
	
	function frand()
	{ 
		return 0.0001*rand(0,9999); 
	}
	function prand($i, $n)
	{
		if ($n <= 1)
			return $this->frand();
		else
			return ($i-1+0.25*($this->frand()-0.5))/($n-1);
	}
	function kscale($a, $b)
	{
		$norm = sqrt($a*$a + $b*$b);
		return pow($norm, 1.3);
	}
}