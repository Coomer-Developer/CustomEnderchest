<?php

namespace coomerdev\command;

use coomerdev\CustomEnderchest;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class EnderChestCommand extends Command {

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$sender instanceof Player) return;
        CustomEnderchest::getInstance()->openEnderchest($sender);
    }

}