<?php

namespace pocketmine\block;

use pocketmine\world\sound\ConduitActivateSound;
use pocketmine\world\sound\ConduitDeactivateSound;
use pocketmine\block\tile\Conduit as TileConduit;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\Transparent;
use pocketmine\block\VanillaBlocks;
use pocketmine\block\Water;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\entity\Entity;
use pocketmine\entity\Zombie;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Facing;
use pocketmine\math\Vector2;
use pocketmine\Server;

class Conduit extends Transparent
{
	public const VALID_STRUCTURE_BLOCKS = [BlockLegacyIds::PRISMARINE, BlockLegacyIds::PRISMARINE_BRICKS_STAIRS, BlockLegacyIds::DARK_PRISMARINE_STAIRS, BlockLegacyIds::SEA_LANTERN];

	protected ?Entity $targetEntity = null;
	protected int $target = -1;
	protected int $validBlocks = -1;
	protected bool $activate = false;

	public function readStateFromWorld(): void
	{
		parent::readStateFromWorld();
		$tile = $this->position->getWorld()->getTile($this->position);
		if ($tile instanceof TileConduit) {
			$this->activate = $tile->isActivate();
			$this->target = $tile->getTarget();
			if ($this->target !== -1) {
				$this->targetEntity = $this->position->getWorld()->getEntity($this->target);
			}
		}
	}

	public function writeStateToWorld(): void
	{
		parent::writeStateToWorld();
		$tile = $this->position->getWorld()->getTile($this->position);
		if ($tile instanceof TileConduit) {
			$tile->setActivate($this->activate);
			$tile->setTarget($this->target);
		}
	}

	public function getLightLevel(): int
	{
		return 15;
	}

	public function onNearbyBlockChange(): void
	{
		$this->position->getWorld()->scheduleDelayedBlockUpdate($this->position, 1);
	}

	public function onScheduledUpdate(): void
	{
		$activeBeforeUpdate = $this->activate;
		$targetBeforeUpdate = $this->targetEntity;
		$blocksBeforeUpdate = $this->validBlocks;
		$tick = Server::getInstance()->getTick();

		if ($tick % 20 === 0) {
			$this->activate = $this->scanStructure();
		}
		if ($this->target !== -1) {
			$this->targetEntity = $this->position->getWorld()->getEntity($this->target);
			if ($this->targetEntity == null) {
				$this->target = -1;
			}
		}

		if ($activeBeforeUpdate !== $this->activate || $targetBeforeUpdate !== $this->targetEntity || $blocksBeforeUpdate !== $this->validBlocks) {
			$this->position->getWorld()->setBlock($this->position, $this);
			if ($activeBeforeUpdate && !$this->activate) {
				$this->position->getWorld()->addSound($this->position->add(0.5, 0.5, 0.5), new ConduitDeactivateSound());
			} elseif (!$activeBeforeUpdate && $this->activate) {
				$this->position->getWorld()->addSound($this->position->add(0.5, 0.5, 0.5), new ConduitActivateSound());
			}
		}
		if (!$this->activate) {
			$this->targetEntity = null;
			$this->target = -1;
		} elseif ($tick % 40 === 0) {
			$this->attackMobs();
			$this->addEffectToPlayers();
		}
		$this->position->getWorld()->scheduleDelayedBlockUpdate($this->position, 1);
	}

	public function scanStructure(): bool
	{
		if(!$this->scanWater()){
			$this->validBlocks = 0;
			return false;
		}
		$validBlocks = $this->scanFrame();
		if($validBlocks < 16){
			$this->validBlocks = 0;
			return false;
		}
		$this->validBlocks = $validBlocks;
		return true;
	}

	protected function scanWater() : bool{
		$pos = $this->position;
		$x = $pos->getX();
		$y = $pos->getY();
		$z = $pos->getZ();
		$world = $pos->getWorld();
		$side = $this->getSide(Facing::DOWN)->getId();
		if($side !== BlockLegacyIds::WATER && $side !== BlockLegacyIds::FLOWING_WATER) return false;
		for($ix = -1; $x <= 1; $ix++){
			for($iz = -1; $iz <= 1; $iz++){
				for ($iy = -1; $iy <= 1; $iy++){
					$block = $world->getBlockAt($x + $ix, $y + $iy, $z + $iz)->getId();
					if($block !== BlockLegacyIds::WATER && $block !== BlockLegacyIds::FLOWING_WATER && $block !== BlockLegacyIds::CONDUIT){
						return false; //TODO: Check Block Layers (need waterlogging system)
					}
				}
			}
		}
		return true;
	}

