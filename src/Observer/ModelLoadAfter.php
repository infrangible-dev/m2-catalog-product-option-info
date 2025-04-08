<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionInfo\Observer;

use FeWeDev\Base\Json;
use FeWeDev\Base\Variables;
use Magento\Catalog\Model\Product\Option;
use Magento\Catalog\Model\Product\Option\Value;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class ModelLoadAfter implements ObserverInterface
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
        $object = $observer->getData('object');

        if ($object instanceof Option) {
            $image = $object->getData('image');

            if (! $this->variables->isEmpty($image) && ! is_array($image)) {
                $object->setData(
                    'image',
                    [$this->json->decode($image)]
                );
            }

            $values = $object->getValues();

            if ($values) {
                /** @var Value $value */
                foreach ($values as $value) {
                    $image = $value->getData('image');

                    if (! $this->variables->isEmpty($image) && ! is_array($image)) {
                        $value->setData(
                            'image',
                            [$this->json->decode($image)]
                        );
                    }
                }
            }
        }

        if ($object instanceof Value) {
            $image = $object->getData('image');

            if (! $this->variables->isEmpty($image) && ! is_array($image)) {
                $object->setData(
                    'image',
                    [$this->json->decode($image)]
                );
            }
        }
    }
}
