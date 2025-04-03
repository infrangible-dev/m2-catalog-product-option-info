<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionInfo\Block\Product\View\Options\Info\DefaultType;

use FeWeDev\Base\Variables;
use Magento\Framework\View\Element\Template;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Value extends Template
{
    /** @var Variables */
    protected $variables;

    public function __construct(Template\Context $context, Variables $variables, array $data = [])
    {
        parent::__construct(
            $context,
            $data
        );

        $this->variables = $variables;
    }

    public function getOptionValue(): \Magento\Catalog\Model\Product\Option\Value
    {
        return $this->getData('option_value');
    }

    public function hasInfo(): bool
    {
        $optionValue = $this->getOptionValue();

        $headline = $optionValue->getData('headline');
        $description = $optionValue->getData('description');
        $image = $optionValue->getData('image');

        return ! $this->variables->isEmpty($headline) || ! $this->variables->isEmpty($description) ||
            ! $this->variables->isEmpty($image);
    }
}
