# Mailjet API Integration

## Configuration

### 1. Environment Variables (.env)
```
API_MAIL=your_mailjet_api_key
SECRET_API_MAIL=your_mailjet_secret_key
MAIL_PROVIDER=mailjet  # or 'mailhog' for local development
```

### 2. Settings Configuration (configs/settings.php)
The mail configuration is automatically loaded from environment variables:
- `MAIL_PROVIDER`: 'mailjet' or 'mailhog'
- API keys are loaded from .env file

### 3. SSL Certificate Issue (Local Development)

If you encounter SSL certificate errors in local development:

**Option 1: Install CA Certificates (RECOMMENDED)**
- Download `cacert.pem` from https://curl.se/ca/cacert.pem
- Add to your `php.ini`:
  ```
  curl.cainfo = "C:\MAMP\htdocs\PortfolioV2\public\cacert.pem"
  openssl.cafile="C:\MAMP\htdocs\PortfolioV2\public\cacert.pem"
  ```

**Option 2: Use SMTP instead of API**
- Set `MAIL_PROVIDER=mailhog` in .env for local testing
- Configure Mailjet SMTP in settings.php

## API Version Note

⚠️ **Important**: This project uses Mailjet API version `v3`, not `v3.1`. The API keys are configured to work with `v3`.

## Local Development Setup

### Option 1: Mailjet SMTP (Recommended for Local Testing)

1. **Configure your .env file:**
   ```
   MAIL_PROVIDER=mailjet_smtp
   API_MAIL=your_mailjet_api_key
   SECRET_API_MAIL=your_mailjet_secret_key
   ```

2. **Verify your sender email in Mailjet:**
   - Go to your Mailjet dashboard
   - Navigate to "Account" > "Sender addresses"
   - Add and verify your email address (e.g., your Gmail or custom domain email)

3. **Update settings.php with your verified email:**
   ```php
   'from' => 'your-verified-email@example.com',
   'admin' => 'your-admin-email@example.com',
   ```

### Option 2: MailHog (For Complete Local Testing)

If you prefer to intercept emails locally without sending them:

1. Install MailHog: https://github.com/mailhog/MailHog
2. Run MailHog: `mailhog`
3. Set in .env: `MAIL_PROVIDER=mailhog`
4. Access MailHog web interface at: http://localhost:8025

### Testing

Run the test scripts:
```bash
# Test API connection
php index.php

# Test SMTP sending (update email addresses first)
php test_smtp.php
```

## Email Structure

The Mailjet integration follows the official documentation format:

```php
$body = [
    'Messages' => [
        [
            'From' => [
                'Email' => "sender@example.com",
                'Name' => "Sender Name"
            ],
            'To' => [
                [
                    'Email' => "recipient@example.com",
                    'Name' => "Recipient Name"
                ]
            ],
            'Subject' => "Your subject",
            'TextPart' => "Plain text version",
            'HTMLPart' => "<p>HTML version</p>"
        ]
    ]
];
```

## Production Setup

1. Set `MAIL_PROVIDER=mailjet` in your production .env
2. Ensure API keys are set
3. Configure your domain in Mailjet dashboard
4. Set up SPF/DKIM records for your domain
5. Test thoroughly before going live

## Troubleshooting

- **SSL errors**: Install CA certificates (see Option 1 above)
- **API version errors**: Ensure you're using `v3`, not `v3.1`
- **API errors**: Check API keys and Mailjet account status
- **Rate limits**: Monitor your Mailjet usage
- **Delivery issues**: Check spam folder and Mailjet logs