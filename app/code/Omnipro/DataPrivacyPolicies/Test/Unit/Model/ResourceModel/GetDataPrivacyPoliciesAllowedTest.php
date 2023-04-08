<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Test\Unit\Model\ResourceModel
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Test\Unit\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Omnipro\DataPrivacyPolicies\Model\ResourceModel\GetDataPrivacyPoliciesAllowed;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit Test for GetDataPrivacyPoliciesAllowed Model
 */
class GetDataPrivacyPoliciesAllowedTest extends TestCase
{

    /**
     * @var MockObject
     */
    private $resourceConnectionMock;

    /**
     * @var GetDataPrivacyPoliciesAllowed
     */
    private $getDataPrivacyPoliciesAllowed;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->resourceConnectionMock = $this->getMockBuilder(ResourceConnection::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConnection', 'getTableName'])
            ->getMock();

        $this->getDataPrivacyPoliciesAllowed = new GetDataPrivacyPoliciesAllowed(
            $this->resourceConnectionMock
        );
    }

    /**
     * Test for execute GetDataPrivacyPoliciesAllowed model
     *
     * @return void
     * @dataProvider getDataForTestIsMandatory
     */
    public function testExecute(int $customerId, ?string $resultQuery, bool $expectedResult): void
    {
        $tableName = 'mage.omnipro_data_privacy_policies_allowed';
        $where = 'customer_id = ?';

        $connectionMock = $this->getMockBuilder(AdapterInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'fetchOne'])
            ->getMockForAbstractClass();

        $this->resourceConnectionMock->expects($this->once())
            ->method('getTableName')
            ->willReturn($tableName);

        $this->resourceConnectionMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($connectionMock);

        $selectMock = $this->getMockBuilder(Select::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['from', 'where'])
            ->getMock();

        $selectMock->expects($this->once())
            ->method('from')
            ->with($tableName)
            ->willReturnSelf();

        $selectMock->expects($this->once())
            ->method('where')
            ->with($where, $customerId)
            ->willReturnSelf();

        $connectionMock->expects($this->once())
            ->method('select')
            ->willReturn($selectMock);

        $connectionMock->expects($this->once())
            ->method('fetchOne')
            ->willReturn($resultQuery);

        $this->assertEquals($expectedResult, $this->getDataPrivacyPoliciesAllowed->execute($customerId));
    }

    /**
     * Data Provider for ExecuteTest
     *
     * @return array
     */
    public function getDataForTestIsMandatory(): array
    {
        $result = [
            [5, '5', true],
            [4, '4', true],
            [6, null, false]
        ];
        return $result;
    }
}
