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
use Omnipro\DataPrivacyPolicies\Model\ResourceModel\SaveDataPrivacyPoliciesAllowed;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit Test for SaveDataPrivacyPoliciesAllowed Model
 */
class SaveDataPrivacyPoliciesAllowedTest extends TestCase
{

    /**
     * @var MockObject
     */
    private $resourceConnectionMock;

    /**
     * @var SaveDataPrivacyPoliciesAllowed
     */
    private $saveDataPrivacyPoliciesAllowed;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->resourceConnectionMock = $this->getMockBuilder(ResourceConnection::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getConnection', 'getTableName'])
            ->getMock();

        $this->saveDataPrivacyPoliciesAllowed = new SaveDataPrivacyPoliciesAllowed(
            $this->resourceConnectionMock
        );
    }

    /**
     * Test for execute SaveDataPrivacyPoliciesAllowed model
     *
     * @return void
     */
    public function testExecute(): void
    {
        $customerId = 5;
        $expectedResult = 1;
        $tableName = 'mage.omnipro_data_privacy_policies_allowed';
        $data = ['customer_id' => $customerId];

        $connectionMock = $this->getMockBuilder(AdapterInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['insertOnDuplicate'])
            ->getMockForAbstractClass();

        $this->resourceConnectionMock->expects($this->once())
            ->method('getTableName')
            ->willReturn($tableName);

        $this->resourceConnectionMock->expects($this->once())
            ->method('getConnection')
            ->willReturn($connectionMock);

        $connectionMock->expects($this->once())
            ->method('insertOnDuplicate')
            ->with($tableName, $data)
            ->willReturn($expectedResult);

        $this->saveDataPrivacyPoliciesAllowed->execute($customerId);
    }
}
