# Unelma Backend - Quick Reference Guide

**Last Updated**: October 28, 2025  
**Version**: 1.0

---

## 🚀 Quick Commands

### Setup
```bash
# Install dependencies
composer install && npm install

# Environment setup
cp .env.example .env && php artisan key:generate

# Database
php artisan migrate
php artisan db:seed --class=AdminUserSeeder
php artisan db:seed --class=PageSeeder
php artisan storage:link

# Start server
php artisan serve
```

### Development
```bash
# Clear all caches
php artisan optimize:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database refresh
php artisan migrate:fresh --seed

# Code formatting
./vendor/bin/pint

# Run tests
php artisan test
```

---

## 🔑 Default Credentials

### Admin Account
```
Email: admin@example.com
Password: password
```

---

## 🌐 URLs

### Development
```
Welcome Page:     http://127.0.0.1:8000
Admin Login:      http://127.0.0.1:8000/admin/login
Admin Dashboard:  http://127.0.0.1:8000/admin/dashboard
API Base:         http://127.0.0.1:8000/api
```

---

## 📡 API Endpoints Cheat Sheet

### Public (No Auth)
```bash
# Register
POST /api/register
Body: {name, email, password, password_confirmation}

# Login
POST /api/login
Body: {email, password}
Response: {token, user}
```

### Protected (Bearer Token Required)
```bash
# Get user
GET /api/user
Header: Authorization: Bearer {token}

# Get profile
GET /api/profile
Header: Authorization: Bearer {token}

# Update profile
PUT /api/profile
Header: Authorization: Bearer {token}
Body: {name, email}

# Change password
POST /api/profile/change-password
Header: Authorization: Bearer {token}
Body: {current_password, password, password_confirmation}

# Delete account
DELETE /api/profile
Header: Authorization: Bearer {token}
Body: {password}

# Logout
POST /api/logout
Header: Authorization: Bearer {token}
```

---

## 🔐 Admin Routes

### Authentication
```
GET  /admin/login          Show login form
POST /admin/login          Login
POST /admin/logout         Logout
```

### Dashboard
```
GET /admin/dashboard       Admin dashboard
```

### User Management
```
GET    /admin/users               List users
GET    /admin/users/create        Create form
POST   /admin/users               Store user
GET    /admin/users/{id}/edit     Edit form
PUT    /admin/users/{id}          Update user
DELETE /admin/users/{id}          Delete user
```

### Settings
```
GET  /admin/settings            Show settings page
PUT  /admin/settings            Update settings
```

### Reports
```
GET  /admin/reports             Show reports dashboard
GET  /admin/reports/export      Export reports (future)
```

### Content Management (Pages)
```
GET    /admin/pages               List all pages
GET    /admin/pages/create        Create page form
POST   /admin/pages               Store new page
GET    /admin/pages/{page}/edit   Edit page form
PUT    /admin/pages/{page}        Update page
DELETE /admin/pages/{page}        Delete page
```

---

## 📁 Important Files

### Controllers
```
app/Http/Controllers/Admin/AuthController.php
app/Http/Controllers/Admin/DashboardController.php
app/Http/Controllers/Admin/UserController.php
app/Http/Controllers/Admin/PageController.php
app/Http/Controllers/Admin/SettingsController.php
app/Http/Controllers/Admin/ReportsController.php
app/Http/Controllers/Api/AuthController.php
app/Http/Controllers/Api/UserProfileController.php
```

### Models
```
app/Models/User.php
app/Models/Page.php
```

### Middleware
```
app/Http/Middleware/IsAdmin.php
```

### Routes
```
routes/web.php              Web routes (admin)
routes/api.php              API routes
```

### Views
```
resources/views/welcome.blade.php
resources/views/admin/auth/login.blade.php
resources/views/admin/dashboard.blade.php
resources/views/admin/users/index.blade.php
resources/views/admin/users/create.blade.php
resources/views/admin/users/edit.blade.php
```

### Config
```
config/cors.php             CORS settings
config/sanctum.php          Sanctum config
config/session.php          Session config
```

---

## 🗄️ Database Tables

```
users                       User accounts
  - id, name, email, profile_picture, is_admin, password

sessions                    Session storage

personal_access_tokens      API tokens (Sanctum)

cache                       Cache storage

jobs                        Queue jobs
```

---

## 🔧 Environment Variables

### Essential Settings
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database

