# SaddhuSync Management System - Deployment & Setup Guide

## 🚀 Quick Start (5 minutes)

### 1. Prerequisites
```bash
# Ensure you have installed:
# - PHP 8.2+
# - Composer
# - MySQL/MariaDB
# - Node.js & npm (for Tailwind CSS)
```

### 2. Install Dependencies
```bash
cd f:\saddhu\ sync\saddhusync
composer install
npm install
```

### 3. Configure Environment
```bash
# Copy environment file
cp .env.example .env

# Generate app key (if not already done)
php artisan key:generate
```

### 4. Database Setup
```bash
# Create database (MySQL):
# CREATE DATABASE vihara_management;

# Update .env with database credentials:
# DB_DATABASE=saddhusync
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Run migrations and seeding
php artisan migrate:fresh --seed
```

### 5. Build Assets
```bash
npm run build
# or for development with watch:
npm run dev
```

### 6. Start Development Server
```bash
php artisan serve
```

Then visit: **http://localhost:8000**

---

## 🔑 Default Login Credentials

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@saddhusync.local | password |
| Officer | officer01@vihara.local | password |
| Officer | officer02@vihara.local | password |
| Member | member01@vihara.local | password |
| Member | member02@vihara.local | password |

⚠️ **Change these passwords immediately in production!**

---

## 📊 System Modules Overview

### Dashboard (`/dashboard`)
- **Overview**: Total members, donations, this month's donations, fund categories
- **Charts**: Donation trends, recent transactions
- **Widgets**: Upcoming rituals, low inventory items, recent donations
- **Access**: Admin, Officer, Member (limited to own data)

### Members (`/members`)
- **List**: View all active/inactive members
- **Register**: Add new congregation member (creates User account + Member profile)
- **Profile**: View member details, donations history, merit history, QR code
- **Edit**: Update member information
- **Actions**: Deactivate/activate member accounts
- **Features**: Automatic QR code token generation, member ID assignment

### Donations (`/donations`)
- **Record**: Log donations via QRIS, bank transfer, cash, check
- **Receipt**: Generate and send digital receipts (email/WhatsApp ready)
- **Categories**: Operational, Social, Infrastructure, Ritual, Education
- **Types**: One-time or recurring donations
- **Anonymous**: Support for anonymous contributions
- **Verification**: Mark donations as verified
- **Listing**: Filter by category, view donation status

### Rituals (`/rituals`)
- **Schedule**: Create prayers, ceremonies, classes, special events
- **Registration**: Members can register for upcoming rituals
- **Attendance**: Digital check-in/out with QR codes
- **Details**: View attendees, duration, type, location
- **Recurring**: Support for repeating rituals (implementation ready)

### Inventory (`/inventory`)
- **Items**: Track temple supplies and equipment
- **Categories**: Religious items, cleaning supplies, office supplies, etc.
- **Monitoring**: Low stock alerts when quantity ≤ reorder level
- **Values**: Calculate total inventory value
- **Adjustments**: Record stock changes with reasons
- **Destruction**: Remove items from inventory

### Petty Cash (`/petty-cash`)
- **Daily Expenses**: Log daily operational expenses
- **Categories**: Office, Utilities, Maintenance, Supplies, Food, Transportation, Other
- **Methods**: Cash, Card, Online transfers
- **Tracking**: User attribution, date tracking
- **Summary**: Daily and monthly totals

### Reports
- **Financial Summary**: Income vs. Expenses by date range
- **Donation Report**: Detailed donation breakdown by category
- **Activity Log**: Complete audit trail of all system changes
- **Expense Report**: Petty cash and expenses overview
- **Member Activity**: Track member participation and merit

---

## 🔐 Security Features

### Built-in Protections
- ✅ **CSRF Protection** - All forms protected with CSRF tokens
- ✅ **Password Hashing** - Bcrypt-hashed passwords, never stored in plain text
- ✅ **Session Management** - Secure session handling with HttpOnly cookies
- ✅ **Role-Based Access** - Admin/Officer/Member permission levels
- ✅ **Audit Logging** - All data changes logged with user/timestamp/IP
- ✅ **Input Validation** - Server-side validation on all endpoints
- ✅ **SQL Injection Protection** - Eloquent ORM prevents SQL injection

