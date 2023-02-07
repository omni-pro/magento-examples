<?php

/*
 * Created on Tue Feb 07 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DeferredExample\Model\Data
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DeferredExample\Model\Data;

use Magento\Framework\DataObject;
use Omnipro\DeferredExample\Api\Data\HttpStatusInterface;

/**
 * Http Status Implementation Data Service Contract
 */
class HttpStatus extends DataObject implements HttpStatusInterface
{

    /**
     * @inheritDoc
     */
    public function getCode(): ?int
    {
        return $this->getData(self::CODE);
    }

    /**
     * @inheritDoc
     */
    public function setCode(int $code): HttpStatusInterface
    {
        return $this->setData(self::CODE, $code);
    }

    /**
     * @inheritDoc
     */
    public function getUrl(): ?string
    {
        return $this->getData(self::URL);
    }

    /**
     * @inheritDoc
     */
    public function setUrl(string $url): HttpStatusInterface
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @inheritDoc
     */
    public function getResponse(): ?string
    {
        return $this->getData(self::RESPONSE);
    }

    /**
     * @inheritDoc
     */
    public function setResponse(string $response): HttpStatusInterface
    {
        return $this->setData(self::RESPONSE, $response);
    }
}
