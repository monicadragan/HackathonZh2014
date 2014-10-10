<?php
/**
* @subpackage  ol_logez Template
*/

defined('_JEXEC') or die;

//define basic parameters
$base_url = $this->baseurl;

//load parameters from template admin panel
$search = $this->countModules('search');
$blogger_icon = $this->params->get('blogger_icon');
$digg_icon = $this->params->get('digg_icon');
$facebook_icon = $this->params->get('facebook_icon');
$flickr_icon = $this->params->get('flickr_icon');
$google_icon = $this->params->get('google_icon');
$linkedin_icon = $this->params->get('linkedin_icon');
$myspace_icon = $this->params->get('myspace_icon');
$pinterest_icon = $this->params->get('pinterest_icon');
$stumble_icon = $this->params->get('stumble_icon');
$twitter_icon = $this->params->get('twitter_icon');
$rssfeed_icon = $this->params->get('rssfeed_icon');
$logo_type = $this->params->get('logo_type');
$logo_text = $this->params->get('logo_text');
$site_slogan = $this->params->get('site_slogan');
$logo_image = $this->params->get('logo_image');
$logo_image_width = $this->params->get('logo_image_width');
$logo_image_height = $this->params->get('logo_image_height');
$logo_image_margin = $this->params->get('logo_image_margin');
$main_menu = '<jdoc:include type="modules" name="mainmenu" style="" />';

//change id of logo heading
$logo_id='logo-image';

if($logo_type =='text') {
$logo_id = 'logo-text';
}
?>

<header id="header" class="container">

<?php if($this->params->get('socialCode',1)) : ?>
<div id="social-bookmarks">
<ul class="social-bookmarks">
<?php if($this->params->get('blogger_icon')) : ?>
<li class="blogger"><a href="<?php echo $blogger_icon; ?>">blogger</a></li>
<?php endif; ?>
<?php if($this->params->get('digg_icon')) : ?>
<li class="digg"><a href="<?php echo $digg_icon; ?>">digg</a></li>
<?php endif; ?>
<?php if ($this->params->get('facebook_icon')) : ?>
<li class="facebook"><a href="<?php echo $facebook_icon; ?>">facebook</a></li>
<?php endif; ?>
<?php if($this->params->get('flickr_icon')) : ?>
<li class="flickr"><a href="<?php echo $flickr_icon; ?>">flickr</a></li>
<?php endif; ?>
<?php if($this->params->get('google_icon')) : ?>
<li class="google"><a href="<?php echo $google_icon; ?>">google</a></li>
<?php endif; ?>
<?php if($this->params->get('linkedin_icon')) : ?>
<li class="linkedin"><a href="<?php echo $linkedin_icon; ?>">linkedin</a></li>
<?php endif; ?>
<?php if($this->params->get('myspace_icon')) : ?>
<li class="myspace"><a href="<?php echo $myspace_icon; ?>">myspace</a></li>
<?php endif; ?>
<?php if($this->params->get('pinterest_icon')) : ?>
<li class="pinterest"><a href="<?php echo $pinterest_icon; ?>">pinterest</a></li>
<?php endif; ?>
<?php if($this->params->get('stumble_icon')) : ?>
<li class="stumbleupon"><a href="<?php echo $stumble_icon; ?>">stumbleupon</a></li>
<?php endif; ?>
<?php if($this->params->get('twitter_icon')) : ?>
<li class="twitter"><a href="<?php echo $twitter_icon; ?>">twitter</a></li>
<?php endif; ?>
<?php if($this->params->get('rssfeed_icon')) : ?>
<li class="rss"><a href="<?php echo $rssfeed_icon; ?>">rss</a></li>
<?php endif; ?>
</ul>
</div> 
<div style="clear:both;"></div>
<?php endif; ?>
<div class="logo">                      	
<h1 id="<?php echo $logo_id; ?>"><a href="<?php echo $base_url; ?>"><?php echo htmlspecialchars($logo_text); ?></a></h1>
<?php if($site_slogan !=''){ ?><span id="site-slogan"><?php echo htmlspecialchars($site_slogan); ?></span> <?php } ?>                        
</div>
<?php if($search){?>
<div class="search-form">
<jdoc:include type="modules" name="search"  />
</div>
<div class="clear"></div>
<?php } ?>
<div class="header-content">	
<nav id="navigation">
<?php echo $main_menu; ?>
<div class="clear"></div>
</nav>
</div>
<div class="clear"></div>

</header>