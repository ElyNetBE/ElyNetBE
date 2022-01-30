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

namespace pocketmine\item;

use pocketmine\entity\Entity;
use pocketmine\entity\Location;
use pocketmine\entity\projectile\Trident as TridentEntity;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\player\Player;
use pocketmine\world\sound\TridentThrowSound;
use function min;

class Trident extends Tool implements Releasable{

	public function getMaxDurability() : int{
		return 251;
	}

	public function onReleaseUsing(Player $player) : ItemUseResult{
		$location = $player->getLocation();

		$diff = $player->getItemUseDuration();
		$p = $diff / 20;
		$baseForce = min((($p ** 2) + $p * 2) / 3, 1) * 3;
		if($baseForce < 0.9 || $diff < 8){
			return ItemUseResult::FAIL();
		}

		$entity = new TridentEntity(Location::fromObject(
			$player->getEyePos(),
			$player->getWorld(),
			($location->yaw > 180 ? 360 : 0) - $location->yaw,
			-$location->pitch
		), $this, $player);
		$entity->setMotion($player->getDirectionVector()->multiply($baseForce));

		$ev = new ProjectileLaunchEvent($entity);
		$ev->call();
		if($ev->isCancelled()){
			$ev->getEntity()->flagForDespawn();
			return ItemUseResult::FAIL();
		}
		$ev->getEntity()->spawnToAll();
		$location->getWorld()->addSound($location, new TridentThrowSound());

		if($player->hasFiniteResources()){
			$item = $entity->getItem();
			$item->applyDamage(1);
			$entity->setItem($item);
			$this->pop();
		}else{
			$entity->setPickupMode(TridentEntity::PICKUP_NONE);
		}
		return ItemUseResult::SUCCESS();
	}

	public function getAttackPoints() : int{
		return 8;
	}

	public function onAttackEntity(Entity $victim) : bool{
		return $this->applyDamage(1);
	}

	public function canStartUsingItem(Player $player) : bool{
		return true;
	}
}