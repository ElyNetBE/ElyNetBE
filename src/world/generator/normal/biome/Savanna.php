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
use pocketmine\world\generator\normal\populator\impl\LakePopulator;
use pocketmine\world\generator\normal\populator\impl\plant\Plant;
use pocketmine\world\generator\normal\populator\impl\PlantPopulator;
use pocketmine\world\generator\normal\populator\impl\TallGrassPopulator;
use pocketmine\world\generator\normal\populator\impl\TreePopulator;
use pocketmine\block\utils\TreeType;
use pocketmine\block\VanillaBlocks;

class Savanna extends GrassyBiome {

	public function __construct() {
		parent::__construct(1.2, 0);

		$flowers = new PlantPopulator(6, 7, 80);
		$flowers->addPlant(new Plant(VanillaBlocks::DANDELION()));
		$flowers->addPlant(new Plant(VanillaBlocks::POPPY()));

		$acacia = new TreePopulator(1, 1, 100, TreeType::ACACIA());
		$tallGrass = new TallGrassPopulator(56, 12);

		$this->addPopulators([new LakePopulator(), $flowers, $acacia, $tallGrass]);

		$this->setElevation(67, 70);
	}

	public function getName(): string {
		return "Savanna";
	}
}