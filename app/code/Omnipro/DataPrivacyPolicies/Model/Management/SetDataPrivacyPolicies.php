<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Model\Management
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Model\Management;

use Omnipro\DataPrivacyPolicies\Api\Management\SetDataPrivacyPoliciesInterface;
use Omnipro\DataPrivacyPolicies\Model\ResourceModel\DeleteDataPrivacyPoliciesAllowed;
use Omnipro\DataPrivacyPolicies\Model\ResourceModel\SaveDataPrivacyPoliciesAllowed;

/**
 * Management Service Contract to set data privacy policies implementation
 */
class SetDataPrivacyPolicies implements SetDataPrivacyPoliciesInterface
{

    /**
     * @var DeleteDataPrivacyPoliciesAllowed
     */
    private $deleteDataPrivacyPoliciesAllowed;

    /**
     * @var SaveDataPrivacyPoliciesAllowed
     */
    private $saveDataPrivacyPoliciesAllowed;

    /**
     * Constructor
     *
     * @param DeleteDataPrivacyPoliciesAllowed $deleteDataPrivacyPoliciesAllowed
     * @param SaveDataPrivacyPoliciesAllowed $saveDataPrivacyPoliciesAllowed
     */
    public function __construct(
        DeleteDataPrivacyPoliciesAllowed $deleteDataPrivacyPoliciesAllowed,
        SaveDataPrivacyPoliciesAllowed $saveDataPrivacyPoliciesAllowed
    ) {
        $this->deleteDataPrivacyPoliciesAllowed = $deleteDataPrivacyPoliciesAllowed;
        $this->saveDataPrivacyPoliciesAllowed = $saveDataPrivacyPoliciesAllowed;
    }

    /**
     * @inheritDoc
     */
    public function execute(int $customerId, bool $allowed): void
    {
        if ($allowed) {
            $this->saveDataPrivacyPoliciesAllowed->execute($customerId);
        } else {
            $this->deleteDataPrivacyPoliciesAllowed->execute($customerId);
        }
    }
}
