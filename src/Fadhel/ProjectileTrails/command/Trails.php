<?php

declare(strict_types=1);

namespace Fadhel\ProjectileTrails\command;

use Fadhel\ProjectileTrails\form\SimpleForm;
use Fadhel\ProjectileTrails\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;

class Trails extends Command
{
    protected $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("projectiletrails", "Select your projectile trails", "", ["pt"]);
        $this->setPermission("projectiletrails.command");
    }
    
    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }

    private function sendForm(Player $player): void
    {
        $form = new SimpleForm(function(Player $player, ?int $data) {
            if ($data === null) return;
            
            switch ($data) {
                case 0:
                    $this->plugin->updateParticle($player, 1);
                    break;
                case 1:
                    $this->plugin->updateParticle($player, 2);
                    break;
                case 2:
                    $this->plugin->updateParticle($player, 3);
                    break;
                case 3:
                    $this->plugin->updateParticle($player, 4);
                    break;
                case 4:
                    $this->plugin->updateParticle($player, 5);
                    break;
                case 5:
                    $this->plugin->updateParticle($player, 6);
                    break;
                case 6:
                    $this->plugin->updateParticle($player, 7);
                    break;
                case 7:
                    $this->plugin->updateParticle($player, 8);
                    break;
                case 8:
                    $this->plugin->updateParticle($player, 9);
                    break;
                case 9:
                    $this->plugin->updateParticle($player, 10);
                    break;
                case 10:
                    $this->plugin->updateParticle($player, 11);
                    break;
                case 11:
                    $this->plugin->updateParticle($player, 12);
                    break;
                case 12:
                    $this->plugin->updateParticle($player, 13);
                    break;
                case 13:
                    $this->plugin->updateParticle($player, 14);
            }
        });
        $form->setTitle(TextFormat::colorize($this->plugin->getConfig()->get("title-message")));
        $form->setContent(TextFormat::colorize($this->plugin->getConfig()->get("content-message")));
        $form->addButton($this->plugin->check($player, 1));
        $form->addButton($this->plugin->check($player, 2));
        $form->addButton($this->plugin->check($player, 3));
        $form->addButton($this->plugin->check($player, 4));
        $form->addButton($this->plugin->check($player, 5));
        $form->addButton($this->plugin->check($player, 6));
        $form->addButton($this->plugin->check($player, 7));
        $form->addButton($this->plugin->check($player, 8));
        $form->addButton($this->plugin->check($player, 9));
        $form->addButton($this->plugin->check($player, 10));
        $form->addButton($this->plugin->check($player, 11));
        $form->addButton($this->plugin->check($player, 12));
        $form->addButton($this->plugin->check($player, 13));
        $form->addButton($this->plugin->check($player, 14));
        $player->sendForm($form);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        if ($sender instanceof Player) {
            $this->sendForm($sender);
            return true;
        } else {
            $sender->sendMessage(TextFormat::RED . "This command can only be used in-game.");
            return false;
        }
    }
}