### Recommended Production Steps
```bash
# 1. Change all default passwords
php artisan tinker
# > User::where('role', 'admin')->first()->update(['password' => Hash::make('new_secure_password')])
# > exit

# 2. Configure SSL/HTTPS
# - Obtain SSL certificate
# - Update APP_URL in .env to https://
# - Configure web server (Apache/Nginx) for SSL

# 3. Set up backups
php artisan schedule:work
# Implement database backup scheduling

# 4. Enable logging
# Update LOG_CHANNEL in .env
# Monitor logs in storage/logs/

# 5. Configure queue workers (for email/SMS)
# php artisan queue:work redis
```

---

## 📧 Email/SMS Integration (Optional)

### Send Donation Receipts via Email

#### 1. Configure Mail Service (Laravel Mail)
```bash
# Update .env with your email provider:
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@saddhusync.local
MAIL_FROM_NAME="SaddhuSync Management System"
```

Supported providers: Gmail, Mailgun, Mailtrap, SendGrid, Amazon SES

#### 2. Implement Receipt Sending
Update `app/Models/Notification.php`:

```php
public static function sendDonationReceipt($donation) {
    Mail::send('emails.donation-receipt', ['donation' => $donation], function ($m) use ($donation) {
        $m->to($donation->member->user->email)
          ->subject('Donation Receipt - ' . config('app.name'));
    });
}
```

#### 3. Send Receipts from Controller
In `DonationController@sendReceipt()`:

```php
public function sendReceipt(Donation $donation) {
    Notification::sendDonationReceipt($donation);
    // Update donation->receipt_sent_at
    return back()->with('success', 'Receipt sent successfully');
}
```

### Send SMS via WhatsApp Business API

#### 1. Integrate Twilio
```bash
composer require twilio/sdk
```

#### 2. Configure Credentials
```bash
# Update .env
TWILIO_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_FROM=+1234567890
```

#### 3. Send SMS Notifications
```php
use Twilio\Rest\Client;

public static function sendWhatsAppReminder($member) {
    $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    
    $twilio->messages->create(
        "whatsapp:" . $member->user->phone,
        [
            "from" => "whatsapp:" . env('TWILIO_FROM'),
            "body" => "Dear {$member->user->name}, thank you for your support to our SaddhuSync."
        ]
    );
}
```

---

## 🎨 Customization

### Change Application Colors
Edit `resources/css/app.css`:

```css
:root {
    --color-saffron: #F4A261;
    --color-gold: #E8B923;
    --color-deep-brown: #3E2723;
    --color-jade: #2A9D8F;
}
```

### Update Fund Categories
Edit `database/seeders/FundCategorySeeder.php`:

```php
FundCategory::create([
    'name' => 'Building Fund',
    'slug' => 'building-fund',
    'description' => 'For temple renovation and improvements',
    'icon' => 'fas fa-hammer',
    'color' => '#FF6B6B'
]);
```

Then re-seed:
```bash
php artisan db:seed --class=FundCategorySeeder
```

### Customize Email Templates
Create `resources/views/emails/donation-receipt.blade.php`:

```blade
<h1>Donation Receipt</h1>
<p>Dear {{ $donation->member->user->name }},</p>
<p>Thank you for your generous donation of Rp{{ number_format($donation->amount) }}</p>
```

---

## 🐛 Common Issues & Troubleshooting

### Issue: "No application encryption key has been specified"
**Solution**: Run `php artisan key:generate`

### Issue: Database migration fails
**Solution**: 
- Check MySQL is running
- Verify credentials in .env
- Check database exists: `php artisan db:create` (if supported by your provider)

### Issue: "Class not found" errors
**Solution**: 
```bash
composer dump-autoload
php artisan cache:clear
php artisan config:clear
```

