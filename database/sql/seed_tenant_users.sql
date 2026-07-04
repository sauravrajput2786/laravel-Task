-- ============================================================
-- SAMPLE DATA: tenant `users` table (run once per tenant DB)
--
-- NOTE: `password` must be a bcrypt hash produced by Laravel's Hash
-- facade, not plain text. Prefer:
--   php artisan tenants:seed
-- which creates these exact three demo users with the password
-- "Password@123" (bcrypt-hashed) across the three tenant databases.
-- ============================================================

-- Run against tenant_ibm:
INSERT INTO `users` (`name`, `email`, `password`, `email_verified_at`, `created_at`, `updated_at`)
VALUES ('IBM Demo User', 'ibmuser@gmail.com', '$2y$12$REPLACE_WITH_BCRYPT_HASH', NOW(), NOW(), NOW());

-- Run against tenant_hcl:
INSERT INTO `users` (`name`, `email`, `password`, `email_verified_at`, `created_at`, `updated_at`)
VALUES ('HCL Demo User', 'hcluser@gmail.com', '$2y$12$REPLACE_WITH_BCRYPT_HASH', NOW(), NOW(), NOW());

-- Run against tenant_infosys:
INSERT INTO `users` (`name`, `email`, `password`, `email_verified_at`, `created_at`, `updated_at`)
VALUES ('Infosys Demo User', 'infyuser@gmail.com', '$2y$12$REPLACE_WITH_BCRYPT_HASH', NOW(), NOW(), NOW());
