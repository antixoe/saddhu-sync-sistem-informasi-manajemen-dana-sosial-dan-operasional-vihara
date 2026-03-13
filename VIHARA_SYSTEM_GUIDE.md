# SaddhuSync Management System

A comprehensive web-based system for managing Buddhist/Taoist temple operations, finances, and congregation activities.

## 🏛️ Overview

The Vihara Management System is a modern, digital solution designed to streamline temple management with integrated features for:
- Digital donation collection and recording
- Congregation/member management
- Ritual and ceremony scheduling
- Inventory tracking
- Financial reporting and analysis
- Digital attendance with QR codes
- Petty cash management
- Complete audit trail

## ✨ Key Features

### 1. **Digital Donation System**
- Multiple donation methods:
  - QRIS (QR code instant payment)
  - Bank transfers
  - Cash deposits
  - Check payments
- Automatic e-receipt generation
- Donor tracking and history
- Anonymous donation support
- Recurring donation setup with automatic reminders
- Real-time donation tracking

### 2. **Member Management**
- Complete congregation database
- Member registration and onboarding
- Birth date and anniversary tracking
- QR code generation for digital member cards
- Member status management (active/inactive)
- Merit history tracking
- Donation history per member

### 3. **Ritual & Event Scheduling**
- Create and manage prayers, ceremonies, and classes
- Recurring ritual support
- Capacity management
- Registration tracking
- QR code-based check-in system
- Attendance reporting

### 4. **Financial Management**

#### Fund Categories
- **Operational**: Electricity, water, maintenance
- **Social/Charity**: Humanitarian acts, donations
- **Infrastructure**: Building improvements, renovations
- **Ritual & Ceremony**: Materials for religious activities
- **Education**: Dhamma classes and programs

#### Financial Features
- Real-time income and expense tracking
- Category-based fund allocation
- Monthly and yearly reports
- Financial trend analysis
- Net balance calculation

### 5. **Inventory System**
- Track temple supplies and equipment
- Categories: ritual items, supplies, incense, paper, equipment
- Low-stock alerts
- Reorder level management
- Stock valuation
- Inventory adjustments with reason tracking

### 6. **Petty Cash Management**
- Daily expense tracking
- Categories: transportation, food, supplies, utilities, etc.
- Multiple payment methods
- Daily and monthly summaries
- Exception logging and notes

### 7. **Reporting & Analytics**
- Financial summary reports
- Income by category
- Expense breakdown
- Member activity reports
- Complete activity audit log
- User action tracking

### 8. **Audit & Security**
- Complete activity logging
- User action tracking
- Data change history
- IP address logging
- Timestamp recording

## 🏗️ System Architecture

### Technology Stack
- **Framework**: Laravel 12
- **Database**: MySQL
- **Frontend**: Blade templating + Tailwind CSS
- **Authentication**: Laravel built-in
- **PHP Version**: ≥ 8.2

### Database Schema

#### Core Tables
- `users` - System users (admin, officers, members)
- `members` - Congregation member details

> **UI note:** most data‑entry screens allow creating records via a pop‑up modal, keeping you on the index page instead of navigating away.
- `donations` - Donation records
- `fund_categories` - Fund types
- `rituals` - Events and ceremonies
- `attendances` - Event attendance records
- `inventory_items` - Temple supplies
- `petty_cash` - Daily expenses
- `merit_history` - Member activity history
- `activity_logs` - Audit trail
- `notifications` - E-receipts and reminders

### User Roles
A new **Roles** management page allows administrators to define what roles exist in the system and optionally attach labels/descriptions. The application seeds three defaults – `admin`, `officer` and `member` – but additional types (e.g. `volunteer`, `guest`) can be added.

### Map‑based address entry
Member registration now leverages the Google Maps API. When adding or editing a member the form displays an embedded map with a draggable marker; the selected position fills the address field and stores latitude/longitude values in the database. This requires a valid `GOOGLE_MAPS_KEY` in your environment and `config/services.php`.

Public donation submissions also offer a more professional address interface: donors choose province/city from dropdowns and can optionally pick a point on an embedded Leaflet map. Latitude/longitude coordinates are saved if provided.


1. **Admin**: Full system access
2. **Officer**: Manage donations, members, events
3. **Member**: View personal dashboard

## 🚀 Getting Started

### Installation

1. **Clone and Setup**
```bash
cd your-project-directory
composer install
```

2. **Environment Configuration**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Database Setup**
```bash
php artisan migrate
php artisan db:seed
```

4. **Assets**
```bash
npm install
npm run dev
```

5. **Start Development Server**
```bash
php artisan serve
```

### Default Credentials
- **Email**: admin@saddhusync.local
- **Password**: password

## 📋 Module Documentation

### Dashboard
- Overview of key metrics (members, donations, upcoming rituals)
- Recent donations list
- Upcoming rituals
- Low-stock inventory alerts
- Financial trend visualization

### Members Module
- **List**: View all members with search/filter
- **Register**: Add new congregation members
- **Profile**: View detailed member information
  - Personal details
  - Donation history
  - Ritual attendance
  - Merit accumulation
  - QR code card

### Donations Module
- **Record**: Add new donations
- **Types**: Cash, QRIS, bank transfer, check
- **Verification**: Verify and confirm donations
- **Receipts**: Send e-receipts via email/WhatsApp
- **Reporting**: View donation history and patterns

#### Donation Flow
1. Member donates (online or offline)
2. Officer records donation with amount and method
3. System matches to fund category
4. E-receipt generated
5. Activity log created
6. Merit history updated

