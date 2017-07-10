<?php

namespace Stimer;

use React\EventLoop\LoopInterface;
use React\EventLoop\Timer\TimerInterface;

class Timer implements TimerInterface
{
    protected $isPeriodic = false;

    private $loop;
    private $interval;
    private $callback;

    private $data;

    private $isActive = false;

    private $leftInterval;
    private $startTime;

    /** @var  TimerInterface */
    private $internalTimer;

    public function __construct(LoopInterface $loop, float $interval, callable $callback)
    {
        $this->loop = $loop;
        $this->interval = $interval;
        $this->callback = $callback;

        $this->leftInterval = $interval;

        $this->start();
    }

    public function getLoop(): LoopInterface
    {
        return $this->loop;
    }

    public function getInterval(): float
    {
        return $this->interval;
    }

    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }

    public function isPeriodic(): bool
    {
        return $this->isPeriodic;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function cancel()
    {
        $this->isActive = false;
        $this->internalTimer->cancel();
    }

    public function pause()
    {
        $this->loop->futureTick(function () {
            $this->isActive = false;
            $this->internalTimer->cancel();

            $this->leftInterval -= $this->elapsed();
        });
    }

    public function resume()
    {
        $this->loop->futureTick(function () {
            $this->start();
        });
    }

    protected function start()
    {
        $this->isActive = true;
        $this->startTime = microtime(true);
        $this->internalTimer = $this->loop->addTimer($this->leftInterval, $this->callback);
    }

    public function getLeftInterval(): float
    {
        return $this->isActive ? $this->interval - $this->elapsed() : $this->leftInterval;
    }

    private function elapsed()
    {
        return microtime(true) - $this->startTime;
    }
}