<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Plugin\Api
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Plugin\Api;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\AuthorizationInterface;
use Omnipro\DataPrivacyPolicies\Api\Management\SetDataPrivacyPoliciesInterface;

/**
 * Plugin to Customer Repository
 */
class CustomerRepositoryPlugin
{
    /**
     * @var UserContextInterface
     */
    private $userContext;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @var SetDataPrivacyPoliciesInterface
     */
    private $setDataPrivacyPolicies;

    /**
     * Constructor
     *
     * @param UserContextInterface $userContext
     * @param AuthorizationInterface $authorization
     * @param SetDataPrivacyPoliciesInterface $setDataPrivacyPolicies
     */
    public function __construct(
        UserContextInterface $userContext,
        AuthorizationInterface $authorization,
        SetDataPrivacyPoliciesInterface $setDataPrivacyPolicies
    ) {
        $this->userContext = $userContext;
        $this->authorization = $authorization;
        $this->setDataPrivacyPolicies = $setDataPrivacyPolicies;
    }

    /**
     * Intercept after save
     *
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $result
     * @param CustomerInterface $customer
     * @param string|null $passwordHash
     * @return CustomerInterface
     */
    public function afterSave(
        CustomerRepositoryInterface $subject,
        CustomerInterface $result,
        CustomerInterface $customer,
        string $passwordHash = null
    ): CustomerInterface {
        $enoughPermission = true;
        if ($this->userContext->getUserType() === UserContextInterface::USER_TYPE_ADMIN
            || $this->userContext->getUserType() === UserContextInterface::USER_TYPE_INTEGRATION
        ) {
            $enoughPermission = $this->authorization->isAllowed(
                'Omnipro_DataPrivacyPolicies::allow_data_privacy_policies'
            );
        }

        $customerId = (int)$result->getId();
        $customerExtensionAttributes = $customer->getExtensionAttributes();

        if ($enoughPermission
            && $customerExtensionAttributes
            && $customerExtensionAttributes->getDataPrivacyPoliciesAllowed() !== null
        ) {
            $isAllowed = (bool)$customerExtensionAttributes->getDataPrivacyPoliciesAllowed();
            $this->setDataPrivacyPolicies->execute($customerId, $isAllowed);
        }

        return $result;
    }
}
