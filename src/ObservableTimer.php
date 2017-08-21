<?php

namespace Stimer;

use Evenement\EventEmitterTrait;
use React\EventLoop\LoopInterface;

class ObservableTimer extends Timer
{
    const CLOSE_TO_TICK = 1;

    use EventEmitterTrait;

    /** @var Timer[] */
    private $observeTimers = [];

    public function __construct(LoopInterface $loop, $interval, callable $callback, array $settings = [])
    {
        $this->setupTimers($settings);

        parent::__construct($loop, $interval, $callback);
    }

    public function pause()
    {
        foreach ($this->observeTimers as $timer) {
            $timer->pause();
        }
        parent::pause();
    }

    public function resume()
    {
        foreach ($this->observeTimers as $timer) {
            $timer->resume();
        }
        parent::resume();
    }

    public function cancel()
    {
        foreach ($this->observeTimers as $timer) {
            $timer->cancel();
        }
        parent::cancel();
    }

    private function setupTimers(array $settings)
    {
        if (array_key_exists(self::CLOSE_TO_TICK, $settings)) {
            $this->observeTimers[] = new Timer(
                $this->getLoop(),
                $this->getInterval() - $settings[self::CLOSE_TO_TICK],
                function () {
                    $this->emit(self::CLOSE_TO_TICK, [$this]);
                }
            );
        }
    }
}