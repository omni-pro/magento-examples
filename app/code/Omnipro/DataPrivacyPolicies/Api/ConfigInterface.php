<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Api
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Api;

/**
 * Config interface definition
 */
interface ConfigInterface
{
    /**
     * Get if is enabled data privacy policies
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Get if checkbox is mandatory
     *
     * @return boolean
     */
    public function isMandatory(): bool;

    /**
     * Get text checkbox data privacy policies
     *
     * @return string
     */
    public function getCheckboxTitle(): string;

    /**
     * Get text link data privacy policies
     *
     * @return string
     */
    public function getLinkTitle(): string;

    /**
     * Get page identifier data privacy policies
     *
     * @return string
     */
    public function getPageIdentifier(): string;
}
