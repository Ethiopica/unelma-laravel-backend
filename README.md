# Unelma Laravel Backend

⚙️ **Unelma Platform Redesign – Backend**  
This repository contains the **backend** for the Unelma Platform redesign project, developed as part of **Software Development Team Project 2** at Business College Helsinki.  
Built with **Laravel 11**, it provides secure RESTful APIs, admin dashboard, user management, and comprehensive authentication for the React frontend.

**Team**: React25K@Team 3  
**Version**: 1.0  
**Last Updated**: October 28, 2025

---

## 📌 Features

### Core Functionality
- ✅ **Admin Dashboard** - Full-featured admin panel with role-based access
- ✅ **User Management** - Complete CRUD operations for users
- ✅ **Profile Pictures** - Image upload and management system
- ✅ **Settings & Reports** - Configuration management and analytics dashboard
- ✅ **RESTful API** - Secure endpoints for frontend integration
- ✅ **Dual Authentication** - Session-based (web) + Token-based (API)
- ✅ **Customer Portal** - Self-service API for user account management
- ✅ **Custom Welcome Page** - Branded landing page with modern design

### Security & Performance
- 🔐 Laravel Sanctum for API authentication
- 🔐 Role-based access control (Admin/User)
- 🔐 Password hashing with bcrypt
- 🔐 CSRF protection for web routes
- 🔐 File upload validation and security
- 🔐 CORS configuration for frontend integration

---

## 🛠️ Tech Stack

- **Backend Framework:** Laravel 11.x
- **Language:** PHP 8.2+
- **Database:** MySQL 8.0+
- **Authentication:** 
  - Laravel Sanctum (Token-based for API)
  - Session-based (Web/Admin)
- **Frontend:** Blade Templates + Tailwind CSS
- **File Storage:** Laravel Storage (local/public disk)
- **API Architecture:** RESTful JSON API

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Node.js & NPM

### Installation

```bash
# 1. Clone the repository
git clone <repository-url>
cd unelma-laravel-backend

# 2. Install dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Configure database in .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=laravel
DB_USERNAME=laravel
DB_PASSWORD=your_password

# 5. Run migrations and seed admin user
php artisan migrate
php artisan db:seed --class=AdminUserSeeder

# 6. Create storage symlink
php artisan storage:link

# 7. Start development server
php artisan serve
```

### Access Points

- **Welcome Page**: http://127.0.0.1:8000
- **Admin Login**: http://127.0.0.1:8000/admin/login
- **Admin Dashboard**: http://127.0.0.1:8000/admin/dashboard
- **API Base URL**: http://127.0.0.1:8000/api

### Default Admin Credentials

```
Email: example@unelma.com
Password: 12345678
```

⚠️ **Change these credentials immediately after first login!**

---

## 📚 Documentation

### Complete Documentation
📖 **[COMPLETE_SYSTEM_DOCUMENTATION.md](./COMPLETE_SYSTEM_DOCUMENTATION.md)** - Master documentation with full system overview

### Feature-Specific Documentation

| Feature | Documentation File | Description |
|---------|-------------------|-------------|
| Admin Dashboard | [ADMIN_DASHBOARD_SETUP.md](./ADMIN_DASHBOARD_SETUP.md) | Admin authentication & dashboard setup |
| User Management | [USER_MANAGEMENT_SETUP.md](./USER_MANAGEMENT_SETUP.md) | CRUD operations for user management |
| Profile Pictures | [PROFILE_PICTURE_FEATURE.md](./PROFILE_PICTURE_FEATURE.md) | Image upload & management system |
| Settings & Reports | [SETTINGS_AND_REPORTS_SETUP.md](./SETTINGS_AND_REPORTS_SETUP.md) | Admin settings & analytics dashboard |
| API Authentication | [API_AUTHENTICATION_SETUP.md](./API_AUTHENTICATION_SETUP.md) | Sanctum setup & API auth guide |
| Customer API | [CUSTOMER_API_DOCUMENTATION.md](./CUSTOMER_API_DOCUMENTATION.md) | Self-service customer endpoints |
| Welcome Page | [WELCOME_PAGE_SETUP.md](./WELCOME_PAGE_SETUP.md) | Landing page customization guide |

---

## 🌐 API Endpoints

### Public Endpoints (No Authentication)
```
POST   /api/register          # Create new user account
POST   /api/login             # Login and get token
```

### Protected Endpoints (Requires Bearer Token)
```
GET    /api/user              # Get current user info
POST   /api/logout            # Logout (invalidate token)
GET    /api/profile           # Get profile details
PUT    /api/profile           # Update profile
POST   /api/profile/change-password   # Change password
DELETE /api/profile           # Delete account
GET    /api/profile/activity  # Get activity log
```

