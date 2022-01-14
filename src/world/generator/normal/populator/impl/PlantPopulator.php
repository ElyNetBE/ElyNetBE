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

namespace pocketmine\world\generator\normal\populator\impl;

use pocketmine\world\generator\normal\populator\AmountPopulator;
use pocketmine\world\generator\normal\populator\impl\plant\Plant;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use function count;

class PlantPopulator extends AmountPopulator {

	/** @var Plant[] */
	private array $plants = [];

	public function addPlant(Plant $plant): void {
		$this->plants[] = $plant;
	}

	public function populateObject(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
		if(count($this->plants) === 0) {
			return;
		}

		$plant = $this->plants[$random->nextBoundedInt(count($this->plants))];
		if($this->getSpawnPositionOn($world->getChunk($chunkX, $chunkZ), $random, $plant->getAllowedUnderground(), $x, $y, $z)) {
			$world->setBlockAt($chunkX * 16 + $x, $y, $chunkZ * 16 + $z, $plant->getBlock());
		}
	}
}