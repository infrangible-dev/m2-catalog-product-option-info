<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionInfo\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @throws \Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context): void
    {
        $setup->startSetup();

        $connection = $setup->getConnection();

        if (version_compare(
            $context->getVersion(),
            '1.2.0',
            '<'
        )) {
            $optionInfoTableName = $connection->getTableName('catalog_product_option_info');

            if ($connection->tableColumnExists(
                $optionInfoTableName,
                'image'
            )) {
                $connection->modifyColumn(
                    $optionInfoTableName,
                    'image',
                    [
                        'type'   => Table::TYPE_TEXT,
                        'length' => 5000,
                    ]
                );
            }

            $optionValueInfoTableName = $connection->getTableName('catalog_product_option_type_info');

            if ($connection->tableColumnExists(
                $optionValueInfoTableName,
                'image'
            )) {
                $connection->modifyColumn(
                    $optionValueInfoTableName,
                    'image',
                    [
                        'type'   => Table::TYPE_TEXT,
                        'length' => 5000,
                    ]
                );
            }
        }

        $setup->endSetup();
    }

    /**
     * @throws \Exception
     */
    protected function addOptionInfoTable(AdapterInterface $connection): void
    {
        $optionInfoTableName = $connection->getTableName('catalog_product_option_info');

        if (! $connection->isTableExists($optionInfoTableName)) {
            $optionTableName = $connection->getTableName('catalog_product_option');
            $storeTableName = $connection->getTableName('store');

            $optionInfoTable = $connection->newTable($optionInfoTableName);

            $optionInfoTable->addColumn(
                'id',
                Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true]
            );
            $optionInfoTable->addColumn(
                'option_id',
                Table::TYPE_INTEGER,
                10,
                ['unsigned' => true, 'nullable' => false]
            );
            $optionInfoTable->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false]
            );
            $optionInfoTable->addColumn(
                'headline',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true]
            );
            $optionInfoTable->addColumn(
                'description',
                Table::TYPE_TEXT,
                10000,
                ['nullable' => true]
            );
            $optionInfoTable->addColumn(
                'image',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true]
            );

            $optionInfoTable->addForeignKey(
                $connection->getForeignKeyName(
                    $optionInfoTableName,
                    'option_id',
                    $optionTableName,
                    'option_id'
                ),
                'option_id',
                $optionTableName,
                'option_id',
                Table::ACTION_CASCADE
            );

            $optionInfoTable->addForeignKey(
                $connection->getForeignKeyName(
                    $optionInfoTableName,
                    'store_id',
                    $storeTableName,
                    'store_id'
                ),
                'store_id',
                $storeTableName,
                'store_id',
                Table::ACTION_CASCADE
            );

            $connection->createTable($optionInfoTable);
        }
    }

    /**
     * @throws \Exception
     */
    protected function addOptionValueInfoTable(AdapterInterface $connection): void
    {
        $optionValueInfoTableName = $connection->getTableName('catalog_product_option_type_info');

        if (! $connection->isTableExists($optionValueInfoTableName)) {
            $optionValueTableName = $connection->getTableName('catalog_product_option_type_value');
            $storeTableName = $connection->getTableName('store');

            $optionValueInfoTable = $connection->newTable($optionValueInfoTableName);

            $optionValueInfoTable->addColumn(
                'id',
                Table::TYPE_INTEGER,
                10,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true]
            );
            $optionValueInfoTable->addColumn(
                'option_type_id',
                Table::TYPE_INTEGER,
                10,
                ['unsigned' => true, 'nullable' => false]
            );
            $optionValueInfoTable->addColumn(
                'store_id',
                Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false]
            );
            $optionValueInfoTable->addColumn(
                'headline',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true]
            );
            $optionValueInfoTable->addColumn(
                'description',
                Table::TYPE_TEXT,
                10000,
                ['nullable' => true]
            );
            $optionValueInfoTable->addColumn(
                'image',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true]
            );

            $optionValueInfoTable->addForeignKey(
                $connection->getForeignKeyName(
                    $optionValueInfoTableName,
                    'option_type_id',
                    $optionValueTableName,
                    'option_type_id'
                ),
                'option_type_id',
                $optionValueTableName,
                'option_type_id',
                Table::ACTION_CASCADE
            );

            $optionValueInfoTable->addForeignKey(
                $connection->getForeignKeyName(
                    $optionValueInfoTableName,
                    'store_id',
                    $storeTableName,
                    'store_id'
                ),
                'store_id',
                $storeTableName,
                'store_id',
                Table::ACTION_CASCADE
            );

            $connection->createTable($optionValueInfoTable);
        }
    }
}
