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

namespace pocketmine\world\utils;

use function sin;

class MathHelper {

	private static MathHelper $instance;

	/** @var float[] */
	private array $sinTable = [];

	private function __construct() {
		for($i = 0; $i < 65536; ++$i) {
			$this->sinTable[$i] = sin((float)$i * M_PI * 2.0 / 65536.0);
		}
	}

	public function sin(float $num): float {
		return $this->sinTable[(int)($num * 10430.378) & 0xffff];
	}

	public function cos(float $num): float {
		return $this->sinTable[(int)($num * 10430.378 + 16384.0) & 0xffff];
	}

	public static function getInstance(): MathHelper {
		return MathHelper::$instance ?? MathHelper::$instance = new MathHelper();
	}
}