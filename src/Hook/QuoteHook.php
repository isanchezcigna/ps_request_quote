<?php
/**
 * NOTICE OF LICENSE
 *
 * This file is licenced under the Software License Agreement.
 * With the purchase or the installation of the software in your application
 * you accept the terms of this License Agreement.
 *
 * @author    Syntax It <support@syntaxit.cl>
 * @copyright 2025 Syntax It
 * @license   Commercial License Agreement
 */

namespace PrestaShop\Module\PsRequestQuote\Hook;

use Configuration;
use Context;
use Product;
use Symfony\Contracts\Translation\TranslatorInterface;
use Db;

class QuoteHook
{
    private $translator;
    private $context;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->context = Context::getContext();
    }

    /**
     * Hook on product buttons to display quote request button
     *
     * @param array $params Hook parameters
     * @return string Rendered template
     */
    public function displayProductButtons($params)
    {
        if (!Configuration::get('PSRQ_ENABLE_QUOTE_BUTTON')) {
            return '';
        }

        $product = $params['product'] ?? null;
        if (!$product || !($product instanceof Product)) {
            return '';
        }

        $quote_button_text = Configuration::get('PSRQ_QUOTE_BUTTON_TEXT');
        if (empty($quote_button_text)) {
            $quote_button_text = $this->translator->trans('Request a Quote', [], 'Modules.Psrequestquote');
        }

        return [
            'hide_buy_button' => (bool)Configuration::get('PSRQ_HIDE_BUY_BUTTON'),
            'quote_button_text' => $quote_button_text,
            'product_id' => $product->id,
            'product_name' => $product->name,
        ];
    }

    /**
     * Check if a product should show quote button instead of buy button
     *
     * @param int $productId Product ID
     * @return bool True if should show quote button
     */
    public function shouldShowQuoteButton($productId)
    {
        if (!Configuration::get('PSRQ_ENABLE_QUOTE_BUTTON')) {
            return false;
        }

        // Future: Check product-level settings or categories
        return true;
    }

    /**
     * Check if product price should be hidden
     *
     * @return bool True if price should be hidden
     */
    public function shouldHidePrice()
    {
        return (bool)Configuration::get('PSRQ_HIDE_PRICE');
    }
}
