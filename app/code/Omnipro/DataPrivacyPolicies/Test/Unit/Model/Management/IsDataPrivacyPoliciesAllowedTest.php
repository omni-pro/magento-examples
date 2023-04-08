<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Test\Unit\Model\Management
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Test\Unit\Model\Management;

use Omnipro\DataPrivacyPolicies\Model\Management\IsDataPrivacyPoliciesAllowed;
use Omnipro\DataPrivacyPolicies\Model\ResourceModel\GetDataPrivacyPoliciesAllowed;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit Test for IsDataPrivacyPoliciesAllowedTest Model
 */
class IsDataPrivacyPoliciesAllowedTest extends TestCase
{

    /**
     * @var MockObject
     */
    private $getDataPrivacyPoliciesAllowedMock;

    /**
     * @var IsDataPrivacyPoliciesAllowed
     */
    private $isDataPrivacyPoliciesAllowed;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->getDataPrivacyPoliciesAllowedMock = $this->getMockBuilder(GetDataPrivacyPoliciesAllowed::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['execute'])
            ->getMock();

        $this->isDataPrivacyPoliciesAllowed = new IsDataPrivacyPoliciesAllowed(
            $this->getDataPrivacyPoliciesAllowedMock
        );
    }

    /**
     * Test for execute IsDataPrivacyPoliciesAllowed model
     *
     * @param int $customerId
     * @param bool $expectedResult
     * @return void
     * @dataProvider getDataForTestExecute
     */
    public function testExecute(int $customerId, bool $expectedResult): void
    {
        $this->getDataPrivacyPoliciesAllowedMock->expects($this->once())
            ->method('execute')
            ->with($customerId)
            ->willReturn($expectedResult);

        $this->assertEquals($expectedResult, $this->isDataPrivacyPoliciesAllowed->execute($customerId));
    }

    /**
     * Data Provider for ExecuteTest
     *
     * @return array
     */
    public function getDataForTestExecute(): array
    {
        $result = [
            [5, true],
            [4, false]
        ];
        return $result;
    }
}
