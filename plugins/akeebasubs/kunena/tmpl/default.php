<?php defined('_JEXEC') or die(); ?>
<div class="row-fluid">
	<div class="span6">
		<div class="control-group">
			<label for="params_kunena_addgroups" class="control-label">
				<?php echo JText::_('PLG_AKEEBASUBS_KUNENA_ADDGROUPS_TITLE'); ?>
			</label>
			<div class="controls">
				<?php echo $this->getSelectField($level, 'add') ?>
				<span class="help-block">
					<?php echo JText::_('PLG_AKEEBASUBS_KUNENA_ADDGROUPS_DESCRIPTION2') ?>
				</span>
			</div>
		</div>
	</div>
	<div class="span6">
		<div class="control-group">
			<label for="params_kunena_removegroups" class="control-label">
				<?php echo JText::_('PLG_AKEEBASUBS_KUNENA_REMOVEGROUPS_TITLE'); ?>
			</label>
			<div class="controls">
				<?php echo $this->getSelectField($level, 'remove') ?>
				<span class="help-block">
					<?php echo JText::_('PLG_AKEEBASUBS_KUNENA_REMOVEGROUPS_DESCRIPTION2') ?>
				</span>
			</div>
		</div>
	</div>
</div>
<div class="alert alert-warning">
	<p><?php echo JText::_('PLG_AKEEBASUBS_KUNENA_USAGENOTE'); ?></p>
</div>