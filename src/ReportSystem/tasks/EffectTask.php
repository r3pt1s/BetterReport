<?php


namespace ReportSystem\tasks;


use pocketmine\entity\Effect;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use ReportSystem\forms\ReportForm;

class EffectTask extends Task {

    private $player;

    public function __construct(string $player) {
        $this->player = $player;
    }

    public function onRun(int $currentTick) {
        $player = Server::getInstance()->getPlayer($this->player);
        if ($player != null) {
            $player->removeEffect(Effect::BLINDNESS);
            $form = new ReportForm();
            $form->send($player);
        } else {
            $this->getHandler()->cancel();
        }
    }
}