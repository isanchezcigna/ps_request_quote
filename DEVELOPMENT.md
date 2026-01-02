# Development Guide - ps_request_quote

## Local Setup

### Prerequisites

- PHP 8.1+
- Composer
- Git
- Local PrestaShop 9 installation

### Installation for Development

```bash
# Clone the repository
git clone https://github.com/syntaxit/ps_request_quote.git
cd ps_request_quote

# Install dependencies
composer install

# Copy to your PrestaShop modules directory (for testing)
cp -r . /path/to/prestashop/modules/ps_request_quote
```

## Project Structure Deep Dive

### Core Files

#### `ps_request_quote.php` - Main Module Class

The entry point for the module. Handles:
- Module installation/uninstallation
- Configuration form display
- Hook registration
- Database table creation

**Key Methods:**
- `__construct()` - Initialize module metadata
- `install()` - Setup database and hooks
- `uninstall()` - Cleanup
- `getContent()` - Admin configuration page
- `hookDisplayProductButtons()` - Frontend button injection

### Service Architecture

#### `src/Service/QuoteService.php`

Handles all database operations:

```php
// Create a quote
$quoteId = $quoteService->createQuote($customerId, $productId, $quantity, $message);

// Retrieve quotes
$quotes = $quoteService->getCustomerQuotes($customerId);
$pending = $quoteService->getPendingQuotes($limit = 10);

// Update status
$quoteService->updateQuoteStatus($quoteId, 'quoted');

// Get statistics
$stats = $quoteService->getStatistics();
```

**Status Constants:**
- `STATUS_PENDING = 'pending'` - New quote request
- `STATUS_QUOTED = 'quoted'` - Admin provided quote
- `STATUS_REJECTED = 'rejected'` - Quote rejected
- `STATUS_EXPIRED = 'expired'` - Quote expired

#### `src/Service/EmailService.php`

Handles email notifications:

```php
// Send to customer
$emailService->sendCustomerQuoteNotification($customerId, $productId, $quantity, $message);

// Send to admin
$emailService->sendAdminQuoteNotification($customerId, $productId, $quantity, $message);
```

#### `src/Hook/QuoteHook.php`

Centralizes hook logic:

```php
// Check if should show quote button
$quoteHook->shouldShowQuoteButton($productId);

// Check if should hide price
$quoteHook->shouldHidePrice();
```

#### `src/Controller/QuoteController.php`

Frontend request handling:

```php
// Handle AJAX quote creation
$controller->createQuote(); // POST

// Retrieve customer quotes
$controller->getCustomerQuotes(); // GET

// Get single quote
$controller->getQuoteDetail(); // GET
```

### Templates

#### `views/templates/hook/quote_button.tpl`

Smarty template for the quote button:

```smarty
{if $hide_buy_button}
    <!-- Hide original add to cart button -->
{/if}

<!-- Quote request form -->
<form id="ps-quote-request-form" method="post">
    <input name="id_product" value="{$product_id}" />
    <input name="quantity" value="1" />
    <textarea name="message"></textarea>
    <button type="submit">{$quote_button_text}</button>
</form>
```

### Configuration

#### `config/services.yml`

Declares Symfony services:

```yaml
services:
  ps_request_quote.hooks:
    class: PrestaShop\Module\PsRequestQuote\Hook\QuoteHook
```

#### `config.xml`

Module metadata for PrestaShop core.

## Adding Features

### Example: Add Product-Level Configuration

1. **Create Migration**

```php
// Add column to products table
ALTER TABLE ps_product ADD COLUMN enable_quote BOOLEAN DEFAULT 1;
```

2. **Update QuoteService**

```php
public function shouldEnableQuote($productId)
{
    $sql = 'SELECT enable_quote FROM ps_product WHERE id_product = ?';
    return $this->db->executeS($sql, [$productId])[0]['enable_quote'];
}
```

3. **Update QuoteHook**

```php
public function shouldShowQuoteButton($productId)
{
    if (!Configuration::get('PSRQ_ENABLE_QUOTE_BUTTON')) {
        return false;
    }
    return $this->quoteService->shouldEnableQuote($productId);
}
```

### Example: Add Admin Quote Management

1. **Create Admin Controller**

