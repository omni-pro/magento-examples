<?php

namespace Omnipro\RabbitMqTest\Api\Async;

/*
 * Created on Tue Feb 21 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Magento\AsynchronousOperations\Api\Data\OperationInterface
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

use Magento\AsynchronousOperations\Api\Data\OperationInterface;

/**
 * Consumer interface for Bulk
 */
interface LogBulkProcessorConsumerInterface
{
    /**
     * Process Bulk operation loggers
     *
     * @param OperationInterface $operation
     * @return void
     */
    public function consume(OperationInterface $operation);
}
