<?php
/**
 * @subpackage  ol_logez Template
 */

defined('_JEXEC') or die;

//Enable bootstrap framework !only for joomla 3.x
$jversion = new JVersion;
$bootstrap_on = $this->params->get('bootstrap_on');
$jquery_version = $this->params->get('jquery_version'); 


if($bootstrap_on == 1){
if (version_compare($jversion->getShortVersion(), '3.0.0', '>')){
JHtml::_('bootstrap.framework');
}
else {
JHtml::_('behavior.framework', true);	
}
}

$app = JFactory::getApplication();
$doc = JFactory::getDocument();//define path
$base_url = $this->baseurl;
$tpl_name = $this->template;
$css_url = ''.$base_url.'/templates/'.$tpl_name.'/css/';
$ol_analytics		= $this->params->get('ol_analytics');
$scripts_url = ''.$base_url.'/templates/'.$tpl_name.'/scripts/';
$framework = 'templates/'.$tpl_name.'/framework/';
//load template parameters
$socialCode          = $this->params->get('socialCode');
$slides          = $this->params->get('slides');
$custom_favicon = $this->params->get('custom_favicon'); 
$responsive_design = $this->params->get('responsive_design');
$predefined_style = $this->params->get('predefined_style');
$jquery_noconflict = $this->params->get('jquery_noconflict');
$jquery_on = $this->params->get('jquery_on');
//add styles
$doc->addStyleSheet($css_url.'reset.css');

if($bootstrap_on==1) {
if (version_compare($jversion->getShortVersion(), '3.0.0', '<')){
$doc->addStyleSheet($css_url.'bootstrap.min.css'); 
}
else {
$doc->addStyleSheet($base_url.'/media/jui/css/bootstrap.min.css');
} 
}
$doc->addStyleSheet($css_url.'menu/superfish.css');
$doc->addStyleSheet($css_url.'template.css');
$doc->addStyleSheet($css_url.''.$predefined_style.'/style.css');

if($socialCode==1) {
$doc->addStyleSheet($css_url.'social.css');
}

//load jQuery
if($jquery_on==1) {
	$doc->addScript('https://ajax.googleapis.com/ajax/libs/jquery/'.$jquery_version.'/jquery.min.js');	
}


//jQuery noConflict script
if($jquery_noconflict==1) {
	$doc->addScript($scripts_url.'noconflict.js');	
}


//menu scripts
$doc->addScript($scripts_url.'menu/hoverIntent.js');
$doc->addScript($scripts_url.'menu/superfish.js');

//add styledeclaration and font files
include ($framework.'settigns.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
		
<jdoc:include type="head" />
<?php if( $ol_analytics ){//<--analytics. ?>
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', '<?php echo $ol_analytics; ?>', 'auto');  // Replace with your property ID.
ga('send', 'pageview');

</script>
<?php }//analytics.--> ?>
<meta name="viewport" content="width=device-width; initial-scale=1.0">
<!--[if lt IE 8]>
    <link type="text/css"  media="screen" rel="stylesheet" href="<?php echo $css_url; ?>ie8.css"/>
<![endif]-->
<!--[if IE 7]>
<link type="text/css"  media="screen" rel="stylesheet" href="<?php echo $css_url; ?>ie7.css"/>
<![endif]-->
<!--[if lte IE 8]>
<script src="<?php echo $scripts_url; ?>ie-html5.js"></script>
<![endif]-->

<?php if ($custom_favicon !='') { ?>
<link rel="icon" type="image" href="<?php echo $base_url; ?>/<?php echo $custom_favicon; ?>" />
<?php } ?>
</head>