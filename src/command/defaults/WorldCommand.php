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

namespace pocketmine\command\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\world\World;
use pocketmine\Server;
use Exception;
use pocketmine\world\utils\WorldUtils;
use pocketmine\world\WorldCreationOptions;
use function is_numeric;
use function mt_rand;

class WorldCommand extends Command{
    
    public function __construct(string $name) {
        parent::__construct($name, "Create the world", null, []);
		$this->setPermission(DefaultPermissions::ROOT_OPERATOR);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) {
	    if(!$this->testPermission($sender)){
			return true;
		}
		if(!isset($args[0])) {
			$sender->sendMessage(TextFormat::RED . "Usage /world (create/tp/list/delete)");
			return;
		}
		
		switch($args[0]) {
		    case "create":
		        if(!isset($args[1])) {
		            $sender->sendMessage(TextFormat::RED . "Usage /world create (name world) (seed) (generator)");
		            return;
		        }
		        if(Server::getInstance()->getWorldManager()->isWorldGenerated($args[1])) {
			$sender->sendMessage(TextFormat::RED . "The name of world already");
			return;
		}

		$seed = mt_rand();
		if(isset($args[2]) && is_numeric($args[2])) {
			$seed = (int)$args[2];
		}

		$generator = WorldUtils::getGeneratorByName($generatorName = $args[3] ?? "");
		if($generator === null) {
			$sender->sendMessage(TextFormat::RED . "Can't find the generator");
			return;
		}

		Server::getInstance()->getWorldManager()->generateWorld(
			name: $args[1],
			options: WorldCreationOptions::create()
				->setSeed($seed)
				->setGeneratorClass($generator->getGeneratorClass())
		);
		$sender->sendMessage(TextFormat::GREEN . "Succsesfuly create {$args[1]} with {$args[2]} seed and generator {$args[3]}");
		    break;
		    case "tp":
		    case "teleport":
			$world = WorldUtils::getLoadedWorldByName($args[1]);
			if($world === null) {
				$sender->sendMessage(TextFormat::RED . "World does'nt exists");
				return;
			}

			if(!isset($args[2])) {
			    $sender->sendMessage(TextFormat::RED . "Usage /world tp (nameworld)");
					return;
				}

				$sender->teleport($world->getSpawnLocation());
				$sender->sendMessage(TextFormat::GREEN . "You has been teleport to {$args[2]}");
		    break;
		    case "list":
		        $worlds = array_values(array_map(static function(string $file): string {
			if(Server::getInstance()->getWorldManager()->isWorldLoaded($file)) {
				return "§7$file > §aLoaded§7, " . count(WorldUtils::getWorldByNameNonNull($file)->getPlayers()) . " Players";
			} else {
				return "§7$file > §cUnloaded";
			}
		}, WorldUtils::getAllWorlds()));

		$sender->sendMessage("Worlds : (" . count($worlds) . ")\n" . implode("\n", $worlds));
		    break;
		    case "remove":
		    case "delete":
		        if(!isset($args[1])) {
		            $sender->sendMessage(TextFormat::RED . "Usage /world delete (name world)");
		            return;
		        }
		        if(!Server::getInstance()->getWorldManager()->isWorldGenerated($args[1]) || !file_exists(Server::getInstance()->getDataPath() . "worlds/$args[1]")) {
			$sender->sendMessage(TextFormat::RED . "World doest'nt exists");
			return;
		}

		$world = Server::getInstance()->getWorldManager()->getWorldByName($args[1]);
		if($world instanceof World) { // World is loaded
			if(WorldUtils::getDefaultWorldNonNull()->getId() == $world->getId()) {
				$sender->sendMessage("§cCould not remove default world!");
				return;
			}
		}
		
		WorldUtils::removeWorld($args[1]);
		$sender->sendMessage(TextFormat::GREEN . "{$args[1]} has deleted world");
		    break;
		}
	}
}
