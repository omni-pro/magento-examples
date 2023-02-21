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
 * Data Service Contract for Log
 */
interface LogInfoInterface
{
    public const TIME = 'time';
    public const TEXT = 'text';
    public const HASH = 'hash';

    /**
     * Getter time
     *
     * @return int|null
     */
    public function getTime(): ?int;

    /**
     * Setter time
     *
     * @param int $time
     * @return LogInfoInterface
     */
    public function setTime(int $time): LogInfoInterface;

    /**
     * Getter text
     *
     * @return string|null
     */
    public function getText(): ?string;

    /**
     * Setter text
     *
     * @param string $text
     * @return LogInfoInterface
     */
    public function setText(string $text): LogInfoInterface;

    /**
     * Getter hash
     *
     * @return string|null
     */
    public function getHash(): ?string;

    /**
     * Setter hash
     *
     * @param string $hash
     * @return LogInfoInterface
     */
    public function setHash(string $hash): LogInfoInterface;
}
