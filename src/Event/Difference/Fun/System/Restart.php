<?php

namespace Event\Difference\Fun\System;

use Event\Difference\Fun\Framework\Email;

use Difference\Fun\App;
use Difference\Fun\Config;

use Difference\Fun\Module\Controller;
use Difference\Fun\Module\Core;
use Difference\Fun\Module\Event;

use Exception;

use Difference\Fun\Exception\ObjectException;

class Restart {

    /**
     * @throws ObjectException
     */
    public static function execute(App $object, $event, $options=[]): void
    {
        if($object->config(Config::POSIX_ID) !== 0){
            return;
        }
        $command = 'ps -aux';
        $object->config('core.execute.mode', Core::STREAM);
        Core::execute($object, $command, $output);
        $output = explode(PHP_EOL, $output);
        foreach($output as $line){
            if(strpos($line, '/usr/bin/start') !== false){
                $line = trim($line);
                $line = preg_replace('/\s+/', ' ', $line);
                $line = explode(' ', $line);
                $pid = $line[1];
                Event::Trigger(
                    $object,
                    'cli.system.restart.notification',
                    $options
                );
                $command = 'kill -9 ' . $pid;
                Core::execute($object, $command);
                break;
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function notification(App $object, $event, $options=[]): void
    {
        $action = $event->get('action');
        Email::queue(
            $object,
            $action,
            $options
        );
    }
}