### Issue: Views not found / 404 errors
**Solution**:
```bash
php artisan view:clear
npm run build
```

### Issue: Cannot login
**Solution**: 
- Reset database: `php artisan migrate:fresh --seed`
- Check user exists: `php artisan tinker` → `User::all()`

### Issue: Missing styling (Tailwind not working)
**Solution**:
```bash
npm install
npm run build
# or for development
npm run dev
```

---

## 📱 Mobile Responsiveness

The system is fully responsive and works on:
- ✅ Desktop (1920px and up)
- ✅ Laptop (1024px - 1920px)
- ✅ Tablet (768px - 1024px)
- ✅ Mobile (320px - 768px)

All views use Tailwind CSS breakpoints:
- `sm`: 640px
- `md`: 768px
- `lg`: 1024px
- `xl`: 1280px

---

## 📈 Scaling & Performance

### Database Optimization
```sql
-- Add indexes for faster queries
ALTER TABLE donations ADD INDEX idx_member_id (member_id);
ALTER TABLE donations ADD INDEX idx_created_at (created_at);
ALTER TABLE activity_logs ADD INDEX idx_user_id (user_id);
ALTER TABLE activity_logs ADD INDEX idx_created_at (created_at);
```

### Caching
```php
// Cache donation totals
Cache::remember('total_donations_' . now()->monthYear, 3600, function () {
    return Donation::where('created_at', '>=', now()->startOfMonth())->sum('amount');
});
```

### Queue Processing
```bash
# For sending emails/SMS asynchronously
php artisan queue:work redis --tries=3
```

---

## 📊 Database Schema Reference

### Core Tables (11 total)

| Table | Purpose | Key Fields |
|-------|---------|-----------|
| users | Authentication | email, password, role, is_active |
| members | Member profiles | user_id, member_id, qr_code_token, join_date |
| fund_categories | Fund types | name, slug, icon, color |
| donations | Donation records | member_id, fund_category_id, amount, method, is_verified |
| rituals | Events/ceremonies | title, type, start_time, location, capacity |
| attendances | Event attendance | ritual_id, member_id, checked_in_at, checked_out_at |
| inventory_items | Supplies/equipment | name, category, quantity, unit, purchase_price, reorder_level |
| petty_cash | Daily expenses | user_id, category, amount, date, payment_method |
| merit_history | Member activities | member_id, activity_type, amount, activity_date |
| activity_logs | Audit trail | user_id, action, model_type, old_values, new_values, ip_address |
| notifications | E-receipts/reminders | type, recipient_id, subject, body, sent_at |

---

## 🎯 Next Steps

### Immediate (Day 1)
- [ ] Database setup & migration
- [ ] Login with demo account
- [ ] Explore all modules
- [ ] Change default passwords

### Short-term (Week 1)
- [ ] Add your temple's information (edit seeders)
- [ ] Upload member data
- [ ] Create rituals schedule
- [ ] Configure email notifications

### Medium-term (Month 1)
- [ ] Deploy to production server
- [ ] Configure SSL/HTTPS
- [ ] Setup backups
- [ ] Train staff on system usage

### Long-term (Ongoing)
- [ ] Monitor activity logs for security
- [ ] Generate regular reports
- [ ] Gather member feedback
- [ ] Plan feature enhancements

---

## 📞 Support & Documentation

- 📖 **Full System Guide**: See `VIHARA_SYSTEM_GUIDE.md`
- 🚀 **Quick Start**: See `QUICKSTART.md`
- 💻 **Laravel Documentation**: https://laravel.com/docs
- 🎨 **Tailwind CSS**: https://tailwindcss.com/docs
- 📧 **Email Setup**: https://laravel.com/docs/mail
- 📱 **API Integration**: See controller methods for extensibility

---

## 📄 License

This system is developed for Buddhist temple management. Feel free to customize for your needs.

**Created**: 2024
**Framework**: Laravel 12
**Database**: MySQL 8.0+
**PHP**: 8.2+

---

**Happy managing! May this system bring ease and organization to your temple operations. 🙏**