	protected function scanFrame() : int{
		$validBlocks = 0;
		$pos = $this->position;
		$x = $pos->getX();
		$y = $pos->getY();
		$z = $pos->getZ();
		$world = $pos->getWorld();
		for($iy = -2; $iy <= 2; $iy++){
			if($iy === 0){
				for($ix = -2; $ix <= 2; $ix++){
					for($iz = -2; $iz <= 2; $iz++){
						if(abs($iz) != 2 && abs($ix) != 2){
							continue;
						}
						if(in_array($world->getBlockAt($x + $ix, $y, $z + $iz)->getId(), self::VALID_STRUCTURE_BLOCKS)){
							$validBlocks++;
						}
					}
				}
			} else {
				$absIY = abs($iy);
				for($ix = -2; $ix <= 2; $ix++){
					if($absIY != 2 && $ix == 0){
						continue;
					}
					if($absIY == 2 || abs($ix) == 2){
						if(in_array($world->getBlockAt($x + $ix, $y + $iy, $z)->getId(), self::VALID_STRUCTURE_BLOCKS)){
							$validBlocks++;
						}
					}
				}

				for($iz = -2; $iz <= 2; $iz++){
					if($absIY != 2 && $iz == 0){
						continue;
					}

					if($absIY == 2 && $iz != 0 || abs($iz) == 2){
						if(in_array($world->getBlockAt($x, $y + $iy, $z + $iz)->getId(), self::VALID_STRUCTURE_BLOCKS)){
							$validBlocks++;
						}
					}
				}
			}
		}
	//	var_dump($validBlocks);
		return $validBlocks;
	}

	public function attackMobs(): void
	{
		$radius = $this->getAttackRadius();
		if ($radius <= 0) {
			return;
		}
		$updated = false;
		$target = $this->targetEntity;
		if ($target !== null && !$this->canAttack($target)) {
			$target = null;
			$updated = true;
			$this->targetEntity = null;
			$this->target = -1;
		}

		if ($target == null) {
			$pos = $this->position;
			$mobs = [];
			$entities = $pos->getWorld()->getCollidingEntities(new AxisAlignedBB($pos->getX() - $radius, $pos->getY() - $radius, $pos->getZ() - $radius, $pos->getX() + 1 + $radius, $pos->getY() + 1 + $radius, $pos->getZ() + 1 + $radius));
			foreach ($entities as $mob) {
				if ($this->canAttack($mob)) $mobs[] = $mob;
			}
			if (count($mobs) > 0) {
				$target = $mobs[array_rand($mobs)];
				$this->targetEntity = $target;
				$this->target = $target->getId();
			}
		}
		if ($target !== null) {
			$ev = new EntityDamageByBlockEvent($this, $target, EntityDamageEvent::CAUSE_MAGIC, 4);
			$target->attack($ev);
			if($ev->isCancelled()){
				$this->targetEntity = null;
				$this->target = -1;
				$updated = true;
			}
		}
		if ($updated) $this->position->getWorld()->setBlock($this->position, $this);
	}

	public function addEffectToPlayers(): void
	{
		$radius = $this->getPlayerRadius();
		if ($radius <= 0) {
			return;
		}
		$radiusSquared = $radius * $radius;

		$conduitPos = new Vector2($this->position->getX(), $this->position->getZ());
		foreach ($this->position->getWorld()->getPlayers() as $player) {
			if ($conduitPos->distanceSquared(new Vector2($player->getPosition()->getX(), $player->getPosition()->getZ())) <= $radiusSquared) {
				$player->getEffects()->add(new EffectInstance(VanillaEffects::CONDUIT_POWER(), 260));
			}
		}
	}

	public function canAttack(Entity $entity): bool
	{
		return $entity instanceof Zombie && !$entity->isFlaggedForDespawn() && $entity->isUnderwater(); //TODO: check this
	}

	public function getPlayerRadius(): int
	{
		return ($this->validBlocks / 7) * 16;
	}

	public function getAttackRadius(): int
	{
		return $this->validBlocks >= 42 ? 8 : 0;
	}
}