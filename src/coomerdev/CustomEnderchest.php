<?php

namespace coomerdev;

use coomerdev\command\EnderChestCommand;
use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\block\EnderChest;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class CustomEnderchest extends PluginBase implements Listener {

    public static CustomEnderchest $instance;

    protected function onLoad(): void {
        self::$instance = $this;
    }

    protected function onEnable(): void {
        if(!InvMenuHandler::isRegistered()) InvMenuHandler::register($this);
        $this->getServer()->getCommandMap()->register("enderchest", new EnderChestCommand("enderchest", "Open EnderChest", "/enderchest", ["ec", "echest"]));
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onInteract(PlayerInteractEvent $event): void {
        if($event->getBlock() instanceof EnderChest) $event->cancel();
    }

    public function openEnderchest(Player $player): void {
        $menu = InvMenu::create($this->getTypeChest($player));
        $menu->setName("{$player->getName()} Enderchest");
        $menu->getInventory()->setContents($player->getEnderInventory()->getContents());
        $menu->setInventoryCloseListener(function(Player $player, Inventory $inventory) use($menu): void {
            $player->getEnderInventory()->setContents($menu->getInventory()->getContents());
        });
        $menu->send($player);
    }

    public function getTypeChest(Player $player): string {
        if($player->hasPermission("use.enderchest.vip") && $this->getServer()->isOp($player->getName())){
            return InvMenu::TYPE_CHEST;
        }
        return InvMenu::TYPE_HOPPER;
    }

    public static function getInstance(): CustomEnderchest {
        return self::$instance;
    }

}