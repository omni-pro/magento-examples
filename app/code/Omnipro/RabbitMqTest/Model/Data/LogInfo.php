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
use Omnipro\RabbitMqTest\Api\Data\LogInfoInterface;

/**
 * Service Contract Data Implementation LogInfo
 */
class LogInfo extends DataObject implements LogInfoInterface
{

    /**
     * @inheritDoc
     */
    public function getTime(): ?int
    {
        return $this->getData(self::TIME);
    }

    /**
     * @inheritDoc
     */
    public function setTime(int $time): LogInfoInterface
    {
        return $this->setData(self::TIME, $time);
    }

    /**
     * @inheritDoc
     */
    public function getText(): ?string
    {
        return $this->getData(self::TEXT);
    }

    /**
     * @inheritDoc
     */
    public function setText(string $text): LogInfoInterface
    {
        return $this->setData(self::TEXT, $text);
    }

    /**
     * @inheritDoc
     */
    public function getHash(): ?string
    {
        return $this->getData(self::HASH);
    }

    /**
     * @inheritDoc
     */
    public function setHash(string $hash): LogInfoInterface
    {
        return $this->setData(self::HASH, $hash);
    }
}
