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

use pocketmine\world\generator\normal\populator\impl\plant\Plant;
use pocketmine\world\generator\normal\populator\impl\PlantPopulator;
use pocketmine\block\VanillaBlocks;

class BadlandsPlateau extends Badlands {

	public function __construct() {
		parent::__construct();

		$deadBush = new PlantPopulator(4, 3);
		$deadBush->addPlant(new Plant(VanillaBlocks::DEAD_BUSH(), [VanillaBlocks::HARDENED_CLAY()]));

		$this->clearPopulators();
		$this->addPopulators([$deadBush]);

		$this->setElevation(84, 87);
	}

	public function getName(): string {
		return "Mesa Plateau";
	}
}