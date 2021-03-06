<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_Followup
 */

namespace Amasty\Followup\Controller\Adminhtml\Blacklist;

use Magento\Framework\Exception\NoSuchEntityException;

class Save extends \Amasty\Followup\Controller\Adminhtml\Blacklist
{
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            $data = $this->getRequest()->getPostValue();

            try {
                $model = $this->_objectManager->create('Amasty\Followup\Model\Blacklist');

                $id = $this->getRequest()->getParam('blacklist_id');

                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong blacklist is specified.'));
                    }
                }

                $model->setData($data);

                $model->save();

                $this->messageManager->addSuccess(__('You saved the blacklist item.'));

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('amasty_followup/*/edit', ['id' => $model->getId()]);
                    return;
                }
                $this->_redirect('amasty_followup/*/');
                return;

            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('blacklist_id');
                if (!empty($id)) {
                    $this->_redirect('amasty_followup/*/edit', ['id' => $id]);
                } else {
                    $this->_redirect('amasty_followup/*/index');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the blacklist data. Please review the error log.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                $this->_redirect('amasty_followup/*/edit', ['id' => $this->getRequest()->getParam('blacklist_id')]);
                return;
            }
        }
    }
}