<?php

/*
 * Created on Tue Feb 07 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DeferredExample\Api\Management
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DeferredExample\Api\Management;

/**
 * Http Status processor management service contract
 */
interface HttpStatusProcessorInterface
{
    /**
     * Get statuses sync mode
     *
     * @param int $max
     * @param int $sleep
     * @return \Omnipro\DeferredExample\Api\Data\HttpStatusInterface[]
     */
    public function getHttpStatusesBySyncMode(int $max = 5, int $sleep = 10000): array;

    /**
     * Get statuses async mode
     *
     * @param int $max
     * @param int $sleep
     * @return \Omnipro\DeferredExample\Api\Data\ResultInterface
     */
    public function getHttpStatusesByAsyncMode(int $max = 5, int $sleep = 10000);
}
