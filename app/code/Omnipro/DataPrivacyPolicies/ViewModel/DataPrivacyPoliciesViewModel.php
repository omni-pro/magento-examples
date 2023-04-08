<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\ViewModel
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\ViewModel;

use Magento\Customer\Model\Session;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Omnipro\DataPrivacyPolicies\Api\ConfigInterface;
use Omnipro\DataPrivacyPolicies\Api\Management\IsDataPrivacyPoliciesAllowedInterface;

/**
 * View model for data privacy policies
 */
class DataPrivacyPoliciesViewModel implements ArgumentInterface
{
    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var IsDataPrivacyPoliciesAllowedInterface
     */
    private $isDataPrivacyPoliciesAllowed;

    /**
     * Constructor
     *
     * @param Session $customerSession
     * @param UrlInterface $url
     * @param ConfigInterface $config
     * @param IsDataPrivacyPoliciesAllowedInterface $isDataPrivacyPoliciesAllowed
     */
    public function __construct(
        Session $customerSession,
        UrlInterface $url,
        ConfigInterface $config,
        IsDataPrivacyPoliciesAllowedInterface $isDataPrivacyPoliciesAllowed
    ) {
        $this->customerSession = $customerSession;
        $this->url = $url;
        $this->config = $config;
        $this->isDataPrivacyPoliciesAllowed = $isDataPrivacyPoliciesAllowed;
    }

    /**
     * Get if data privacy policies is enabled
     *
     * @return bool
     */
    public function isDataPrivacyPoliciesEnabled(): bool
    {
        return $this->config->isEnabled();
    }

    /**
     * Get if data privacy policies is mandatory
     *
     * @return bool
     */
    public function isDataPrivacyPoliciesMandatory(): bool
    {
        return $this->config->isMandatory();
    }

    /**
     * Get checkbox title data privacy policies
     *
     * @return string
     */
    public function getDataPrivacyPoliciesCheckBoxTitle(): string
    {
        return $this->config->getCheckboxTitle();
    }

    /**
     * Get link title data privacy policies
     *
     * @return string
     */
    public function getDataPrivacyPoliciesLinkTitle(): string
    {
        return $this->config->getLinkTitle();
    }

    /**
     * Get cms page url for data privacy policies
     *
     * @return string
     */
    public function getDataPrivacyPoliciesPageUrl(): string
    {
        $pageIdentifier = $this->config->getPageIdentifier();
        return $this->url->getUrl($pageIdentifier);
    }

    /**
     * Is merchant data privacy allowed allowed by Customer.
     *
     * @return bool
     */
    public function isDataPrivacyPoliciesAllowed(): bool
    {
        $customerId = $this->customerSession->getId();

        return $customerId && $this->isDataPrivacyPoliciesAllowed->execute((int)$customerId);
    }
}
