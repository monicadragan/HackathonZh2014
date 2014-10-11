<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;
?>

<?php if($this->countFlexiblock('footer-mod')) : ?>
<section id="jm-footer-mod" class="<?php echo $this->getClass('block#footer-mod') ?>">
	<div class="container-fluid">
		<div id="jm-footer-mod-in">
			<?php echo $this->renderFlexiblock('footer-mod','jmmodule'); ?>
		</div>
	</div>
</section>
<?php endif; ?>