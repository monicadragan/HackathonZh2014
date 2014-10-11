<?php
/*--------------------------------------------------------------
# Copyright (C) joomla-monster.com
# License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
# Website: http://www.joomla-monster.com
# Support: info@joomla-monster.com
---------------------------------------------------------------*/

defined('_JEXEC') or die;

//get top-bar modules grid size
$topbar1span = ($this->checkModules('top-bar2')) ? '6' : '12';
$topbar2span = ($this->checkModules('top-bar1')) ? '6' : '12';

?>

<?php if($this->checkModules('top-bar1') or $this->checkModules('top-bar2')) : ?>
<section id="jm-top-bar" class="<?php echo $this->getClass('block#top-bar') ?>">
	<div class="container-fluid">
		<div class="row-fluid">
			<?php if($this->checkModules('top-bar1')) : ?>
			<div id="jm-top-bar1" class="pull-left <?php echo 'span'.$topbar1span; $this->getClass('top-bar1') ?>">
				<jdoc:include type="modules" name="<?php echo $this->getPosition('top-bar1') ?>" style="jmmoduleraw" />
			</div>
			<?php endif; ?>
			<?php if($this->checkModules('top-bar2')) : ?>
			<div id="jm-top-bar2" class="pull-right <?php echo 'span'.$topbar2span; $this->getClass('top-bar2') ?>">
				<jdoc:include type="modules" name="<?php echo $this->getPosition('top-bar2') ?>" style="jmmoduleraw" />
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>