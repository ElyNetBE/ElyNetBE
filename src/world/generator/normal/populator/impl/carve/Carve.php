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

namespace pocketmine\world\generator\normal\populator\impl\carve;

use pocketmine\block\BlockLegacyIds;
use pocketmine\utils\Random;
use pocketmine\world\format\Chunk;
use pocketmine\world\World;
use function floor;
use function max;
use function min;

abstract class Carve {

	protected Random $random;

	final public function __construct(Random $random) {
		$this->random = $random;
	}

	/**
	 * @param Chunk $populatedChunk Is chunk, which will be updated (it should be same chunk / population)
	 *
	 * @param int $chunkX X coordinate from 'original' chunk (from chunk, where the carve starts)
	 * @param int $chunkZ Z coordinate from 'original' chunk (from chunk, where the carve starts)
	 */
	abstract public function carve(Chunk $populatedChunk, int $populatedChunkX, int $populatedChunkZ, int $chunkX, int $chunkZ): void;

	protected function carveSphere(Chunk $chunk, int $populatedChunkX, int $populatedChunkZ, float $centerX, float $centerY, float $centerZ, float $horizontalSize, float $verticalSize): void {
		$realChunkX = $populatedChunkX * 16;
		$realChunkZ = $populatedChunkZ * 16;

		if(
			($centerX < $realChunkX - 8.0 - $horizontalSize * 2.0) ||
			($centerZ < $realChunkZ - 8.0 - $horizontalSize * 2.0) ||
			($centerX > $realChunkX + 24.0 + $horizontalSize * 2.0) ||
			($centerZ > $realChunkZ + 24.0 + $horizontalSize * 2.0)
		) return;

		$minX = (int)max(0, (int)floor($centerX - $horizontalSize) - $realChunkX - 1);
		$maxX = (int)min(16, (int)floor($centerX + $horizontalSize) - $realChunkX + 1);
		$minY = (int)max(1, (int)floor($centerY - $verticalSize) - 1);
		$maxY = (int)min(World::Y_MAX - 8, (int)floor($centerY + $verticalSize) + 1);
		$minZ = (int)max(0, (int)floor($centerZ - $horizontalSize) - $realChunkZ - 1);
		$maxZ = (int)min(16, (int)floor($centerZ + $horizontalSize) - $realChunkZ + 1);

		if($this->collidesWithLiquids($chunk, $minX, $maxX, $minY, $maxY, $minZ, $maxZ)) {
			return;
		}

		for($x = $minX; $x < $maxX; ++$x) {
			$modX = ($x + $realChunkX + 0.5 - $centerX) / $horizontalSize;
			for($z = $minZ; $z < $maxZ; ++$z) {
				$modZ = ($z + $realChunkZ + 0.5 - $centerZ) / $horizontalSize;

				if(($modXZ = ($modX ** 2) + ($modZ ** 2)) < 1.0) {
					for($y = $maxY; $y > $minY; --$y) {
						$modY = ($y - 0.5 - $centerY) / $verticalSize;

						if($this->continue($modXZ, $modY, $y)) {
							if($chunk->getFullBlock($x, $y, $z) >> 4 == BlockLegacyIds::WATER || $chunk->getFullBlock($x, $y + 1, $z) >> 4 == BlockLegacyIds::WATER) {
								continue;
							}

							if($y < 11) {
								$chunk->setFullBlock($x, $y, $z, BlockLegacyIds::STILL_LAVA << 4);
								continue;
							}

							if(
								$chunk->getFullBlock($x, $y - 1, $z) >> 4 == BlockLegacyIds::DIRT &&
								$chunk->getFullBlock($x, $y + 1, $z) >> 4 == BlockLegacyIds::AIR &&
								$y > 62
							) {
								$chunk->setFullBlock($x, $y - 1, $z, BlockLegacyIds::GRASS << 4);
							}

							$chunk->setFullBlock($x, $y, $z, BlockLegacyIds::AIR);
						}
					}
				}
			}
		}
	}

	private function collidesWithLiquids(Chunk $chunk, int $minX, int $maxX, int $minY, int $maxY, int $minZ, int $maxZ): bool {
		for($x = $minX; $x < $maxX; ++$x) {
			for($z = $minZ; $z < $maxZ; ++$z) {
				for($y = $minY - 1; $y < $maxY + 1; ++$y) {
					$id = $chunk->getFullBlock($x, $y, $z) << 4;
					if(
						$id == BlockLegacyIds::FLOWING_WATER ||
						$id == BlockLegacyIds::STILL_LAVA ||
						$id == BlockLegacyIds::FLOWING_LAVA ||
						$id == BlockLegacyIds::STILL_LAVA
					) {
						return true;
					}

					if($y != $maxY + 1 && $x != $minX && $x != $maxX - 1 && $z != $minZ && $z != $maxZ - 1) {
						$y = $maxY;
					}
				}
			}
		}
		return false;
	}

	protected function canReach(int $chunkX, int $chunkZ, float $x, float $z, int $angle, int $maxAngle, float $radius): bool {
		return (($x - ($chunkX * 16) - 8) ** 2) + (($z - ($chunkZ * 16) - 8) ** 2) - (($maxAngle - $angle) ** 2) <= ($radius + 18) ** 2;
	}

	abstract protected function continue(float $modXZ, float $modY, int $y): bool;

	/** @noinspection PhpUnusedParameterInspection */
	public function canCarve(Random $random, int $chunkX, int $chunkZ): bool {
		return true;
	}
}