<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2021 Amasty (https://www.amasty.com)
 * @package Amasty_SocialLogin
 */


declare(strict_types=1);

namespace Amasty\SocialLogin\Model;

use Amasty\SocialLogin\Model\Source\Shape;
use Magento\Store\Model\ScopeInterface;

class ConfigData
{
    const GENERAL_POPUP_ENABLED = 'general/popup_enabled';

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Source\ButtonPosition
     */
    private $buttonPosition;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    private $encryptor;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Amasty\SocialLogin\Model\Source\ButtonPosition $buttonPosition,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->buttonPosition = $buttonPosition;
        $this->encryptor = $encryptor;
    }

    /**
     * @param $path
     * @return string|bool|int
     */
    public function getConfigValue($path)
    {
        return $this->scopeConfig->getValue('amsociallogin/' . $path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getSecretKey($type)
    {
        $key = $this->getConfigValue($type . '/api_secret');
        if ($key) {
            $key = $this->encryptor->decrypt($key);
        }

        return $key;
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getCustomerConfig($path)
    {
        return $this->scopeConfig->getValue('customer/' . $path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getWebConfig($path)
    {
        return $this->scopeConfig->getValue('web/' . $path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    public function getLoginPosition()
    {
        $positions = $this->getConfigValue('general/login_position');
        $positions = explode(',', $positions);

        return $positions;
    }

    /**
     * @return string
     */
    public function getButtonShapeClass()
    {
        $buttonsShape = $this->getButtonShape();

        return ($buttonsShape == Shape::RECTANGULAR
            || $buttonsShape == Shape::SQUARE) ? '-rectangular' : '';
    }

    /**
     * @return int
     */
    public function getButtonShape()
    {
        return $this->getConfigValue('general/button_shape');
    }

    /**
     * @return bool
     */
    public function getButtonLabelState()
    {
        return ($this->getButtonShape() == Shape::RECTANGULAR);
    }

    /**
     * @return string
     */
    public function getPositionTitle()
    {
        $positions = $this->buttonPosition->toArray();
        $current = $this->getSocialLoginPosition();

        $position =  array_key_exists($current, $positions) ? $current : 'bottom';
        return $position;
    }

    /**
     * @return bool|int|string
     */
    private function getSocialLoginPosition()
    {
        return $this->getConfigValue('general/button_position');
    }

    public function isPopupEnabled(): bool
    {
        return (bool)$this->getConfigValue(self::GENERAL_POPUP_ENABLED);
    }
}
