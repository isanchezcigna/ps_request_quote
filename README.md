# Request a Quote Module for PrestaShop 9

**Professional B2B Quote Request Solution by Syntax It**

![PrestaShop 9](https://img.shields.io/badge/PrestaShop-9.0+-green)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue)
![License](https://img.shields.io/badge/License-Commercial-red)

## Overview

**ps_request_quote** is a professional PrestaShop 9 module that enables B2B e-commerce functionality by allowing customers to request price quotes instead of making direct purchases. This is the first module in the **Syntax It Quote Management Suite**.

### Key Features

✅ **Replace "Add to Cart" with "Request Quote" Button**
- Disable direct purchasing for selected products
- Fully customizable quote request button text
- Support for multiple languages out of the box (English, Spanish)

✅ **Quote Management System**
- Database storage of all quote requests with customer details
- Quote status tracking (Pending, Quoted, Rejected, Expired)
- Customer dashboard integration ready
- Admin notification system

✅ **Smart Configuration**
- Enable/disable quote button globally
- Replace or hide the buy button
- Optional price hiding for quote products
- Customizable button text

✅ **Professional Email Notifications**
- Automatic confirmation emails to customers (ES/EN)
- Admin notifications for new quote requests
- Multi-language support

✅ **Modern Architecture**
- PSR-4 autoloading with Composer
- Service-based architecture for easy extension
- Built for growth as part of a suite
- Clean separation of concerns (Hooks, Services, Controllers)

## System Requirements

- **PrestaShop**: 9.0.0+
- **PHP**: 8.1.0+
- **MySQL**: 5.7+
- **Composer**: For dependency management

## Installation

### Method 1: Via File Upload (Recommended for Deployment)

1. Download the module as ZIP from the GitHub releases
2. Log in to your PrestaShop back office
3. Navigate to **Modules > Module Manager**
4. Click "Upload a Module"
5. Select the `ps_request_quote.zip` file
6. Click "Install"

### Method 2: Manual Installation

1. Clone or download this repository
2. Extract the folder and rename it to `ps_request_quote`
3. Upload to your PrestaShop server: `modules/ps_request_quote/`
4. Go to **Modules > Module Manager** and search for "Request a Quote"
5. Click "Install"

### Method 3: Via Composer (Development)

```bash
cd your-prestashop-root
composer require syntaxit/ps_request_quote
```

## Configuration

After installation:

1. Navigate to **Modules > Module Manager > Request a Quote** (or search for it)
2. Click "Configure" to access the settings panel
3. Configure the following options:

### Configuration Options

| Option | Description | Default |
|--------|-------------|---------|
| **Enable Quote Button** | Activate the quote request functionality | ✓ Enabled |
| **Hide Buy Button** | Replace the "Add to Cart" button with "Request Quote" | ✓ Enabled |
| **Quote Button Text** | Customizable text for the quote button | "Request a Quote" |
| **Hide Price** | Hide product prices on quote-enabled products | ✗ Disabled |

## Usage

### For Customers

1. **Browse Products**: Customer visits a product page with quote feature enabled
2. **Request Quote**: Instead of "Add to Cart", they see "Request Quote" button
3. **Fill Details**:
   - Select quantity
   - Add optional message with special requests
4. **Submit**: Click submit to send quote request
5. **Confirmation**: Receive email confirmation of their quote request

### For Administrators

1. **Receive Notifications**: Get email alerts for new quote requests
2. **Review Requests**: Check quote requests in the module configuration area
3. **Manage Status**: Update quote status to:
   - **Pending**: Initial state
   - **Quoted**: Price quote provided
   - **Rejected**: Quote rejected
   - **Expired**: Quote expired
4. **Contact Customer**: Use customer info to send personalized quotes

## Database Structure

The module creates a `ps_psrq_quotes` table with the following structure:

```sql
CREATE TABLE `ps_psrq_quotes` (
  `id_quote` INT(11) NOT NULL AUTO_INCREMENT,
  `id_customer` INT(11),
  `id_product` INT(11),
  `quantity` INT(11) NOT NULL DEFAULT 1,
  `status` VARCHAR(50) DEFAULT 'pending',
  `message` LONGTEXT,
  `date_add` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `date_upd` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_quote`),
  FOREIGN KEY (`id_customer`) REFERENCES `ps_customer`(`id_customer`) ON DELETE SET NULL,
  FOREIGN KEY (`id_product`) REFERENCES `ps_product`(`id_product`) ON DELETE CASCADE
)
```

## File Structure

```
ps_request_quote/
├── ps_request_quote.php              # Main module class
├── composer.json                     # Composer configuration
├── config.xml                        # Module metadata
├── config/
│   └── services.yml                  # Symfony service definitions
├── src/
│   ├── Hook/
│   │   └── QuoteHook.php            # Hook handlers
│   ├── Service/
│   │   ├── QuoteService.php         # Database operations
│   │   ├── EmailService.php         # Email handling
│   │   └── QuoteController.php      # Request handling
├── views/
│   └── templates/
│       └── hook/
│           └── quote_button.tpl     # Frontend button template
├── mails/
│   ├── en/
│   │   ├── quote_request_confirmation.txt
│   │   └── quote_request_admin.txt
│   └── es/
│       ├── quote_request_confirmation.txt
│       └── quote_request_admin.txt
├── translations/
│   ├── en/
│   │   └── messages.xlf
│   └── es/
│       └── messages.xlf
├── .gitignore
├── README.md
└── LICENSE
```

## API Reference

### Services

#### QuoteService

```php
// Create new quote request
quoteService->createQuote(
    int $customerId,
    int $productId,
    int $quantity = 1,
    string $message = ''
): int|false