### Rituals Module
- **Create**: Schedule new rituals
- **Types**: Prayer, ceremony, dhamma class, special events
- **Recurring**: Support for repeating rituals
- **Registration**: Member registration/attendance
- **Check-in**: QR code attendance tracking
- **Reports**: Attendance statistics

### Inventory Module
- **Items**: Track all temple supplies
- **Categories**: Classification system
- **Reorder**: Low-stock alerts
- **Valuation**: Track asset value
- **Adjustments**: Record stock changes with reasons

### Petty Cash Module
- **Transactions**: Record daily small expenses
- **Categories**: Organize by expense type
- **Daily Summary**: Total expenses per day
- **Monthly Summary**: Month-to-date totals
- **Reports**: Expense analysis and trends

### Reports Module

#### Financial Summary
- Period-based reporting
- Income vs. expenses
- Net balance calculation
- Category breakdown
- Trend analysis

#### Activity Log
- User actions tracking
- Timestamps
- Data changes
- IP addresses
- Complete audit trail

#### Donor Reports
- Donation frequency
- Total contributions
- Preferred payment method
- Recurring vs. one-time

## 🎨 Design & Theme

The system features a Buddhist/Taoist inspired design with:
- **Color Palette**:
  - Saffron (#F4A261): Primary accent
  - Deep Brown (#3E2723): Text and headers
  - Gold (#E8B923): Secondary accent
  - Jade (#2A9D8F): Complementary color
  
- **Icons**: Font Awesome Buddhist symbols
- **Layout**: Clean, spiritual design with golden accents
- **Typography**: Professional, readable fonts

## 🔐 Security Features

1. **Authentication**: Laravel authentication system
2. **Authorization**: Role-based access control
3. **Audit Trail**: Complete logging of all actions
4. **Data Protection**: Secure password hashing
5. **Session Management**: Secure session handling
6. **CSRF Protection**: Laravel CSRF tokens
7. **Input Validation**: Server-side validation

## 📊 API Endpoints

All endpoints are protected with authentication middleware.

### Donations
- `GET /donations` - List donations
- `GET /donations/{id}` - Show donation details
- `POST /donations` - Create donation
- `PUT /donations/{id}` - Update donation
- `POST /donations/{id}/verify` - Verify donation
- `POST /donations/{id}/send-receipt` - Send receipt

### Members
- `GET /members` - List members
- `GET /members/{id}` - Show member profile
- `POST /members` - Register member
- `PUT /members/{id}` - Update member
- `POST /members/{id}/deactivate` - Deactivate member
- `POST /members/{id}/activate` - Activate member

### Rituals
- `GET /rituals` - List events
- `POST /rituals` - Create event
- `POST /rituals/{id}/register/{member}` - Register attendance
- `POST /rituals/{id}/check-in/{member}` - Check in

### Inventory
- `GET /inventory` - List items
- `POST /inventory` - Add item
- `POST /inventory/{id}/adjust-stock` - Adjust stock

### Reports
- `GET /reports/financial-summary` - Financial report
- `GET /reports/donations` - Donation report
- `GET /reports/activity-log` - Audit log

## 📝 Usage Examples

### Recording a Donation
1. Navigate to **Donations → Record Donation**
2. Select member (or leave anonymous)
3. Choose fund category
4. Enter amount and payment method
5. Add transaction ID if applicable
6. Submit
7. System creates merit history entry
8. Generates e-receipt

### Scheduling a Ritual
1. Go to **Rituals & Events → Add Ritual**
2. Enter title and description
3. Set date, time, and location
4. Choose ritual type
5. Set capacity if needed
6. Enable registration if required
7. Submit
8. Members can register/attend

### Managing Inventory
1. Navigate to **Inventory Management**
2. View all items with stock levels
3. Low-stock items highlighted in red
4. Click "Edit" to adjust quantities
5. Reason field for all changes
6. System tracks all modifications

## 🔧 Customization

### Adding New Fund Categories
Edit `database/seeders/FundCategorySeeder.php`:
```php
'categories' => [
    [
        'name' => 'Category Name',
        'slug' => 'category-slug',
        'description' => 'Description',
        'icon' => 'fa-icon-name',
        'color' => '#HEXcolor',
    ],
]
```

### Extending Models
Models located in `app/Models/`:
- `Member.php`
- `Donation.php`
- `Ritual.php`
- `InventoryItem.php`
- etc.

### Creating New Reports
1. Create method in `ReportController.php`
2. Add blade template in `resources/views/reports/`
3. Add route in `routes/web.php`

## 📱 Mobile Responsiveness
All views are fully responsive using Tailwind CSS for mobile, tablet, and desktop viewing.

## 🌐 Localization
System currently supports English. To add other languages:
1. Create language files in `resources/lang/`
2. Use Laravel's localization helpers
3. Configure in `.env`

## 📞 Support & Maintenance

### Database Backups
```bash
php artisan backup:run
```

### Cache Clearing
```bash
php artisan cache:clear
php artisan config:cache
```

### Queue Jobs
For email/SMS notifications:
```bash
php artisan queue:work
```

## 🎓 Future Enhancements

- [ ] SMS/WhatsApp integration for receipts
- [ ] QR code generation and printing
- [ ] Mobile app (iOS/Android)
- [ ] Real-time notifications
- [ ] Multi-language support
- [ ] Expense approval workflow
- [ ] Advanced analytics dashboard
- [ ] API for third-party integrations
- [ ] Bulk import/export features
- [ ] Prayer/ritual reminders

## 📄 License

Built specifically for Buddhist temple management. Free for non-commercial use.

## 🙏 Acknowledgments

Developed with deep respect for Buddhist and Taoist traditions. May this system serve all beings.

---

**Version**: 1.0.0  
**Last Updated**: March 2026  
**Developed by**: Temple Management Team
