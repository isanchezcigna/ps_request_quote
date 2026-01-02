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

if (!defined('_PS_VERSION_')) {
    exit;
}

class ps_request_quote extends Module
{
    public function __construct()
    {
        $this->name = 'ps_request_quote';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Syntax It';
        $this->need_instance = 1;
        $this->bootstrap = true;
        $this->ps_versions_compliancy = [
            'min' => '9.0.0',
            'max' => _PS_VERSION_,
        ];
        $this->dependencies = [];

        parent::__construct();

        $this->displayName = $this->l('Request a Quote');
        $this->description = $this->l('Allow customers to request quotes instead of direct purchases. Professional B2B solution.');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }

        // Register hooks
        if (!$this->registerHook('displayProductPriceBlock')
            || !$this->registerHook('displayProductButtons')
            || !$this->registerHook('displayAdminProductsExtra')
            || !$this->registerHook('actionAdminProductsControllerSaveBefore')) {
            return false;
        }

        // Create database tables if needed
        if (!$this->createDatabase()) {
            return false;
        }

        // Set default configuration
        $this->setConfiguration();

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }

        // Drop tables
        $this->dropDatabase();

        // Delete configuration
        $this->deleteConfiguration();

        return true;
    }

    public function getContent()
    {
        $output = '';

        // Process form submission
        if (Tools::isSubmit('submit' . $this->name)) {
            $output .= $this->processConfiguration();
        }

        // Display configuration form
        $output .= $this->displayConfigurationForm();

        return $output;
    }

    private function processConfiguration()
    {
        $output = '';
        $errors = [];

        // Validate and process settings
        $enable_quote_button = Tools::getValue('PSRQ_ENABLE_QUOTE_BUTTON');
        $hide_buy_button = Tools::getValue('PSRQ_HIDE_BUY_BUTTON');
        $quote_button_text = Tools::getValue('PSRQ_QUOTE_BUTTON_TEXT');
        $hide_price = Tools::getValue('PSRQ_HIDE_PRICE');

        if (empty($quote_button_text)) {
            $errors[] = $this->l('Quote button text is required');
        }

        if (count($errors)) {
            foreach ($errors as $error) {
                $output .= $this->displayError($error);
            }
        } else {
            Configuration::updateValue('PSRQ_ENABLE_QUOTE_BUTTON', (bool)$enable_quote_button);
            Configuration::updateValue('PSRQ_HIDE_BUY_BUTTON', (bool)$hide_buy_button);
            Configuration::updateValue('PSRQ_QUOTE_BUTTON_TEXT', $quote_button_text);
            Configuration::updateValue('PSRQ_HIDE_PRICE', (bool)$hide_price);
            $output .= $this->displayConfirmation($this->l('Settings updated successfully'));
        }

        return $output;
    }

    private function displayConfigurationForm()
    {
        $html = '<div class="panel">';
        $html .= '<h3>' . $this->l('Request a Quote Configuration') . '</h3>';
        $html .= '<form method="post" action="' . htmlspecialchars($_SERVER['REQUEST_URI']) . '">';
        $html .= '<fieldset>';
        $html .= '<legend><img src="' . $this->_path . 'logo.png" alt="" class="imgm" />' . $this->l('Settings') . '</legend>';

        $html .= '<div class="form-group">';
        $html .= '<label for="PSRQ_ENABLE_QUOTE_BUTTON">' . $this->l('Enable Quote Button') . '</label>';
        $html .= '<input type="checkbox" id="PSRQ_ENABLE_QUOTE_BUTTON" name="PSRQ_ENABLE_QUOTE_BUTTON" value="1" ' . (Configuration::get('PSRQ_ENABLE_QUOTE_BUTTON') ? 'checked' : '') . '/>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label for="PSRQ_HIDE_BUY_BUTTON">' . $this->l('Hide Buy Button (Replace with Quote)') . '</label>';
        $html .= '<input type="checkbox" id="PSRQ_HIDE_BUY_BUTTON" name="PSRQ_HIDE_BUY_BUTTON" value="1" ' . (Configuration::get('PSRQ_HIDE_BUY_BUTTON') ? 'checked' : '') . '/>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label for="PSRQ_QUOTE_BUTTON_TEXT">' . $this->l('Quote Button Text') . '</label>';
        $html .= '<input type="text" id="PSRQ_QUOTE_BUTTON_TEXT" name="PSRQ_QUOTE_BUTTON_TEXT" value="' . htmlspecialchars(Configuration::get('PSRQ_QUOTE_BUTTON_TEXT')) . '" />';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<label for="PSRQ_HIDE_PRICE">' . $this->l('Hide Price on Quote Products') . '</label>';
        $html .= '<input type="checkbox" id="PSRQ_HIDE_PRICE" name="PSRQ_HIDE_PRICE" value="1" ' . (Configuration::get('PSRQ_HIDE_PRICE') ? 'checked' : '') . '/>';
        $html .= '</div>';

        $html .= '<div class="form-group">';
        $html .= '<button type="submit" name="submit' . $this->name . '" class="btn btn-default">';
        $html .= '<i class="process-icon-save"></i>' . $this->l('Save') . '</button>';
        $html .= '</div>';

        $html .= '</fieldset>';
        $html .= '</form>';
        $html .= '</div>';

        return $html;
    }

    private function setConfiguration()
    {
        Configuration::updateValue('PSRQ_ENABLE_QUOTE_BUTTON', true);
        Configuration::updateValue('PSRQ_HIDE_BUY_BUTTON', true);
        Configuration::updateValue('PSRQ_QUOTE_BUTTON_TEXT', 'Request a Quote');
        Configuration::updateValue('PSRQ_HIDE_PRICE', false);
    }

    private function deleteConfiguration()
    {
        Configuration::deleteByName('PSRQ_ENABLE_QUOTE_BUTTON');
        Configuration::deleteByName('PSRQ_HIDE_BUY_BUTTON');
        Configuration::deleteByName('PSRQ_QUOTE_BUTTON_TEXT');
        Configuration::deleteByName('PSRQ_HIDE_PRICE');
    }

    private function createDatabase()
    {
        // Create quote requests table
        $sql = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'psrq_quotes` (
            `id_quote` INT(11) NOT NULL AUTO_INCREMENT,
            `id_customer` INT(11),
            `id_product` INT(11),
            `quantity` INT(11) NOT NULL DEFAULT 1,
            `status` VARCHAR(50) DEFAULT "pending",
            `message` LONGTEXT,
            `date_add` DATETIME DEFAULT CURRENT_TIMESTAMP,
            `date_upd` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (`id_quote`),
            FOREIGN KEY (`id_customer`) REFERENCES `' . _DB_PREFIX_ . 'customer`(`id_customer`) ON DELETE SET NULL,
            FOREIGN KEY (`id_product`) REFERENCES `' . _DB_PREFIX_ . 'product`(`id_product`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

        return Db::getInstance()->execute($sql);
    }

    private function dropDatabase()
    {
        $sql = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'psrq_quotes`';
        return Db::getInstance()->execute($sql);
    }

    public function hookDisplayProductButtons($params)
    {
        if (!Configuration::get('PSRQ_ENABLE_QUOTE_BUTTON')) {
            return '';
        }

        $product = $params['product'] ?? null;
        if (!$product) {
            return '';
        }

        $this->context->smarty->assign([
            'hide_buy_button' => Configuration::get('PSRQ_HIDE_BUY_BUTTON'),
            'quote_button_text' => Configuration::get('PSRQ_QUOTE_BUTTON_TEXT'),
            'product_id' => $product->id,
        ]);

        return $this->fetch('module:ps_request_quote/views/templates/hook/quote_button.tpl');
    }

    public function hookDisplayProductPriceBlock($params)
    {
        if (!Configuration::get('PSRQ_ENABLE_QUOTE_BUTTON') || !Configuration::get('PSRQ_HIDE_PRICE')) {
            return '';
        }

        return '';
    }
}
