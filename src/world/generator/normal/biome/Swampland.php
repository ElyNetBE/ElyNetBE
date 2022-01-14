<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace pocketmine\world\generator\normal\biome;

use pocketmine\world\generator\normal\biome\types\GrassyBiome;
use pocketmine\world\generator\normal\populator\impl\plant\Plant;
use pocketmine\world\generator\normal\populator\impl\PlantPopulator;
use pocketmine\world\generator\normal\populator\impl\TallGrassPopulator;
use pocketmine\world\generator\normal\populator\impl\TreePopulator;
use pocketmine\block\utils\TreeType;
use pocketmine\block\VanillaBlocks;

class Swampland extends GrassyBiome {

	public function __construct() {
		parent::__construct(0.8, 0.5);

		$mushrooms = new PlantPopulator(2, 2, 95);
		$mushrooms->addPlant(new Plant(VanillaBlocks::RED_MUSHROOM()));
		$mushrooms->addPlant(new Plant(VanillaBlocks::BROWN_MUSHROOM()));

		$flowers = new PlantPopulator(6, 7, 80);
		$flowers->addPlant(new Plant(VanillaBlocks::DANDELION()));
		$flowers->addPlant(new Plant(VanillaBlocks::POPPY()));

		$oak = new TreePopulator(2, 2, 100, TreeType::OAK(), true);
		$tallGrass = new TallGrassPopulator(56, 12);

		$this->addPopulators([$mushrooms, $flowers, $oak, $tallGrass]);

		$this->setElevation(60, 65);
	}

	public function getName(): string {
		return "Swampland";
	}
}