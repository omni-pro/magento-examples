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
use Magento\Framework\Exception\NotFoundException;
use Omnipro\DeferredExample\Api\Data\HttpStatusInterface;
use Omnipro\DeferredExample\Api\Data\ResultInterface;

/**
 * Result Implementation Data Service Contract
 */
class Result extends DataObject implements ResultInterface
{

    /**
     * @inheritDoc
     */
    public function append(HttpStatusInterface $httpStatus): ResultInterface
    {
        $httpStatuses = $this->getData(self::HTTP_STATUSES);

        if (empty($httpStatuses)) {
            $httpStatuses = [];
        }

        $httpStatuses[] = $httpStatus;
        return $this->setData(self::HTTP_STATUSES, $httpStatuses);
    }

    /**
     * @inheritDoc
     */
    public function reset(): ResultInterface
    {
        return $this->unsetData(self::HTTP_STATUSES);
    }

    /**
     * @inheritDoc
     */
    public function getHttpStatusesByCode(int $code): array
    {
        $httpStatuses = $this->getData(self::HTTP_STATUSES);
        $httpStatusesFounded = [];

        if (empty($httpStatuses)) {
            throw new NotFoundException(__('Not found http statuses'));
        } else {
            foreach ($httpStatuses as $httpStatus) {
                /**
                 * @var \Omnipro\DeferredExample\Model\Data\HttpStatus $httpStatus
                 */
                if ($httpStatus->getCode() === $code) {
                    $httpStatusesFounded[] = $httpStatus;
                }
            }
        }

        if (empty($httpStatusesFounded)) {
            throw new NotFoundException(__('Not found http statuses'));
        }
        return $httpStatus;
    }

    /**
     * @inheritDoc
     */
    public function getAllHttpStatusesCodes(): array
    {
        $httpStatuses = $this->getData(self::HTTP_STATUSES);

        if (empty($httpStatuses)) {
            throw new NotFoundException(__('Not found http statuses'));
        }

        return $httpStatuses;
    }
}
