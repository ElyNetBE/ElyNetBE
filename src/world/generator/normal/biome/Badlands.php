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

use pocketmine\world\generator\hell\populator\Ore;
use pocketmine\world\generator\normal\biome\types\CoveredBiome;
use pocketmine\world\generator\normal\populator\impl\CactusPopulator;
use pocketmine\world\generator\normal\populator\impl\plant\Plant;
use pocketmine\world\generator\normal\populator\impl\PlantPopulator;
use pocketmine\block\utils\DyeColor;
use pocketmine\block\VanillaBlocks;
use pocketmine\world\generator\object\OreType;

class Badlands extends CoveredBiome {

	public function __construct() {
		parent::__construct(2, 0);

		$this->setGroundCover([
			VanillaBlocks::RED_SAND(),
			VanillaBlocks::HARDENED_CLAY(),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::RED()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::YELLOW()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::YELLOW()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::BROWN()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::WHITE()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::ORANGE()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::RED()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::YELLOW()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::YELLOW()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::BROWN()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::WHITE()),
			VanillaBlocks::STAINED_CLAY()->setColor(DyeColor::ORANGE())
		]);

		$cactus = new CactusPopulator(3, 2);

		$deadBush = new PlantPopulator(3, 2);
		$deadBush->addPlant(new Plant(VanillaBlocks::DEAD_BUSH(), [VanillaBlocks::STAINED_CLAY()]));

		$ore = new Ore();
		$ore->setOreTypes([new OreType(VanillaBlocks::GOLD_ORE(), VanillaBlocks::STONE(), 12, 0, 0, 128)]);

		$this->addPopulators([
			$cactus,
			$deadBush,
			$ore
		]);

		$this->setElevation(63, 67);
	}

	public function getName(): string {
		return "Mesa";
	}
}