### Admin Web Routes (Session Authentication)
```
GET    /admin/login           # Show login form
POST   /admin/login           # Process login
GET    /admin/dashboard       # Admin dashboard
GET    /admin/users           # List all users
POST   /admin/users           # Create user
PUT    /admin/users/{id}      # Update user
DELETE /admin/users/{id}      # Delete user
```

### API Usage Example

#### Register User
```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### Login
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

#### Get Profile (with token)
```bash
curl -X GET http://127.0.0.1:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

---

## 🗄️ Database Schema

### Users Table
- `id` - Primary key
- `name` - User's full name
- `email` - Unique email address
- `profile_picture` - Path to profile image (nullable)
- `is_admin` - Boolean flag for admin access
- `email_verified_at` - Email verification timestamp
- `password` - Hashed password
- `created_at` / `updated_at` - Timestamps

### Sessions Table
- Session storage for web authentication

### Personal Access Tokens Table
- Laravel Sanctum tokens for API authentication

---

## 📁 Project Structure

```
unelma-laravel-backend/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # Admin controllers
│   │   │   └── Api/                # API controllers
│   │   └── Middleware/
│   │       └── IsAdmin.php         # Admin middleware
│   └── Models/
│       └── User.php
│
├── database/
│   ├── migrations/                 # Database migrations
│   └── seeders/
│       └── AdminUserSeeder.php     # Admin user seeder
│
├── resources/
│   └── views/
│       ├── welcome.blade.php       # Landing page
│       └── admin/                  # Admin views
│
├── routes/
│   ├── web.php                     # Web routes
│   └── api.php                     # API routes
│
├── storage/
│   └── app/
│       └── public/
│           └── profile_pictures/   # Uploaded images
│
├── Documentation Files
├── COMPLETE_SYSTEM_DOCUMENTATION.md
├── ADMIN_DASHBOARD_SETUP.md
├── USER_MANAGEMENT_SETUP.md
├── PROFILE_PICTURE_FEATURE.md
├── API_AUTHENTICATION_SETUP.md
├── CUSTOMER_API_DOCUMENTATION.md
├── WELCOME_PAGE_SETUP.md
└── README.md                       # This file
```

---

## 🔧 Development

### Running Tests
```bash
php artisan test
```

### Code Formatting
```bash
./vendor/bin/pint
```

### Clear Caches
```bash
php artisan optimize:clear
```

### Database Management
```bash
# Fresh migration
php artisan migrate:fresh

# Fresh with seeding
php artisan migrate:fresh --seed

# Rollback
php artisan migrate:rollback
```

---

## 🚢 Deployment

### Production Checklist

1. **Environment Configuration**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize Application**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   composer install --optimize-autoloader --no-dev
   ```

3. **Security**
   - Change default admin password
   - Use HTTPS only
   - Set secure session cookies
   - Configure proper CORS origins

4. **Database**
   ```bash
   php artisan migrate --force
   php artisan db:seed --class=AdminUserSeeder
   ```

5. **Storage**
   ```bash
   php artisan storage:link
   chmod -R 775 storage bootstrap/cache
   ```

For detailed deployment instructions, see [COMPLETE_SYSTEM_DOCUMENTATION.md](./COMPLETE_SYSTEM_DOCUMENTATION.md#-deployment-guide)

---

## 🐛 Troubleshooting

### Common Issues

#### Storage Symlink Not Working
```bash
rm public/storage
php artisan storage:link
```

#### Permission Errors
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### CORS Issues
Update `config/cors.php` with your frontend URL

For more troubleshooting tips, see [COMPLETE_SYSTEM_DOCUMENTATION.md](./COMPLETE_SYSTEM_DOCUMENTATION.md#-troubleshooting)

---

## 📊 Features Status

| Feature | Status | Version |
|---------|--------|---------|
| Admin Authentication | ✅ Complete | 1.0 |
| User Management (CRUD) | ✅ Complete | 1.0 |
| Profile Pictures | ✅ Complete | 1.0 |
| Settings & Reports | ✅ Complete | 1.0 |
| API Authentication | ✅ Complete | 1.0 |
| Customer Self-Service API | ✅ Complete | 1.0 |
| Welcome Page | ✅ Complete | 1.0 |
| Email Verification | 🔄 Planned | 2.0 |
| Two-Factor Auth | 🔄 Planned | 2.0 |

---

## 🤝 Contributing

### Development Workflow

1. Create a feature branch
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. Make your changes and commit
   ```bash
   git add .
   git commit -m "feat: add your feature description"
   ```

3. Push to your branch
   ```bash
   git push origin feature/your-feature-name
   ```

4. Create a Pull Request

### Commit Message Convention
- `feat:` - New features
- `fix:` - Bug fixes
- `docs:` - Documentation updates
- `refactor:` - Code refactoring
- `test:` - Test updates
- `chore:` - Maintenance tasks

---

## 📝 License

This project is part of a student assignment at Business College Helsinki.

---

## 👥 Team

**React25K@Team 3**  
Software Development Team Project 2  
Business College Helsinki

---


