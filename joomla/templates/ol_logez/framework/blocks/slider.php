<?php
/**
* @subpackage  ol_logez Template
*/

defined('_JEXEC') or die;

$caption         = $this->params->get ('caption');
$menu            = $this->params->get ('menu');
$slides = $this->params->get('slides');
$showHeaderTh = $this->params->get('showHeaderTh');
$HeaderHight = $this->params->get('HeaderHight');
$slideseffect = $this->params->get('slideseffect');
$slidescaption1 = $this->params->get('slidescaption1');
$slidescaption2 = $this->params->get('slidescaption2');
$slidescaption3 = $this->params->get('slidescaption3');
$slidescaption4 = $this->params->get('slidescaption4');
$slidescaption5 = $this->params->get('slidescaption5');
$slidescaption6 = $this->params->get('slidescaption6');
$slidescaption7 = $this->params->get('slidescaption7');
$slidescaption8 = $this->params->get('slidescaption8');
$slidesimage1 = $this->params->get('slidesimage1');
$slidesimage2 = $this->params->get('slidesimage2');
$slidesimage3 = $this->params->get('slidesimage3');
$slidesimage4 = $this->params->get('slidesimage4');
$slidesimage5 = $this->params->get('slidesimage5');
$slidesimage6 = $this->params->get('slidesimage6');
$slidesimage7 = $this->params->get('slidesimage7');
$slidesimage8 = $this->params->get('slidesimage8');
$slideslink1 = $this->params->get('slideslink1');
$slideslink2 = $this->params->get('slideslink2');
$slideslink3 = $this->params->get('slideslink3');
$slideslink4 = $this->params->get('slideslink4');
$slideslink5 = $this->params->get('slideslink5');
$slideslink6 = $this->params->get('slideslink6');
$slideslink7 = $this->params->get('slideslink7');
$slideslink8 = $this->params->get('slideslink8');

if ($slidesimage1 || $slidesimage2 || $slidesimage3 || $slidesimage4 || $slidesimage5 || $slidesimage6 || $slidesimage7 || $slidesimage8) {
// use images from template manager
} else {
// use default images
$slidesimage1 = $this->baseurl . '/templates/' . $this->template . '/header/header01.jpg';
$slidesimage2 = $this->baseurl . '/templates/' . $this->template . '/header/header02.jpg';
}
?>

