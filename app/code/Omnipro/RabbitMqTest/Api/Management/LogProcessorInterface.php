<?php

/*
 * Created on Tue Feb 21 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\RabbitMqTest\Api\Management
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\RabbitMqTest\Api\Management;

use Omnipro\RabbitMqTest\Api\Data\LogInfoInterface;
use Omnipro\RabbitMqTest\Api\Data\ResponseInterface;

/**
 * Service Contract Management LogProcessor
 */
interface LogProcessorInterface
{

    public const TOPIC_NAME_UN_BULK = 'omnipro.integration.loggerprocessor.unbulk';
    public const TOPIC_NAME_BULK_BATCH = 'omnipro.integration.loggerprocessor.bulk.batch';
    public const TOPIC_NAME_BULK_UN_BATCH = 'omnipro.integration.loggerprocessor.bulk.unbatch';

    /**
     * Schedule Process log
     *
     * @param \Omnipro\RabbitMqTest\Api\Data\LogInfoInterface $logInfo
     * @param bool $useBulk
     * @param int $repetitions
     * @return \Omnipro\RabbitMqTest\Api\Data\ResponseInterface
     */
    public function schedule(LogInfoInterface $logInfo, bool $useBulk = true, int $repetitions = 1): ResponseInterface;

    /**
     * Process log
     *
     * @param \Omnipro\RabbitMqTest\Api\Data\LogInfoInterface $logInfo
     * @param bool $forceTime
     * @return bool
     */
    public function execute(LogInfoInterface $logInfo, bool $forceTime = true): bool;
}
