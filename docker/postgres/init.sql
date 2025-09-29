CREATE TYPE weapon_type AS ENUM (
    'Axe', 'Bow', 'Dagger', 'Gauntlet', 'Gun', 
    'Mace', 'Polearm', 'Staff', 'Sword', 'Wand', 'Whip'
);

CREATE TYPE item_tag AS ENUM (
    'Legend', 'Adventure Coins', 'Rare', 'Pseudo Rare', 'Seasonal', 'Special Offer'
);

CREATE TABLE IF NOT EXISTS weapons (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    hash TEXT NOT NULL,
    description TEXT NOT NULL,
    type weapon_type NOT NULL,
    registered_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS weapon_tags (
    weapon_id INT NOT NULL,
    tag item_tag NOT NULL,
    PRIMARY KEY (weapon_id, tag),
    FOREIGN KEY (weapon_id) REFERENCES weapons(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS players (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    level INT NOT NULL,
    registered_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS players_mined (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    mined_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS armors (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    hash TEXT NOT NULL,
    description TEXT NOT NULL,
    registered_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS armor_tags (
    armor_id INT NOT NULL,
    tag item_tag NOT NULL,
    PRIMARY KEY (armor_id, tag),
    FOREIGN KEY (armor_id) REFERENCES armors(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS capes (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    hash TEXT NOT NULL,
    description TEXT NOT NULL,
    can_access_bank BOOLEAN NOT NULL,
    registered_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS cape_tags (
    cape_id INT NOT NULL,
    tag item_tag NOT NULL,
    PRIMARY KEY (cape_id, tag),
    FOREIGN KEY (cape_id) REFERENCES capes(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS helmets (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    hash TEXT NOT NULL,
    description TEXT NOT NULL,
    registered_at TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS helmet_tags (
    helmet_id INT NOT NULL,
    tag item_tag NOT NULL,
    PRIMARY KEY (helmet_id, tag),
    FOREIGN KEY (helmet_id) REFERENCES helmets(id) ON DELETE CASCADE
);
