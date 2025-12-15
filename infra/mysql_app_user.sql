CREATE DATABASE IF NOT EXISTS dbSS
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'userSS'@'%' IDENTIFIED WITH mysql_native_password BY 'pass';
ALTER USER 'userSS'@'%' IDENTIFIED WITH mysql_native_password BY 'pass';
GRANT ALL PRIVILEGES ON dbSS.* TO 'userSS'@'%';
FLUSH PRIVILEGES;
