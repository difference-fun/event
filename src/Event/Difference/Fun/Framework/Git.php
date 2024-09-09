<?php

namespace Event\Difference\Fun\Framework;

use Difference\Fun\App;
use Difference\Fun\Config;

use Difference\Fun\Module\File;

use Exception;

class Git {

    /**
     * @throws Exception
     */
    public static function configure(App $object, $event, $options=[]): void
    {
        if($object->config(Config::POSIX_ID) !== 0){
            return;
        }
        $commands = $object->config('git.command');
        if(is_array($commands)){
            foreach($commands as $command){
                exec($command);
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function restore(App $object, $event, $options=[]): void
    {
        if($object->config(Config::POSIX_ID) !== 0){
            return;
        }
        $dir = $object->config('project.dir.root') .
            '.git' .
            $object->config('ds')
        ;
        $command = 'chown 1000:1000 -R ' . $dir;
        exec($command);
        $source = $object->config('project.dir.data') .
            'Git' .
            $object->config('ds') .
            '.gitconfig'
        ;
        $target = '/root/.gitconfig';
        if(File::exist($source)){
            $command = 'cp ' . $source . ' ' . $target;
            exec($command);
        }
    }
}