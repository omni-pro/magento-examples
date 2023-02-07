<?php

/*
 * Created on Tue Feb 07 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DeferredExample\Api\Data
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DeferredExample\Api\Data;

/**
 * Http Status Data Service Contract
 */
interface HttpStatusInterface
{
    public const CODE = 'code';
    public const URL = 'url';
    public const RESPONSE = 'response';

    /**
     * Getter code
     *
     * @return int|null
     */
    public function getCode(): ?int;

    /**
     * Setter Code
     *
     * @param int $code
     * @return HttpStatusInterface
     */
    public function setCode(int $code): HttpStatusInterface;

    /**
     * Getter URL
     *
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * Setter URL
     *
     * @param string $url
     * @return HttpStatusInterface
     */
    public function setUrl(string $url): HttpStatusInterface;

    /**
     * Getter Response
     *
     * @return string|null
     */
    public function getResponse(): ?string;

    /**
     * Setter Response
     *
     * @param string $response
     * @return HttpStatusInterface
     */
    public function setResponse(string $response): HttpStatusInterface;
}
