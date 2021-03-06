<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Followup
 */

namespace Amasty\Followup\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Psr\Log\LoggerInterface;
use Amasty\Followup\Controller\RegistryConstants;

abstract class Queue extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    /**
     * @var \Magento\Framework\Translate\InlineInterface
     */
    protected $_translateInline;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;
    /**
     * @var LoggerInterface
     */
    protected $logger;

    protected $resultForwardFactory;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Translate\InlineInterface $translateInline,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        LoggerInterface $logger
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_fileFactory = $fileFactory;
        $this->_translateInline = $translateInline;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->resultRawFactory = $resultRawFactory;
        $this->resultForwardFactory = $resultForwardFactory;

        $this->logger = $logger;
        parent::__construct($context);
    }

    protected function _initAction()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_Followup::followup_queue');
        $resultPage->addBreadcrumb(__('Marketing'), __('Marketing'));
        return $resultPage;
    }

    protected function _isAllowed()
    {

        return $this->_authorization->isAllowed('Amasty_Followup::followup_queue');
    }

    protected function prepareDefaultCustomerTitle(\Magento\Backend\Model\View\Result\Page $resultPage)
    {
        $resultPage->getConfig()->getTitle()->prepend(__('Queue'));
    }

    protected function initCurrentQueue($queue)
    {
        $this->_coreRegistry->register(RegistryConstants::CURRENT_AMASTY_AMFOLLOWUP_QUEUE, $queue);

        return $queue;
    }
}