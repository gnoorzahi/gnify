# Gnify Shop - Multi-Tenant E-commerce Platform

<p align="center">
  <img src="https://via.placeholder.com/400x100/4F46E5/FFFFFF?text=Gnify+Shop" alt="Gnify Shop Logo">
</p>

<p align="center">
  <strong>A powerful multi-tenant e-commerce platform built with Laravel</strong>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12.x-red" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.2-blue" alt="PHP Version">
  <img src="https://img.shields.io/badge/PostgreSQL-13-blue" alt="PostgreSQL Version">
  <img src="https://img.shields.io/badge/Redis-7-red" alt="Redis Version">
  <img src="https://img.shields.io/badge/Docker-Ready-green" alt="Docker Ready">
</p>

## 🚀 About Gnify Shop

Gnify Shop is a comprehensive multi-tenant e-commerce platform that allows you to manage multiple online stores under a single domain using subdomains. Perfect for marketplace owners, SaaS providers, or businesses managing multiple brands.

### ✨ Key Features

- **🏪 Multi-Tenant Architecture** - Create unlimited shops with isolated data
- **🌐 Domain Management** - Assign custom domains and subdomains to each shop
- **👑 Super Admin Dashboard** - Centralized management of all tenants and domains
- **🛒 Complete E-commerce** - Products, categories, cart, orders, and user management
- **🔐 Authentication System** - Laravel Breeze with role-based access control
- **📱 Responsive Design** - Mobile-friendly interface with Tailwind CSS
- **🐳 Docker Ready** - Complete containerized development environment
- **⚡ High Performance** - Redis caching and PostgreSQL database

## 🛠️ Technology Stack

- **Backend:** Laravel 12, PHP 8.2
- **Database:** PostgreSQL 13
- **Cache/Sessions:** Redis 7
- **Frontend:** Blade Templates, Tailwind CSS
- **Email Testing:** MailHog
- **Containerization:** Docker & Docker Compose

## 🚀 Quick Start

### Prerequisites

- Docker and Docker Compose
- Git

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd gnify
   ```

2. **Start the application**
   ```bash
   chmod +x setup.sh
   ./setup.sh
   ```

3. **Run migrations and seeders**
   ```bash
   docker-compose exec app php artisan migrate
   docker-compose exec app php artisan db:seed --class=SuperAdminSeeder
   docker-compose exec app php artisan db:seed --class=ShopSeeder
   ```

### 🌐 Access Points

- **Main Shop:** http://localhost:8080
- **Super Admin Dashboard:** http://localhost:8080/super-admin/dashboard
- **MailHog (Email Testing):** http://localhost:8026
- **Database:** localhost:5433 (PostgreSQL)
- **Redis:** localhost:6380

### 🔐 Default Credentials

**Super Admin Account:**
- Email: `admin@gnify.com`
- Password: `password`

## 📋 Features Overview

### 🏪 Shop Management
- Product catalog with search and filtering
- Category management with hierarchical structure
- Shopping cart with session persistence
- Order management and tracking
- User registration and authentication

### 👑 Super Admin Features
- **Dashboard:** Overview statistics and recent activity
- **Tenant Management:** Create, edit, and manage multiple shops
- **Domain Management:** Assign and verify domains/subdomains
- **User Management:** Super admin and tenant user control
- **Analytics:** System-wide metrics and tenant statistics

### 🌐 Multi-Tenant System
- **Domain Resolution:** Automatic tenant detection from domain/subdomain
- **Data Isolation:** Complete separation of tenant data
- **Flexible Domains:** Support for subdomains and custom domains
- **Status Management:** Active, inactive, and suspended tenant states

## 🏗️ Architecture

### Database Schema
```
tenants (id, name, slug, status, plan, settings...)
├── domains (tenant_id, domain, subdomain, is_primary...)
├── users (tenant_id, name, email, is_super_admin...)
├── categories (tenant_id, name, slug, parent_id...)
├── products (tenant_id, category_id, name, price...)
├── carts (tenant_id, user_id, session_id...)
└── orders (tenant_id, user_id, order_number...)
```

### Key Components
- **TenantMiddleware:** Resolves tenant from domain
- **SuperAdminMiddleware:** Protects admin routes
- **Domain Model:** Handles domain resolution and verification
- **Tenant Model:** Manages tenant lifecycle and relationships

## 🔧 Configuration

### Environment Variables
Key environment variables for multi-tenant setup:

```env
APP_NAME="Gnify Shop"
APP_URL=http://localhost:8080

DB_CONNECTION=pgsql
DB_HOST=db
DB_DATABASE=gnify_shop
DB_USERNAME=gnify_user
DB_PASSWORD=secret

REDIS_HOST=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
```

### Docker Services
- **app:** Laravel application (PHP 8.2 + Apache)
- **db:** PostgreSQL 13 database
- **redis:** Redis 7 for caching and sessions
- **mailhog:** Email testing server

## 🚦 Usage

### Creating a New Tenant Shop

1. **Login to Super Admin Dashboard**
   - Navigate to http://localhost:8080/super-admin/dashboard
   - Login with super admin credentials

2. **Create Tenant**
   - Go to "Tenants" → "Create Tenant"
   - Fill in shop details (name, slug, plan, status)

3. **Assign Domain**
   - Go to "Domains" → "Create Domain"
   - Assign domain/subdomain to the tenant
   - Mark as primary if needed

4. **Access Tenant Shop**
   - Visit the assigned domain
   - Data is automatically isolated to that tenant

### Production Deployment

For production use:

1. **DNS Configuration**
   ```
   *.yourdomain.com CNAME yourdomain.com
   ```

2. **SSL Setup**
   - Configure wildcard SSL certificate
   - Update web server configuration

3. **Environment**
   - Set proper database credentials
   - Configure Redis for production
   - Set up mail service

## 📚 API Documentation

### Domain Resolution
The system automatically resolves tenants based on the request domain:

- `shop1.yourdomain.com` → Tenant with subdomain "shop1"
- `custom-domain.com` → Tenant with custom domain
- `localhost` → Main shop (no tenant isolation)

### Tenant Isolation
All database queries are automatically scoped to the current tenant:

```php
// Automatically includes tenant_id in queries
Product::all(); // Only returns products for current tenant
User::create($data); // Automatically sets tenant_id
```

## 🧪 Testing

Run the test suite:
```bash
docker-compose exec app php artisan test
```

For local subdomain testing, add to your hosts file:
```
127.0.0.1 shop1.localhost
127.0.0.1 shop2.localhost
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new features
5. Submit a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- Check the [Multi-Tenant Guide](MULTI_TENANT_GUIDE.md)
- Review the Docker logs: `docker-compose logs app`
- Open an issue on GitHub

## 🔮 Roadmap

- [ ] Advanced tenant analytics
- [ ] Billing and subscription management
- [ ] Custom themes per tenant
- [ ] API for tenant management
- [ ] Advanced domain verification
- [ ] Multi-language support
- [ ] Advanced product features (variants, bundles)
- [ ] Integration marketplace

---

<p align="center">
  Built with ❤️ using Laravel and modern web technologies
</p>
