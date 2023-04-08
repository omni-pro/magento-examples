<?php

/*
 * Created on Sat Apr 08 2023
 * @author Daniel Antonio Moreno Ramirez <daniel.moreno@omni.pro>
 * @package Omnipro\DataPrivacyPolicies\Model\ResourceModel
 * @category Omnipro
 * @copyright Copyright (c) 2023 Omnipro (https://www.omni.pro/)
 */

namespace Omnipro\DataPrivacyPolicies\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;

/**
 * Save Data Privacies Policies
 */
class SaveDataPrivacyPoliciesAllowed
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * Constructor
     *
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Save record data privacy policies
     *
     * @param int $customerId
     * @return void
     */
    public function execute(int $customerId): void
    {
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('omnipro_data_privacy_policies_allowed');

        $connection->insertOnDuplicate(
            $tableName,
            [
                'customer_id' => $customerId
            ]
        );
    }
}
