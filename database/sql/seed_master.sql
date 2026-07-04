-- ============================================================
-- SAMPLE DATA: master.clients + master.client_users
--
-- NOTE: `clients.db_password` is stored using Laravel's `encrypted`
-- Eloquent cast (AES-256-CBC, tied to your app's APP_KEY). A value
-- inserted here in plain text will NOT be usable by the app as-is.
-- Prefer `php artisan db:seed` (database/seeders/ClientSeeder.php),
-- which lets the model cast handle encryption for you.
--
-- This file documents the expected row shape for reference only.
-- ============================================================

INSERT INTO `clients`
    (`client_name`, `client_code`, `db_server`, `db_port`, `db_name`, `db_user`, `db_password`, `created_at`, `updated_at`)
VALUES
    ('IBM',              'IBM',  '127.0.0.1', 3306, 'tenant_ibm',     'root', 'ENCRYPTED_PASSWORD_HERE', NOW(), NOW()),
    ('HCL Technologies', 'HCL',  '127.0.0.1', 3306, 'tenant_hcl',     'root', 'ENCRYPTED_PASSWORD_HERE', NOW(), NOW()),
    ('Infosys',          'INFY', '127.0.0.1', 3306, 'tenant_infosys', 'root', 'ENCRYPTED_PASSWORD_HERE', NOW(), NOW());

INSERT INTO `client_users` (`email`, `client_code`, `created_at`, `updated_at`)
VALUES
    ('ibmuser@gmail.com',  'IBM',  NOW(), NOW()),
    ('hcluser@gmail.com',  'HCL',  NOW(), NOW()),
    ('infyuser@gmail.com', 'INFY', NOW(), NOW());
