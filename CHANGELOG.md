# Changelog

All notable changes to the ps_request_quote module will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2025-01-02

### Added
- Initial release of ps_request_quote module
- Core quote request functionality
- Replace "Add to Cart" with "Request Quote" button
- Customizable quote button text
- Optional price hiding for quote products
- Quote status tracking (Pending, Quoted, Rejected, Expired)
- Customer quote history tracking
- Database storage of all quote requests
- Multi-language support (English, Spanish)
- Email notifications for customers and administrators
- PSR-4 autoloading with Composer
- Service-based architecture (QuoteService, EmailService, QuoteHook)
- Professional configuration panel in back office
- Email templates for customer confirmation
- Email templates for admin notifications
- Admin statistics and quote management ready

### Features
- Global enable/disable quote button
- Per-product quote request form
- Quantity selection
- Customer message support
- AJAX form submission
- Automatic email notifications
- Database table creation on install
- Clean database table removal on uninstall
- Translation support via XLIFF files

### Security
- Input validation and sanitization
- Prepared database statements
- Customer access verification
- XSS protection

### Documentation
- Comprehensive README with features and installation
- Development guide with contribution guidelines
- Architecture documentation
- Database schema documentation
- API reference

---

## Upcoming Features (Roadmap)

### v1.1.0
- [ ] Admin dashboard with quote statistics
- [ ] Customer quote management page in account
- [ ] Product-level quote configuration (enable/disable per product)
- [ ] Custom email templates editor in back office
- [ ] Quote expiration dates
- [ ] Quote reminder emails

### v1.2.0
- [ ] Quote templates (price ranges)
- [ ] Bulk quote operations
- [ ] Quote import/export functionality
- [ ] Advanced filtering and search
- [ ] Quote comparison tool

### v1.5.0 - Quote Management Suite

#### ps_quote_converter
- Convert approved quotes to actual orders
- Maintain quote history in order metadata
- Custom pricing application

#### ps_quote_templates
- Pre-built quote templates
- Save quote drafts
- Quote versioning

#### ps_quote_automation
- Automatic quote expiration
- Reminder email sequences
- Follow-up automation
- Status-based actions

#### ps_quote_analytics
- Quote-to-order conversion tracking
- Sales analytics by quote
- Customer quote behavior analysis
- Revenue impact reports

---

## Compatibility

- **PrestaShop**: 9.0.0+
- **PHP**: 8.1.0+
- **MySQL**: 5.7+

## License

Commercial License - All rights reserved by Syntax It
