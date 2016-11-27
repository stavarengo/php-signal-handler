<?php
namespace Sta\PhpSignalHandler;

interface Listener
{
    public function handleSignal($signal);
}
