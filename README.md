# php-signal-handler

A new and taste way to handle signal from your operational system.
If you need to detect when some other process ask your script to stop, this is the library you are looking for.

Internally We use [pcntl-signal](http://php.net/manual/en/function.pcntl-signal.php) PHP function, so, if you wanna to create your own code to detect this signals, read the docs of this function is a good start.

## Installing

Execute `composer require stavarengo/php-signal-handler`

## How to use it
We use the observer patner to notify listeners when the signal it wants to listen arrives.

So, first you need to implement the interface `\Sta\PhpSignalHandler\Listener`.

After that, just call `\Sta\PhpSignalHandler\SignalHandler::attach(array(SIGTERM), $listener)`, where:
 1. `SIGTERM` is one of the constants about signals that PHP offers to us ([see here](http://php.net/manual/en/function.pcntl-signal.php)).
 2. You will only notified about the signals you passed as the first parameter, in the example above, only the signal `SIGTERM`.
 2. `$listener` is an instance of a class that implements the interface `\Sta\PhpSignalHandler\Listener`.

Thats all! When your script receive a signal that you are interested, the listener will be notified.
