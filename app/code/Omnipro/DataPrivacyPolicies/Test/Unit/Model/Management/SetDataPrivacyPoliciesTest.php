<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Test\Unit\Model\Management
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Test\Unit\Model\Management;

use Omnipro\DataPrivacyPolicies\Model\Management\SetDataPrivacyPolicies;
use Omnipro\DataPrivacyPolicies\Model\ResourceModel\DeleteDataPrivacyPoliciesAllowed;
use Omnipro\DataPrivacyPolicies\Model\ResourceModel\SaveDataPrivacyPoliciesAllowed;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit Test for SetDataPrivacyPoliciesTest Model
 */
class SetDataPrivacyPoliciesTest extends TestCase
{

    /**
     * @var MockObject
     */
    private $deleteDataPrivacyPoliciesAllowedMock;

    /**
     * @var MockObject
     */
    private $saveDataPrivacyPoliciesAllowedMock;

    /**
     * @var SetDataPrivacyPolicies
     */
    private $setDataPrivacyPolicies;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->deleteDataPrivacyPoliciesAllowedMock = $this->getMockBuilder(DeleteDataPrivacyPoliciesAllowed::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['execute'])
            ->getMock();

        $this->saveDataPrivacyPoliciesAllowedMock = $this->getMockBuilder(SaveDataPrivacyPoliciesAllowed::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['execute'])
            ->getMock();

        $this->setDataPrivacyPolicies = new SetDataPrivacyPolicies(
            $this->deleteDataPrivacyPoliciesAllowedMock,
            $this->saveDataPrivacyPoliciesAllowedMock
        );
    }

    /**
     * Test for execute SetDataPrivacyPolicies model logic save
     *
     * @return void
     */
    public function testExecuteSave(): void
    {
        $customerId = 5;

        $this->saveDataPrivacyPoliciesAllowedMock->expects($this->once())
            ->method('execute')
            ->with($customerId);

        $this->setDataPrivacyPolicies->execute($customerId, true);
    }

    /**
     * Test for execute SetDataPrivacyPolicies model logic delete
     *
     * @return void
     */
    public function testExecuteDelete(): void
    {
        $customerId = 5;

        $this->deleteDataPrivacyPoliciesAllowedMock->expects($this->once())
            ->method('execute')
            ->with($customerId);

        $this->setDataPrivacyPolicies->execute($customerId, false);
    }
}
