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

namespace pocketmine\world\generator\normal\populator;

use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use function is_null;

abstract class AmountPopulator extends Populator {

	private int $baseAmount;

	private int $randomAmount;

	private int $spawnPercentage = 100;

	public function __construct(int $baseAmount, int $randomAmount, ?int $spawnPercentage = null) {
		$this->baseAmount = $baseAmount;
		$this->randomAmount = $randomAmount;

		if(!is_null($spawnPercentage)) {
			$this->spawnPercentage = $spawnPercentage;
		}
	}

	public function setBaseAmount(int $baseAmount): void {
		$this->baseAmount = $baseAmount;
	}

	public function setRandomAmount(int $randomAmount): void {
		$this->randomAmount = $randomAmount;
	}

	public function setSpawnPercentage(int $percentage): void {
		$this->spawnPercentage = $percentage;
	}

	public final function populate(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
		if($random->nextRange($this->spawnPercentage, 100) != 100) {
			return;
		}

		$amount = $random->nextBoundedInt($this->randomAmount + 1) + $this->baseAmount;
		for($i = 0; $i < $amount; $i++) {
			$this->populateObject($world, $chunkX, $chunkZ, $random);
		}
	}

	abstract public function populateObject(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void;
}