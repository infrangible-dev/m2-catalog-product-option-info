<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionInfo\Setup;

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
}
