CREATE TABLE IF NOT EXISTS weapons (
    id INTEGER PRIMARY KEY,
    name TEXT NOT NULL,
    description TEXT NOT NULL,
    type TEXT NOT NULL CHECK(type IN (
        'Axe', 'Bow', 'Dagger', 'Gauntlet', 'Gun', 
        'Mace', 'Polearm', 'Staff', 'Sword', 'Wand', 'Whip'
    ))
);

CREATE TABLE IF NOT EXISTS weapon_tags (
    weapon_id INTEGER NOT NULL,
    tag TEXT NOT NULL CHECK(tag IN (
        'Legend', 'Adventure Coins', 'Rare', 'Pseudo Rare', 'Seasonal'
    )),
    PRIMARY KEY (weapon_id, tag),
    FOREIGN KEY (weapon_id) REFERENCES weapons(id) ON DELETE CASCADE
);
