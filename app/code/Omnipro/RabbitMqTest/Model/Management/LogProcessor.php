<?php

/*
 * Created on Tue Feb 21 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\RabbitMqTest\Model\Management
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\RabbitMqTest\Model\Management;

use Magento\AsynchronousOperations\Api\Data\OperationInterface;
use Magento\AsynchronousOperations\Api\Data\OperationInterfaceFactory;
use Magento\Authorization\Model\UserContextInterface;
use Magento\Framework\Bulk\BulkManagementInterface;
use Magento\Framework\DataObject\IdentityGeneratorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Math\Random;
use Magento\Framework\MessageQueue\PublisherInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Omnipro\RabbitMqTest\Api\Data\LogInfoInterface;
use Omnipro\RabbitMqTest\Api\Data\ResponseInterface;
use Omnipro\RabbitMqTest\Api\Data\ResponseInterfaceFactory;
use Omnipro\RabbitMqTest\Api\Management\LogProcessorInterface;
use Psr\Log\LoggerInterface;

/**
 * Service Contract Management LogProcessor implementation
 */
class LogProcessor implements LogProcessorInterface
{

    /**
     * @var OperationInterfaceFactory
     */
    private $operationFactory;

    /**
     * @var UserContextInterface
     */
    private $userContext;

    /**
     * @var BulkManagementInterface
     */
    private $bulkManagement;

    /**
     * @var IdentityGeneratorInterface
     */
    private $identityGenerator;

    /**
     * @var Random
     */
    private $random;

    /**
     * @var PublisherInterface
     */
    private $publisher;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var ResponseInterfaceFactory
     */
    private $responseFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param OperationInterfaceFactory $operationFactory
     * @param UserContextInterface $userContext
     * @param BulkManagementInterface $bulkManagement
     * @param IdentityGeneratorInterface $identityGenerator
     * @param Random $random
     * @param PublisherInterface $publisher
     * @param SerializerInterface $serializer
     * @param ResponseInterfaceFactory $responseFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        OperationInterfaceFactory $operationFactory,
        UserContextInterface $userContext,
        BulkManagementInterface $bulkManagement,
        IdentityGeneratorInterface $identityGenerator,
        Random $random,
        PublisherInterface $publisher,
        SerializerInterface $serializer,
        ResponseInterfaceFactory $responseFactory,
        LoggerInterface $logger
    ) {
        $this->operationFactory = $operationFactory;
        $this->userContext = $userContext;
        $this->bulkManagement = $bulkManagement;
        $this->identityGenerator = $identityGenerator;
        $this->random = $random;
        $this->publisher = $publisher;
        $this->serializer = $serializer;
        $this->responseFactory = $responseFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function schedule(LogInfoInterface $logInfo, bool $useBulk = true, int $repetitions = 1): ResponseInterface
    {
        $response = $this->responseFactory->create();
        try {
            $transactionId = $useBulk ? $this->scheduleBulk($logInfo, $repetitions) : $this->scheduleUnBulk($logInfo);

            $response->setTransactionId($transactionId)
                ->setStatus(true)
                ->setMessage('Operation scheduled');
        } catch (\Exception $e) {
            $response->setStatus(false)
                ->setMessage($e->getMessage());

            $this->logger->error('[Omnipro\RabbitMqTest\Model\Management\LogProcessor::schedule] - Error', [
                'exception' => $e->getTrace()
            ]);
        }

        return $response;
    }

    /**
     * Send request log info to direct queue. This method use bulk
     *
     * @param LogInfoInterface $logInfo
     * @param int $numOperations
     * @return string
     */
    private function scheduleBulk(LogInfoInterface $logInfo, int $numOperations): string
    {
        /**
         * @var \Omnipro\RabbitMqTest\Model\Data\LogInfo $logInfo
         */
        $bulkUuid = $this->identityGenerator->generateId();
        $bulkDescription = 'Async Log Processor';
        $operations = [];

        for ($i = 0; $i < $numOperations; $i++) {
            $logInfo->setHash($bulkUuid);
            $data = [
                'data' => [
                    'bulk_uuid' => $bulkUuid,
                    'topic_name' => $numOperations === 1 ? self::TOPIC_NAME_BULK_UN_BATCH : self::TOPIC_NAME_BULK_BATCH,
                    'serialized_data' => $this->serializer->serialize($logInfo->toArray()),
                    'status' => OperationInterface::STATUS_TYPE_OPEN,
                ]
            ];
            $operations[] = $this->operationFactory->create($data);
        }

        $result = $this->bulkManagement->scheduleBulk(
            $bulkUuid,
            $operations,
            $bulkDescription,
            $this->userContext->getUserId()
        );

        if (!$result) {
            throw new LocalizedException(
                __('Could not scheduled log processor.')
            );
        }
        return $bulkUuid;
    }

    /**
     * Send request log info to direct queue. This method not use bulk
     *
     * @param LogInfoInterface $logInfo
     * @return string
     */
    private function scheduleUnBulk(LogInfoInterface $logInfo): string
    {
        $hash = $this->random->getUniqueHash();

        $logInfo->setHash($hash);
        $this->publisher->publish(self::TOPIC_NAME_UN_BULK, $logInfo);

        return $hash;
    }

    /**
     * @inheritDoc
     */
    public function execute(LogInfoInterface $logInfo, bool $forceTime = true): bool
    {
        if ($forceTime) {
            sleep($logInfo->getTime());
        }

        $this->logger->info('[Omnipro\RabbitMqTest\Model\Management\LogProcessor::execute] - Process Log', [
            'hash' => $logInfo->getHash() ? $logInfo->getHash() : '',
            'message' => $logInfo->getText(),
            'time' => $logInfo->getTime(),
        ]);

        return true;
    }
}
