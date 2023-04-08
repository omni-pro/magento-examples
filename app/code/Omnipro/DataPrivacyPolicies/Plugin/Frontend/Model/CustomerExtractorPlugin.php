<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Plugin\Frontend\Model
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Plugin\Frontend\Model;

use Magento\Customer\Api\Data\CustomerExtensionFactory;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Framework\App\RequestInterface;

/**
 * Plugin to Customer Extractor
 */
class CustomerExtractorPlugin
{
    /**
     * @var CustomerExtensionFactory
     */
    private $customerExtensionFactory;

    /**
     * Constructor
     *
     * @param CustomerExtensionFactory $customerExtensionFactory
     */
    public function __construct(
        CustomerExtensionFactory $customerExtensionFactory
    ) {
        $this->customerExtensionFactory = $customerExtensionFactory;
    }

    /**
     * Intercept after extract
     *
     * @param CustomerExtractor $subject
     * @param CustomerInterface $customer
     * @param string $formCode
     * @param RequestInterface $request
     * @param array $attributeValues
     * @return CustomerInterface
     */
    public function afterExtract(
        CustomerExtractor $subject,
        CustomerInterface $customer,
        string $formCode,
        RequestInterface $request,
        array $attributeValues = []
    ): CustomerInterface {
        $dataPrivacyPoliciesStatus = $this->getDataPrivacyPoliciesStatus(
            $request->getParam('data_privacy_policies_allowed')
        );

        $extensionAttributes = $customer->getExtensionAttributes();
        if ($extensionAttributes == null) {
            $extensionAttributes = $this->customerExtensionFactory->create();
        }
        $extensionAttributes->setDataPrivacyPoliciesAllowed($dataPrivacyPoliciesStatus);
        $customer->setExtensionAttributes($extensionAttributes);

        return $customer;
    }

    /**
     * Get Data privacy policies status
     *
     * @param string $data
     * @return int
     */
    private function getDataPrivacyPoliciesStatus(?string $data): int
    {
        return $data === null ? 0 : ($data === 'on' ? 1 : 0);
    }
}
