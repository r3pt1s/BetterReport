<?php

namespace ReportSystem;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use ReportSystem\commands\ReportCommand;
use ReportSystem\commands\ReportsCommand;
use ReportSystem\listener\EventListener;
use ReportSystem\report\ReportManager;

class ReportSystem extends PluginBase {

    public static function getPrefix(): string {
        return "§3§lReport§bSystem §r§8» §r§7";
    }

    private static $instance;
    private $reportManager;
    public $reportEdited = [];

    public function onEnable() {
        self::$instance = $this;
        $this->saveResource("reasons.yml");
        $this->reportManager = new ReportManager();
        $this->reportManager->loadReasons();
        $this->reportManager->loadReports();
        $this->reportManager->loadCReports();
                $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->registerAll("reportsystem", [
            new ReportCommand("report", "Report Command", "", ["r"]),
            new ReportsCommand("reports", "Reports Command", "", ["rs"])
        ]);
    }

    public static function getInstance(): self {
        return self::$instance;
    }

    public function getReportManager(): ReportManager {
        return $this->reportManager;
    }
}