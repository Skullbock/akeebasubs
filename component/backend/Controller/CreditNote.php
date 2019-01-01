<?php
/**
 * @package   AkeebaSubs
 * @copyright Copyright (c)2010-2019 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

namespace Akeeba\Subscriptions\Admin\Controller;

defined('_JEXEC') or die;

use FOF30\Container\Container;
use FOF30\Controller\DataController;
use FOF30\Controller\Exception\ItemNotFound;
use FOF30\View\Exception\AccessForbidden;

class CreditNote extends DataController
{
	public function __construct(Container $container, array $config = array())
	{
		parent::__construct($container, $config);

		$this->cacheableTasks = [];
	}

	public function onBeforeRead()
	{
		// Load the model
		/** @var \Akeeba\Subscriptions\Admin\Model\CreditNotes $model */
		$model = $this->getModel();

		// If there is no record loaded, try loading a record based on the id passed in the input object
		if (!$model->getId())
		{
			$ids = $this->getIDsFromRequest($model, true);

			if ($model->getId() != reset($ids))
			{
				$key = strtoupper($this->container->componentName . '_ERR_' . $model->getName() . '_NOTFOUND');
				throw new ItemNotFound(\JText::_($key), 404);
			}
		}

		// Check that this is the item's owner or an administrator
		$user = $this->container->platform->getUser();
		$sub  = $model->subscription;

		if (!$this->checkACL('core.manage') && ($sub->user_id != $user->id))
		{
			throw new AccessForbidden;
		}
	}

	public function download()
	{
		// Load the model
		/** @var \Akeeba\Subscriptions\Admin\Model\CreditNotes $model */
		$model = $this->getModel();

		// If there is no record loaded, try loading a record based on the id passed in the input object
		if (!$model->getId())
		{
			$ids = $this->getIDsFromRequest($model, true);

			if ($model->getId() != reset($ids))
			{
				$key = strtoupper($this->container->componentName . '_ERR_' . $model->getName() . '_NOTFOUND');
				throw new ItemNotFound(\JText::_($key), 404);
			}
		}

		// Check that this is the item's owner or an administrator
		$user = $this->container->platform->getUser();
		$sub  = $model->subscription;

		if (!$this->checkACL('core.manage') && ($sub->user_id != $user->id))
		{
			throw new AccessForbidden;
		}

		// Get the PDF data (generated on the fly)
		$fileData = $model->getPDFData();

		// Our invoice wasn't in any directory? Well, let's create it
		if (empty($fileData))
		{
			$key = strtoupper($this->container->componentName . '_ERR_' . $model->getName() . '_NOTFOUND');
			throw new ItemNotFound(\JText::_($key), 404);
		}

		// Clear any existing data
		while (@ob_end_clean())
		{
			;
		}

		// Fix IE bugs
		if (empty($model->display_number))
		{
			$basename = 'creditnote_' . $model->creditnote_no;
		}
		else
		{
			$basename = $model->display_number;
		}

		// Add extension
		$basename .= '.pdf';

		if (isset($_SERVER['HTTP_USER_AGENT']) && strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE'))
		{
			$header_file = preg_replace('/\./', '%2e', $basename, substr_count($basename, '.') - 1);

			if (ini_get('zlib.output_compression'))
			{
				ini_set('zlib.output_compression', 'Off');
			}
		}
		else
		{
			$header_file = $basename;
		}

		// Get the PDF file's data
		@clearstatcache();

		// Disable caching
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public", false);

		// Send MIME headers
		header("Content-Description: File Transfer");
		header('Content-Type: application/pdf');
		header("Accept-Ranges: bytes");
		header('Content-Disposition: attachment; filename="' . $header_file . '"');
		header('Content-Transfer-Encoding: binary');
		header('Connection: close');

		error_reporting(0);

		if (!ini_get('safe_mode'))
		{
			set_time_limit(0);
		}

		echo $fileData;

		$this->container->platform->closeApplication();
	}

	public function send()
	{
		// Load the model
		/** @var \Akeeba\Subscriptions\Admin\Model\CreditNotes $model */
		$model = $this->getModel();

		// If there is no record loaded, try loading a record based on the id passed in the input object
		if (!$model->getId())
		{
			$ids = $this->getIDsFromRequest($model, true);

			if ($model->getId() != reset($ids))
			{
				$key = strtoupper($this->container->componentName . '_ERR_' . $model->getName() . '_NOTFOUND');
				throw new ItemNotFound(\JText::_($key), 404);
			}
		}

		// Check that this is an administrator
		if (!$this->checkACL('core.manage'))
		{
			throw new AccessForbidden;
		}

		// Email the PDF file
		$invoice = $model->invoice;
		$status  = ($model->emailPDF($invoice) === true);

		// Post-action redirection
		if ($customURL = $this->input->get('returnurl', '', 'string'))
		{
			$customURL = base64_decode($customURL);
		}

		$url = !empty($customURL) ? $customURL : 'index.php?option=com_akeebasubs&view=CreditNotes';

		if (!$status)
		{
			$this->setRedirect($url, \JText::_('COM_AKEEBASUBS_CREDITNOTES_MSG_NOTSENT'), 'error');
		}
		else
		{
			$this->setRedirect($url, \JText::_('COM_AKEEBASUBS_CREDITNOTES_MSG_SENT'));
		}
	}

	public function generate()
	{
		// Load the model
		/** @var \Akeeba\Subscriptions\Admin\Model\CreditNotes $model */
		$model = $this->getModel();
		/** @var \Akeeba\Subscriptions\Admin\Model\Invoices $invoicesModel */
		$invoicesModel = $this->container->factory->model('Invoices')->tmpInstance();

		// If there is no record loaded, try loading a record based on the id passed in the input object
		$found = $model->getId() > 0;

		if (!$found)
		{
			$ids = $this->getIDsFromRequest($model, true);

			if ($model->getId() == reset($ids))
			{
				$invoicesModel = $model->invoice;
				$found         = true;
			}
		}

		if (!$found)
		{
			$ids = $this->getIDsFromRequest($invoicesModel, true);

			if ($invoicesModel->getId() == reset($ids))
			{
				$found = true;
			}
		}

		if (!$found)
		{
			$key = strtoupper($this->container->componentName . '_ERR_' . $model->getName() . '_NOTFOUND');
			throw new ItemNotFound(\JText::_($key), 404);
		}

		// Check that this is an administrator
		if (!$this->checkACL('core.manage'))
		{
			throw new AccessForbidden;
		}

		// (Re-)generate the invoice
		$status = ($model->createCreditNote($invoicesModel) === true);

		// Post-action redirection
		if ($customURL = $this->input->get('returnurl', '', 'string'))
		{
			$customURL = base64_decode($customURL);
		}

		$url = !empty($customURL) ? $customURL : 'index.php?option=com_akeebasubs&view=CreditNotes';

		if ($status === false)
		{
			$this->setRedirect($url, \JText::_('COM_AKEEBASUBS_CREDITNOTES_MSG_NOTGENERATED'), 'error');
		}
		else
		{
			$this->setRedirect($url, \JText::_('COM_AKEEBASUBS_CREDITNOTES_MSG_GENERATED'));
		}
	}
}
