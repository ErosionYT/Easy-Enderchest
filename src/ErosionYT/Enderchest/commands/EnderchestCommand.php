<?php

namespace ErosionYT\Enderchest\commands;

use muqsit\invmenu\inventories\BaseFakeInventory;
use pocketmine\{
    block\Block,
    command\CommandSender,
    command\PluginCommand,
    Server,
    Player};

use ErosionYT\Enderchest\{
    EnderchestInventory, Enderchest
};

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\MenuIds;

class EnderchestCommand extends PluginCommand{
    
    public function __construct(Enderchest $owner){
        parent::__construct("enderchest", $owner);
        $this->setDescription($this->getPlugin()->getConfig()->get("commandDescription"));
        $this->setPermission($this->getPlugin()->getConfig()->get("commandPermission"));
        $this->setAliases(["ec"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): bool{
        if($player instanceof Player){
            $menu = InvMenu::create(MenuIds::TYPE_CHEST);
            $menu->setName("Ender Chest");
            $menu->setInventoryCloseListener([$this, "onInventoryClose"]);
            $inventory = $menu->getInventory();
            $inventory->setContents($player->getEnderChestInventory()->getContents(true));

            $menu->send($player);
        }
        return true;
    }

    public function onInventoryClose(Player $player, BaseFakeInventory $inventory): void{
        $player->getEnderChestInventory()->setContents($inventory->getContents(true));
    }
}