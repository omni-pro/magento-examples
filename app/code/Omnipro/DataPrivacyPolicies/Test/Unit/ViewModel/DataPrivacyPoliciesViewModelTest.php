<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Test\Unit\ViewModel
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Test\Unit\ViewModel;

use Magento\Customer\Model\Session;
use Magento\Framework\UrlInterface;
use Omnipro\DataPrivacyPolicies\Api\ConfigInterface;
use Omnipro\DataPrivacyPolicies\Api\Management\IsDataPrivacyPoliciesAllowedInterface;
use Omnipro\DataPrivacyPolicies\ViewModel\DataPrivacyPoliciesViewModel;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit Test for DataPrivacyPoliciesViewModel Model
 */
class DataPrivacyPoliciesViewModelTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $customerSessionMock;

    /**
     * @var MockObject
     */
    private $urlMock;

    /**
     * @var MockObject
     */
    private $configMock;

    /**
     * @var MockObject
     */
    private $isDataPrivacyPoliciesAllowdMock;

    /**
     * @var DataPrivacyPoliciesViewModel
     */
    private $dataPrivacyPoliciesViewModel;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->customerSessionMock = $this->getMockBuilder(Session::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getId'])
            ->getMock();

        $this->urlMock = $this->getMockBuilder(UrlInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getUrl'])
            ->getMockForAbstractClass();

        $this->configMock = $this->getMockBuilder(ConfigInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isEnabled', 'isMandatory', 'getCheckboxTitle', 'getLinkTitle', 'getPageIdentifier'])
            ->getMockForAbstractClass();

        $this->isDataPrivacyPoliciesAllowdMock = $this->getMockBuilder(IsDataPrivacyPoliciesAllowedInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['execute'])
            ->getMockForAbstractClass();

        $this->dataPrivacyPoliciesViewModel = new DataPrivacyPoliciesViewModel(
            $this->customerSessionMock,
            $this->urlMock,
            $this->configMock,
            $this->isDataPrivacyPoliciesAllowdMock
        );
    }

    /**
     * Test for IsDataPrivacyPoliciesEnabled DataPrivacyPoliciesViewModel model
     *
     * @param bool $expectedResult
     * @return void
     * @dataProvider getDataForTestIsDataPrivacyPoliciesEnabled
     */
    public function testIsDataPrivacyPoliciesEnabled(bool $expectedResult): void
    {
        $this->configMock->expects($this->once())
            ->method('isEnabled')
            ->willReturn(true);

        $this->assertEquals(true, $this->dataPrivacyPoliciesViewModel->isDataPrivacyPoliciesEnabled());
    }

    /**
     * Test for IsDataPrivacyPoliciesMandatory DataPrivacyPoliciesViewModel model
     *
     * @param bool $expectedResult
     * @return void
     * @dataProvider getDataForTestIsDataPrivacyPoliciesMandatory
     */
    public function testIsDataPrivacyPoliciesMandatory(bool $expectedResult): void
    {
        $this->configMock->expects($this->once())
            ->method('isMandatory')
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $this->dataPrivacyPoliciesViewModel->isDataPrivacyPoliciesMandatory());
    }

    /**
     * Test for GetDataPrivacyPoliciesCheckBoxTitle DataPrivacyPoliciesViewModel model
     *
     * @return void
     */
    public function testGetDataPrivacyPoliciesCheckBoxTitle(): void
    {
        $expectedResult = 'Checkbox title';

        $this->configMock->expects($this->once())
            ->method('getCheckboxTitle')
            ->willReturn($expectedResult);

        $this->assertEquals(
            $expectedResult,
            $this->dataPrivacyPoliciesViewModel->getDataPrivacyPoliciesCheckBoxTitle()
        );
    }

    /**
     * Test for GetDataPrivacyPoliciesLinkTitle DataPrivacyPoliciesViewModel model
     *
     * @return void
     */
    public function testGetDataPrivacyPoliciesLinkTitle(): void
    {
        $expectedResult = 'Link title';

        $this->configMock->expects($this->once())
            ->method('getLinkTitle')
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $this->dataPrivacyPoliciesViewModel->getDataPrivacyPoliciesLinkTitle());
    }

    /**
     * Test for GetDataPrivacyPoliciesPageUrl DataPrivacyPoliciesViewModel model
     *
     * @return void
     */
    public function testGetDataPrivacyPoliciesPageUrl(): void
    {
        $pageIdentifier = 'no-route';
        $baseUrl = 'https://www.magento.com/';
        $expectedResult = $baseUrl . $pageIdentifier;

        $this->configMock->expects($this->once())
            ->method('getPageIdentifier')
            ->willReturn($pageIdentifier);

        $this->urlMock->expects($this->once())
            ->method('getUrl')
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $this->dataPrivacyPoliciesViewModel->getDataPrivacyPoliciesPageUrl());
    }

    /**
     * Test for GetDataPrivacyPoliciesPageUrl DataPrivacyPoliciesViewModel model
     *
     * @param int|null $customerId
     * @param bool $expectedResult
     * @return void
     * @dataProvider getDataForTestIsDataPrivacyPoliciesAllowed
     */
    public function testIsDataPrivacyPoliciesAllowed(?int $customerId, bool $expectedResult): void
    {
        $this->customerSessionMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);

        $this->isDataPrivacyPoliciesAllowdMock->expects($this->any())
            ->method('execute')
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $this->dataPrivacyPoliciesViewModel->isDataPrivacyPoliciesAllowed());
    }

    /**
     * Data Provider for IsDataPrivacyPoliciesEnabled Test
     *
     * @return array
     */
    public function getDataForTestIsDataPrivacyPoliciesEnabled(): array
    {
        $result = [
            [true],
            [false]
        ];
        return $result;
    }

    /**
     * Data Provider for IsDataPrivacyPoliciesMandatory Test
     *
     * @return array
     */
    public function getDataForTestIsDataPrivacyPoliciesMandatory(): array
    {
        $result = [
            [true],
            [false]
        ];
        return $result;
    }

    /**
     * Data Provider for IsDataPrivacyPolciiesAllowed Test
     *
     * @return array
     */
    public function getDataForTestIsDataPrivacyPoliciesAllowed(): array
    {
        $result = [
            [5, true],
            [4, false],
            [null, false],
        ];
        return $result;
    }
}
