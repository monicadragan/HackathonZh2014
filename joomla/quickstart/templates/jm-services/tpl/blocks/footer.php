<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;

//get information about 'back to top' button
$backtotop = $this->params->get('backToTop', '1');
?>

<footer id="jm-footer">
	<div class="container-fluid">
		<div class="row-fluid">
		<?php if($this->checkModules('copyrights')) : ?>
			<div id="jm-copyrights" class="span6 <?php echo $this->getClass('copyrights') ?>">
				<jdoc:include type="modules" name="<?php echo $this->getPosition('copyrights') ?>" style="raw" />
			</div>
			<?php endif; ?>
			<div id="jm-poweredby" class="span6 text-right">
				<a href="http://www.joomla-monster.com/" onfocus="blur()" target="_blank" title="Joomla Templates">Joomla Templates</a> by Joomla-Monster.com
			</div>
			<?php if($backtotop == '1') : ?>
			<div id="jm-back-top">
				<a href="#top"><span>&nbsp;</span></a>
			</div>
			<?php endif; ?>
		</div>
	</div>
</footer>