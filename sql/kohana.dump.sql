--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: delete_user(integer); Type: FUNCTION; Schema: public; Owner: denis
--

CREATE FUNCTION delete_user(user_id integer) RETURNS void
    LANGUAGE plpgsql
    AS $$BEGIN
    IF user_id<>0 THEN
        DELETE FROM users WHERE id=user_id;
    END IF;
END;$$;


ALTER FUNCTION public.delete_user(user_id integer) OWNER TO denis;

--
-- Name: FUNCTION delete_user(user_id integer); Type: COMMENT; Schema: public; Owner: denis
--

COMMENT ON FUNCTION delete_user(user_id integer) IS 'Deletes user by given id';


--
-- Name: save_user(integer, character varying, character varying, character varying, character varying, character varying, character varying, character varying); Type: FUNCTION; Schema: public; Owner: denis
--

CREATE FUNCTION save_user(user_id integer, user_name character varying, user_surname character varying, user_email character varying, user_personal_code character varying, user_address character varying, user_country character varying, user_city character varying, OUT record_id integer) RETURNS integer
    LANGUAGE plpgsql
    AS $$BEGIN
    IF user_name='' THEN
        RAISE EXCEPTION 'Name could not be empty' USING HINT = 'users_name_not_empty';
    END IF;

    IF LENGTH(user_name) < 2 THEN
        RAISE EXCEPTION 'Minimum length of name are 2 characters' USING HINT = 'users_name_min_length';
    END IF;
    
    IF LENGTH(user_name) > 50 THEN
        RAISE EXCEPTION 'Maximum length of name are 50 characters' USING HINT = 'users_name_max_length';
    END IF;

    IF user_name ~ E'[^A-Za-z0-9_-]+' THEN
        RAISE EXCEPTION 'Name should contain only alphanumerical, hyphen and undescore characters' USING HINT = 'users_name_alpha_dash';
    END IF;

    IF user_surname='' THEN
        RAISE EXCEPTION 'Surname could not be empty' USING HINT = 'users_surname_not_empty';
    END IF;

    IF LENGTH(user_surname) < 2 THEN
        RAISE EXCEPTION 'Minimum length of surname are 2 characters' USING HINT = 'users_surname_min_length';
    END IF;
    
    IF LENGTH(user_surname) > 50 THEN
        RAISE EXCEPTION 'Maximum length of surname are 50 characters' USING HINT = 'users_surname_max_length';
    END IF;

    IF user_surname ~ E'[^A-Za-z0-9_-]+' THEN
        RAISE EXCEPTION 'Surname should contain only alphanumerical, hyphen and undescore characters' USING HINT = 'users_surname_alpha_dash';
    END IF;

    IF user_email='' THEN
        RAISE EXCEPTION 'Email could not be empty' USING HINT = 'users_email_not_empty';
    END IF;

    IF user_id=0 THEN
        PERFORM id FROM users WHERE email=user_email;
        IF FOUND THEN
            RAISE EXCEPTION 'Such email is already exist' USING HINT = 'users_email_unique_violation';
        END IF;
    ELSE
        PERFORM id FROM users WHERE id<>user_id AND email=user_email;
        IF FOUND THEN
            RAISE EXCEPTION 'Such email is already exist' USING HINT = 'users_email_unique_violation';
        END IF;
    END IF;

    IF LENGTH(user_email) < 4 THEN
        RAISE EXCEPTION 'Minimum length of email are 4 characters' USING HINT = 'users_email_min_length';
    END IF;
    
    IF LENGTH(user_email) > 100 THEN
        RAISE EXCEPTION 'Maximum length of email are 100 characters' USING HINT = 'users_email_max_length';
    END IF;

    IF NOT user_email ~ E'[A-Za-z0-9._-]+@[A-Za-z0-9.-]+[.][A-Za-z]{2,4}' THEN
        RAISE EXCEPTION 'Please specify valid email' USING HINT = 'users_email_email';
    END IF;
IF user_personal_code<>'' AND LENGTH(user_personal_code)<>32 THEN
        RAISE EXCEPTION 'Personal code length should be exact 32 characters' USING HINT = 'users_personal_code_exact_length';
    END IF;

    IF user_personal_code<>'' AND user_personal_code ~ E'[^A-Za-z0-9]+' THEN
        RAISE EXCEPTION 'Personal code should contain only alphabetical with hyphens' USING HINT = 'users_personal_code_alpha_numeric';
    END IF;

    IF LENGTH(user_address) > 100 THEN
        RAISE EXCEPTION 'Maximum length of address are 100 characters' USING HINT = 'users_address_max_length';
    END IF;

    IF user_address ~ E'[^ .,A-Za-z0-9_-]+' THEN
        RAISE EXCEPTION 'Address should contain only alphanumerical, underscore, hyphen, point or comma characters';
    END IF;

    IF user_country='' THEN
        RAISE EXCEPTION 'Country could not be empty' USING HINT = 'users_country_not_empty';
    END IF;

    IF LENGTH(user_country) > 30 THEN
        RAISE EXCEPTION 'Maximum length of country are 30 characters' USING HINT = 'users_country_max_length';
    END IF;

    IF user_country ~ E'[^A-Za-z]+' THEN
        RAISE EXCEPTION 'Country should contain only alphabetical characters' USING HINT = 'users_country_alpha';
    END IF;

    IF user_city='' THEN
        RAISE EXCEPTION 'City could not be empty' USING HINT = 'users_city_not_empty';
    END IF;

    IF LENGTH(user_city) > 30 THEN
        RAISE EXCEPTION 'Maximum length of city are 30 characters' USING HINT = 'users_city_max_length';
    END IF;

    IF user_city ~ E'[^A-Za-z]+' THEN
        RAISE EXCEPTION 'City should contain only alphabetical characters' USING HINT = 'users_city_alpha';
    END IF;