<?php if (($this->countModules('header') && $slides == 2) || ($slides == 1)): ?>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/css/msilders.css" type="text/css">
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/scripts/jquery.mobile.customized.min.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/scripts/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/scripts/msilders.min.js"></script>
<script>
jQuery(function(){
jQuery('#msilders_wrap').msilders({
alignment			: 'center',
autoAdvance			: true,
easing				: 'easeInOutExpo',
fx					: 'random',
gridDifference		: 250,	//to make the grid blocks slower than the slices, this value must be smaller than transPeriod
height				: '<?php echo $HeaderHight; ?>px',
imagePath			: '',
hover				: true,
loader				: 'none',
loaderColor			: '#E3E3E3', 
loaderBgColor		: '#FAFAFA',
loaderOpacity		: .8,	//0, .1, .2, .3, .4, .5, .6, .7, .8, .9, 1
loaderPadding		: 2,	//how many empty pixels you want to display between the loader and its background
loaderStroke		: 7,	//the thickness both of the pie loader and of the bar loader. Remember: for the pie, the loader thickness must be less than a half of the pie diameter	
pieDiameter			: 38,
piePosition			: 'rightTop',		
barDirection		: 'leftToRight',
barPosition			: 'bottom',
navigation			: true,
playPause			: true,
pauseOnClick		: true,
navigationHover		: true,
pagination			: true,
overlayer			: true,	//a layer on the images to prevent the users grab them simply by clicking the right button of their mouse (.msilders_overlayer)
opacityOnGrid		: false,	//true, false. Decide to apply a fade effect to blocks and slices: if your slideshow is fullscreen or simply big, I recommend to set it false to have a smoother effect
minHeight			: '200px',	//you can also leave it blank
portrait			: false, //true, false. Select true if you don't want that your images are cropped
cols				: 8,
rows				: 5,
slicedCols			: 12,
slicedRows			: 8,
slideOn				: 'random',
thumbnails			: <?php echo $showHeaderTh; ?>,
time				: 6000,
transPeriod			: 1500,
//Mobile
mobileAutoAdvance	: true, //true, false. Auto-advancing for mobile devices
mobileEasing		: '',	//leave empty if you want to display the same easing on mobile devices and on desktop etc.
mobileFx			: '',	//leave empty if you want to display the same effect on mobile devices and on desktop etc.
mobileNavHover		: true	//same as above, but only for mobile devices

});
});
</script>
<div class="container" id="showwrap">
<div id="wrapper" class="wrapper" >
<div class="msilders_wrap msilders_black_skin" id="msilders_wrap">
<?php if ($slidesimage1): ?>
<div data-thumb="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/thumb.php?src=<?php echo $slidesimage1; ?>&a=t&w=160&h=80&q=100" data-src="<?php echo $slidesimage1; ?>" data-link="<?php echo $slideslink1; ?>" >
<?php if ($slidescaption1): ?><div class="msilders_caption fadeFromRight"><?php echo $slidescaption1; ?></div><?php endif;?></div>
<?php endif;?>
<?php if ($slidesimage2): ?>
<div data-thumb="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/thumb.php?src=<?php echo $slidesimage2; ?>&a=t&w=160&h=80&q=100" data-src="<?php echo $slidesimage2; ?>" data-link="<?php echo $slideslink2; ?>" >
<?php if ($slidescaption2): ?><div class="msilders_caption fadeFromRight"><?php echo $slidescaption2; ?></div><?php endif;?></div>
<?php endif;?>
<?php if ($slidesimage3): ?>
<div data-thumb="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/thumb.php?src=<?php echo $slidesimage3; ?>&a=t&w=160&h=80&q=100" data-src="<?php echo $slidesimage3; ?>" data-link="<?php echo $slideslink3; ?>" >
<?php if ($slidescaption3): ?><div class="msilders_caption fadeFromRight"><?php echo $slidescaption3; ?></div><?php endif;?></div>
<?php endif;?>
<?php if ($slidesimage4): ?>
<div data-thumb="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/thumb.php?src=<?php echo $slidesimage4; ?>&a=t&w=160&h=80&q=100" data-src="<?php echo $slidesimage4; ?>" data-link="<?php echo $slideslink4; ?>" >
<?php if ($slidescaption4): ?><div class="msilders_caption fadeFromRight"><?php echo $slidescaption4; ?></div><?php endif;?></div>
<?php endif;?>
<?php if ($slidesimage5): ?>
<div data-thumb="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/thumb.php?src=<?php echo $slidesimage5; ?>&a=t&w=160&h=80&q=100" data-src="<?php echo $slidesimage5; ?>" data-link="<?php echo $slideslink5; ?>" >
<?php if ($slidescaption5): ?><div class="msilders_caption fadeFromBottom"><?php echo $slidescaption5; ?></div><?php endif;?></div>
<?php endif;?>
<?php if ($slidesimage6): ?>
<div data-thumb="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/thumb.php?src=<?php echo $slidesimage6; ?>&a=t&w=160&h=80&q=100" data-src="<?php echo $slidesimage6; ?>" data-link="<?php echo $slideslink6; ?>" >
<?php if ($slidescaption6): ?><div class="msilders_caption fadeFromBottom"><?php echo $slidescaption6; ?></div><?php endif;?></div>
<?php endif;?>
<?php if ($slidesimage7): ?>
<div data-thumb="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/thumb.php?src=<?php echo $slidesimage7; ?>&a=t&w=160&h=80&q=100" data-src="<?php echo $slidesimage7; ?>" data-link="<?php echo $slideslink7; ?>" >
<?php if ($slidescaption7): ?><div class="msilders_caption fadeFromBottom"><?php echo $slidescaption7; ?></div><?php endif;?></div>
<?php endif;?>
<?php if ($slidesimage8): ?>
<div data-thumb="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/thumb.php?src=<?php echo $slidesimage8; ?>&a=t&w=160&h=80&q=100" data-src="<?php echo $slidesimage8; ?>" data-link="<?php echo $slideslink8; ?>" >
<?php if ($slidescaption8): ?><div class="msilders_caption fadeFromBottom"><?php echo $slidescaption8; ?></div><?php endif;?></div>
<?php endif;?>
</div>
</div>
</div>
<?php endif; ?>		
<div class="clear"></div>       
