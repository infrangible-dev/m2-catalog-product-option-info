<?php

declare(strict_types=1);

namespace Infrangible\CatalogProductOptionInfo\Plugin\Catalog\Block\Product\View;

use Magento\Catalog\Model\Product\Option;

/**
 * @author      Andreas Knollmann
 * @copyright   2014-2025 Softwareentwicklung Andreas Knollmann
 * @license     http://www.opensource.org/licenses/mit-license.php MIT
 */
class Options
{
    public function afterGetOptionHtml(
        \Magento\Catalog\Block\Product\View\Options $subject,
        string $result,
        Option $option
    ): string {
        /** @var \Infrangible\CatalogProductOptionInfo\Block\Product\View\Options\Option $block */
        $block = $subject->getChildBlock('product_info_options_option');

        if ($block) {
            $block->setData(
                'option',
                $option
            );

            $block->setData(
                'option_html',
                $result
            );

            return $block->toHtml();
        } else {
            return $result;
        }
    }
}