SANCTUM_STATEFUL_DOMAINS=localhost:3000,localhost:5173,127.0.0.1:3000,127.0.0.1:5173
```

---

## 🛠️ Common Tasks

### Create Admin User
```bash
php artisan db:seed --class=AdminUserSeeder
```

### Fix Storage Link
```bash
rm public/storage
php artisan storage:link
```

### Fix Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### View Routes
```bash
php artisan route:list
php artisan route:list --path=api
php artisan route:list --path=admin
```

### Database Info
```bash
php artisan db:show
php artisan db:table users
php artisan migrate:status
```

### Debug
```bash
php artisan tinker
tail -f storage/logs/laravel.log
```

---

## 📝 Validation Rules

### User Registration/Creation
```php
'name' => ['required', 'string', 'max:255']
'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
'password' => ['required', 'confirmed', Password::defaults()]
'is_admin' => ['boolean']
'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
```

### Profile Update
```php
'name' => ['required', 'string', 'max:255']
'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,{id}']
```

### Password Change
```php
'current_password' => ['required', 'string']
'password' => ['required', 'confirmed', Password::defaults()]
```

---

## 🔍 Testing Examples

### cURL Examples

#### Register User
```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

#### Login
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

#### Get Profile
```bash
curl -X GET http://127.0.0.1:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

#### Update Profile
```bash
curl -X PUT http://127.0.0.1:8000/api/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Updated Name",
    "email": "newemail@example.com"
  }'
```

---

## 🐛 Quick Fixes

### "Table not found" Error
```bash
php artisan migrate
```

### "Storage symlink failed"
```bash
rm public/storage
php artisan storage:link
```

### "419 Page Expired"
```bash
php artisan cache:clear
php artisan config:clear
```

### "CORS Error"
Update `config/cors.php`:
```php
'allowed_origins' => ['http://localhost:3000'],
'supports_credentials' => true,
```

### "Token Mismatch"
```bash
php artisan config:clear
php artisan key:generate
```

### "Permission Denied"
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 📦 Production Deployment

### Quick Checklist
```bash
# 1. Environment
APP_ENV=production
APP_DEBUG=false

# 2. Optimize
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Security
# Change admin password
# Use HTTPS
# Set secure cookies

# 4. Database
php artisan migrate --force
php artisan db:seed --class=AdminUserSeeder

# 5. Storage
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

---

## 🔗 Documentation Links

- **[README.md](./README.md)** - Project overview
- **[COMPLETE_SYSTEM_DOCUMENTATION.md](./COMPLETE_SYSTEM_DOCUMENTATION.md)** - Full system docs
- **[ADMIN_DASHBOARD_SETUP.md](./ADMIN_DASHBOARD_SETUP.md)** - Admin setup
- **[USER_MANAGEMENT_SETUP.md](./USER_MANAGEMENT_SETUP.md)** - User CRUD
- **[PROFILE_PICTURE_FEATURE.md](./PROFILE_PICTURE_FEATURE.md)** - Image uploads
- **[API_AUTHENTICATION_SETUP.md](./API_AUTHENTICATION_SETUP.md)** - API auth
- **[CUSTOMER_API_DOCUMENTATION.md](./CUSTOMER_API_DOCUMENTATION.md)** - Customer API
- **[WELCOME_PAGE_SETUP.md](./WELCOME_PAGE_SETUP.md)** - Landing page

---

## 💡 Pro Tips

### Use Artisan Tinker for Quick Tests
```bash
php artisan tinker

# Get user count
>>> User::count();

# Find user by email
>>> User::where('email', 'admin@example.com')->first();

# Create user
>>> User::create(['name' => 'Test', 'email' => 'test@test.com', 'password' => Hash::make('password')]);
```

### Watch Logs in Real-Time
```bash
tail -f storage/logs/laravel.log
```

### Quick Database Inspection
```bash
php artisan db:show
php artisan db:table users
```

### Clear Everything
```bash
php artisan optimize:clear
```

### List All Routes
```bash
php artisan route:list
```

---

## ⚡ Keyboard Shortcuts (Tinker)

```bash
php artisan tinker

# Use class
>>> use App\Models\User;

# Arrow keys - command history
# Ctrl+C - exit
# Ctrl+L - clear screen
```

---

## 📊 Status Codes

### Success
- `200` - OK
- `201` - Created
- `204` - No Content

### Client Errors
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `419` - CSRF Token Mismatch
- `422` - Validation Error

### Server Errors
- `500` - Internal Server Error
- `503` - Service Unavailable

---

## 🎯 Feature Flags

| Feature | Status | Location |
|---------|--------|----------|
| Admin Auth | ✅ | `/admin/login` |
| User CRUD | ✅ | `/admin/users` |
| Profile Pics | ✅ | User forms |
| API Auth | ✅ | `/api/*` |
| Customer API | ✅ | `/api/profile` |
| Welcome Page | ✅ | `/` |

---

**Quick Reference v1.0** - For detailed information, see the full documentation files.

