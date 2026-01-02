# Quick Start Guide - ps_request_quote

## ⚡ Installation (5 minutes)

### Step 1: Download the Module

**Option A: From GitHub**
```bash
git clone https://github.com/syntaxit/ps_request_quote.git
cd ps_request_quote
```

**Option B: Download ZIP**
- Visit [GitHub Releases](https://github.com/syntaxit/ps_request_quote/releases)
- Download `ps_request_quote-1.0.0.zip`
- Extract to `modules/ps_request_quote/`

### Step 2: Copy to PrestaShop

```bash
# Copy to your PrestaShop modules directory
cp -r ps_request_quote /path/to/prestashop/modules/
```

### Step 3: Install in PrestaShop Admin

1. Log in to PrestaShop Back Office
2. Navigate to **Modules > Module Manager**
3. Search for **"Request a Quote"**
4. Click **"Install"**
5. Click **"Configure"**

## 🎯 Configuration (2 minutes)

### Basic Setup

After installation, you'll see the configuration form with these options:

| Setting | Recommendation | Value |
|---------|----------------|-------|
| **Enable Quote Button** | ✅ Always enable | Checked |
| **Hide Buy Button** | ✅ For B2B mode | Checked |
| **Quote Button Text** | Customize to your language | "Request a Quote" or "Solicitar Cotización" |
| **Hide Price** | Optional | Unchecked (unless B2B only) |

### Example Configurations

#### For B2B E-commerce
```
☑ Enable Quote Button
☑ Hide Buy Button          (Disable direct purchasing)
☐ Hide Price              (Show prices to generate interest)
Button Text: "Request a Quote"
```

#### For Mixed B2B/B2C
```
☑ Enable Quote Button
☐ Hide Buy Button          (Allow both purchasing and quotes)
☐ Hide Price
Button Text: "Request a Quote"
```

#### For Exclusive B2B
```
☑ Enable Quote Button
☑ Hide Buy Button
☑ Hide Price              (Hide prices until quote requested)
Button Text: "Request a Quote for Pricing"
```

## 🧪 Testing (3 minutes)

### Test the Installation

1. **Go to any product page** on your store's front office
2. **Scroll to the product buttons area**
3. **Verify:**
   - ✓ Quote button appears (or replaces buy button)
   - ✓ Button text is correct
   - ✓ Quantity selector works
   - ✓ Message field is visible

### Test Quote Submission

1. **As a customer:**
   - Set quantity to 5
   - Add a message: "What's your bulk discount?"
   - Click "Request a Quote"
   - Verify success message appears

2. **Check your email:**
   - You should receive confirmation email
   - Admin should receive notification

## 📧 Email Configuration

Emails are sent automatically when a quote is requested:

### Customer Receives:
- **Subject**: Quote Request Confirmation
- **Content**: Order summary + acknowledgment
- **Language**: Based on store language

### Admin Receives:
- **Subject**: New Quote Request Received
- **Content**: Full customer details + message
- **Email**: Sent to `PS_SHOP_EMAIL`

**To customize emails:**
- Edit templates in `mails/[language]/` folder
- Restart PrestaShop cache

## 📱 Frontend View

When module is active, customers see:

```
╔══════════════════════════════════════════════════════╗
║  REQUEST A QUOTE FORM                                ║
╠══════════════════════════════════════════════════════╣
║                                                      ║
║  Quantity:  [−] [ 1 ] [+]                            ║
║                                                      ║
║  Message (optional):                                 ║
║  ┌──────────────────────────────────────────────┐  ║
║  │ Any special request or question?             │  ║
║  │                                              │  ║
║  │                                              │  ║
║  └──────────────────────────────────────────────┘  ║
║                                                      ║
║  [📧 REQUEST A QUOTE]                               ║
║                                                      ║
╚══════════════════════════════════════════════════════╝
```

## 🔑 Key Features

✨ **What You Get:**
- ✅ Quote request button on products
- ✅ Customizable button text
- ✅ Quantity selection
- ✅ Message/notes field
- ✅ Email notifications (Customer + Admin)
- ✅ Multi-language support (ES, EN)
- ✅ Database storage of all requests
- ✅ Status tracking (Pending, Quoted, Rejected, Expired)

## 🚀 Next Steps

### After Installation

1. **Add Your Logo**
   - See [LOGO_INSTRUCTIONS.md](LOGO_INSTRUCTIONS.md)
   - Place `logo.png` in module root
   - Commit changes

2. **Customize Settings**
   - Change button text to your language
   - Adjust email templates if needed
   - Test with different products

3. **Create Documentation for Your Team**
   - How to handle quote requests
   - Email response templates
   - Pricing workflow

4. **Integrate with Your Sales Process**
   - Set up email notifications
   - Create sales team response procedure
   - Track quote-to-order conversion

## 🆘 Troubleshooting

### Quote button doesn't appear

**Check:**
1. Module is installed and activated
   ```
   Admin > Modules > Module Manager > Search "Request a Quote"
   ```
2. "Enable Quote Button" is checked in configuration
3. Clear browser cache (Ctrl+Shift+Delete)
4. Clear PrestaShop cache (Admin > Performance)

### Emails not sending

**Check:**
1. PrestaShop email settings are configured
   ```
   Admin > Shop Parameters > Email > Outgoing Emails
   ```
2. `PS_SHOP_EMAIL` is set correctly
3. Email server credentials are valid
4. Check server error logs: `var/logs/`

### Database error after install

**Solution:**
1. Check file permissions: `chmod 755 modules/ps_request_quote/`
2. Verify PHP version: `php -v` (needs 8.1+)
3. Check MySQL connection
4. Try uninstalling and reinstalling

## 💻 Command Line Access

### Check Module Status

```bash
# Check if module directory exists
ls -la modules/ps_request_quote/

# Check if database table created
mysql -u root -p prestashop_db -e "SHOW TABLES LIKE '%psrq%';"

# View module logs
tail -f var/logs/202501*.log
```

### Reset Module

```bash
# From PrestaShop root directory
rm -rf modules/ps_request_quote/
git clone https://github.com/syntaxit/ps_request_quote.git modules/ps_request_quote

# Then reinstall from Admin > Modules
```

## 📊 Monitoring

### Check Quote Requests

```sql
-- Connect to your database
mysql prestashop_db

-- View all quotes
SELECT * FROM ps_psrq_quotes ORDER BY date_add DESC;

-- Count by status
SELECT status, COUNT(*) as count FROM ps_psrq_quotes GROUP BY status;

-- Find quotes from today
SELECT * FROM ps_psrq_quotes 
WHERE DATE(date_add) = CURDATE()
ORDER BY date_add DESC;
```

## 📞 Support

**Need help?**

- 📖 **Documentation**: [README.md](README.md)
- 🛠️ **Development Guide**: [DEVELOPMENT.md](DEVELOPMENT.md)
- 💬 **GitHub Issues**: [Open an issue](https://github.com/syntaxit/ps_request_quote/issues)
- 📧 **Email Support**: support@syntaxit.cl
- 🌐 **Website**: https://syntaxit.cl

## ✅ Checklist

- [ ] Module installed successfully
- [ ] Quote button visible on product pages
- [ ] Can submit a test quote
- [ ] Received confirmation email
- [ ] Admin received notification
- [ ] Button text customized (if needed)
- [ ] Logo added (optional)
- [ ] Sales team trained on process
- [ ] Ready to go live!

---

**🎉 You're ready to start accepting quote requests!**

For advanced features and future versions, see [CHANGELOG.md](CHANGELOG.md)
