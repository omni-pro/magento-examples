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
use Omnipro\DataPrivacyPolicies\Model\ResourceModel\DeleteDataPrivacyPoliciesAllowed;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit Test for DeleteDataPrivacyPoliciesAllowed Model
 */
class DeleteDataPrivacyPoliciesAllowedTest extends TestCase
{

    /**
     * @var MockObject
     */
    private $resourceConnectionMock;

    /**
     * @var DeleteDataPrivacyPoliciesAllowed
     */
    private $deleteDataPrivacyPoliciesAllowed;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->resourceConnectionMock = $this->getMockBuilder(ResourceConnection::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConnection', 'getTableName'])
            ->getMock();

        $this->deleteDataPrivacyPoliciesAllowed = new DeleteDataPrivacyPoliciesAllowed(
            $this->resourceConnectionMock
        );
    }

    /**
     * Test for execute DeleteDataPrivacyPoliciesAllowed model
     *
     * @return void
     */
    public function testExecute(): void
    {
        $customerId = 5;
        $expectedResult = 0;
        $tableName = 'mage.omnipro_data_privacy_policies_allowed';
        $where = ['customer_id = ?' => $customerId];

        $connectionMock = $this->getMockBuilder(AdapterInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['delete'])
            ->getMockForAbstractClass();

        $this->resourceConnectionMock->expects($this->once())
            ->method('getTableName')
            ->willReturn($tableName);

        $this->resourceConnectionMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($connectionMock);

        $connectionMock->expects($this->once())
            ->method('delete')
            ->with($tableName, $where)
            ->willReturn($expectedResult);

        $this->deleteDataPrivacyPoliciesAllowed->execute($customerId);
    }
}
