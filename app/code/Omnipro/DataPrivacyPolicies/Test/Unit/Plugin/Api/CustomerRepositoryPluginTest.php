<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Test\Unit\Plugin\Api
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Test\Unit\Plugin\Api;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerExtensionInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\AuthorizationInterface;
use Omnipro\DataPrivacyPolicies\Api\Management\SetDataPrivacyPoliciesInterface;
use Omnipro\DataPrivacyPolicies\Plugin\Api\CustomerRepositoryPlugin;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * Unit Test for CustomerRepositoryPlugin Model
 */
class CustomerRepositoryPluginTest extends TestCase
{

    /**
     * @var MockObject
     */
    private $userContextMock;

    /**
     * @var MockObject
     */
    private $authorizationMock;

    /**
     * @var MockObject
     */
    private $setDataPrivacyPoliciesMock;

    /**
     * @var CustomerRepositoryPlugin
     */
    private $customerRepositoryPlugin;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        $this->userContextMock = $this->getMockBuilder(UserContextInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getUserType'])
            ->getMockForAbstractClass();

        $this->authorizationMock = $this->getMockBuilder(AuthorizationInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isAllowed'])
            ->getMockForAbstractClass();

        $this->setDataPrivacyPoliciesMock = $this->getMockBuilder(SetDataPrivacyPoliciesInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['execute'])
            ->getMockForAbstractClass();

        $this->customerRepositoryPlugin = new CustomerRepositoryPlugin(
            $this->userContextMock,
            $this->authorizationMock,
            $this->setDataPrivacyPoliciesMock
        );
    }

    /**
     * Test for afterSave CustomerRepositoryPlugin model
     *
     * @param int $userType
     * @return void
     * @dataProvider getDataForTestAfterSave
     */
    public function testAfterSave(int $userType): void
    {
        $customerId = 5;

        $this->userContextMock->expects($this->atLeastOnce())
            ->method('getUserType')
            ->willReturn($userType);

        $customerRepositoryMock = $this->getMockBuilder(CustomerRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $resultMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getId'])
            ->getMockForAbstractClass();

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getExtensionAttributes'])
            ->getMockForAbstractClass();

        $resultMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);

        $customerExtensionAttributesMock = $this->getMockBuilder(CustomerExtensionInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDataPrivacyPoliciesAllowed'])
            ->getMockForAbstractClass();

        $customerMock->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($customerExtensionAttributesMock);

        $customerExtensionAttributesMock->expects($this->exactly(2))
            ->method('getDataPrivacyPoliciesAllowed')
            ->willReturn(1);

        $this->setDataPrivacyPoliciesMock->expects($this->once())
            ->method('execute')
            ->with($customerId, true);

        $this->customerRepositoryPlugin->afterSave(
            $customerRepositoryMock,
            $resultMock,
            $customerMock,
            null
        );
    }

    /**
     * Test for afterSave CustomerRepositoryPlugin model
     *
     * @param int $userType
     * @return void
     * @dataProvider getDataForTestAfterSave
     */
    public function testAfterSaveWithoutExtensionAttributes(int $userType): void
    {
        $customerId = 5;

        $this->userContextMock->expects($this->atLeastOnce())
            ->method('getUserType')
            ->willReturn($userType);

        $customerRepositoryMock = $this->getMockBuilder(CustomerRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $resultMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getId'])
            ->getMockForAbstractClass();

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getExtensionAttributes'])
            ->getMockForAbstractClass();

        $resultMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);

        $customerMock->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn(null);

        $this->customerRepositoryPlugin->afterSave(
            $customerRepositoryMock,
            $resultMock,
            $customerMock,
            null
        );
    }

    /**
     * Test for afterSave CustomerRepositoryPlugin model
     *
     * @param int $userType
     * @return void
     * @dataProvider getDataForTestAfterSaveAdminOrIntegrationAuthorized
     */
    public function testAfterSaveAdminOrIntegrationAuthorized(int $userType): void
    {
        $customerId = 5;

        $this->userContextMock->expects($this->atLeastOnce())
            ->method('getUserType')
            ->willReturn($userType);

        $this->authorizationMock->expects($this->once())
            ->method('isAllowed')
            ->willReturn(true);

        $customerRepositoryMock = $this->getMockBuilder(CustomerRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $resultMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getId'])
            ->getMockForAbstractClass();

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getExtensionAttributes'])
            ->getMockForAbstractClass();

        $resultMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);

        $customerExtensionAttributesMock = $this->getMockBuilder(CustomerExtensionInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDataPrivacyPoliciesAllowed'])
            ->getMockForAbstractClass();

        $customerMock->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($customerExtensionAttributesMock);

        $customerExtensionAttributesMock->expects($this->exactly(2))
            ->method('getDataPrivacyPoliciesAllowed')
            ->willReturn(1);

        $this->setDataPrivacyPoliciesMock->expects($this->once())
            ->method('execute')
            ->with($customerId, true);

        $this->customerRepositoryPlugin->afterSave(
            $customerRepositoryMock,
            $resultMock,
            $customerMock,
            null
        );
    }

    /**
     * Test for afterSave CustomerRepositoryPlugin model
     *
     * @param int $userType
     * @return void
     * @dataProvider getDataForTestAfterSaveAdminOrIntegrationAuthorized
     */
    public function testAfterSaveAdminOrIntegrationUnAuthorized(int $userType): void
    {
        $customerId = 5;

        $this->userContextMock->expects($this->atLeastOnce())
            ->method('getUserType')
            ->willReturn($userType);

        $this->authorizationMock->expects($this->once())
            ->method('isAllowed')
            ->willReturn(false);

        $customerRepositoryMock = $this->getMockBuilder(CustomerRepositoryInterface::class)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $resultMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getId'])
            ->getMockForAbstractClass();

        $customerMock = $this->getMockBuilder(CustomerInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getExtensionAttributes'])
            ->getMockForAbstractClass();

        $resultMock->expects($this->once())
            ->method('getId')
            ->willReturn($customerId);

        $customerExtensionAttributesMock = $this->getMockBuilder(CustomerExtensionInterface::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getDataPrivacyPoliciesAllowed'])
            ->getMockForAbstractClass();

        $customerMock->expects($this->once())
            ->method('getExtensionAttributes')
            ->willReturn($customerExtensionAttributesMock);

        $this->customerRepositoryPlugin->afterSave(
            $customerRepositoryMock,
            $resultMock,
            $customerMock,
            null
        );
    }

    /**
     * Data Provider for afterSave
     *
     * @return array
     */
    public function getDataForTestAfterSaveAdminOrIntegrationAuthorized(): array
    {
        $result = [
            [UserContextInterface::USER_TYPE_ADMIN],
            [UserContextInterface::USER_TYPE_INTEGRATION]
        ];
        return $result;
    }

    /**
     * Data Provider for afterSave
     *
     * @return array
     */
    public function getDataForTestAfterSave(): array
    {
        $result = [
            [UserContextInterface::USER_TYPE_CUSTOMER],
            [UserContextInterface::USER_TYPE_GUEST]
        ];
        return $result;
    }
}
