<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Model\Management
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Model\Management;

use Omnipro\DataPrivacyPolicies\Api\Management\IsDataPrivacyPoliciesAllowedInterface;
use Omnipro\DataPrivacyPolicies\Model\ResourceModel\GetDataPrivacyPoliciesAllowed;

/**
 * Management Service Contract to get data privacy policies implementation
 */
class IsDataPrivacyPoliciesAllowed implements IsDataPrivacyPoliciesAllowedInterface
{

    /**
     * @var GetDataPrivacyPoliciesAllowed
     */
    private $getDataPrivacyPoliciesAllowed;

    /**
     * Constructor
     *
     * @param GetDataPrivacyPoliciesAllowed $getDataPrivacyPoliciesAllowed
     */
    public function __construct(
        GetDataPrivacyPoliciesAllowed $getDataPrivacyPoliciesAllowed
    ) {
        $this->getDataPrivacyPoliciesAllowed = $getDataPrivacyPoliciesAllowed;
    }

    /**
     * @inheritDoc
     */
    public function execute(int $customerId): bool
    {
        return $this->getDataPrivacyPoliciesAllowed->execute($customerId);
    }
}
