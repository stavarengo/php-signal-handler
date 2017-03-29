<?php
namespace Sta\PhpSignalHandler;

/**
 * Interface Listener
 *
 * @package Sta\PhpSignalHandler
 */
interface Listener
{
    /**
     * Method that will be invoked when your app receives an signal.
     *
     * @param $signal
     *      The signal received.
     */
    public function handleSignal($signal);
}
