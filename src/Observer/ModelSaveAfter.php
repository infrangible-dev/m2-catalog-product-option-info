<?php /** @noinspection PhpDeprecationInspection */

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionInfo\Observer;

use FeWeDev\Base\Arrays;
use FeWeDev\Base\Variables;
use Infrangible\Core\Helper\Database;
use Magento\Catalog\Model\Product\Option;
use Magento\Catalog\Model\Product\Option\Value;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\Store;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class ModelSaveAfter implements ObserverInterface
{
    /** @var Database */
    protected $databaseHelper;

    /** @var Arrays */
    protected $arrays;

    /** @var Variables */
    protected $variables;

    public function __construct(Database $databaseHelper, Arrays $arrays, Variables $variables)
    {
        $this->databaseHelper = $databaseHelper;
        $this->arrays = $arrays;
        $this->variables = $variables;
    }

    /**
     * @throws \Exception
     */
    public function execute(Observer $observer): void
    {
        $object = $observer->getData('object');

        if ($object instanceof Option) {
            $optionId = $object->getData('option_id');
            $storeId = $object->getData('store_id');
            $headline = $object->getData('headline');
            $description = $object->getData('description');
            $image = $object->getData('image');

            if ($this->variables->isEmpty($headline)) {
                $headline = null;
            }

            if ($this->variables->isEmpty($description)) {
                $description = null;
            }

            if ($this->variables->isEmpty($image)) {
                $image = null;
            }

            $isDeleteRecord =
                $storeId > Store::DEFAULT_STORE_ID && $headline === null && $description === null && $image === null;

            $dbAdapter = $object->getResource()->getConnection();

            $tableName = $dbAdapter->getTableName('catalog_product_option_info');

            $query = $this->databaseHelper->select(
                $tableName,
                ['id', 'headline', 'description', 'image']
            );

            $query->where(
                'option_id = ?',
                $optionId
            );

            $query->where(
                'store_id  = ?',
                $storeId
            );

            $queryResult = $this->databaseHelper->fetchRow(
                $query,
                $dbAdapter
            );

            if ($queryResult === null) {
                if (! $isDeleteRecord) {
                    $this->databaseHelper->createTableData(
                        $dbAdapter,
                        $tableName,
                        [
                            'option_id'   => $optionId,
                            'store_id'    => $storeId,
                            'headline'    => $headline,
                            'description' => $description,
                            'image'       => $image
                        ]
                    );
                }
            } else {
                $currentHeadline = $this->arrays->getValue(
                    $queryResult,
                    'headline'
                );

                $currentDescription = $this->arrays->getValue(
                    $queryResult,
                    'description'
                );

                $currentImage = $this->arrays->getValue(
                    $queryResult,
                    'image'
                );

                $id = $this->arrays->getValue(
                    $queryResult,
                    'id'
                );

                if ($isDeleteRecord) {
                    $this->databaseHelper->deleteTableData(
                        $dbAdapter,
                        $tableName,
                        sprintf(
                            'id = %d',
                            $id
                        )
                    );
                } elseif ($currentHeadline != $headline || $currentDescription != $description ||
                    $currentImage != $image) {

                    $this->databaseHelper->updateTableData(
                        $dbAdapter,
                        $tableName,
                        [
                            'headline'    => $headline,
                            'description' => $description,
                            'image'       => $image
                        ],
                        sprintf(
                            'id = %d',
                            $id
                        )
                    );
                }
            }
        }

        if ($object instanceof Value) {
            $optionTypeId = $object->getData('option_type_id');
            $storeId = $object->getData('store_id');
            $headline = $object->getData('headline');
            $description = $object->getData('description');
            $image = $object->getData('image');

            if ($this->variables->isEmpty($headline)) {
                $headline = null;
            }

            if ($this->variables->isEmpty($description)) {
                $description = null;
            }

            if ($this->variables->isEmpty($image)) {
                $image = null;
            }

            $isDeleteRecord =
                $storeId > Store::DEFAULT_STORE_ID && $headline === null && $description === null && $image === null;

            $dbAdapter = $object->getResource()->getConnection();

            $tableName = $dbAdapter->getTableName('catalog_product_option_type_info');

            $query = $this->databaseHelper->select(
                $tableName,
                ['id', 'headline', 'description', 'image']
            );

            $query->where(
                'option_type_id = ?',
                $optionTypeId
            );

            $query->where(
                'store_id  = ?',
                $storeId
            );

            $queryResult = $this->databaseHelper->fetchRow(
                $query,
                $dbAdapter
            );

            if ($queryResult === null) {
                if (! $isDeleteRecord) {
                    $this->databaseHelper->createTableData(
                        $dbAdapter,
                        $tableName,
                        [
                            'option_type_id' => $optionTypeId,
                            'store_id'       => $storeId,
                            'headline'       => $headline,
                            'description'    => $description,
                            'image'          => $image
                        ]
                    );
                }
            } else {
                $currentHeadline = $this->arrays->getValue(
                    $queryResult,
                    'headline'
                );

                $currentDescription = $this->arrays->getValue(
                    $queryResult,
                    'description'
                );

                $currentImage = $this->arrays->getValue(
                    $queryResult,
                    'image'
                );

                $id = $this->arrays->getValue(
                    $queryResult,
                    'id'
                );

                if ($isDeleteRecord) {
                    $this->databaseHelper->deleteTableData(
                        $dbAdapter,
                        $tableName,
                        sprintf(
                            'id = %d',
                            $id
                        )
                    );
                } elseif ($currentHeadline != $headline || $currentDescription != $description ||
                    $currentImage != $image) {

                    $this->databaseHelper->updateTableData(
                        $dbAdapter,
                        $tableName,
                        [
                            'headline'    => $headline,
                            'description' => $description,
                            'image'       => $image
                        ],
                        sprintf(
                            'id = %d',
                            $id
                        )
                    );
                }
            }
        }
    }
}
