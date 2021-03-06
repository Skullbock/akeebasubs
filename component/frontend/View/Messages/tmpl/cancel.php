<?php
/**
 * @package   AkeebaSubs
 * @copyright Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

// Protect from unauthorized access
defined('_JEXEC') or die();

?>

<h1 class="componentheading">
	<?php echo $this->escape(JText::_('COM_AKEEBASUBS_MESSAGE_SORRY')) ?>
</h1>

<?php echo $this->message ?>

<div class="akeebasubs-goback">
	<p><a href="<?php echo JURI::base()?>"><?php echo JText::_('COM_AKEEBASUBS_MESSAGE_BACK')?></a></p>
</div>

<?php echo $this->pluginHTML; ?>
