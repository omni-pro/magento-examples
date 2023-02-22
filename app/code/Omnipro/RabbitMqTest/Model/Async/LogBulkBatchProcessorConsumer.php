<?php

/*
 * Created on Tue Feb 21 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\RabbitMqTest\Model\Async
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\RabbitMqTest\Model\Async;

use Magento\AsynchronousOperations\Api\Data\OperationInterface;
use Magento\AsynchronousOperations\Api\Data\OperationListInterface;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;
use Omnipro\RabbitMqTest\Api\Async\LogBulkBatchProcessorConsumerInterface;
use Omnipro\RabbitMqTest\Api\Data\LogInfoInterfaceFactory;
use Omnipro\RabbitMqTest\Api\Management\LogProcessorInterface;

/**
 * Log Bulk Batch Consumer
 */
class LogBulkBatchProcessorConsumer implements LogBulkBatchProcessorConsumerInterface
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LogProcessorInterface
     */
    private $logProcessor;

    /**
     * @var LogInfoInterfaceFactory
     */
    private $logInfoFactory;

    /**
     * Constructor
     *
     * @param EntityManager $entityManager
     * @param SerializerInterface $serializer
     * @param LogProcessorInterface $logProcessor
     * @param LogInfoInterfaceFactory $logInfoFactory
     */
    public function __construct(
        EntityManager $entityManager,
        SerializerInterface $serializer,
        LogProcessorInterface $logProcessor,
        LogInfoInterfaceFactory $logInfoFactory
    ) {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->logProcessor = $logProcessor;
        $this->logInfoFactory = $logInfoFactory;
    }

    /**
     * @inheritDoc
     */
    public function consume(OperationListInterface $operationList)
    {
        foreach ($operationList->getItems() as $operation) {
            try {
                /**
                 * @var \Omnipro\RabbitMqTest\Model\Data\LogInfo $logInfo
                 */
                $logInfo = $this->logInfoFactory->create();
                $logInfo->addData($this->serializer->unserialize($operation->getSerializedData()));
                $result = $this->logProcessor->execute($logInfo);

                $operation->setStatus(OperationInterface::STATUS_TYPE_COMPLETE)
                    ->setResultMessage($this->serializer->serialize($result));
            } catch (\Zend_Db_Adapter_Exception | NoSuchEntityException | LocalizedException  | \Exception $e) {
                $operation->setStatus(OperationInterface::STATUS_TYPE_NOT_RETRIABLY_FAILED)
                    ->setErrorCode($e->getCode())
                    ->setResultMessage($e->getMessage());
            }
        }
        $this->entityManager->save($operation);
    }
}
