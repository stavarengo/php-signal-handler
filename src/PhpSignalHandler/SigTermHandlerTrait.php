<?php

namespace Sta\PhpSignalHandler;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Sta\PhpSignalHandler\Exception\ClassMustImplementListenerInterface;

/**
 * This trait turns any class in a listener of the signal SIGTERM.
 * The SIGTERM signal is an standard signal sent by the S.O. that means: "Save anything you need and close, please".
 * In another words, SIGTERM is a signal sent by the S.O. when it wants your app to be closed, but instead of just
 * kill your app, the S.O. is  politely asking you to close ASAP.
 *
 * @package Sta\PhpSignalHandler
 */
trait SigTermHandlerTrait
{
    /**
     * @var bool
     */
    protected $sigTermReceived = false;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $_sigTermHandlerTraitLogger;

    /**
     * SignalHandlerTrait constructor.
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @throws \Sta\PhpSignalHandler\Exception\ClassMustImplementListenerInterface
     * @internal param array $signals
     */
    public function __construct(LoggerInterface $logger = null)
    {
        if (!isset(class_implements(self::class)[Listener::class])) {
            throw new ClassMustImplementListenerInterface(
                sprintf('The class that wants to use this Trait must implement the interface %s.', Listener::class)
            );
        }

        /** @var \Sta\PhpSignalHandler\Listener $this */
        SignalHandler::attach(SIGTERM, $this);
        $this->_sigTermHandlerTraitLogger = $logger;
    }

    /**
     * Override this method if you need to execute something before existing.
     */
    protected function executeItBeforeExit()
    {
    }

    /**
     * Override this method if you want to be notified when your app receives an SIGTERM.
     */
    protected function executeItWhenSigTermArrives()
    {
    }

    /**
     * You shoud invoke this method everytime your app have a change to check for SIGTERM and then exit.
     */
    public function exitIfAsked()
    {
        if ($this->sigTermReceived) {
            $this->_sigTermHandlerTraitLog(LogLevel::NOTICE, 'Existing because I received an SIGTERM signal.');
            $this->executeItBeforeExit();

            exit;
        }
    }

    /**
     * Implementation of {@link \Sta\PhpSignalHandler\Listener::handleSignal()}
     *
     * @param $signal
     */
    public function handleSignal($signal)
    {
        $this->_sigTermHandlerTraitLog(LogLevel::NOTICE, 'Received a SIGTERM signal.');
        $this->sigTermReceived = true;
    }

    private function _sigTermHandlerTraitLog($priority, $msg, $extra = [])
    {
        if (!$this->_sigTermHandlerTraitLogger) {
            return;
        }

        if (is_array($msg)) {
            $msg = call_user_func_array('sprintf', $msg);
        }

        $msg = get_class($this) . ': ' . $msg;
        $this->_sigTermHandlerTraitLogger->log($priority, $msg, $extra);
    }
}
