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

namespace pocketmine\world\generator\hell\populator;

use pocketmine\block\VanillaBlocks;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\populator\Populator;
use function pow;

class GlowstoneSphere implements Populator {

	public const SPHERE_RADIUS = 3;

	public function populate(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void {
		$world->getChunk($chunkX, $chunkZ);
		if($random->nextRange(0, 10) !== 0) return;

		$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
		$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);

		$sphereY = 0;

		for($y = 0; $y < 127; $y++) {
			if($world->getBlockAt($x, $y, $z)->isSameType(VanillaBlocks::AIR())) {
				$sphereY = $y;
			}
		}

		if($sphereY < 80) {
			return;
		}

		$this->placeGlowstoneSphere($world, $random, new Vector3($x, $sphereY - $random->nextRange(2, 4), $z));
	}

	public function placeGlowStoneSphere(ChunkManager $world, Random $random, Vector3 $position): void {
		for($x = $position->getX() - $this->getRandomRadius($random); $x < $position->getX() + $this->getRandomRadius($random); $x++) {
			$xsqr = ($position->getX() - $x) * ($position->getX() - $x);
			for($y = $position->getY() - $this->getRandomRadius($random); $y < $position->getY() + $this->getRandomRadius($random); $y++) {
				$ysqr = ($position->getY() - $y) * ($position->getY() - $y);
				for($z = $position->getZ() - $this->getRandomRadius($random); $z < $position->getZ() + $this->getRandomRadius($random); $z++) {
					$zsqr = ($position->getZ() - $z) * ($position->getZ() - $z);
					if(($xsqr + $ysqr + $zsqr) < (pow(2, $this->getRandomRadius($random)))) {
						if($random->nextRange(0, 4) !== 0) {
							/** @phpstan-ignore-next-line */
							$world->setBlockAt($x, $y, $z, VanillaBlocks::GLOWSTONE());
						}
					}
				}
			}
		}
	}

	public function getRandomRadius(Random $random): int {
		return $random->nextRange(self::SPHERE_RADIUS, self::SPHERE_RADIUS + 2);
	}

}