<?php

namespace Fadhel\ProjectileTrails;

use pocketmine\entity\projectile\Projectile;
use pocketmine\player\Player;
use pocketmine\scheduler\Task as PMTask;

class Task extends PMTask {

    private $plugin;
    private $projectile;

    public function __construct(Main $plugin, Projectile $projectile) {
        $this->plugin = $plugin;
        $this->projectile = $projectile;
    }

    public function onRun(): void {
        if ($this->projectile->isClosed() || !$this->projectile->isAlive()) {
            $this->getHandler()->cancel();
            return;
        }
        $player = $this->projectile->getOwningEntity();
        if ($player instanceof Player) {
            $this->plugin->spawnParticle($player, $this->projectile);
        }
    }
}
