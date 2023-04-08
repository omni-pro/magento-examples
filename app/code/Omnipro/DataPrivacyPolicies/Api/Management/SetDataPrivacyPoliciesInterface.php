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
 * Management Service Contract to set data privacy policies
 */
interface SetDataPrivacyPoliciesInterface
{
    /**
     * Set data privacy policies customer
     *
     * @param int $customerId
     * @param bool $allowed
     * @return void
     */
    public function execute(int $customerId, bool $allowed): void;
}
