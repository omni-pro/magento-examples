<?php

/*
 * Created on Tue Feb 07 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DeferredExample\Api\Data
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DeferredExample\Api\Data;

/**
 * Result Data Service Contract
 */
interface ResultInterface
{
    public const HTTP_STATUSES = 'http_statuses';

    /**
     * Append item
     *
     * @param HttpStatusInterface $httpStatus
     * @return ResultInterface
     */
    public function append(HttpStatusInterface $httpStatus): ResultInterface;

    /**
     * Reset items
     *
     * @return ResultInterface
     */
    public function reset(): ResultInterface;

    /**
     * Get specific items by code
     *
     * @param int $code
     * @return \Omnipro\DeferredExample\Api\Data\HttpStatusInterface[]
     */
    public function getHttpStatusesByCode(int $code): array;

    /**
     * Get all items
     *
     * @return \Omnipro\DeferredExample\Api\Data\HttpStatusInterface[]
     */
    public function getAllHttpStatusesCodes(): array;
}