```php
// src/Controller/Admin/QuoteAdminController.php
namespace PrestaShop\Module\PsRequestQuote\Controller\Admin;

class QuoteAdminController
{
    public function listQuotes()
    {
        $quotes = $this->quoteService->getPendingQuotes(50);
        return $quotes;
    }
    
    public function updateQuoteStatus($quoteId, $status)
    {
        return $this->quoteService->updateQuoteStatus($quoteId, $status);
    }
}
```

2. **Register Admin Route**

Update `ps_request_quote.php`:

```php
public function registerControllers()
{
    $admin_controllers = [
        'quote' => 'QuoteAdminController',
    ];
    // Register with PrestaShop routing
}
```

## Testing

### Manual Testing Checklist

- [ ] Module installs without errors
- [ ] Configuration form displays correctly
- [ ] Quote button appears on product pages
- [ ] Buy button hides when enabled
- [ ] Quote form submits successfully
- [ ] Customer receives confirmation email
- [ ] Admin receives notification email
- [ ] Quote saves to database
- [ ] Quote status can be updated
- [ ] Module uninstalls cleanly

### Database Testing

```sql
-- Check quotes created
SELECT * FROM ps_psrq_quotes;

-- Check by customer
SELECT * FROM ps_psrq_quotes WHERE id_customer = 1;

-- Check by status
SELECT status, COUNT(*) FROM ps_psrq_quotes GROUP BY status;
```

## Debugging

### Enable Debug Mode

In your PrestaShop `config/defines.inc.php`:

```php
define('_PS_DEBUG_MODE_', true);
```

### View Logs

```bash
# PrestaShop logs
tail -f var/logs/*.log

# PHP error log
tail -f /var/log/php/error.log
```

### Database Inspection

```sql
-- Check table structure
DESC ps_psrq_quotes;

-- Check configuration values
SELECT * FROM ps_configuration WHERE name LIKE 'PSRQ%';
```

## Code Style

This project follows:
- **PSR-2**: General coding standard
- **PSR-4**: Autoloading standard
- **PrestaShop**: Module development best practices

### Pre-commit Checks

Before committing:

```bash
# PHP syntax check
find src -name '*.php' -exec php -l {} \;

# Composer validation
composer validate
```

## Performance Considerations

### Database Queries

- Use indexes on `id_customer` and `id_product` for faster queries
- Batch insert for multiple quote operations
- Cache customer quote counts in Configuration

### Frontend

- Minimize JavaScript in template
- Use AJAX for form submission (already implemented)
- Cache quote button HTML in page render

## Security Best Practices

### Input Validation

```php
// Always validate and sanitize
$productId = (int)Tools::getValue('id_product');
$message = Tools::purifyHTML(Tools::getValue('message'));
$quantity = max(1, (int)Tools::getValue('quantity'));
```

### Database Security

```php
// Use prepared statements
$stmt = $this->db->prepare('SELECT * FROM table WHERE id = ?');
$stmt->execute([$id]);
```

### Access Control

```php
// Verify customer ownership
if ((int)$quote['id_customer'] !== (int)$this->context->customer->id) {
    return $this->jsonResponse(false, 'Access denied');
}
```

## Release Process

### Creating a Release

1. Update version in `ps_request_quote.php` and `config.xml`
2. Update `CHANGELOG.md`
3. Tag release: `git tag v1.1.0`
4. Push to GitHub: `git push origin v1.1.0`
5. Create GitHub Release with release notes
6. Package ZIP for distribution

## Contributing

### Pull Request Process

1. Fork the repository
2. Create feature branch: `git checkout -b feature/my-feature`
3. Commit changes: `git commit -m 'Add my feature'`
4. Push branch: `git push origin feature/my-feature`
5. Create Pull Request with description
6. Await review and approval

### Commit Message Guidelines

- Use present tense ("Add feature" not "Added feature")
- Use imperative mood ("Move cursor to..." not "Moves cursor to...")
- Limit to 50 characters for first line
- Reference issues: "Fixes #123"

## Resources

- [PrestaShop 9 Development Documentation](https://devdocs.prestashop-project.org/9/)
- [PrestaShop Module Development Guide](https://devdocs.prestashop-project.org/9/modules/)
- [Symfony Documentation](https://symfony.com/doc/)
- [Composer Documentation](https://getcomposer.org/)

## Support

For development questions:
- Open an issue on GitHub
- Contact: dev@syntaxit.cl
- Documentation: docs.syntaxit.cl

---

Happy coding! 🚀
