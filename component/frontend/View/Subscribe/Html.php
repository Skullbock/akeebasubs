<?php
/**
 * @package   AkeebaSubs
 * @copyright Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Subscriptions\Site\View\Subscribe;

defined('_JEXEC') or die;

use FOF30\View\DataView\Html as BaseView;

class Html extends BaseView
{
	/**
	 * The subscription form, created by the payment plugin
	 *
	 * @var  string
	 */
	public $form = '';

	/**
	 * Runs before displaying the view
	 *
	 * @param   string  $tpl  Ignored.
	 *
	 * @throws  \Exception
	 */
	public function onBeforeSubscribe($tpl = null)
	{

		\JFactory::getApplication()->setHeader('X-Cache-Control', 'False', true);
	}
}
