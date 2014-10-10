<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;

//get logo and site description
$logo = htmlspecialchars($this->params->get('logo'));
$logotext = htmlspecialchars($this->params->get('logoText'));
$sitedescription = htmlspecialchars($this->params->get('siteDescription'));
$app = JFactory::getApplication();
$sitename = $app->getCfg('sitename');

//get logo and topmenu grid size
$logospan = ($this->checkModules('top-menu-nav')) ? '3' : '12';
$topmenuspan = (($logo != '') or ($logotext != '') or ($sitedescription != '')) ? '9' : '12';

?>

<?php if ($this->checkModules('top-menu-nav') or ($logo != '') or ($logotext != '') or ($sitedescription != '')) : ?>
<section id="jm-logo-nav">
	<div class="container-fluid">
		<div class="row-fluid">
			<?php if (($logo != '') or ($logotext != '') or ($sitedescription != '')) : ?>
			<div class="<?php echo 'span'.$logospan; ?>">
		        <div id="jm-logo-sitedesc" class="text-center">
		        	<div id="jm-logo-sitedesc-in">
			            <?php if (($logo != '') or ($logotext != '')) : ?>
			            <h1 id="jm-logo">
			                <a href="<?php echo JURI::base(); ?>" onfocus="blur()" >
			                    <?php if ($logo != '') : ?>
			                    <img src="<?php echo JURI::base(), $logo; ?>" alt="<?php if(!$logotext) { echo $sitename; } else { echo $logotext; }; ?>" border="0" />
			                    <?php else : ?>
			                    <?php echo '<span>'.$logotext.'</span>';?>
			                    <?php endif; ?>
			                </a>
			            </h1>
			            <?php endif; ?>
			            <?php if ($sitedescription != '') : ?>
			            <div id="jm-sitedesc">
			                <?php echo $sitedescription; ?>
			            </div>
			            <?php endif; ?>
			    	</div>
		        </div>
		    </div>
	        <?php endif; ?>
	        <?php if($this->checkModules('top-menu-nav')) : ?>
			<nav id="jm-top-menu-nav" class="<?php echo 'span'.$topmenuspan; ?>">
				<jdoc:include type="modules" name="<?php echo $this->getPosition('top-menu-nav') ?>" style="jmmoduleraw" />
			</nav>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>