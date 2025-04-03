<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionInfo\Block\Product\View\Options\Info;

use FeWeDev\Base\Variables;
use Magento\Catalog\Model\Product\Option;
use Magento\Catalog\Model\Product\Option\Value;
use Magento\Framework\View\Element\Template;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class DefaultType extends Template
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

    public function getOption(): Option
    {
        return $this->getData('option');
    }

    public function hasInfo(): bool
    {
        $option = $this->getOption();

        $headline = $option->getData('headline');
        $description = $option->getData('description');
        $image = $option->getData('image');

        return ! $this->variables->isEmpty($headline) || ! $this->variables->isEmpty($description) ||
            ! $this->variables->isEmpty($image);
    }

    public function getOptionValueInfoHtml(Value $optionValue): string
    {
        $renderer = $this->getChildBlock('value');

        $renderer->setData(
            'option_value',
            $optionValue
        );

        return $renderer->toHtml();
    }
}
