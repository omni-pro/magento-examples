<?php

/*
 * Created on Tue Feb 21 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\RabbitMqTest\Model\Async
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\RabbitMqTest\Model\Async;

use Magento\AsynchronousOperations\Api\Data\OperationListInterfaceFactory;
use Magento\Framework\MessageQueue\MergerInterface;
use Magento\Framework\MessageQueue\MergedMessageInterfaceFactory;

/**
 * Merger class for consumer batch
 */
class Merger implements MergerInterface
{

    /**
     * @var OperationListInterfaceFactory
     */
    private $operationListFactory;

    /**
     * @var MergedMessageInterfaceFactory
     */
    private $mergedMessageFactory;

    /**
     * Constructor
     *
     * @param OperationListInterfaceFactory $operationListFactory
     * @param MergedMessageInterfaceFactory $mergedMessageFactory
     */
    public function __construct(
        OperationListInterfaceFactory $operationListFactory,
        MergedMessageInterfaceFactory $mergedMessageFactory
    ) {
        $this->operationListFactory = $operationListFactory;
        $this->mergedMessageFactory = $mergedMessageFactory;
    }

    /**
     * @inheritdoc
     */
    public function merge(array $messages)
    {
        $result = [];

        foreach ($messages as $topicName => $topicMessages) {
            $operationList = $this->operationListFactory->create(['items' => $topicMessages]);
            $messagesIds = array_keys($topicMessages);
            $result[$topicName][] = $this->mergedMessageFactory->create(
                [
                    'mergedMessage' => $operationList,
                    'originalMessagesIds' => $messagesIds
                ]
            );
        }

        return $result;
    }
}
