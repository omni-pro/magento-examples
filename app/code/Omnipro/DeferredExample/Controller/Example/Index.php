<?php

/*
 * Created on Tue Feb 07 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DeferredExample\Controller\Example
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DeferredExample\Controller\Example;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\ValidatorException;
use Omnipro\DeferredExample\Api\Management\HttpStatusProcessorInterface;

/**
 * Index Controller
 */
class Index implements HttpGetActionInterface
{

    private const MODE_SYNC = 'sync';
    private const MODE_ASYNC = 'async';
    private const AVAILABLE_MODES = [self::MODE_SYNC, self::MODE_ASYNC];

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * @var HttpStatusProcessorInterface
     */
    private $httpStatusProcessor;

    /**
     * Constructor
     *
     * @param RequestInterface $request
     * @param ResultFactory $resultFactory
     * @param HttpStatusProcessorInterface $httpStatusProcessor
     */
    public function __construct(
        RequestInterface $request,
        ResultFactory $resultFactory,
        HttpStatusProcessorInterface $httpStatusProcessor
    ) {
        $this->request = $request;
        $this->resultFactory = $resultFactory;
        $this->httpStatusProcessor = $httpStatusProcessor;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        /**
         * @var \Magento\Framework\Controller\Result\Json
         */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        try {
            $params = $this->request->getParams();
            $this->validateParams($params);

            return $resultJson->setData($this->getHttpStatuses($params['mode']));
        } catch (ValidatorException $e) {
            return $resultJson->setData([
                'exception' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get data http statuses
     *
     * @param string $mode
     * @return array
     */
    private function getHttpStatuses(string $mode): array
    {
        if ($mode === self::MODE_SYNC) {
            return $this->runOperation(function () {
                return $this->getDataForHttpStatuses($this->httpStatusProcessor->getHttpStatusesBySyncMode());
            });
        }
        if ($mode === self::MODE_ASYNC) {
            return $this->runOperation(function () {
                return $this->getDataForHttpStatuses(
                    $this->httpStatusProcessor->getHttpStatusesByAsyncMode()->getAllHttpStatusesCodes()
                );
            });
        }
    }

    /**
     * Use this method fot get time execution callable
     * The result returned is array as
     * [
     *  'results' => 'Results from callable'
     *  'execution_time' => Execution time callable
     * ]
     *
     * @param callable $callback
     * @return array
     */
    private function runOperation(callable $callback): array
    {
        //Start operation
        $timeStart = microtime(true);
        $result['results'] = $callback();
        $timeEnd = microtime(true);

        //Add time
        $result['execution_time'] = ($timeEnd - $timeStart) / 60;

        return $result;
    }

    /**
     * Get data for https statuses objects
     *
     * @param \Omnipro\DeferredExample\Api\Data\HttpStatusInterface[] $httpStatuses
     * @return array
     */
    private function getDataForHttpStatuses(array $httpStatuses): array
    {
        $result = [];

        foreach ($httpStatuses as $httpStatus) {
            /**
             * @var \Omnipro\DeferredExample\Model\Data\HttpStatus $httpStatus
             */
            $result[] = $httpStatus->getData();
        }

        return $result;
    }

    /**
     * Validate params in request
     *
     * @param array $params
     * @return void
     */
    private function validateParams(array $params)
    {
        if (empty($params['mode'])) {
            throw new ValidatorException(__('The mode is required'));
        }
        if (!in_array($params['mode'], self::AVAILABLE_MODES)) {
            throw new ValidatorException(__('The mode is invalid'));
        }
    }
}
