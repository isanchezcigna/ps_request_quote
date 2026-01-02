# Logo Setup Instructions

## Logo Requirements

### Specifications

- **Format**: PNG (transparent background recommended)
- **Dimensions**: 200x200 pixels minimum, square aspect ratio
- **File Size**: Under 500KB
- **Filename**: `logo.png`
- **Location**: Root directory of the module (`ps_request_quote/logo.png`)

### Design Recommendations

- **Style**: Professional, clean design
- **Colors**: Should work on both light and dark backgrounds
- **Text**: Include "Syntax It" branding
- **Icon**: Consider a simple quote/chat icon (envelope, quotation marks, etc.)

### Example Dimensions

```
┌─────────────────────────┐
│                         │
│    SYNTAX IT LOGO      │
│                         │
│   Request a Quote       │
│                         │
└─────────────────────────┘
    200x200 pixels
```

## Installation Steps

### Method 1: Manual Upload (Recommended)

1. **Create/Prepare Your Logo**
   - Design or obtain the Syntax It logo
   - Ensure it meets specifications above
   - Save as `logo.png`

2. **Upload to Repository**
   ```bash
   # If working locally
   cp your_logo.png ps_request_quote/logo.png
   git add logo.png
   git commit -m "Add Syntax It logo"
   git push
   ```

   Or via GitHub web interface:
   - Navigate to the `ps_request_quote` repository
   - Click "Add file" > "Upload files"
   - Select your `logo.png`
   - Commit the change

3. **Verify Installation**
   - In PrestaShop back office
   - Go to Modules > Module Manager
   - Search for "Request a Quote"
   - Logo should appear in module card

### Method 2: Using Git

```bash
# Clone the repo
git clone https://github.com/syntaxit/ps_request_quote.git
cd ps_request_quote

# Copy your logo
cp /path/to/your/logo.png ./logo.png

# Add and commit
git add logo.png
git commit -m "Add Syntax It branding logo"
git push origin main
```

## Logo Display Locations

### Back Office

The logo appears in:
1. **Module Manager** - Module card/listing
2. **Module Configuration** - Above the settings form
3. **Module Details** - Module information popup

### Front Office

The logo may appear in:
1. Future customer quote dashboard
2. Email templates (as brand identifier)
3. Quote confirmation pages

## Logo File Structure

```
ps_request_quote/
├── logo.png                    ← ADD HERE
├── ps_request_quote.php
├── composer.json
├── config.xml
├── README.md
├── ...
```

## Branding Guidelines

### Color Palette (Suggested for Syntax It)

- **Primary**: #0066cc (Professional Blue)
- **Secondary**: #f5f5f5 (Light Gray)
- **Accent**: #00b74a (Success Green)
- **Text**: #333333 (Dark Gray)

### Font Recommendations

- **Sans-serif**: Inter, Segoe UI, Arial
- **Monospace**: JetBrains Mono, Monaco (for code elements)

## Common Logo Mistakes to Avoid

❌ **Don't use:**
- Logos larger than 500KB
- Animated GIFs or videos
- Text-only logos without icon
- Non-square aspect ratios
- Low-resolution graphics
- Logos with transparent text

✅ **Do use:**
- Clean, professional design
- High-resolution PNG (retina-ready 2x)
- Clear brand identity
- Good contrast for visibility
- Proper transparency for integration

## Testing the Logo

After uploading:

```bash
# 1. Check file exists
ls -la ps_request_quote/logo.png

# 2. Verify size and format
file ps_request_quote/logo.png

# 3. In PrestaShop, clear cache
# Admin > Advanced > Performance > Clear Cache

# 4. Check Module Manager page
# Admin > Modules > Module Manager
# Search for "Request a Quote"
```

## FAQ

### Q: Can I use a PNG with a colored background?
A: Yes, but transparent backgrounds integrate better with different theme designs.

### Q: What's the recommended file size?
A: 200x200 pixels is ideal, but anything between 128x128 and 512x512 works.

### Q: Can I update the logo later?
A: Yes, simply replace the `logo.png` file and push the changes.

### Q: Does the logo affect module performance?
A: No, it's loaded only in the back office module manager.

### Q: Can I use a JPG instead of PNG?
A: PrestaShop prefers PNG for transparency, but JPG works too.

## Support

If you need help with logo design or branding:
- **Contact**: design@syntaxit.cl
- **Website**: https://syntaxit.cl
- **Guidelines**: https://docs.syntaxit.cl/branding

---

**Ready to brand your module? Add the logo and commit! 🚀**
