# Multi-Tenant Domain Management Guide

## System Overview

This Laravel application now supports multi-tenancy where you can:
- Manage multiple shops under one main domain
- Create subdomains for each shop (e.g., shop1.yourdomain.com, shop2.yourdomain.com)
- Isolate data between tenants
- Manage everything from a super admin dashboard

## Access Credentials

### Super Admin Account
- **URL:** http://localhost:8080/super-admin/dashboard
- **Email:** admin@gnify.com
- **Password:** password

### Demo Tenant
- **Tenant:** Demo Shop
- **Domain:** demo.localhost (configured but not accessible via browser due to localhost limitations)

## How to Use

### 1. Access Super Admin Dashboard
1. Go to http://localhost:8080/login
2. Login with admin@gnify.com / password
3. Navigate to http://localhost:8080/super-admin/dashboard

### 2. Create a New Tenant (Shop)
1. In super admin dashboard, click "Tenants" in sidebar
2. Click "Create Tenant" button
3. Fill in tenant details:
   - **Name:** Your Shop Name
   - **Slug:** shop-name (used for subdomain)
   - **Plan:** basic/pro/enterprise
   - **Status:** active

### 3. Add Domain/Subdomain to Tenant
1. Go to "Domains" in super admin sidebar
2. Click "Create Domain" 
3. Configure domain:
   - **Tenant:** Select the tenant you created
   - **Domain:** yourdomain.com
   - **Subdomain:** shop1 (creates shop1.yourdomain.com)
   - **Is Primary:** Yes
   - **Status:** active

### 4. DNS Configuration (Production)
For production use, configure DNS:
```
*.yourdomain.com CNAME yourdomain.com
```
or
```
shop1.yourdomain.com A [Your Server IP]
shop2.yourdomain.com A [Your Server IP]
```

## Architecture Features

### Data Isolation
- Each tenant has completely isolated data
- Products, categories, orders, users are tenant-specific
- Tenant resolution happens automatically via domain/subdomain

### Domain Resolution Flow
1. User visits `shop1.yourdomain.com`
2. `TenantMiddleware` resolves tenant from domain
3. Application sets tenant context
4. All database queries are scoped to that tenant

### Super Admin Capabilities
- View all tenants and their statistics
- Create/edit/delete tenants
- Manage domain assignments
- Monitor system-wide metrics
- Access any tenant's data

## File Structure

### Models
- `app/Models/Tenant.php` - Tenant management
- `app/Models/Domain.php` - Domain/subdomain management
- Updated existing models with tenant relationships

### Controllers
- `app/Http/Controllers/SuperAdmin/DashboardController.php`
- `app/Http/Controllers/SuperAdmin/TenantController.php`
- `app/Http/Controllers/SuperAdmin/DomainController.php`

### Middleware
- `app/Http/Middleware/SuperAdminMiddleware.php` - Protects super admin routes
- `app/Http/Middleware/TenantMiddleware.php` - Resolves tenant from domain

### Views
- `resources/views/super-admin/` - Super admin interface
- `resources/views/layouts/super-admin.blade.php` - Admin layout

## Testing Locally

Since localhost doesn't support real subdomains, you can:

1. **Test with host file entries:**
   ```
   127.0.0.1 shop1.localhost
   127.0.0.1 shop2.localhost
   ```

2. **Use ngrok for external domains:**
   ```bash
   ngrok http 8080
   # Then configure domains with the ngrok URL
   ```

## Production Deployment

1. Set up wildcard SSL certificate for `*.yourdomain.com`
2. Configure web server (nginx/apache) to handle wildcard subdomains
3. Set up DNS wildcards
4. Update environment variables for production database
5. Set up automated tenant provisioning if needed

## Security Features

- Super admin routes protected by middleware
- Data isolation between tenants
- Domain verification system
- Status-based tenant access control

## Next Steps

1. **Billing Integration:** Add subscription management
2. **Automated Provisioning:** API for creating tenants programmatically  
3. **Tenant Admin Panels:** Individual admin interfaces for each tenant
4. **Custom Themes:** Allow tenants to customize their shop appearance
5. **Analytics:** Tenant-specific analytics and reporting