-- Map IDs for Lineage Remastered
-- This file contains map data for the game

CREATE TABLE IF NOT EXISTS `mapids` (
  `mapid` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `region` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `min_level` int(3) DEFAULT NULL,
  `max_level` int(3) DEFAULT NULL,
  `teleport_flag` tinyint(1) DEFAULT 1,
  `escape_flag` tinyint(1) DEFAULT 1,
  `resurrection_flag` tinyint(1) DEFAULT 1,
  `take_pets_flag` tinyint(1) DEFAULT 1,
  `recall_pets_flag` tinyint(1) DEFAULT 1,
  `usable_item_flag` tinyint(1) DEFAULT 1,
  `usable_skill_flag` tinyint(1) DEFAULT 1,
  `is_pvp` tinyint(1) DEFAULT 0,
  `is_safe_zone` tinyint(1) DEFAULT 0,
  `is_instance` tinyint(1) DEFAULT 0,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`mapid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert sample map data
INSERT INTO `mapids` (`mapid`, `name`, `region`, `type`, `min_level`, `max_level`, `teleport_flag`, `escape_flag`, `resurrection_flag`, `take_pets_flag`, `recall_pets_flag`, `usable_item_flag`, `usable_skill_flag`, `is_pvp`, `is_safe_zone`, `is_instance`, `description`, `image_path`) VALUES
(0, 'Talking Island', 'Aden Kingdom', 'Field', 1, 20, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 'Talking Island is the starting area for human characters. It features a castle town and surrounding wilderness with low-level monsters.', 'maps/images/talking_island.jpg'),
(1, 'Elven Forest', 'Aden Kingdom', 'Field', 1, 20, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 'The Elven Forest is the starting area for elven characters. It is a lush forest with streams and ancient elven structures.', 'maps/images/elven_forest.jpg'),
(2, 'Dark Elf Village', 'Aden Kingdom', 'Field', 1, 20, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 'The Dark Elf Village is the starting area for dark elf characters. It is located in an underground cavern system.', 'maps/images/dark_elf_village.jpg'),
(3, 'Orc Village', 'Aden Kingdom', 'Field', 1, 20, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 'The Orc Village is the starting area for orc characters. It is a rugged, mountainous region with primitive structures.', 'maps/images/orc_village.jpg'),
(4, 'Dwarf Village', 'Aden Kingdom', 'Field', 1, 20, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 'The Dwarf Village is the starting area for dwarf characters. It is located in a mining complex within a mountain.', 'maps/images/dwarf_village.jpg'),
(5, 'Gludio', 'Aden Kingdom', 'Town', 1, 99, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 'Gludio is a major town in the Aden Kingdom. It serves as a hub for low to mid-level players.', 'maps/images/gludio.jpg'),
(6, 'Giran', 'Aden Kingdom', 'Town', 20, 99, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 'Giran is a major trading hub in the Aden Kingdom. It features a large marketplace and is a center for commerce.', 'maps/images/giran.jpg'),
(7, 'Oren', 'Aden Kingdom', 'Town', 30, 99, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 'Oren is a town located in the northeastern part of the Aden Kingdom. It is known for its magical research.', 'maps/images/oren.jpg'),
(8, 'Aden Castle Town', 'Aden Kingdom', 'Town', 40, 99, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 'Aden Castle Town is the capital of the Aden Kingdom. It is a large, fortified city with a grand castle.', 'maps/images/aden_castle.jpg'),
(9, 'Heine', 'Aden Kingdom', 'Town', 35, 99, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 'Heine is a city built on water in the southern part of the Aden Kingdom. It is known for its canals and bridges.', 'maps/images/heine.jpg'),
(10, 'Dion', 'Aden Kingdom', 'Town', 15, 99, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 'Dion is a town in the central part of the Aden Kingdom. It is surrounded by fertile farmland.', 'maps/images/dion.jpg'),
(11, 'Floran Village', 'Aden Kingdom', 'Village', 15, 30, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 'Floran Village is a small farming community near Dion. It is known for its agricultural products.', 'maps/images/floran_village.jpg'),
(12, 'Gludin Village', 'Aden Kingdom', 'Village', 5, 25, 1, 1, 1, 1, 1, 1, 1, 0, 1, 0, 'Gludin Village is a port town in the western part of the Aden Kingdom. It serves as a gateway to the continent of Gracia.', 'maps/images/gludin_village.jpg'),
(13, 'Ant Nest', 'Aden Kingdom', 'Dungeon', 15, 25, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 'The Ant Nest is a dungeon filled with giant ants. It is a popular hunting ground for low to mid-level players.', 'maps/images/ant_nest.jpg'),
(14, 'Cruma Tower', 'Aden Kingdom', 'Dungeon', 30, 40, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 'Cruma Tower is a multi-level dungeon filled with golems and other constructs. It was once a research facility.', 'maps/images/cruma_tower.jpg'),
(15, 'Dragon Valley', 'Aden Kingdom', 'Field', 50, 70, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 'Dragon Valley is a dangerous area inhabited by dragons and drakes. It leads to the lair of the dragon Antharas.', 'maps/images/dragon_valley.jpg'),
(16, 'Ivory Tower', 'Aden Kingdom', 'Dungeon', 40, 60, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 'The Ivory Tower is a magical research facility. It is home to many magical creatures and experiments gone wrong.', 'maps/images/ivory_tower.jpg'),
(17, 'Lair of Antharas', 'Aden Kingdom', 'Raid', 70, 99, 1, 0, 1, 1, 1, 1, 1, 0, 0, 1, 'The Lair of Antharas is where the mighty dragon Antharas resides. Only the strongest adventurers dare to challenge him.', 'maps/images/antharas_lair.jpg'),
(18, 'Tower of Insolence', 'Aden Kingdom', 'Dungeon', 60, 80, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 'The Tower of Insolence is a massive tower with multiple floors. Each floor contains progressively stronger monsters.', 'maps/images/tower_of_insolence.jpg'),
(19, 'Forge of the Gods', 'Aden Kingdom', 'Raid', 75, 99, 1, 0, 1, 1, 1, 1, 1, 0, 0, 1, 'The Forge of the Gods is where the dragon Valakas resides. It is filled with lava and fire elementals.', 'maps/images/forge_of_gods.jpg'),
(20, 'Cemetery', 'Aden Kingdom', 'Dungeon', 20, 35, 1, 1, 1, 1, 1, 1, 1, 0, 0, 0, 'The Cemetery is filled with undead creatures. It is a popular hunting ground for mid-level players.', 'maps/images/cemetery.jpg');