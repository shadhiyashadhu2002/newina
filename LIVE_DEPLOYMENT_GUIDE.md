# Adding New Service Executives to Live Server

## Option 1: Using Migration (Recommended)

1. Upload the new migration file to your live server:
   ```
   database/migrations/2025_10_09_051751_add_new_service_executives.php
   ```

2. On your live server, run:
   ```bash
   php artisan migrate
   ```

## Option 2: Using PHP Script (Alternative)

1. Upload the script to your live server:
   ```
   add_live_executives.php
   ```

2. On your live server, run:
   ```bash
   php add_live_executives.php
   ```

## Option 3: Manual Database Insert (If needed)

Run these SQL queries directly in your live database:

```sql
INSERT INTO users (name, first_name, email, password, user_type, is_admin, code, gender, phone, phone2, mobile_number_1, whatsapp_number, welcome_call_completed, comments, created_by, created_at, updated_at) VALUES
('Rumsi Service', 'Rumsi', 'rumsi@service.com', '$2y$12$hash_here', 'staff', 0, 'STAFF008', 'Female', '9876543218', '9876543218', '9876543218', '9876543218', 0, 'Service Executive', 1, NOW(), NOW()),
('Thasni Service', 'Thasni', 'thasni@service.com', '$2y$12$hash_here', 'staff', 0, 'STAFF009', 'Female', '9876543219', '9876543219', '9876543219', '9876543219', 0, 'Service Executive', 1, NOW(), NOW()),
('Thashfeeha Service', 'Thashfeeha', 'thashfeeha@service.com', '$2y$12$hash_here', 'staff', 0, 'STAFF010', 'Female', '9876543220', '9876543220', '9876543220', '9876543220', 0, 'Service Executive', 1, NOW(), NOW()),
('Mufeeda Service', 'Mufeeda', 'mufeeda@service.com', '$2y$12$hash_here', 'staff', 0, 'STAFF011', 'Female', '9876543221', '9876543221', '9876543221', '9876543221', 0, 'Service Executive', 1, NOW(), NOW());
```

## Verification

After adding the executives, verify they appear by:

1. Checking the database:
   ```sql
   SELECT first_name, email FROM users WHERE user_type = 'staff' ORDER BY first_name;
   ```

2. Testing the dropdown in the application

## Login Credentials for New Executives:

- **Rumsi**: rumsi@service.com / rumsi123
- **Thasni**: thasni@service.com / thasni123  
- **Thashfeeha**: thashfeeha@service.com / thashfeeha123
- **Mufeeda**: mufeeda@service.com / mufeeda123