<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Test\Unit\Plugin\Frontend\Model
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Test\Unit\Plugin\Frontend\Model;

use Magento\Customer\Api\Data\CustomerExtensionFactory;
use Magento\Customer\Api\Data\CustomerExtensionInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\CustomerExtractor;
use Magento\Framework\App\RequestInterface;
use Omnipro\DataPrivacyPolicies\Plugin\Frontend\Model\CustomerExtractorPlugin;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit Test for CustomerExtractorPlugin Model
 */
class CustomerExtractorPluginTest extends TestCase
{

    /**
     * @var MockObject
     */
    private $customerExtensionFactoryMock;

    /**
     * @var CustomerExtractorPlugin
     */
    private $customerExtractorPlugin;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->customerExtensionFactoryMock = $this->getMockBuilder(CustomerExtensionFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMockForAbstractClass();

        $this->customerExtractorPlugin = new CustomerExtractorPlugin(
            $this->customerExtensionFactoryMock
        );
    }

    /**
     * Test for afterExtract CustomerExtractorPlugin model
     *
     * @param string|null $requestParamDataPrivacyPoliciesAllowed
     * @return void
     * @dataProvider getDataForTestAfterExtract
     */
    public function testAfterExtract(?string $requestParamDataPrivacyPoliciesAllowed): void
    {
        $customerExtractorMock = $this->getMockBuilder(CustomerExtractor::class)
            ->disableOriginalConstructor()
            ->getMock();

        $requestMock = $this->getMockBuilder(RequestInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getParam'])
            ->getMockForAbstractClass();

        $requestMock->expects($this->once())
            ->method('getParam')
            ->willReturn($requestParamDataPrivacyPoliciesAllowed);

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getExtensionAttributes', 'setExtensionAttributes'])
            ->getMockForAbstractClass();

        $customerExtensionAttributesMock = $this->getMockBuilder(CustomerExtensionInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['setDataPrivacyPoliciesAllowed'])
            ->getMockForAbstractClass();

        $customerExtensionAttributesMock->expects($this->once())
            ->method('setDataPrivacyPoliciesAllowed')
            ->willReturnSelf();

        $customerMock->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn(null);

        $this->customerExtensionFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($customerExtensionAttributesMock);

        $customerMock->expects($this->once())
            ->method('setExtensionAttributes')
            ->willReturnSelf();

        $this->assertEquals($customerMock, $this->customerExtractorPlugin->afterExtract(
            $customerExtractorMock,
            $customerMock,
            '',
            $requestMock,
            []
        ));
    }

    /**
     * Data Provider for AfterExtractTest
     *
     * @return array
     */
    public function getDataForTestAfterExtract(): array
    {
        $result = [
            ['on'],
            ['1'],
            [null]
        ];
        return $result;
    }
}