// Get customer quotes
quoteService->getCustomerQuotes(
    int $customerId,
    string $status = null
): array

// Get pending quotes (admin)
quoteService->getPendingQuotes(int $limit = 10): array

// Update quote status
quoteService->updateQuoteStatus(
    int $quoteId,
    string $status
): bool
```

#### EmailService

```php
// Send confirmation to customer
emailService->sendCustomerQuoteNotification(
    int $customerId,
    int $productId,
    int $quantity,
    string $message = ''
): bool

// Notify admin of new request
emailService->sendAdminQuoteNotification(
    int $customerId,
    int $productId,
    int $quantity,
    string $message = ''
): bool
```

## Hooks Available

- `displayProductButtons`: Injects quote button on product pages
- `displayProductPriceBlock`: Can hide prices for quote products
- `actionAdminProductsControllerSaveBefore`: Ready for future product-level settings

## Roadmap (Future Versions)

### v1.1.0
- [ ] Admin dashboard with quote statistics
- [ ] Product-level quote configuration
- [ ] Custom quote email templates in back office

### v1.2.0
- [ ] Quote import/export functionality
- [ ] API endpoints for quote management
- [ ] Advanced filtering and search

### v1.5.0 - Quote Suite
- [ ] **ps_quote_converter**: Convert quotes to orders
- [ ] **ps_quote_templates**: Pre-built quote templates
- [ ] **ps_quote_automation**: Automatic quote expiration, reminders
- [ ] **ps_quote_analytics**: Quote-to-order conversion analytics

## Translation/Localization

The module supports multiple languages out of the box:
- **Spanish (ES)** ✓ Fully translated
- **English (EN)** ✓ Fully translated

### Adding New Languages

1. Create translation files in `translations/{language_code}/messages.xlf`
2. Add email templates in `mails/{language_code}/`
3. Test and validate in PrestaShop back office

## Support & Documentation

### Official Support
- **Website**: [https://syntaxit.cl](https://syntaxit.cl)
- **Email**: support@syntaxit.cl
- **Documentation**: [docs.syntaxit.cl](https://docs.syntaxit.cl)

### Community
- **GitHub Issues**: Report bugs and feature requests
- **Discussions**: Community support and tips

## Development

### Prerequisites

```bash
git clone https://github.com/yourusername/ps_request_quote.git
cd ps_request_quote
composer install
```

### Running Tests

```bash
composer test
```

### Code Standards

This project follows PSR-2 and PrestaShop coding standards.

```bash
composer phpcs  # Check code style
composer phpcbf # Fix code style issues
```

## License

**Commercial License Agreement** - All rights reserved by Syntax It

This module is provided under a commercial license. Unauthorized copying, distribution, or modification is strictly prohibited.

For licensing inquiries, please contact: **support@syntaxit.cl**

## Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Changelog

### v1.0.0 (2025-01-02)
- ✨ Initial release
- 🎯 Core quote request functionality
- 📧 Multi-language email notifications
- 🎨 Professional UI for quote requests
- 💾 Database management for quote tracking
- 🔧 Comprehensive configuration options

## Credits

Developed with ❤️ by **Syntax It**

---

**Ready to transform your B2B sales? Install ps_request_quote today!**
