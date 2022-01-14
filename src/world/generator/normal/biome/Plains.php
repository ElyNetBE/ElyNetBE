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
use pocketmine\block\VanillaBlocks;

class Plains extends GrassyBiome {

	public function __construct() {
		parent::__construct(0.8, 0.4);

		$flowers = new PlantPopulator(9, 7, 85);
		$flowers->addPlant(new Plant(VanillaBlocks::DANDELION()));
		$flowers->addPlant(new Plant(VanillaBlocks::POPPY()));

		$daisy = new PlantPopulator(9, 7, 85);
		$daisy->addPlant(new Plant(VanillaBlocks::OXEYE_DAISY()));

		$bluet = new PlantPopulator(9, 7, 85);
		$bluet->addPlant(new Plant(VanillaBlocks::AZURE_BLUET()));

		$tulips = new PlantPopulator(9, 7, 85);
		$tulips->addPlant(new Plant(VanillaBlocks::PINK_TULIP()));
		$tulips->addPlant(new Plant(VanillaBlocks::ORANGE_TULIP()));

		$tree = new TreePopulator(2, 1, 85);
		$lake = new LakePopulator();
		$tallGrass = new TallGrassPopulator(89, 26);

		$this->addPopulators([$lake, $flowers, $daisy, $bluet, $tulips, $tree, $tallGrass]);

		$this->setElevation(62, 66);
	}

	public function getName(): string {
		return "Plains";
	}
}