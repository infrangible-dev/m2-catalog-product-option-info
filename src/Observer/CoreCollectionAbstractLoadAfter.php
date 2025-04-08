<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionInfo\Observer;

use FeWeDev\Base\Json;
use FeWeDev\Base\Variables;
use Magento\Catalog\Model\Product\Option;
use Magento\Catalog\Model\ResourceModel\Product\Option\Collection;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class CoreCollectionAbstractLoadAfter implements ObserverInterface
{
    /** @var Variables */
    protected $variables;

    /** @var Json */
    protected $json;

    public function __construct(Variables $variables, Json $json)
    {
        $this->variables = $variables;
        $this->json = $json;
    }

    public function execute(Observer $observer): void
    {
        $collection = $observer->getData('collection');

        if ($collection instanceof Collection) {
            /** @var Option $option */
            foreach ($collection as $option) {
                $image = $option->getData('image');

                if (! $this->variables->isEmpty($image) && ! is_array($image)) {
                    $option->setData(
                        'image',
                        [$this->json->decode($image)]
                    );
                }

                $values = $option->getValues();

                if ($values) {
                    /** @var Option\Value $value */
                    foreach ($values as $value) {
                        $image = $value->getData('image');

                        if (! $this->variables->isEmpty($image)) {
                            $value->setData(
                                'image',
                                [$this->json->decode($image)]
                            );
                        }
                    }
                }
            }
        }

        if ($collection instanceof \Magento\Catalog\Model\ResourceModel\Product\Option\Value\Collection) {
            /** @var Option\Value $value */
            foreach ($collection as $value) {
                $image = $value->getData('image');

                if (! $this->variables->isEmpty($image)) {
                    $value->setData(
                        'image',
                        [$this->json->decode($image)]
                    );
                }
            }
        }
    }
}
