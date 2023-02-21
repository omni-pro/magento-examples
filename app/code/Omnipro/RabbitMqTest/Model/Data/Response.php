<?php

/*
 * Created on Tue Feb 21 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\RabbitMqTest\Model\Data
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\RabbitMqTest\Model\Data;

use Magento\Framework\DataObject;
use Omnipro\RabbitMqTest\Api\Data\ResponseInterface;

/**
 * Service Contract Data Implementation Response
 */
class Response extends DataObject implements ResponseInterface
{

    /**
     * @inheritDoc
     */
    public function getTransactionId(): ?string
    {
        return $this->getData(self::TRANSACTION_ID);
    }

    /**
     * @inheritDoc
     */
    public function setTransactionId(string $transactionId): ResponseInterface
    {
        return $this->setData(self::TRANSACTION_ID, $transactionId);
    }

    /**
     * @inheritDoc
     */
    public function getMessage(): ?string
    {
        return $this->getData(self::MESSAGE);
    }

    /**
     * @inheritDoc
     */
    public function setMessage(string $message): ResponseInterface
    {
        return $this->setData(self::MESSAGE, $message);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): ?bool
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(bool $status): ResponseInterface
    {
        return $this->setData(self::STATUS, $status);
    }
}
