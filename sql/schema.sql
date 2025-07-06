DROP TABLE IF EXISTS stat CASCADE;
DROP TABLE IF EXISTS constellation CASCADE;
DROP TABLE IF EXISTS site_user CASCADE;

CREATE TABLE IF NOT EXISTS site_user (
    id SERIAL PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    privilege TEXT NOT NULL CHECK (privilege IN ('user', 'admin')),
    profile_img TEXT UNIQUE
);

CREATE TABLE IF NOT EXISTS stat (
    id SERIAL PRIMARY KEY,
    str_code TEXT NOT NULL,
    username TEXT NOT NULL REFERENCES site_user(username) ON DELETE CASCADE,
    total BIGINT NOT NULL DEFAULT 0 CHECK (total >= 0),
    success BIGINT NOT NULL DEFAULT 0 CHECK (success >= 0 AND success <= total),

    CONSTRAINT stat_unique_per_user UNIQUE (str_code, username)
);

CREATE TABLE IF NOT EXISTS constellation (
    id SERIAL PRIMARY KEY,
    "name" TEXT UNIQUE NOT NULL,
    story TEXT,
    main_star TEXT,
    hemisphere TEXT CHECK (hemisphere IN ('northern', 'southern', 'equatorial')),
    symbolism TEXT
);
