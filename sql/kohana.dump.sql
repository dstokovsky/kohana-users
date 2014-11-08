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

CREATE OR REPLACE FUNCTION save_user(IN user_id integer, IN user_name varchar(50), IN user_surname varchar(50), IN user_email varchar(100), 
IN user_personal_code varchar(32), IN user_address varchar(100), IN user_country varchar(30), IN user_city varchar(30), 
OUT record_id integer) RETURNS integer AS
$BODY$BEGIN
    record_id:=user_id;

    IF length(user_personal_code)=0 THEN
        user_personal_code:=MD5(CONCAT_WS('-', user_name, user_surname, user_email, user_address, user_country, user_city));
    END IF;

    IF record_id=0 THEN
        INSERT INTO users (name, surname, email, personal_code, address, country, city) VALUES (user_name, user_surname, user_email, user_personal_code, user_address, user_country, user_city);

        EXECUTE 'SELECT LASTVAL()'
        INTO record_id;
    ELSE
        UPDATE users SET name=user_name, surname=user_surname, email=user_email, personal_code=user_personal_code, address=user_address, country=user_country, city=user_city WHERE id=record_id;
    END IF;
END;$BODY$
LANGUAGE plpgsql VOLATILE NOT LEAKPROOF;
COMMENT ON FUNCTION public.save_user(IN integer, IN varchar(50), IN varchar(50), IN varchar(100), IN varchar(32), IN varchar(100), IN varchar(30), IN varchar(30), OUT integer) IS 'Saves user with given data';

CREATE OR REPLACE FUNCTION delete_user(IN user_id integer) RETURNS void AS
$BODY$BEGIN
    IF user_id<>0 THEN
        DELETE FROM users WHERE id=user_id;
    END IF;
END;$BODY$
LANGUAGE plpgsql VOLATILE NOT LEAKPROOF;
COMMENT ON FUNCTION public.delete_user(IN integer) IS 'Deletes user by given id';