<?php
namespace Sta\PhpSignalHandler;

declare(ticks = 1);

use Sta\PhpSignalHandler\Exception\ExtensionNoLoaded;
use Sta\PhpSignalHandler\Exception\RequiredFunctionIsDisabled;

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

    /**
     * @param $signal
     * @param Listener $listener
     * @return bool
     * @throws ExtensionNoLoaded
     * @throws RequiredFunctionIsDisabled
     */
    public static function attach($signal, Listener $listener)
    {
        if (!self::$instance) {
            if (!extension_loaded('pcntl')) {
                throw ExtensionNoLoaded::create('pcntl');
            }

            $disabledFunctions = explode(',', ini_get('disable_functions'));
            if (in_array('pcntl_signal', $disabledFunctions)) {
                throw RequiredFunctionIsDisabled::create('pcntl_signal');
            }

            self::$instance = new self();
        }

        $signals      = (array)$signal;
        $mustRegister = [];

        foreach ($signals as $signalNumber) {
            if (!isset(self::$listeners[$signalNumber])) {
                self::$listeners[$signalNumber] = [];
                $mustRegister[$signalNumber]    = true;
            }

            self::$listeners[$signalNumber][] = $listener;

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
