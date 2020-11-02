CREATE SCHEMA IF NOT EXISTS shop;

-- DROP TABLE shop.user
CREATE TABLE IF NOT EXISTS shop.user
(
  id            SERIAL PRIMARY KEY,
  username      TEXT(32)  NOT NULL,
  password_hash TEXT      NOT NULL,
  last_login    TIMESTAMP NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- DROP TABLE shop.user_contact_data
CREATE TABLE IF NOT EXISTS shop.user_contact_data
(
  id            SERIAL PRIMARY KEY,
  user_id       BIGINT UNSIGNED   NOT NULL,
  email         TEXT(256)         NOT NULL,
  phone_number  TEXT(32),
  street_name   TEXT              NOT NULL,
  street_number SMALLINT UNSIGNED NOT NULL,
  flat_number   SMALLINT UNSIGNED,
  city          TEXT              NOT NULL,
  province      TEXT              NOT NULL,
  postal_code   TEXT(32)          NOT NULL,
  country       TEXT              NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (user_id) REFERENCES shop.user (id)
);

-- DROP TABLE shop.user_contact_data
CREATE TABLE IF NOT EXISTS shop.shopping_cart
(
  id         SERIAL PRIMARY KEY,
  user_id    BIGINT UNSIGNED NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (user_id) REFERENCES shop.user (id)
);

-- DROP TABLE shop.shop_contact_data
CREATE TABLE IF NOT EXISTS shop.shop_contact_data
(
  id            SERIAL PRIMARY KEY,
  active        BOOL              NOT NULL,
  email         TEXT(256)         NOT NULL,
  phone_number  TEXT(32)          NOT NULL,
  street_name   TEXT              NOT NULL,
  street_number SMALLINT UNSIGNED NOT NULL,
  flat_number   SMALLINT UNSIGNED,
  city          TEXT              NOT NULL,
  province      TEXT              NOT NULL,
  postal_code   TEXT(32)          NOT NULL,
  country       TEXT              NOT NULL,
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- DROP TABLE shop.system_settings
CREATE TABLE IF NOT EXISTS shop.system_settings
(
  id          SERIAL PRIMARY KEY,
  setting_key TEXT NOT NULL,
  value       TEXT NOT NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- DROP TABLE shop.transaction_details
CREATE TABLE IF NOT EXISTS shop.transaction_details
(
  id                 SERIAL,
  first_name         TEXT(64)  NOT NULL,
  last_name          TEXT(64)  NOT NULL,
  email              TEXT(256),
  credit_card_number TEXT(32),
  finalized_at       TIMESTAMP NOT NULL,
  created_at         TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- DROP TABLE shop.transaction
CREATE TABLE IF NOT EXISTS shop.transaction
(
  id                     SERIAL,
  shopping_cart_id       BIGINT UNSIGNED,
  transaction_details_id BIGINT UNSIGNED NOT NULL,
  payment_type           ENUM ('physical', 'credit_card', 'paypal','payu'),
  payment_reference      TEXT,
  created_at             TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (transaction_details_id) REFERENCES shop.transaction_details (id),
  FOREIGN KEY (shopping_cart_id) REFERENCES shop.shopping_cart (id)
);

-- DROP TABLE shop.product_category
CREATE TABLE IF NOT EXISTS shop.product_category
(
  id        SERIAL,
  parent_id BIGINT UNSIGNED,
  name      TEXT(32) NOT NULL,

  FOREIGN KEY (parent_id) REFERENCES shop.product_category (id)
);

-- DROP TABLE shop.product
CREATE TABLE IF NOT EXISTS shop.product
(
  id             SERIAL,
  name           TEXT                    NOT NULL,
  catalog_number TEXT,
  price          DECIMAL(15, 2) UNSIGNED NOT NULL,
  category_id    BIGINT UNSIGNED         NOT NULL,
  created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (category_id) REFERENCES shop.product_category (id)
);

-- DROP TABLE shop.shopping_cart_product
CREATE TABLE IF NOT EXISTS shop.shopping_cart_product
(
  id               SERIAL,
  shopping_cart_id BIGINT UNSIGNED NOT NULL,
  product_id       BIGINT UNSIGNED NOT NULL,
  quantity         BIGINT UNSIGNED NOT NULL,
  created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  FOREIGN KEY (shopping_cart_id) REFERENCES shop.shopping_cart (id),
  FOREIGN KEY (product_id) REFERENCES shop.product (id)
);