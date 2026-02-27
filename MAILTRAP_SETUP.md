# Mailtrap Email Verification Setup

This document explains how to set up and use the Mailtrap email verification system for La Petite Pâtisserie.

## Configuration

### 1. Environment Setup

Update your `.env` file with your Mailtrap credentials:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@lapetitepatisserie.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Get Mailtrap Credentials

1. Sign up for a free Mailtrap account at [https://mailtrap.io](https://mailtrap.io)
2. Create a new inbox or use the default demo inbox
3. Go to the SMTP Settings tab
4. Copy the username and password to your `.env` file

## File Structure

```
├── config/
│   └── mail.php                     # Mail configuration
├── app/
│   ├── Services/
│   │   └── EmailVerificationService.php
│   ├── Mail/
│   │   └── EmailVerificationMail.php
│   └── Http/Controllers/
│       └── EmailVerificationController.php
├── resources/views/
│   ├── emails/
│   │   └── verification.blade.php
│   └── auth/
│       ├── verification-success.blade.php
│       └── verification-failed.blade.php
└── routes/
    └── web.php                      # Updated with verification routes
```

## Usage

### Sending Verification Emails

When a user registers, send them a verification email:

```php
use App\Services\EmailVerificationService;

$emailService = app(EmailVerificationService::class);
$emailService->sendVerificationEmail($user);
```

### API Endpoints

- `POST /email/verify/send` - Send verification email (authenticated)
- `POST /email/verify/resend` - Resend verification email (authenticated)
- `GET /email/verify/{token}` - Verify email with token
- `GET /email/verify/status` - Check verification status (authenticated)
- `GET /verification/success` - Verification success page
- `GET /verification/failed` - Verification failed page

### Database Migration

Add these columns to your `users` table if not already present:

```php
Schema::table('users', function (Blueprint $table) {
    $table->string('email_verification_token')->nullable();
    $table->timestamp('email_verified_at')->nullable();
});
```

## Testing

1. Update your `.env` file with real Mailtrap credentials
2. Register a new user or trigger email verification
3. Check your Mailtrap inbox for the verification email
4. Click the verification link to test the flow

## Features

- ✅ Secure token-based verification
- ✅ Token expiration (handled by database cleanup)
- ✅ Resend verification functionality
- ✅ Beautiful email templates
- ✅ Success and error pages
- ✅ API endpoints for AJAX requests
- ✅ Status checking endpoint

## Security Notes

- Verification tokens are 60-character random strings
- Tokens are invalidated after successful verification
- Email verification status is stored in `email_verified_at` timestamp
- All verification endpoints require authentication except the verify link

## Troubleshooting

### Email not sending
- Check Mailtrap credentials in `.env`
- Verify `MAIL_MAILER` is set to `smtp`
- Check Laravel logs for errors

### Verification link not working
- Ensure the token exists in the database
- Check if the email is already verified
- Verify the route configuration

### Templates not displaying
- Clear view cache: `php artisan view:clear`
- Check file permissions in `resources/views`