record_id:=user_id;

    IF LENGTH(user_personal_code)=0 THEN
        user_personal_code:=MD5(CONCAT_WS('-', user_name, user_surname, user_email, user_address, user_country, user_city));
    END IF;

    IF record_id=0 THEN
        INSERT INTO users (name, surname, email, personal_code, address, country, city) VALUES (user_name, user_surname, user_email, user_personal_code, user_address, user_country, user_city);

        EXECUTE 'SELECT LASTVAL()'
        INTO record_id;
    ELSE
        UPDATE users SET name=user_name, surname=user_surname, email=user_email, personal_code=user_personal_code, address=user_address, country=user_country, city=user_city WHERE id=record_id;
    END IF;
END;$$;


ALTER FUNCTION public.save_user(user_id integer, user_name character varying, user_surname character varying, user_email character varying, user_personal_code character varying, user_address character varying, user_country character varying, user_city character varying, OUT record_id integer) OWNER TO denis;

--
-- Name: FUNCTION save_user(user_id integer, user_name character varying, user_surname character varying, user_email character varying, user_personal_code character varying, user_address character varying, user_country character varying, user_city character varying, OUT record_id integer); Type: COMMENT; Schema: public; Owner: denis
--

COMMENT ON FUNCTION save_user(user_id integer, user_name character varying, user_surname character varying, user_email character varying, user_personal_code character varying, user_address character varying, user_country character varying, user_city character varying, OUT record_id integer) IS 'Saves user with given data';


--
-- Name: update_timestamp(); Type: FUNCTION; Schema: public; Owner: denis
--

CREATE FUNCTION update_timestamp() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.update_timestamp() OWNER TO denis;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: users; Type: TABLE; Schema: public; Owner: denis; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    surname character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    personal_code character varying(32) NOT NULL,
    address character varying(100) DEFAULT ''::character varying NOT NULL,
    country character varying(30) NOT NULL,
    city character varying(30) NOT NULL,
    created_at timestamp with time zone DEFAULT now() NOT NULL,
    updated_at timestamp with time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.users OWNER TO denis;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: denis
--

CREATE SEQUENCE user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_id_seq OWNER TO denis;

--
-- Name: user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: denis
--

ALTER SEQUENCE user_id_seq OWNED BY users.id;


--
-- Name: v_members; Type: VIEW; Schema: public; Owner: denis
--

CREATE VIEW v_members AS
    SELECT users.id, users.name, users.surname, users.email, users.personal_code, users.address, users.country, users.city, users.created_at, users.updated_at FROM users;


ALTER TABLE public.v_members OWNER TO denis;

--
-- Name: id; Type: DEFAULT; Schema: public; Owner: denis
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('user_id_seq'::regclass);


--
-- Name: country_city_idx; Type: INDEX; Schema: public; Owner: denis; Tablespace: 
--

CREATE INDEX country_city_idx ON users USING btree (country, city);


--
-- Name: created_at_idx; Type: INDEX; Schema: public; Owner: denis; Tablespace: 
--

CREATE INDEX created_at_idx ON users USING btree (created_at);


--
-- Name: email_unq_idx; Type: INDEX; Schema: public; Owner: denis; Tablespace: 
--

CREATE UNIQUE INDEX email_unq_idx ON users USING btree (email);


--
-- Name: name_surname_idx; Type: INDEX; Schema: public; Owner: denis; Tablespace: 
--

CREATE INDEX name_surname_idx ON users USING btree (name, surname);


--
-- Name: pcode_unq_idx; Type: INDEX; Schema: public; Owner: denis; Tablespace: 
--

CREATE UNIQUE INDEX pcode_unq_idx ON users USING btree (personal_code);


--
-- Name: updated_at_idx; Type: INDEX; Schema: public; Owner: denis; Tablespace: 
--

CREATE INDEX updated_at_idx ON users USING btree (updated_at);


--
-- Name: set_update_action_time; Type: TRIGGER; Schema: public; Owner: denis
--

CREATE TRIGGER set_update_action_time BEFORE UPDATE ON users FOR EACH ROW EXECUTE PROCEDURE update_timestamp();


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

