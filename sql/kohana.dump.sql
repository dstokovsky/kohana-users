 CREATE TABLE users (
    id serial,
    name varchar(50) NOT NULL,
    surname varchar(50) NOT NULL,
    email varchar(100) NOT NULL,
    personal_code varchar(32) NOT NULL,
    address varchar(100) NOT NULL DEFAULT '',
    country varchar(30) NOT NULL,
    city varchar(30) NOT NULL,
    created_at timestamp with time zone NOT NULL DEFAULT current_timestamp,
    updated_at timestamp with time zone NOT NULL DEFAULT current_timestamp
);
CREATE UNIQUE INDEX email_unq_idx ON users (email);
CREATE UNIQUE INDEX pcode_unq_idx ON users (personal_code);
CREATE INDEX name_surname_idx ON users USING btree (name, surname);
CREATE INDEX country_city_idx ON users USING btree (country, city);
CREATE INDEX created_at_idx ON users USING btree (created_at);
CREATE INDEX updated_at_idx ON users USING btree (updated_at);

CREATE OR REPLACE FUNCTION update_timestamp() RETURNS TRIGGER 
LANGUAGE plpgsql
AS
$$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$;

CREATE TRIGGER set_update_action_time
  BEFORE UPDATE
  ON users
  FOR EACH ROW
  EXECUTE PROCEDURE update_timestamp();

CREATE VIEW v_members AS
    SELECT *
    FROM users;