<?php
namespace Sta\PhpSignalHandler;

declare(ticks = 1);

class SignalHandler
{
    /**
     * @var array
     */
    protected static $listeners = [];

    /**
     * @var SignalHandler
     */
    protected static $instance;

    /**
     * SignalHandler constructor.
     */
    private function __construct()
    {
    }

    public static function attach($signal, Listener $listener)
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        $signals      = (array)$signal;
        $mustRegister = [];

        foreach ($signals as $signalNumber) {
            if (!isset(self::$listeners[$signalNumber])) {
                self::$listeners[$signalNumber] = [];
                $mustRegister[$signalNumber]    = true;
            }

            self::$listeners[$signal][] = $listener;

        }

        foreach ($mustRegister as $signalNumber => $v) {
            pcntl_signal($signalNumber, self::$instance);
        }
    }

    public function __invoke($signal)
    {
        if (isset(self::$listeners[$signal])) {
            /** @var Listener $listener */
            foreach (self::$listeners[$signal] as $listener) {
                $listener->handleSignal($signal);
            }
        }
    }
}
