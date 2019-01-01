<?php
/**
 * @package   AkeebaSubs
 * @copyright Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') or die();

?>
{{-- "Do Not Track" warning --}}
@if($this->dnt && $this->cparams->warndnt)
	<div class="akeeba-block--warning" style="text-align: center">
		@lang('COM_AKEEBASUBS_DNT_WARNING')
	</div>
@endif
