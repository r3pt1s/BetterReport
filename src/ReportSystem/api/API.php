<?php


namespace ReportSystem\api;


use pocketmine\utils\Config;
use ReportSystem\ReportSystem;

class API {

    public static function setNotify(string $player, bool $value) {
        $cfg = self::getConfig();
        $cfg->setNested($player . ".Notify", $value);
        $cfg->save();
    }

    public static function isNotify(string $player): bool {
        return self::getConfig()->getNested($player . ".Notify");
    }

    public static function initPlayer(string $player) {
        self::setNotify($player, false);
    }

    public static function getConfig(): Config {
        return new Config(ReportSystem::getInstance()->getDataFolder() . "players.yml", 2);
    }
}