<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Test\Unit\Plugin\Adminhtml\Model
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Test\Unit\Plugin\Adminhtml\Model;

use Magento\Customer\Model\Customer\DataProviderWithDefaultAddresses;
use Magento\Framework\AuthorizationInterface;
use Omnipro\DataPrivacyPolicies\Api\ConfigInterface;
use Omnipro\DataPrivacyPolicies\Api\Management\IsDataPrivacyPoliciesAllowedInterface;
use Omnipro\DataPrivacyPolicies\Plugin\Adminhtml\Model\DataProviderWithDefaultAddressesPlugin;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit Test for DataProviderWithDefaultAddressesPlugin Model
 */
class DataProviderWithDefaultAddressesPluginTest extends TestCase
{

    /**
     * @var MockObject
     */
    private $authorizationMock;

    /**
     * @var MockObject
     */
    private $configMock;

    /**
     * @var MockObject
     */
    private $isDataPrivacyPoliciesAllowedMock;

    /**
     * @var DataProviderWithDefaultAddressesPlugin
     */
    private $dataProviderWithDefaultAddressesPlugin;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->authorizationMock = $this->getMockBuilder(AuthorizationInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isAllowed'])
            ->getMockForAbstractClass();

        $this->configMock = $this->getMockBuilder(ConfigInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isEnabled'])
            ->getMockForAbstractClass();

        $this->isDataPrivacyPoliciesAllowedMock = $this->getMockBuilder(IsDataPrivacyPoliciesAllowedInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['execute'])
            ->getMockForAbstractClass();

        $this->dataProviderWithDefaultAddressesPlugin = new DataProviderWithDefaultAddressesPlugin(
            $this->authorizationMock,
            $this->configMock,
            $this->isDataPrivacyPoliciesAllowedMock
        );
    }

    /**
     * Test for afterGetData DataProviderWithDefaultAddresses model
     *
     * @param int $customerId
     * @param bool $isAllowed
     * @return void
     * @dataProvider getDataForTestAfterGetData
     */
    public function testAfterGetData(int $customerId, bool $isAllowed): void
    {
        $dataProviderWithDefaultAddressesMock = $this->getMockBuilder(DataProviderWithDefaultAddresses::class)
            ->disableOriginalConstructor()
            ->getMock();

        $result = [
            $customerId => [
                'customer_id' => $customerId
            ]
        ];

        $expectedResult = [
            $customerId => [
                'customer_id' => $customerId,
                'customer' => [
                    'extension_attributes' => [
                        'data_privacy_policies_allowed' => (string)(int)$isAllowed
                    ]
                ]
            ]
        ];

        $this->isDataPrivacyPoliciesAllowedMock->expects($this->once())
            ->method('execute')
            ->with($customerId)
            ->willReturn($isAllowed);

        $this->assertEquals($expectedResult, $this->dataProviderWithDefaultAddressesPlugin->afterGetData(
            $dataProviderWithDefaultAddressesMock,
            $result
        ));
    }

    /**
     * Test for afterGetMeta DataProviderWithDefaultAddresses model
     *
     * @return void
     */
    public function testAfterGetMetaWithConfigDisabled(): void
    {
        $config = false;
        $expectedResult = [
            'customer' => [
                'children' => [
                    'extension_attributes.data_privacy_policies_allowed' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'visible' => $config
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $dataProviderWithDefaultAddressesMock = $this->getMockBuilder(DataProviderWithDefaultAddresses::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock->expects($this->once())
            ->method('isEnabled')
            ->willReturn($config);

        $this->assertEquals(
            $expectedResult,
            $this->dataProviderWithDefaultAddressesPlugin->afterGetMeta(
                $dataProviderWithDefaultAddressesMock,
                []
            )
        );
    }

    /**
     * Test for afterGetMeta DataProviderWithDefaultAddresses model
     *
     * @return void
     */
    public function testAfterGetMetaAuthorized(): void
    {
        $expectedResult = [
            'customer' => [
                'children' => [
                    'extension_attributes.data_privacy_policies_allowed' => [
                        'arguments' => [
                            'data' => [
                                'config' => []
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $dataProviderWithDefaultAddressesMock = $this->getMockBuilder(DataProviderWithDefaultAddresses::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock->expects($this->once())
            ->method('isEnabled')
            ->willReturn(true);

        $this->authorizationMock->expects($this->once())
            ->method('isAllowed')
            ->willReturn(true);

        $this->assertEquals(
            $expectedResult,
            $this->dataProviderWithDefaultAddressesPlugin->afterGetMeta(
                $dataProviderWithDefaultAddressesMock,
                []
            )
        );
    }

    /**
     * Test for afterGetMeta DataProviderWithDefaultAddresses model
     *
     * @return void
     */
    public function testAfterGetMetaUnAuthorized(): void
    {
        $expectedResult = [
            'customer' => [
                'children' => [
                    'extension_attributes.data_privacy_policies_allowed' => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'disabled' => true,
                                    'notice' => __('You have no permission to change Opt-In preference.'),
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $dataProviderWithDefaultAddressesMock = $this->getMockBuilder(DataProviderWithDefaultAddresses::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->configMock->expects($this->once())
            ->method('isEnabled')
            ->willReturn(true);

        $this->authorizationMock->expects($this->once())
            ->method('isAllowed')
            ->willReturn(false);

        $this->assertEquals(
            $expectedResult,
            $this->dataProviderWithDefaultAddressesPlugin->afterGetMeta(
                $dataProviderWithDefaultAddressesMock,
                []
            )
        );
    }

    /**
     * Data Provider for afterGetData
     *
     * @return array
     */
    public function getDataForTestAfterGetData(): array
    {
        $result = [
            [5, true],
            [4, false]
        ];
        return $result;
    }
}
