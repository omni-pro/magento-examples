<?php

/*
 * Created on Tue Feb 07 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DeferredExample\Model\Management
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DeferredExample\Model\Management;

use Magento\Framework\Async\CallbackDeferredFactory;
use Magento\Framework\HTTP\AsyncClient\HttpException;
use Magento\Framework\HTTP\AsyncClient\Request;
use Magento\Framework\HTTP\AsyncClientInterface;
use Magento\Framework\HTTP\AsyncClient\RequestFactory;
use Magento\Framework\HTTP\ClientInterface;
use Omnipro\DeferredExample\Api\Data\HttpStatusInterfaceFactory;
use Omnipro\DeferredExample\Api\Data\ResultInterfaceFactory;
use Omnipro\DeferredExample\Api\Management\HttpStatusProcessorInterface;
use Omnipro\DeferredExample\Model\Data\Result\ProxyDeferredFactory;
use Psr\Log\LoggerInterface;

/**
 * Http Status processor implementation management service contract
 */
class HttpStatusProcessor implements HttpStatusProcessorInterface
{

    private const AVAILABLE_STATUSES_CODES = [200, 201, 202, 203, 206];
    private const SERVICE_URL = 'https://httpstat.us/';

    /**
     * @var CallbackDeferredFactory
     */
    private $callbackDeferredFactory;

    /**
     * @var AsyncClientInterface
     */
    private $asyncClient;

    /**
     * @var RequestFactory
     */
    private $requestFactory;

    /**
     * @var ClientInterface
     */
    private $syncClient;

    /**
     * @var HttpStatusInterfaceFactory
     */
    private $httpStatusFactory;

    /**
     * @var ResultInterfaceFactory
     */
    private $resultFactory;

    /**
     * @var ProxyDeferredFactory
     */
    private $proxyDeferredFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param AsyncClientInterface $asyncClient
     * @param RequestFactory $requestFactory
     * @param ClientInterface $syncClient
     * @param ProxyDeferredFactory $proxyDeferredFactory
     * @param CallbackDeferredFactory $callbackDeferredFactory
     * @param HttpStatusInterfaceFactory $httpStatusFactory
     * @param ResultInterfaceFactory $resultFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        AsyncClientInterface $asyncClient,
        RequestFactory $requestFactory,
        ClientInterface $syncClient,
        ProxyDeferredFactory $proxyDeferredFactory,
        CallbackDeferredFactory $callbackDeferredFactory,
        HttpStatusInterfaceFactory $httpStatusFactory,
        ResultInterfaceFactory $resultFactory,
        LoggerInterface $logger
    ) {
        $this->asyncClient = $asyncClient;
        $this->requestFactory = $requestFactory;
        $this->syncClient = $syncClient;
        $this->proxyDeferredFactory = $proxyDeferredFactory;
        $this->callbackDeferredFactory = $callbackDeferredFactory;
        $this->httpStatusFactory = $httpStatusFactory;
        $this->resultFactory = $resultFactory;
        $this->logger = $logger;
    }

    /**
     * Get statuses sync mode
     *
     * @param int $max
     * @param int $sleep
     * @return \Omnipro\DeferredExample\Api\Data\HttpStatusInterface[]
     */
    public function getHttpStatusesBySyncMode(int $max = 5, int $sleep = 10000): array
    {
        $results = [];

        //Generate and call sync operations
        for ($i = 0; $i < $max; $i++) {
            $httpStatusCode = $this->getRandomHttpStatusCode();
            $serviceUrl = $this->getServiceUrl($httpStatusCode, $sleep);
            $this->syncClient->get($serviceUrl);

            $results[] = $this->httpStatusFactory->create()
                ->setCode($httpStatusCode)
                ->setUrl($serviceUrl)
                ->setResponse($this->syncClient->getBody());
        }

        return $results;
    }

    /**
     * Get statuses async mode
     *
     * @param int $max
     * @param int $sleep
     * @return \Omnipro\DeferredExample\Api\Data\ResultInterface
     */
    public function getHttpStatusesByAsyncMode(int $max = 5, int $sleep = 10000)
    {
        $requests = [];

        //Generate async operations
        for ($i = 0; $i < $max; $i++) {
            $httpStatusCode = $this->getRandomHttpStatusCode();
            $url = $this->getServiceUrl($httpStatusCode, $sleep);

            $requests[] = [
                'code' => $httpStatusCode,
                'url' => $url,
                'deferred' => $this->asyncClient->request(
                    $this->requestFactory->create([
                        'url' => $url,
                        'method' => Request::METHOD_GET,
                        'headers' => ['Accept' => 'text/plain'],
                        'body' => null
                    ])
                )
            ];
        }

        //Call async operations
        return $this->proxyDeferredFactory->create([
            'deferred' => $this->callbackDeferredFactory->create(
                [
                    'callback' => function () use ($requests) {
                        $result = $this->resultFactory->create();

                        foreach ($requests as $request) {
                            $response = null;

                            try {
                                $response = $request['deferred']->get()->getBody();
                            } catch (HttpException $e) {
                                $this->logger->error(
                                    '[Omnipro\DeferredExample\Model\StatusProcessor::getWrapperStatusesByAsyncMode] - ',
                                    [
                                        'exception' => $e->getMessage()
                                    ]
                                );
                            }

                            if ($response) {
                                $result->append($this->httpStatusFactory->create()
                                    ->setCode($request['code'])
                                    ->setUrl($request['url'])
                                    ->setResponse($response));
                            }
                        }

                        return $result;
                    }
                ]
            )
        ]);
    }

    /**
     * Get random http status code
     *
     * @return int
     */
    private function getRandomHttpStatusCode(): int
    {
        return self::AVAILABLE_STATUSES_CODES[array_rand(self::AVAILABLE_STATUSES_CODES)];
    }

    /**
     * Get service URL
     *
     * @param int $httpStatusCode
     * @param int $sleep
     * @return string
     */
    private function getServiceUrl(int $httpStatusCode, int $sleep = 10000): string
    {
        return self::SERVICE_URL . $httpStatusCode . '?sleep=' . $sleep;
    }
}
