<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionInfo\Block\Product\View\Options;

use Infrangible\Core\Helper\Registry;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Option;
use Magento\Framework\View\Element\Template;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Info extends Template
{
    /** @var Registry */
    protected $registryHelper;

    /** @var Product */
    private $product;

    public function __construct(Template\Context $context, Registry $registryHelper, array $data = [])
    {
        parent::__construct(
            $context,
            $data
        );

        $this->registryHelper = $registryHelper;
    }

    public function getProduct(): Product
    {
        if (! $this->product) {
            if ($this->registryHelper->registry('current_product')) {
                $this->product = $this->registryHelper->registry('current_product');
            } else {
                throw new \LogicException('Product is not defined');
            }
        }

        return $this->product;
    }

    public function getOptions(): ?array
    {
        return $this->getProduct()->getOptions();
    }

    public function getOptionInfoHtml(Option $option): string
    {
        $type = $option->getGroupByType($option->getType());

        $renderer = $this->getChildBlock($type);

        if (! $renderer) {
            $renderer = $this->getChildBlock('default');
        }

        $renderer->setData(
            'option',
            $option
        );

        return $renderer->toHtml();
    }

    public function getOptionsConfig(): string
    {
        //return $this->getProduct()->getOptions();

        return '{}';
    }
}
