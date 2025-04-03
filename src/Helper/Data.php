<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionInfo\Helper;

use Magento\Catalog\Model\ResourceModel\Product\Option\Collection;
use Magento\Store\Model\Store;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Data
{
    public function addInfoToResult(Collection $collection, $storeId): void
    {
        if ($collection->isLoaded() || $collection->hasFlag('info')) {
            return;
        }

        $dbAdapter = $collection->getConnection();

        $tableName = $dbAdapter->getTableName('catalog_product_option_info');

        $select = $collection->getSelect();

        $select->joinLeft(
            ['default_option_info' => $tableName],
            sprintf(
                'default_option_info.option_id = main_table.option_id AND %s',
                $dbAdapter->quoteInto(
                    'default_option_info.store_id = ?',
                    Store::DEFAULT_STORE_ID
                )
            ),
            ['default_headline' => 'headline', 'default_description' => 'description', 'default_image' => 'image']
        );

        $headlineExpr = $dbAdapter->getCheckSql(
            'store_option_info.headline IS NULL',
            'default_option_info.headline',
            'store_option_info.headline'
        );

        $descriptionExpr = $dbAdapter->getCheckSql(
            'store_option_info.description IS NULL',
            'default_option_info.description',
            'store_option_info.description'
        );

        $imageExpr = $dbAdapter->getCheckSql(
            'store_option_info.image IS NULL',
            'default_option_info.image',
            'store_option_info.image'
        );

        $select->joinLeft(
            ['store_option_info' => $tableName],
            sprintf(
                'store_option_info.option_id = main_table.option_id AND %s',
                $dbAdapter->quoteInto(
                    'store_option_info.store_id = ?',
                    $storeId
                )
            ),
            [
                'store_headline'    => 'headline',
                'store_description' => 'description',
                'store_image'       => 'image',
                'headline'          => $headlineExpr,
                'description'       => $descriptionExpr,
                'image'             => $imageExpr
            ]
        );

        $collection->setFlag(
            'info',
            true
        );
    }

    public function addValueInfoToResult(
        \Magento\Catalog\Model\ResourceModel\Product\Option\Value\Collection $collection,
        $storeId
    ): void {
        if ($collection->isLoaded() || $collection->hasFlag('info')) {
            return;
        }

        $dbAdapter = $collection->getConnection();

        $tableName = $dbAdapter->getTableName('catalog_product_option_type_info');

        $select = $collection->getSelect();

        $select->joinLeft(
            ['default_option_value_info' => $tableName],
            sprintf(
                'default_option_value_info.option_type_id = main_table.option_type_id AND %s',
                $dbAdapter->quoteInto(
                    'default_option_value_info.store_id = ?',
                    Store::DEFAULT_STORE_ID
                )
            ),
            ['default_headline' => 'headline', 'default_description' => 'description', 'default_image' => 'image']
        );

        $headlineExpr = $dbAdapter->getCheckSql(
            'store_option_value_info.headline IS NULL',
            'default_option_value_info.headline',
            'store_option_value_info.headline'
        );

        $descriptionExpr = $dbAdapter->getCheckSql(
            'store_option_value_info.description IS NULL',
            'default_option_value_info.description',
            'store_option_value_info.description'
        );

        $imageExpr = $dbAdapter->getCheckSql(
            'store_option_value_info.image IS NULL',
            'default_option_value_info.image',
            'store_option_value_info.image'
        );

        $select->joinLeft(
            ['store_option_value_info' => $tableName],
            sprintf(
                'store_option_value_info.option_type_id = main_table.option_type_id AND %s',
                $dbAdapter->quoteInto(
                    'store_option_value_info.store_id = ?',
                    $storeId
                )
            ),
            [
                'store_headline'    => 'headline',
                'store_description' => 'description',
                'store_image'       => 'image',
                'headline'          => $headlineExpr,
                'description'       => $descriptionExpr,
                'image'             => $imageExpr
            ]
        );

        $collection->setFlag(
            'info',
            true
        );
    }
}
