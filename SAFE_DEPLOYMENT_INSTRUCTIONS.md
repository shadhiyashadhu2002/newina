# LIVE SERVER DEPLOYMENT - SAFE METHOD

## Step 1: Clean up failed migration (if needed)

Since the migration failed, you might need to rollback:

```bash
# Check migration status
php artisan migrate:status

# If the migration shows as failed, rollback the last batch
php artisan migrate:rollback --step=1

# Or mark it as not run
# DELETE FROM migrations WHERE migration = '2025_10_09_051751_add_new_service_executives';
```

## Step 2: Use the Safe Script (RECOMMENDED)

Upload `add_executives_safe.php` to your live server and run:

```bash
php add_executives_safe.php
```

This script:
- ✅ Automatically finds available staff codes
- ✅ Checks for existing users by email AND name
- ✅ Handles duplicate prevention
- ✅ Shows detailed progress and verification
- ✅ Lists all staff members for confirmation

## Step 3: Verify Results

After running the script, you should see:
- ✅ Ramsi added with available staff code
- ✅ Thasni added with available staff code  
- ✅ Thashfeeha added with available staff code
- ✅ Mufeeda added with available staff code

## Step 4: Test the Dropdown

1. Clear cache: `php artisan cache:clear`
2. Go to Add New Service page
3. Check Service Executive dropdown
4. Verify all 4 new names appear

## Troubleshooting

If executives still don't appear:
1. Check database: `SELECT first_name FROM users WHERE user_type = 'staff' ORDER BY first_name;`
2. Clear cache: `php artisan cache:clear && php artisan view:clear`
3. Check browser cache (hard refresh)

## Login Credentials

- **Ramsi**: ramsi@service.com / ramsi123
- **Thasni**: thasni@service.com / thasni123  
- **Thashfeeha**: thashfeeha@service.com / thashfeeha123
- **Mufeeda**: mufeeda@service.com / mufeeda123