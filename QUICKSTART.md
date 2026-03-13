# SaddhuSync Management System - Quick Start Guide

## What's Included

This is a complete **Buddhist Temple Management System** built with Laravel, featuring:

### Core Modules
1. **Dashboard** - Overview of operations
2. **Members** - Congregation management
3. **Donations** - Digital donation tracking
4. **Rituals** - Event scheduling & attendance
5. **Inventory** - Supply management
6. **Petty Cash** - Daily expense tracking
7. **Reports** - Financial & activity reports

## 📦 What's Been Created

### Database
✅ 11 migrations creating tables for:
- Members, Donations, Rituals, Inventory
- Fund Categories, Attendance, Merit History
- Petty Cash, Notifications, Activity Logs

### Models (10 total)
✅ Complete data models with relationships:
- User, Member, Donation, FundCategory
- Ritual, Attendance, InventoryItem, PettyCash
- MeritHistory, ActivityLog, Notification

### Controllers (7 total)
✅ Feature controllers:
- DashboardController
- DonationController
- MemberController
- RitualController
- InventoryController
- PettyCashController
- ReportController

### Views (20+ templates)
✅ Beautiful Buddhist-themed UI:
- Responsive Tailwind CSS design
- Saffron/gold/brown color scheme
- Clean, spiritual layout
- Mobile-friendly

## 🚀 Running the System

### 1. Install Dependencies
```bash
composer install
npm install
```

### 2. Setup Database
```bash
php artisan migrate:fresh
php artisan db:seed
```

### 3. Build Frontend
```bash
npm run dev
```

### 4. Start Server
```bash
php artisan serve
```

### 5. Login
- URL: `http://localhost:8000/login`
- Email: `admin@saddhusync.local`
- Password: `password`

## 🎯 Key Features

### Donations
- Record gifts via QRIS, bank, cash
- Automatic e-receipts
- Anonymous option
- Monthly recurring setup
- Merit tracking

### Members
- Register congregation
- Track birth dates
- QR code member cards
- Donation history
- Activity tracking

### Rituals
- Schedule prayers/ceremonies
- Classes and special events
- Registration & attendance
- QR code check-in
- Attendance reporting

### Inventory
- Track supplies
- Low-stock alerts
- Asset valuation
- Stock adjustments
- Category organization

### Petty Cash
- Daily expense logging
- Category tracking
- Daily/monthly summaries
- Multiple payment methods

### Reports
- Financial summaries
- Activity auditing
- Member reports
- Donation analysis
- Real-time tracking

## 📊 Fund Categories

Pre-configured categories:
1. **Operational** - Electricity, water, maintenance
2. **Social/Charity** - Humanitarian assistance
3. **Infrastructure** - Building improvements
4. **Ritual & Ceremony** - Religious materials
5. **Education** - Dhamma classes

## 🎨 Design Highlights

- **Color Theme**: Saffron, Gold, Deep Brown
- **Icons**: Font Awesome + Buddhist symbols
- **Layout**: Sidebar navigation + main content
- **Responsive**: Works on all devices
- **Spiritual**: Peaceful, meditation-friendly design

## 📁 Project Structure

```
app/
  ├── Models/          (10 models)
  └── Http/Controllers/ (7 controllers)
database/
  ├── migrations/      (11 migrations)
  └── seeders/         (2 seeders)
resources/
  ├── views/
  │   ├── layouts/     (app.blade, auth.blade)
  │   ├── dashboard/
  │   ├── members/
  │   ├── donations/
  │   ├── rituals/
  │   ├── inventory/
  │   ├── petty-cash/
  │   ├── reports/
  │   └── auth/
routes/
  └── web.php          (All routes)
```

## � Map‑Assisted Addresses

Members can now set their address by choosing a location on a map. The create/edit form includes an interactive Google Map with a draggable marker; moving the pin updates the address field (and stores latitude/longitude for later use). Make sure a `GOOGLE_MAPS_KEY` is defined in your `.env` and `config/services.php` is updated accordingly.

The public donation form also provides a polished address input: province/city/postal code selectors plus a Leaflet map for optional geolocation. Donor messages and contact details are still accepted as before.
## �🔐 User Roles

There is now a dedicated **Roles** screen under Operations where administrators can create, edit and delete role entries. The table is seeded with `admin`, `officer` and `member` by default but you may add additional types such as `volunteer` or `guest`.

1. **Admin** - Full system access
2. **Officer** - Manage donations, members, events
3. **Member** - View own profile & donations

## ⚙️ Initial Setup Checklist

- [x] Database migrations
- [x] Models with relationships
- [x] Controllers with CRUD
- [x] Routes (30+ endpoints)
- [x] Views for all features ("Add" buttons now open in‑page modals instead of separate create pages)
- [x] Authentication system
- [x] Audit logging
- [x] Buddhist-themed design
- [x] Responsive layout
- [x] Sample data seeding

## 🎓 Next Steps

### To Start Using:
1. Run migrations: `php artisan migrate`
2. Seed data: `php artisan db:seed`
3. Start server: `php artisan serve`
4. Login with demo credentials
5. Explore features!

### To Customize:
1. Edit fund categories in `FundCategorySeeder.php`
2. Modify colors in `resources/views/layouts/app.blade.php`
3. Add new donation methods in controllers
4. Extend models with new fields
5. Create additional reports

### To Deploy:
1. Configure `.env` for production
2. Run migrations: `php artisan migrate --force`
3. Build assets: `npm run build`
4. Setup queue worker for emails
5. Configure backup strategy

## 📖 Documentation

- **Full Guide**: See `VIHARA_SYSTEM_GUIDE.md`
- **Inline Comments**: Check model and controller code
- **Blade Templates**: Learn from view structure

## 🆘 Troubleshooting

**Database errors?**
- Check `.env` database credentials
- Run: `php artisan cache:clear`
- Try: `php artisan migrate:fresh --seed`

**Migration issues?**
- Clear cache: `php artisan cache:clear`
- Reset: `php artisan migrate:rollback`
- Refresh: `php artisan migrate:refresh --seed`

**Logo/images missing?**
- Ensure Tailwind CSS is built: `npm run dev`
- Check Font Awesome CDN is loaded

## 🎉 You're Ready!

The complete system is ready to use. All features are:
- ✅ Fully functional
- ✅ Professionally designed
- ✅ Database-backed
- ✅ Authentication-protected
- ✅ Audit-logged
- ✅ Production-ready

### Start managing your temple digitally today! 🏛️

---

**Need help?** Check the detailed guide or review controller code for implementation details.
