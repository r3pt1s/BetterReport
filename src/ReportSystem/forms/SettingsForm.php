<?php


namespace ReportSystem\forms;


use FormAPI\CustomForm;
use FormAPI\SimpleForm;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\Player;
use ReportSystem\api\API;
use ReportSystem\ReportSystem;
use ReportSystem\tasks\EffectTask;

class SettingsForm {

    private $changes = [];

    public function send(Player $player) {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) {
                return false;
            }

            if ($data == 0) {
                if (API::isNotify($player->getName())) {
                    $this->changes[$player->getName()]["Notify"] = false;
                    $this->send($player);
                } else {
                    $this->changes[$player->getName()]["Notify"] = true;
                    $this->send($player);
                }
            } else if ($data == 1) {
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::BLINDNESS)));
                $player->sendTitle("§aSAVED!", "", -1, 2, -1);
                ReportSystem::getInstance()->getScheduler()->scheduleDelayedTask(new EffectTask($player->getName()), 40);
                if (isset($this->changes[$player->getName()])) {
                    if (isset($this->changes[$player->getName()]["Notify"])) {
                        API::setNotify($player->getName(), $this->changes[$player->getName()]["Notify"]);
                        unset($this->changes[array_search($player->getName(), $this->changes)]);
                    }
                }
            }
        });
        $form->setTitle("§bSettings");
        $form->setContent("§eChoose an Option!");
        $form->addButton("§cNotify\n§8[" . $this->boolToString(API::isNotify($player->getName())) . "§8]");
        $form->addButton("§aSave");
        $player->sendForm($form);
    }

    private function boolToString(bool $value): string {
        $string = "";
        switch ($value) {
            case true:
                $string = "§aActivated§7";
                break;
            case false:
                $string = "§4Deactivated§7";
        }
        return $string;
    }
}