<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Fpc
 */


namespace Amasty\Fpc\Controller\Adminhtml\Report;

use Amasty\Fpc\Controller\Adminhtml\Report;
use Magento\Backend\App\Action;
use Magento\Framework\App\RequestInterface;
use Amasty\Fpc\Model\ReportsGraphData;
use Magento\Framework\Controller\Result\JsonFactory;

class WarmedPagesDataRefresh extends Report
{
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var ReportsGraphData
     */
    private $graphData;
    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    public function __construct(
        Action\Context $context,
        ReportsGraphData $graphData,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->request = $context->getRequest();
        $this->graphData = $graphData;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return string|null
     */
    public function execute()
    {
        $key = $this->request->getParam('key_warm');

        if (!$key) {
            return null;
        }
        $stats = $this->graphData->getDataForWarmed($key);

        return $this->jsonFactory->create()->setData($stats);
    }
}
