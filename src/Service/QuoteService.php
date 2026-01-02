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

use Db;
use DbQuery;
use PrestaShopException;

class QuoteService
{
    const TABLE_NAME = 'psrq_quotes';
    const STATUS_PENDING = 'pending';
    const STATUS_QUOTED = 'quoted';
    const STATUS_REJECTED = 'rejected';
    const STATUS_EXPIRED = 'expired';

    private $db;
    private $prefix;

    public function __construct()
    {
        $this->db = Db::getInstance();
        $this->prefix = _DB_PREFIX_;
    }

    /**
     * Create a new quote request
     *
     * @param int $customerId Customer ID
     * @param int $productId Product ID
     * @param int $quantity Quantity
     * @param string $message Customer message
     * @return int|false Quote ID or false on error
     */
    public function createQuote($customerId, $productId, $quantity = 1, $message = '')
    {
        $sql = 'INSERT INTO `' . $this->prefix . self::TABLE_NAME . '`
                (id_customer, id_product, quantity, status, message, date_add, date_upd)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())';

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $customerId,
            $productId,
            (int)$quantity,
            self::STATUS_PENDING,
            $message,
        ]);

        if ($result) {
            return $this->db->Insert_ID();
        }

        return false;
    }

    /**
     * Get quote by ID
     *
     * @param int $quoteId Quote ID
     * @return array|null Quote data or null if not found
     */
    public function getQuote($quoteId)
    {
        $sql = new DbQuery();
        $sql->select('*')
            ->from(self::TABLE_NAME)
            ->where('id_quote = ' . (int)$quoteId);

        return $this->db->getRow($sql);
    }

    /**
     * Get quotes by customer
     *
     * @param int $customerId Customer ID
     * @param string $status Filter by status (optional)
     * @return array Array of quotes
     */
    public function getCustomerQuotes($customerId, $status = null)
    {
        $sql = new DbQuery();
        $sql->select('q.*, p.name as product_name, c.firstname, c.lastname')
            ->from(self::TABLE_NAME, 'q')
            ->leftJoin('product', 'p', 'p.id_product = q.id_product')
            ->leftJoin('customer', 'c', 'c.id_customer = q.id_customer')
            ->where('q.id_customer = ' . (int)$customerId)
            ->orderBy('q.date_add DESC');

        if ($status) {
            $sql->where('q.status = \'' . pSQL($status) . '\'');
        }

        return $this->db->executeS($sql);
    }

    /**
     * Get all pending quotes (for admin dashboard)
     *
     * @param int $limit Limit results
     * @return array Array of quotes
     */
    public function getPendingQuotes($limit = 10)
    {
        $sql = new DbQuery();
        $sql->select('q.*, p.name as product_name, c.firstname, c.lastname, c.email')
            ->from(self::TABLE_NAME, 'q')
            ->leftJoin('product', 'p', 'p.id_product = q.id_product')
            ->leftJoin('customer', 'c', 'c.id_customer = q.id_customer')
            ->where('q.status = \'' . self::STATUS_PENDING . '\'')
            ->orderBy('q.date_add DESC')
            ->limit($limit);

        return $this->db->executeS($sql);
    }

    /**
     * Update quote status
     *
     * @param int $quoteId Quote ID
     * @param string $status New status
     * @return bool Success
     */
    public function updateQuoteStatus($quoteId, $status)
    {
        $allowed_statuses = [
            self::STATUS_PENDING,
            self::STATUS_QUOTED,
            self::STATUS_REJECTED,
            self::STATUS_EXPIRED,
        ];

        if (!in_array($status, $allowed_statuses)) {
            return false;
        }

        $sql = 'UPDATE `' . $this->prefix . self::TABLE_NAME . '`
                SET status = ?, date_upd = NOW()
                WHERE id_quote = ?';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, (int)$quoteId]);
    }

    /**
     * Delete quote
     *
     * @param int $quoteId Quote ID
     * @return bool Success
     */
    public function deleteQuote($quoteId)
    {
        $sql = 'DELETE FROM `' . $this->prefix . self::TABLE_NAME . '`
                WHERE id_quote = ?';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([(int)$quoteId]);
    }

    /**
     * Get statistics for admin dashboard
     *
     * @return array Statistics data
     */
    public function getStatistics()
    {
        $sql = new DbQuery();
        $sql->select('status, COUNT(*) as count')
            ->from(self::TABLE_NAME)
            ->groupBy('status');

        $results = $this->db->executeS($sql);
        $stats = [
            'total' => 0,
            'pending' => 0,
            'quoted' => 0,
            'rejected' => 0,
            'expired' => 0,
        ];

        foreach ($results as $row) {
            $stats[$row['status']] = (int)$row['count'];
            $stats['total'] += (int)$row['count'];
        }

        return $stats;
    }
}
