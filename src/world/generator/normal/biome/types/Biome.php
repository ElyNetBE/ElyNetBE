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

namespace pocketmine\world\generator\normal\biome\types;

use pocketmine\world\generator\normal\populator\Populator;

abstract class Biome extends \pocketmine\world\biome\Biome {

	private bool $isFrozen;

	public function __construct(float $temperature, float $rainfall) {
		$this->temperature = $temperature;
		$this->rainfall = $rainfall;

		$this->isFrozen = ($temperature <= 0);
	}

	public function isFrozen(): bool {
		return $this->isFrozen;
	}

	public function setFrozen(bool $isFrozen = true): void {
		$this->isFrozen = $isFrozen;
	}

	/**
	 * @param Populator[] $populators
	 */
	public function addPopulators(array $populators = []): void {
		foreach($populators as $populator) {
			$this->addPopulator($populator);
		}
	}
}
