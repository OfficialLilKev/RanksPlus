<?php

namespace RanksPlus;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\permission\PermissionAttachment;
use pocketmine\utils\Config;
use pocketmine\player\Player;

class RankPlugin extends PluginBase implements Listener {

    /** @var Config */
    private $ranks;

    /** @var PermissionAttachment[] */
    private $attachments = [];

    public function onEnable(): void {
        @mkdir($this->getDataFolder());
        $this->saveResource("ranks.yml", false);

        $this->ranks = new Config($this->getDataFolder() . "ranks.yml", Config::YAML);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("RankPlus has been enabled!");
    }

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $this->applyRankToPlayer($player);
    }

    public function onChat(PlayerChatEvent $event): void {
        $player = $event->getPlayer();
        $name = strtolower($player->getName());

        $rankName = $this->getPlayerRankName($name);
        $rankData = $this->ranks->get($rankName);

        $prefix = $rankData['prefix'] ?? "§7";
        // Remove angle brackets from prefix if present
        $prefix = str_replace(['<', '>'], '', $prefix);

        $message = $event->getMessage();

        // Cancel default chat message so we can send our own formatted message
        $event->cancel();

        // Format: PREFIX PlayerName > Message
        $formattedMessage = "{$prefix} {$player->getName()} > {$message}";

        // Send to all online players
        foreach ($this->getServer()->getOnlinePlayers() as $p) {
            $p->sendMessage($formattedMessage);
        }

        // Also log to console
        $this->getServer()->getLogger()->info($formattedMessage);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($command->getName() === "setrank") {
            if (!$sender->hasPermission("ranksplus.setrank")) {
                $sender->sendMessage("§cYou do not have permission to use this command.");
                return true;
            }

            if (count($args) !== 2) {
                $sender->sendMessage("§cUsage: /setrank <rank> <player>");
                return false;
            }

            $rankName = strtoupper($args[0]);
            $playerName = strtolower($args[1]);

            if (!$this->ranks->exists($rankName)) {
                $sender->sendMessage("§cRank '$rankName' does not exist.");
                return true;
            }

            $this->setPlayerRankName($playerName, $rankName);
            $sender->sendMessage("§aSet rank of $playerName to $rankName.");

            $player = $this->getServer()->getPlayerExact($args[1]);
            if ($player !== null) {
                $this->applyRankToPlayer($player);
            }

            return true;
        }
        return false;
    }

    private function applyRankToPlayer(Player $player): void {
        $name = strtolower($player->getName());

        $rankName = $this->getPlayerRankName($name);
        $rankData = $this->ranks->get($rankName);

        if ($rankData === null) {
            $this->getLogger()->warning("Rank '$rankName' does not exist in ranks.yml. Defaulting to Member.");
            $rankData = $this->ranks->get("Member");
        }

        // Remove old attachment if exists
        if (isset($this->attachments[$name])) {
            $player->removeAttachment($this->attachments[$name]);
        }

        $attachment = $player->addAttachment($this);
        $this->attachments[$name] = $attachment;

        if (isset($rankData['permissions']) && is_array($rankData['permissions'])) {
            foreach ($rankData['permissions'] as $permission) {
                $attachment->setPermission($permission, true);
            }
        }

        $prefix = $rankData['prefix'] ?? "§7";
        $suffix = $rankData['suffix'] ?? "";

        $player->setNameTag($prefix . $player->getName() . $suffix);
        $player->setDisplayName($prefix . $player->getName() . $suffix);
        $player->sendPopup($prefix . $suffix);
    }

    private function getPlayerRankName(string $playerName): string {
        $playerRank = $this->ranks->get(strtolower($playerName));
        if (is_array($playerRank) && isset($playerRank['rank'])) {
            return strtoupper($playerRank['rank']);
        }
        return "Member";
    }

    private function setPlayerRankName(string $playerName, string $rankName): void {
        $this->ranks->set(strtolower($playerName), ['rank' => $rankName]);
        $this->ranks->save();
    }
}
