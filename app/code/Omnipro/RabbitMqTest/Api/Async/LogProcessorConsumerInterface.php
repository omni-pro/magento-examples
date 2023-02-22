<?php

/*
 * Created on Tue Feb 21 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\RabbitMqTest\Api\Async
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\RabbitMqTest\Api\Async;

use Omnipro\RabbitMqTest\Api\Data\LogInfoInterface;

/**
 * Consumer interface for log info
 */
interface LogProcessorConsumerInterface
{

    /**
     * Execute process log
     *
     * @param LogInfoInterface $logInfo
     * @return void
     */
    public function consume(LogInfoInterface $logInfo);
}
