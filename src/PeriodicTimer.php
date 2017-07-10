<?php

namespace Stimer;

use React\EventLoop\LoopInterface;

class PeriodicTimer extends Timer
{
    /** @var  Timer */
    private $internalTimer;

    public function __construct(LoopInterface $loop, $interval, callable $callback)
    {
        parent::__construct($loop, $interval, $callback);
        $this->isPeriodic = true;
    }

    public function pause()
    {
        $this->internalTimer->pause();
    }

    public function resume()
    {
        $this->internalTimer->resume();
    }

    protected function start()
    {
        $this->internalTimer = $this->createInternalTimer();
    }

    public function cancel()
    {
        $this->internalTimer->cancel();
    }

    public function getLeftInterval(): float
    {
        return $this->internalTimer->getLeftInterval();
    }

    private function createInternalTimer(): Timer
    {
        return new Timer($this->getLoop(), $this->getInterval(), function () {
            $this->getCallback()();
            $this->internalTimer->cancel();
            $this->internalTimer = $this->createInternalTimer();
        });
    }
}