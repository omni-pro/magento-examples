<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Model
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Omnipro\DataPrivacyPolicies\Api\ConfigInterface;

/**
 * Config Model
 */
class Config implements ConfigInterface
{
    private const CONFIG_PATH_ENABLE = 'omnipro_data_privacy_policies/general/enable';
    private const CONFIG_PATH_MANDATORY = 'omnipro_data_privacy_policies/general/mandatory';
    private const CONFIG_PATH_CHECKBOX_TITLE = 'omnipro_data_privacy_policies/general/checkbox_title';
    private const CONFIG_PATH_LINK_TITLE = 'omnipro_data_privacy_policies/general/link_title';
    private const CONFIG_PATH_CMS_PAGE = 'omnipro_data_privacy_policies/general/cms_page';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::CONFIG_PATH_ENABLE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @inheritDoc
     */
    public function isMandatory(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::CONFIG_PATH_MANDATORY,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @inheritDoc
     */
    public function getCheckboxTitle(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_PATH_CHECKBOX_TITLE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @inheritDoc
     */
    public function getLinkTitle(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_PATH_LINK_TITLE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * @inheritDoc
     */
    public function getPageIdentifier(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_PATH_CMS_PAGE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }
}
