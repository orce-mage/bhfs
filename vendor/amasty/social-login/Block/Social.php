<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_SocialLogin
 */


namespace Amasty\SocialLogin\Block;

use Amasty\SocialLogin\Model\Source\LoginPosition;
use Magento\Framework\View\Element\Template;
use Amasty\SocialLogin\Model\Source\RedirectType;

class Social extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Amasty\SocialLogin\Model\SocialData
     */
    private $socialData;

    /**
     * @var \Amasty\SocialLogin\Model\ConfigData
     */
    private $configData;

    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;

    public function __construct(
        Template\Context $context,
        \Amasty\SocialLogin\Model\SocialData $socialData,
        \Amasty\SocialLogin\Model\ConfigData $configData,
        \Magento\Customer\Model\Session $session,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->socialData = $socialData;
        $this->configData = $configData;
        $this->session = $session;
    }

    /**
     * @return bool|string
     */
    public function toHtml()
    {
        if (!$this->configData->getConfigValue('general/enabled')) {
            return '';
        }

        $name = $this->getNameInLayout();
        $name = str_replace('amsociallogin-social-', '', $name);

        $enabled = $this->getEnabledBlockPositions();
        $html = in_array($name, $enabled) ? parent::toHtml() : '';

        return $html;
    }

    /**
     * @return array
     */
    private function getEnabledBlockPositions()
    {
        $enabled = $this->configData->getLoginPosition();
        if ($this->session->isLoggedIn()) {
            unset($enabled[array_search(LoginPosition::CHECKOUT_CART, $enabled)]);
        }

        return $enabled;
    }

    /**
     * @return array
     */
    public function getEnabledSocials()
    {
        return $this->socialData->getEnabledSocials();
    }

    /**
     * @return bool
     */
    public function isPopupEnabled()
    {
        return $this->configData->isPopupEnabled();
    }

    /**
     * @return string
     */
    public function getButtonShapeClass()
    {
        return $this->configData->getButtonShapeClass();
    }

    /**
     * @return bool
     */
    public function getButtonLabelState()
    {
        return $this->configData->getButtonLabelState();
    }

    /**
     * @return string
     */
    public function getPositionTitle()
    {
        return $this->configData->getPositionTitle();
    }
}
