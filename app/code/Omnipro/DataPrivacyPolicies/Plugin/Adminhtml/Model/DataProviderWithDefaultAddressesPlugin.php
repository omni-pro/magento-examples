<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Plugin\Adminhtml\Model
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Plugin\Adminhtml\Model;

use Magento\Customer\Model\Customer\DataProviderWithDefaultAddresses;
use Magento\Framework\AuthorizationInterface;
use Omnipro\DataPrivacyPolicies\Api\ConfigInterface;
use Omnipro\DataPrivacyPolicies\Api\Management\IsDataPrivacyPoliciesAllowedInterface;

/**
 * Plugin to Data Provider
 */
class DataProviderWithDefaultAddressesPlugin
{
    /**
     * @var AuthorizationInterface
     */
    private $authorization;

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
     * @param AuthorizationInterface $authorization
     * @param ConfigInterface $config
     * @param IsDataPrivacyPoliciesAllowedInterface $isDataPrivacyPoliciesAllowed
     */
    public function __construct(
        AuthorizationInterface $authorization,
        ConfigInterface $config,
        IsDataPrivacyPoliciesAllowedInterface $isDataPrivacyPoliciesAllowed
    ) {
        $this->authorization = $authorization;
        $this->config = $config;
        $this->isDataPrivacyPoliciesAllowed = $isDataPrivacyPoliciesAllowed;
    }

    /**
     * Intercept after get data
     *
     * @param DataProviderWithDefaultAddresses $subject
     * @param array $result
     * @return array
     */
    public function afterGetData(
        DataProviderWithDefaultAddresses $subject,
        array $result
    ): array {
        $isDataPrivacyPoliciesAllowed = [];

        foreach ($result as $id => $entityData) {
            if ($id) {
                $dataPrivacyPoliciesStatus = (int)$this->isDataPrivacyPoliciesAllowed->execute(
                    (int)$entityData['customer_id']
                );
                $isDataPrivacyPoliciesAllowed[$id]['customer']['extension_attributes']['data_privacy_policies_allowed']
                    = (string)$dataPrivacyPoliciesStatus;
            }
        }

        return array_replace_recursive($result, $isDataPrivacyPoliciesAllowed);
    }

    /**
     * Intercept after get meta
     *
     * @param DataProviderWithDefaultAddresses $subject
     * @param array $result
     * @return array
     */
    public function afterGetMeta(
        DataProviderWithDefaultAddresses $subject,
        array $result
    ): array {
        if (!$this->config->isEnabled()) {
            $dataPrivacyPoliciesAllowedConfig = ['visible' => false];
        } elseif (!$this->authorization->isAllowed('Omnipro_DataPrivacyPolicies::allow_data_privacy_policies')) {
            $dataPrivacyPoliciesAllowedConfig = [
                'disabled' => true,
                'notice' => __('You have no permission to change Opt-In preference.'),
            ];
        } else {
            $dataPrivacyPoliciesAllowedConfig = [];
        }

        $config = [
            'customer' => [
                'children' => [
                    'extension_attributes.data_privacy_policies_allowed' => [
                        'arguments' => [
                            'data' => [
                                'config' => $dataPrivacyPoliciesAllowedConfig,
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return array_replace_recursive($result, $config);
    }
}
