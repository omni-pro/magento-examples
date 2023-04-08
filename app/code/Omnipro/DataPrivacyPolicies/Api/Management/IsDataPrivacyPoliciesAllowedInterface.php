<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Api\Management
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Api\Management;

/**
 * Management Service Contract to get data privacy policies
 */
interface IsDataPrivacyPoliciesAllowedInterface
{

    /**
     * Check if customer has data privacy policies allowed
     *
     * @param int $customerId
     * @return bool
     */
    public function execute(int $customerId): bool;
}
