<?php


namespace ReportSystem\report;


class Report {

    private $reviewed = false;
    private $name;
    private $reason;
    private $player;
    private $reporter;

    public function __construct(string $name, string $reason, string $player, string $reporter) {
        $this->name = $name;
        $this->reason = $reason;
        $this->player = $player;
        $this->reporter = $reporter;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }

    /**
     * @param string $reason
     */
    public function setReason(string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @return string
     */
    public function getPlayer(): string
    {
        return $this->player;
    }

    /**
     * @param string $player
     */
    public function setPlayer(string $player): void
    {
        $this->player = $player;
    }

    /**
     * @return string
     */
    public function getReporter(): string
    {
        return $this->reporter;
    }

    /**
     * @param string $reporter
     */
    public function setReporter(string $reporter): void
    {
        $this->reporter = $reporter;
    }

    /**
     * @return bool
     */
    public function isReviewed(): bool
    {
        return $this->reviewed;
    }

    /**
     * @param bool $reviewed
     */
    public function setReviewed(bool $reviewed): void
    {
        $this->reviewed = $reviewed;
    }
}