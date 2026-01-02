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

namespace PrestaShop\Module\PsRequestQuote\Controller;

use Context;
use Configuration;
use Product;
use Tools;
use PrestaShop\Module\PsRequestQuote\Service\QuoteService;
use PrestaShop\Module\PsRequestQuote\Service\EmailService;

class QuoteController
{
    private $context;
    private $quoteService;
    private $emailService;

    public function __construct()
    {
        $this->context = Context::getContext();
        $this->quoteService = new QuoteService();
        $this->emailService = new EmailService();
    }

    /**
     * Create a new quote request
     *
     * @return array JSON response
     */
    public function createQuote()
    {
        // Check if user is logged in
        if (!$this->context->customer->isLogged()) {
            return $this->jsonResponse(false, 'You must be logged in to request a quote');
        }

        // Get and validate POST data
        $productId = (int)Tools::getValue('id_product');
        $quantity = (int)Tools::getValue('quantity', 1);
        $message = Tools::getValue('message', '');

        // Validate product
        $product = new Product($productId);
        if (!$product->id) {
            return $this->jsonResponse(false, 'Invalid product');
        }

        // Validate quantity
        if ($quantity < 1 || $quantity > 999999) {
            return $this->jsonResponse(false, 'Invalid quantity');
        }

        // Sanitize message
        $message = Tools::purifyHTML($message);
        if (strlen($message) > 1000) {
            $message = substr($message, 0, 1000);
        }

        try {
            // Create quote in database
            $quoteId = $this->quoteService->createQuote(
                $this->context->customer->id,
                $productId,
                $quantity,
                $message
            );

            if (!$quoteId) {
                return $this->jsonResponse(false, 'Failed to create quote request');
            }

            // Send notifications
            $this->emailService->sendCustomerQuoteNotification(
                $this->context->customer->id,
                $productId,
                $quantity,
                $message
            );

            $this->emailService->sendAdminQuoteNotification(
                $this->context->customer->id,
                $productId,
                $quantity,
                $message
            );

            return $this->jsonResponse(true, 'Quote request sent successfully!');
        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Get customer quotes
     *
     * @return array JSON response
     */
    public function getCustomerQuotes()
    {
        // Check if user is logged in
        if (!$this->context->customer->isLogged()) {
            return $this->jsonResponse(false, 'You must be logged in');
        }

        try {
            $quotes = $this->quoteService->getCustomerQuotes($this->context->customer->id);
            return $this->jsonResponse(true, 'Quotes retrieved', ['quotes' => $quotes]);
        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'Failed to retrieve quotes');
        }
    }

    /**
     * Get quote detail
     *
     * @return array JSON response
     */
    public function getQuoteDetail()
    {
        $quoteId = (int)Tools::getValue('id_quote');

        // Check if user is logged in
        if (!$this->context->customer->isLogged()) {
            return $this->jsonResponse(false, 'You must be logged in');
        }

        try {
            $quote = $this->quoteService->getQuote($quoteId);

            if (!$quote) {
                return $this->jsonResponse(false, 'Quote not found');
            }

            // Verify ownership
            if ((int)$quote['id_customer'] !== (int)$this->context->customer->id) {
                return $this->jsonResponse(false, 'Access denied');
            }

            return $this->jsonResponse(true, 'Quote details', ['quote' => $quote]);
        } catch (\Exception $e) {
            return $this->jsonResponse(false, 'Failed to retrieve quote');
        }
    }

    /**
     * Return JSON response
     *
     * @param bool $success
     * @param string $message
     * @param array $data
     * @return array
     */
    private function jsonResponse($success = true, $message = '', $data = [])
    {
        return array_merge([
            'success' => $success,
            'message' => $message,
        ], $data);
    }
}
