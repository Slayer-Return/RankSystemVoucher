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

namespace slayerretrun\ranksystemvoucher\events;

use slayerretrun\ranksystemvoucher\RankSystemVoucher;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\utils\TextFormat as T;

class EventListener implements Listener
{
    public function __construct(private RankSystemVoucher $plugin)
    {
        //NOOP
    }

    public function onPlayerTapToBlock(PlayerInteractEvent $event) : void
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        if ($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_BLOCK) return;
        foreach ($this->plugin->rank_voucher->getAll() as $name => $details){
            if ($item->getTypeId() !== $this->plugin->parseItem($details["item"])->getTypeId()) continue;
            if ($item->getNamedTag()->getCompoundTag("RankSystemVoucher")->getString("Rank") === $name){
                if ($player->hasPermission("ranksystemvoucher.ranks." . $details["permission"])){
                    foreach ($details["commands"] as $command){
                        $ranks = $this->plugin->getRankSystem()->getSessionManager()->get($player->getName())->getRanks();
                        foreach ($ranks as $old_rank){
                            $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender($this->plugin->getServer(), $this->plugin->getServer()->getLanguage()), str_replace(["{player}", "{old-rank}"], [$player->getName(), $old_rank->getName()], $command));
                        }
                    }
                    $item->pop();
                    $player->getInventory()->setItemInHand($item);
                    $rank = $this->plugin->getRankSystem()->getRankManager()->getRank($name);
                    $player->sendMessage(RankSystemVoucher::PREFIX . str_replace("{rank}", $rank->getName(), $details["message"]));
                    $event->cancel();
                    return;
                } else {
                    $player->sendMessage(RankSystemVoucher::PREFIX . T::RED . "You don't have permission to use this command.");
                    $event->cancel();
                    return;
                }
            }
        }
    }
}