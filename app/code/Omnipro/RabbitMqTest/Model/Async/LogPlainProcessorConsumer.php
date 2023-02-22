<?php

/*
 * Created on Tue Feb 21 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\RabbitMqTest\Model\Async
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\RabbitMqTest\Model\Async;

use Omnipro\RabbitMqTest\Api\Async\LogProcessorConsumerInterface;
use Omnipro\RabbitMqTest\Api\Data\LogInfoInterface;
use Omnipro\RabbitMqTest\Api\Management\LogProcessorInterface;

/**
 * Log Plain Consumer
 */
class LogPlainProcessorConsumer implements LogProcessorConsumerInterface
{

    /**
     * @var LogProcessorInterface
     */
    private $logProcessor;

    /**
     * Constructor
     *
     * @param LogProcessorInterface $logProcessor
     */
    public function __construct(
        LogProcessorInterface $logProcessor
    ) {
        $this->logProcessor = $logProcessor;
    }

    /**
     * @inheritDoc
     */
    public function consume(LogInfoInterface $logInfo)
    {
        $this->logProcessor->execute($logInfo, false);
    }
}
