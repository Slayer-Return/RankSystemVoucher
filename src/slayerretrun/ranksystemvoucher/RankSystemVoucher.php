<?php

/**
 *   _____ _                       _____      _                    
 *  / ____| |                     |  __ \    | |                   
 * | (___ | | __ _ _   _  ___ _ __| |__) |___| |_ _ __ _   _ _ __  
 *  \___ \| |/ _` | | | |/ _ \ '__|  _  // _ \ __| '__| | | | '_ \ 
 *  ____) | | (_| | |_| |  __/ |  | | \ \  __/ |_| |  | |_| | | | |
 * |_____/|_|\__,_|\__, |\___|_|  |_|  \_\___|\__|_|   \__,_|_| |_|
 *                  __/ |                                          
 *                 |___/                                           
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * @author SlayerRetrun Team
 * @link https://github.com/Slayer-Return
 * 
 * 
 */

declare(strict_types=1);

namespace slayerretrun\ranksystemvoucher;

use IvanCraft623\RankSystem\RankSystem;
use slayerretrun\ranksystemvoucher\events\EventListener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as T;

class RankSystemVoucher extends PluginBase
{
    public const PREFIX = "§b[§cRankSystem§eVoucher§b]§r ";
    public Config $rank_voucher;

    protected function onLoad() : void
    {
        $this->saveResource("rank_voucher.yml");
    }

    protected function onEnable() : void
    {
        $this->rank_voucher = new Config($this->getDataFolder() . "rank_voucher.yml", Config::YAML);

        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        if ($this->getRankSystem() === null){
            $this->getLogger()->emergency("There are no RankSystem plugin.");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool
    {
        if (!$sender instanceof Player){
            $sender->sendMessage(self::PREFIX . T::RED . "You must be logged in to use this command.");
            return true;
        }
        if ($cmd->getName() === "ranksystemvoucher"){
            if (!isset($args[0])){
                $sender->sendMessage(self::PREFIX . T::RED . "Use: /ranksystemvoucher <player> <rank> <amount>");
                return true;
            } else {
                if (!isset($args[1])){
                    $sender->sendMessage(self::PREFIX . T::RED . "Use: /ranksystemvoucher " . $args[0] . " <rank> <amount>");
                    return true;
                } else {
                    $player = $this->getServer()->getPlayerByPrefix($args[0]);
                    if ($player === null){
                        $sender->sendMessage(self::PREFIX . T::RED . "Player " . $args[0] . " is offline!");
                        return true;
                    }
                    foreach ($this->rank_voucher->getAll() as $name => $details){
                        if ($name === $args[1]){
                            $item = $this->parseItem($details["item"]);
                            $item->setCustomName($details["customname"]);
                            $item->setNamedTag(CompoundTag::create()->setTag("RankSystemVoucher", CompoundTag::create()->setString("Rank", (string) $name)));
                            $item->setLore($details["lore"]);
                            if (isset($args[2])){
                                $player->getInventory()->addItem($item->setCount((int) $args[2]));
                                $player->sendMessage(self::PREFIX . T::GREEN . "Success add " . $args[2] . " ranksystemvoucher " . $args[1]);
                                return true;
                            } else {
                                $player->getInventory()->addItem($item->setCount(1));
                                $player->sendMessage(self::PREFIX . T::GREEN . "Success add ranksystemvoucher " . $args[1]);
                                return true;
                            }
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }

    public function getRankSystem() : ?RankSystem
    {
        return $this->getServer()->getPluginManager()->getPlugin("RankSystem") !== null ? RankSystem::getInstance() : null;
    }

    public function parseItem(string $item_name) : Item
    {
        return StringToItemParser::getInstance()->parse($item_name);
    }
}