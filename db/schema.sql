DROP TABLE IF EXISTS stat CASCADE;
DROP TABLE IF EXISTS constellation CASCADE;
DROP TABLE IF EXISTS site_user CASCADE;
DROP TABLE IF EXISTS picture CASCADE;
DROP TABLE IF EXISTS sky_guess_pair CASCADE;

CREATE TABLE IF NOT EXISTS picture (
    id SERIAL PRIMARY KEY,
    data BYTEA NOT NULL,
    mime_type TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS site_user (
    id SERIAL PRIMARY KEY,
    username TEXT UNIQUE NOT NULL,
    email TEXT UNIQUE NOT NULL,
    password_hash TEXT NOT NULL,
    privilege TEXT NOT NULL CHECK (privilege IN ('user', 'admin')),
    profile_picture_id INTEGER REFERENCES picture(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS stat (
    id SERIAL PRIMARY KEY,
    str_code TEXT NOT NULL,
    user_id INTEGER NOT NULL REFERENCES site_user(id) ON DELETE CASCADE,
    total BIGINT NOT NULL DEFAULT 0 CHECK (total >= 0),
    success BIGINT NOT NULL DEFAULT 0 CHECK (success >= 0 AND success <= total),

    CONSTRAINT stat_unique_per_user UNIQUE (str_code, user_id)
);

CREATE TABLE IF NOT EXISTS constellation (
    id SERIAL PRIMARY KEY,
    "name" TEXT UNIQUE NOT NULL,
    story TEXT,
    main_star TEXT,
    hemisphere TEXT CHECK (hemisphere IN ('northern', 'southern', 'equatorial')),
    symbolism TEXT,
    header_picture_id INTEGER NOT NULL REFERENCES picture(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS sky_guess_pair (
    id SERIAL PRIMARY KEY,
    constellation_id INTEGER NOT NULL REFERENCES constellation(id) ON DELETE CASCADE,
    clean_picture_id INTEGER NOT NULL REFERENCES picture(id) ON DELETE CASCADE,
    lines_picture_id INTEGER NOT NULL REFERENCES picture(id) ON DELETE CASCADE,

    CONSTRAINT pair_unique_per_constellation UNIQUE (constellation_id, clean_picture_id, lines_picture_id)
);