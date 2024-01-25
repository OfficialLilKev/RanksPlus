<?php

namespace YourPluginNamespace;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class RankPlugin extends PluginBase implements Listener {

    private $ranks;
    private $permissions;

    public function onEnable() {
        $this->saveResource("ranks.yml", false);
        $this->saveResource("permissions.yml", false);

        $this->ranks = new Config($this->getDataFolder() . "ranks.yml", Config::YAML);
        $this->permissions = new Config($this->getDataFolder() . "permissions.yml", Config::YAML);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("RankPlugin has been enabled!");
    }

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();

        // Get player's rank
        $rank = $this->getPlayerRank($name);

        // Apply permissions
        $player->addAttachment($this, $rank['permissions'], true);

        // Apply prefix and suffix
        $player->setNameTag($rank['prefix'] . $name . $rank['suffix']);
    }

    private function getPlayerRank($playerName) {
        $defaultRank = [
            'prefix' => TextFormat::WHITE,
            'suffix' => TextFormat::RESET,
            'permissions' => [],
        ];

        $playerRank = $this->ranks->get(strtolower($playerName), $defaultRank);

        // Merge player-specific permissions with rank permissions
        $playerPermissions = $this->permissions->get(strtolower($playerName), []);
        $mergedPermissions = array_merge($playerRank['permissions'], $playerPermissions);

        return [
            'prefix' => $playerRank['prefix'],
            'suffix' => $playerRank['suffix'],
            'permissions' => $mergedPermissions,
        ];
    }
}
