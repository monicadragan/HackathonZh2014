<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;

// get direction
$direction = $this->params->get('direction', 'ltr');

// sticky topbar
$stickytopbar = ($this->checkModules('top-bar1') or $this->checkModules('top-bar2')) ? 'class="stickytopbar"' : '';

// responsive
$responsivelayout = $this->params->get('responsiveLayout', '1');
$responsivedisabled = ($responsivelayout != '1') ? 'responsive-disabled' : '';

// define default blocks and their default order (can be changed in layout builder)
$blocks = $this->getBlocks('top-bar,topmenu,system-message,header,top1,top2,main,bottom1,bottom2,footer-mod,footer');

?>
<!DOCTYPE html>
<html 
	xmlns="http://www.w3.org/1999/xhtml" 
	xml:lang="<?php echo $this->language; ?>" 
	lang="<?php echo $this->language; ?>" 
	dir="<?php echo $direction; ?>"
>
<head>
	<?php $this->renderBlock('head'); ?>
</head>
<body class="<?php echo $responsivedisabled; ?>">
	<div id="jm-allpage" <?php echo $stickytopbar; ?>>
		<?php foreach($blocks as $block) { ?>
			<?php $this->renderBlock($block); ?>
		<?php } ?>
	</div>
</body>
</html>