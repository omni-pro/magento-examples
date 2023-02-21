<?php

/*
 * Created on Tue Feb 21 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\RabbitMqTest\Api\Data
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\RabbitMqTest\Api\Data;

/**
 * Data Service Contract for Response
 */
interface ResponseInterface
{

    public const TRANSACTION_ID = 'transaction_id';
    public const MESSAGE = 'message';
    public const STATUS = 'status';

    /**
     * Getter transaction id
     *
     * @return string|null
     */
    public function getTransactionId(): ?string;

    /**
     * Setter transaction id
     *
     * @param string $transactionId
     * @return ResponseInterface
     */
    public function setTransactionId(string $transactionId): ResponseInterface;

    /**
     * Getter message
     *
     * @return string|null
     */
    public function getMessage(): ?string;

    /**
     * Setter message
     *
     * @param string $message
     * @return ResponseInterface
     */
    public function setMessage(string $message): ResponseInterface;

    /**
     * Getter status
     *
     * @return bool|null
     */
    public function getStatus(): ?bool;

    /**
     * Setter status
     *
     * @param bool $status
     * @return ResponseInterface
     */
    public function setStatus(bool $status): ResponseInterface;
}
