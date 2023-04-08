<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Test\Unit\Model
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Test\Unit\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Omnipro\DataPrivacyPolicies\Model\Config;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit Test for Config Model
 */
class ConfigTest extends TestCase
{

    /**
     * @var MockObject
     */
    private $scopeConfigMock;

    /**
     * @var Config
     */
    private $configModel;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isSetFlag', 'getValue'])
            ->getMockForAbstractClass();

        $this->configModel = new Config($this->scopeConfigMock);
    }

    /**
     * Test for isEnabled config model
     *
     * @return void
     */
    public function testIsEnabled(): void
    {
        $expectedResult = true;

        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->willReturn($expectedResult);

        $this->assertTrue($this->configModel->isEnabled());
    }

    /**
     * Test for isMandatory config model
     *
     * @return void
     * @dataProvider getDataForTestIsMandatory
     */
    public function testIsMandatory(bool $expectedResult): void
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $this->configModel->isMandatory());
    }

    /**
     * Data Provider for IsMandatoryTest
     *
     * @return array
     */
    public function getDataForTestIsMandatory(): array
    {
        $result = [
            'Result true' => [true],
            'Result False' => [false]
        ];
        return $result;
    }

    /**
     * Test for getCheckboxTitle config model
     *
     * @return void
     */
    public function testGetCheckboxTitle(): void
    {
        $expectedResult = 'Checkbox Title';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $this->configModel->getCheckboxTitle());
    }

    /**
     * Test for getLinkTitle config model
     *
     * @return void
     */
    public function testGetLinkTitle(): void
    {
        $expectedResult = 'Link Title';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $this->configModel->getLinkTitle());
    }

    /**
     * Test for getPageIdentifier config model
     *
     * @return void
     */
    public function testPageIdentifier(): void
    {
        $expectedResult = 'no-route';

        $this->scopeConfigMock->expects($this->once())
            ->method('getValue')
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $this->configModel->getPageIdentifier());
    }
}
