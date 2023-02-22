<?php

/*
 * Created on Tue Feb 21 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\RabbitMqTest\Api\Async
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\RabbitMqTest\Api\Async;

use Magento\AsynchronousOperations\Api\Data\OperationListInterface;

/**
 * Consumer interface for Bulk Batch
 */
interface LogBulkBatchProcessorConsumerInterface
{
    /**
     * Process Bulk operation loggers
     *
     * @param OperationListInterface $operationList
     * @return void
     */
    public function consume(OperationListInterface $operationList);
}
