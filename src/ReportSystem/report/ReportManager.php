<?php


namespace ReportSystem\report;


use pocketmine\Server;
use pocketmine\utils\Config;
use ReportSystem\api\API;
use ReportSystem\ReportSystem;

class ReportManager {

    private $reasons = [];

    /** @var Report[] $reports */
    private $reports = [];

    /** @var Report[] $creports */
    private $creports = [];

    public function loadReasons() {
        $cfg = new Config(ReportSystem::getInstance()->getDataFolder() . "reasons.yml", 2);
        foreach ($cfg->getAll() as $reportKey => $reportValues) {
            foreach ($reportValues as $reportReason) {
                if ($reportReason == null || empty($reportReason) || $reportReason == "") continue;
                $this->reasons[strtolower($reportReason)] = $reportReason;
            }
        }

    }

    public function loadReports() {
        foreach ($this->getConfig()->getAll() as $name => $reportData) {
            if (!isset($reportData["Player"]) && !isset($reportData["Reason"]) && !isset($reportData["Reporter"]) && !isset($reportData["Name"])) continue;
            $this->reports[$name] = new Report($name, $reportData["Reason"], $reportData["Player"], $reportData["Reporter"]);
        }
    }

    public function getFreeId() {
        return count($this->getConfig()->getAll(true)) + count($this->getCConfig()->getAll(true)) + 1;
    }

    public function loadCReports() {
        foreach ($this->getCConfig()->getAll() as $name => $reportData) {
            if (!isset($reportData["Player"]) && !isset($reportData["Reason"]) && !isset($reportData["Reporter"]) && !isset($reportData["Name"])) continue;
            $this->creports[$name] = new Report($name, $reportData["Reason"], $reportData["Player"], $reportData["Reporter"]);
        }
    }

    public function getConfig(): Config {
        return new Config(ReportSystem::getInstance()->getDataFolder() . "reports.yml", 2);
    }

    public function getCConfig(): Config {
        return new Config(ReportSystem::getInstance()->getDataFolder() . "creports.yml", 2);
    }

    /** @return Report[] */
    public function getReports(): array {
        return $this->reports;
    }

    public function getReportByName(string $name): ?Report {
        foreach ($this->reports as $report) {
            if ($report->getName() == $name) {
                return $report;
            }
        }
        return null;
    }

    public function createReport(Report $report) {
        $this->reports[$report->getName()] = $report;
        $cfg = $this->getConfig();
        $cfg->setNested($report->getName() . ".Name", $report->getName());
        $cfg->setNested($report->getName() . ".Player", $report->getPlayer());
        $cfg->setNested($report->getName() . ".Reporter", $report->getReporter());
        $cfg->setNested($report->getName() . ".Reason", $report->getReason());
        $cfg->save();

        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if ($player->hasPermission("report.see")) {
                if (API::isNotify($player->getName())) {
                    $player->sendMessage(ReportSystem::getPrefix() . "§aNeuer Report!");
                    $player->sendMessage(ReportSystem::getPrefix() . "§e" . $report->getReporter() . " §ahat §e"  . $report->getPlayer() . " §afür §c§l" . $report->getReason() . " §r§arepoted!");
                }
            }
        }
    }

    public function isClosedReport(string $name): bool {
        foreach ($this->getCReports() as $report) {
            if ($report->getName() == $name) {
                return true;
            }
        }
        return false;
    }

    public function closeReport(Report $report, string $status) {
        unset($this->reports[array_search($report, $this->reports)]);
        $this->creports[$report->getName()] = $report;
        $ocfg = $this->getConfig();
        $ocfg->remove($report->getName());
        $ocfg->save();
        $cfg = $this->getCConfig();
        $cfg->setNested($report->getName() . ".Name", $report->getName());
        $cfg->setNested($report->getName() . ".Player", $report->getPlayer());
        $cfg->setNested($report->getName() . ".Reporter", $report->getReporter());
        $cfg->setNested($report->getName() . ".Reason", $report->getReason());
        $cfg->setNested($report->getName() . ".Status", $status);
        $cfg->save();

        $reporter = $report->getReporter();
        if (($reporterPlayer = Server::getInstance()->getPlayer($reporter)) != null) {
            if ($status == "ACCEPTED") {
                $reporterPlayer->sendMessage(ReportSystem::getPrefix() . "§aDein Report gegen §e" . $report->getPlayer() . " §awurde §2angenommen§a!");
            } else if ($status == "DENIED") {
                $reporterPlayer->sendMessage(ReportSystem::getPrefix() . "§aDein Report gegen §e" . $report->getPlayer() . " §awurde §cabgelehnt§a!");
            }
        } else {
            if ($status != "CLOSED") {
                ReportSystem::getInstance()->reportEdited[$reporter]["Report"] = $report->getName();
                ReportSystem::getInstance()->reportEdited[$reporter]["Reason"] = $report->getReason();
                ReportSystem::getInstance()->reportEdited[$reporter]["Player"] = $report->getPlayer();
                ReportSystem::getInstance()->reportEdited[$reporter]["Status"] = $status;
            }
        }
    }

    public function deleteReport(Report $report) {
        unset($this->reports[array_search($report, $this->reports)]);
        unset($this->creports[array_search($report, $this->reports)]);
        $cfg = $this->getConfig();
        $cfg->remove($report->getName());
        $cfg->save();
        $cfg2 = $this->getCConfig();
        $cfg2->remove($report->getName());
        $cfg2->save();
    }

    public function getCReportByName(string $name): ?Report {
        foreach ($this->creports as $report) {
            if ($report->getName() == $name) {
                return $report;
            }
        }
        return null;
    }

    /** @return Report[] */
    public function getCReports(): array {
        return $this->creports;
    }

    public function getReasons(): array {
        return $this->reasons;
    }
}