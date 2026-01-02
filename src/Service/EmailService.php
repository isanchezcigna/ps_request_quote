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

namespace PrestaShop\Module\PsRequestQuote\Service;

use Configuration;
use Context;
use Customer;
use Mail;
use Product;
use Shop;
use Symfony\Contracts\Translation\TranslatorInterface;

class EmailService
{
    private $translator;
    private $context;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
        $this->context = Context::getContext();
    }

    /**
     * Send quote request confirmation to customer
     *
     * @param int $customerId Customer ID
     * @param int $productId Product ID
     * @param int $quantity Quantity
     * @param string $message Customer message
     * @return bool Success
     */
    public function sendCustomerQuoteNotification($customerId, $productId, $quantity, $message = '')
    {
        try {
            $customer = new Customer($customerId);
            $product = new Product($productId);

            if (!$customer->id || !$product->id) {
                return false;
            }

            $templateVars = [
                '{customer_name}' => $customer->firstname . ' ' . $customer->lastname,
                '{product_name}' => $product->name,
                '{quantity}' => $quantity,
                '{message}' => nl2br($message),
                '{shop_name}' => Configuration::get('PS_SHOP_NAME'),
                '{shop_url}' => Shop::getShopDomain(true),
            ];

            return Mail::send(
                $this->context->language->id,
                'quote_request_confirmation',
                $this->translator->trans('Quote Request Confirmation', [], 'Modules.Psrequestquote'),
                $templateVars,
                $customer->email,
                $customer->firstname . ' ' . $customer->lastname,
                null,
                null,
                null,
                null,
                _PS_MODULE_DIR_ . 'ps_request_quote/mails/'
            );
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Send quote request notification to admin
     *
     * @param int $customerId Customer ID
     * @param int $productId Product ID
     * @param int $quantity Quantity
     * @param string $message Customer message
     * @return bool Success
     */
    public function sendAdminQuoteNotification($customerId, $productId, $quantity, $message = '')
    {
        try {
            $customer = new Customer($customerId);
            $product = new Product($productId);

            if (!$customer->id || !$product->id) {
                return false;
            }

            $adminEmail = Configuration::get('PS_SHOP_EMAIL');

            $templateVars = [
                '{customer_name}' => $customer->firstname . ' ' . $customer->lastname,
                '{customer_email}' => $customer->email,
                '{customer_phone}' => $customer->phone,
                '{product_name}' => $product->name,
                '{quantity}' => $quantity,
                '{message}' => nl2br($message),
                '{shop_name}' => Configuration::get('PS_SHOP_NAME'),
                '{admin_url}' => $this->getAdminQuoteLink(),
            ];

            return Mail::send(
                $this->context->language->id,
                'quote_request_admin',
                $this->translator->trans('New Quote Request', [], 'Modules.Psrequestquote'),
                $templateVars,
                $adminEmail,
                Configuration::get('PS_SHOP_NAME'),
                null,
                null,
                null,
                null,
                _PS_MODULE_DIR_ . 'ps_request_quote/mails/'
            );
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get admin quote management link
     *
     * @return string Admin URL
     */
    private function getAdminQuoteLink()
    {
        $context = Context::getContext();
        return $context->shop->getBaseURL(true) . 'admin-dev/index.php?controller=AdminModules&configure=ps_request_quote';
    }
}
