<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionInfo\Block\Product\View\Options;

use Magento\Framework\View\Element\Template;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Option extends Template
{
    public function getOption(): \Magento\Catalog\Model\Product\Option
    {
        return $this->getData('option');
    }

    public function getOptionHtml(): string
    {
        return $this->getData('option_html');
    }
}
