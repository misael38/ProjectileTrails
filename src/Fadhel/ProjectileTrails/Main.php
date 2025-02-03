<?php

declare(strict_types=1);

namespace Fadhel\ProjectileTrails;

use Fadhel\ProjectileTrails\command\Trails;
use pocketmine\color\Color;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\world\particle\AngryVillagerParticle;
use pocketmine\world\particle\DustParticle;
use pocketmine\world\particle\EnchantParticle;
use pocketmine\world\particle\EnchantmentTableParticle;
use pocketmine\world\particle\ExplodeParticle;
use pocketmine\world\particle\FlameParticle;
use pocketmine\world\particle\HappyVillagerParticle;
use pocketmine\world\particle\HeartParticle;
use pocketmine\world\particle\LavaDripParticle;
use pocketmine\world\particle\LavaParticle;
use pocketmine\world\particle\PortalParticle;
use pocketmine\world\particle\WaterDripParticle;
use pocketmine\world\particle\WaterParticle;
use SQLite3;

class Main extends PluginBase
{

    protected $database;

    protected $particles =
        [
            1 => "Angry Villager", 2 => "Enchantment", 3 => "Explode",
            4 => "Happy Villager", 5 => "Heart", 6 => "Flame",
            7 => "Lava", 8 => "Lava Drip", 9 => "Portal",
            10 => "Rainbow Dust", 11 => "Enchant", 12 => "Water",
            13 => "Water Drip", 14 => "Turn Off"
        ];

    public function onEnable(): void
    {
        $this->getServer()->getCommandMap()->register("projectiletrails", new Trails($this));
        $this->getServer()->getPluginManager()->registerEvents(new Listeners($this), $this);
        $this->database = new SQLite3($this->getDataFolder() . "players.db");
        $this->database->exec("CREATE TABLE IF NOT EXISTS players(player VARCHAR(16), particle INT DEFAULT 0);");
        $this->info();
    }
    
    protected function info(): void
    {
        if ($this->getConfig()->get("enable-arrow") === true) {
            $this->getServer()->getLogger()->info("[ProjectileTrails] Arrow enabled!");
        }
        if ($this->getConfig()->get("enable-egg") === true) {
            $this->getServer()->getLogger()->info("[ProjectileTrails] Egg enabled!");
        }
        if ($this->getConfig()->get("enable-snowball") === true) {
            $this->getServer()->getLogger()->info("[ProjectileTrails] Snowball enabled!");
        }
    }

    public function getParticle(string $player): int
    {
        $stmt = $this->database->prepare("SELECT particle FROM players WHERE player = :player");
        $stmt->bindValue(":player", strtolower($player));
        $result = $stmt->execute();
        return (int)$result->fetchArray(SQLITE3_ASSOC)["particle"];
    }

    public function spawnParticle($player, $projectile): void
    {
        switch ($this->getParticle($player->getName())) {
            case 1:
                $world = $projectile->getWorld();
                $position = $projectile->getPosition();
                $world->addParticle($position, new AngryVillagerParticle());
                break;
            case 2:
                $world = $projectile->getWorld();
                $position = $projectile->getPosition();
                $world->addParticle($position, new EnchantmentTableParticle());
                break;
            case 3:
                $world = $projectile->getWorld();
                $position = $projectile->getPosition();
                $world->addParticle($position, new ExplodeParticle());
                break;
            case 4:
                $world = $projectile->getWorld();
                $position = $projectile->getPosition();
                $world->addParticle($position, new HappyVillagerParticle());
                break;
            case 5:
                $world = $projectile->getWorld();
                $position = $projectile->getPosition();
                $world->addParticle($position, new HeartParticle(1));
                break;
            case 6:
                $world = $projectile->getWorld();
                $position = $projectile->getPosition();
                $world->addParticle($position, new FlameParticle());
                break;
            case 7:
                $world = $projectile->getWorld();
                $position = $projectile->getPosition();
                $world->addParticle($position, new LavaParticle());
                break;
            case 8:
                $world = $projectile->getWorld();
                $position = $projectile->getPosition();
                $world->addParticle($position, new LavaDripParticle());
                break;
            case 9:
                $world = $projectile->getWorld();
                $position = $projectile->getPosition();
                $world->addParticle($position, new PortalParticle());
                break;
            case 10:
                $world = $projectile->getWorld();
                $position = $projectile->getPosition();
                $world->addParticle($position, new DustParticle(new Color(mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255))));
                break;
            case 11:
                $world = $projectile->getWorld();
                $position = $projectile->getPosition();
                $world->addParticle($position, new EnchantParticle(new Color(mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255))));
                break;
            case 12:
                $world = $projectile->getWorld();
                $position = $projectile->getPosition();
                $world->addParticle($position, new WaterParticle());
                break;
            case 13:
                $world = $projectile->getWorld();
                $position = $projectile->getPosition();
                $world->addParticle($position, new WaterDripParticle());
                break;
            case 14:
        }
    }

    public function sync(string $playerName): void
    {
        $result = $this->database->querySingle("SELECT particle FROM players WHERE player = '$playerName'", true);
        if (!$result) {
            $this->database->exec("INSERT INTO players (player, particle) VALUES ('$playerName', 0)");
        }
    }

    public function check(Player $player, int $particle): string
    {
        if ($player->hasPermission("projectiletrails." . strtolower(str_replace(" ", "", $this->particles[$particle])))) {
            $message = TextFormat::BOLD . TextFormat::GREEN . $this->particles[$particle];
        } else {
            $message = TextFormat::BOLD . TextFormat::RED . $this->particles[$particle];
        }
        if ($this->getParticle($player->getName()) === $particle) {
            $message = TextFormat::BOLD . TextFormat::YELLOW . $this->particles[$particle];
        }
        return $message;
    }

    public function updateParticle(Player $player, int $particle): void
    {
        if ($this->getParticle($player->getName()) === $particle && $particle < 14) {
            $player->sendMessage(TextFormat::colorize(str_replace("{particle}", $this->particles[$particle], $this->getConfig()->get("error-same"))));
            return;
        }
        if ($player->hasPermission("projectiletrails." . strtolower(str_replace(" ", "", $this->particles[$particle])))) {
            $stmt = $this->database->prepare("UPDATE players SET particle = :particle WHERE player = :player");
            $stmt->bindValue(":particle", $particle);
            $stmt->bindValue(":player", strtolower($player->getName()));
            $stmt->execute();
            $player->sendMessage($particle < 14 ? TextFormat::colorize(str_replace("{particle}", $this->particles[$particle], $this->getConfig()->get("change-message"))) : TextFormat::colorize($this->getConfig()->get("disable-message")));
        } else {
            $player->sendMessage(TextFormat::colorize(str_replace("{particle}", $this->particles[$particle], $this->getConfig()->get("error-perms"))));
        }
    }
}
