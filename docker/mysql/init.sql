CREATE DATABASE IF NOT EXISTS liveset
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS liveset_api
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

GRANT ALL ON liveset.* TO 'liveset'@'%';
GRANT ALL ON liveset_api.* TO 'liveset'@'%';
FLUSH PRIVILEGES;