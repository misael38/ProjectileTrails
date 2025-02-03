<?php

declare(strict_types=1);

namespace Fadhel\ProjectileTrails;

use pocketmine\entity\projectile\Arrow;
use pocketmine\entity\projectile\Egg;
use pocketmine\entity\projectile\Snowball;
use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\player\Player;

class Listeners implements Listener
{
    protected $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onLogin(PlayerLoginEvent $event): void
    {
        $player = $event->getPlayer();
        $this->plugin->sync($player->getName());
    }

    public function onLaunch(ProjectileLaunchEvent $event): void
    {
        $projectile = $event->getEntity();
        $player = $projectile->getOwningEntity();
        if ($player instanceof Player) {
            if ($this->plugin->getConfig()->get("enable-arrow") === true && $projectile instanceof Arrow) {
                $this->plugin->getScheduler()->scheduleRepeatingTask(new Task($this->plugin, $projectile), 1);
            }
            if ($this->plugin->getConfig()->get("enable-egg") === true && $projectile instanceof Egg) {
                $this->plugin->getScheduler()->scheduleRepeatingTask(new Task($this->plugin, $projectile), 1);
            }
            if ($this->plugin->getConfig()->get("enable-snowball") === true && $projectile instanceof Snowball) {
                $this->plugin->getScheduler()->scheduleRepeatingTask(new Task($this->plugin, $projectile), 1);
            }
        }
    }
}
