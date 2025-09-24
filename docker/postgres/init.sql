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
    type weapon_type NOT NULL
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
    level INT NOT NULL
);

CREATE TABLE IF NOT EXISTS armors (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    hash TEXT NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS armor_tags (
    armor_id INT NOT NULL,
    tag item_tag NOT NULL,
    PRIMARY KEY (armor_id, tag),
    FOREIGN KEY (armor_id) REFERENCES armors(id) ON DELETE CASCADE
);
