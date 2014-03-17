<?php

namespace pho\Watcher;

class Watcher
{
    private $paths;

    private $modifiedTimes;

    private $listeners;

    private $inotify;

    /**
     * Creates a new instance of Watcher.
     */
    public function __construct()
    {
        $this->modifiedTimes = [];
        $this->listeners = [];
        $this->paths = [];

        if (function_exists('inotify_init')) {
            $this->inotify = inotify_init();
            
            // use inotify without blocking
            $read = array($this->inotify);
            $write = null;
            $except = null;
            if (false !== stream_select($read, $write, $except, 0)) {
                stream_set_blocking($this->inotify, 0);
            } else {
                unset($this->inotify);
                $this->inotify = null;
            }
        }
    }
    
    /**
     * Cleanup while destructing the instance of Watcher
     */
    public function __destruct()
    {
        if (!is_null($this->inotify)) {
            fclose($this->inotify);
        }
    }

    /**
     * Adds a closure to be invoked when a path being watched is modified.
     * All listeners are invoked in the order in which they were added.
     *
     * @param callbable $listener The listener to invoke on change
     */
    public function addListener(\Closure $listener)
    {
        $this->listeners[] = $listener;
    }

    /**
     * Adds the path to the list of files and folders to monitor for changes.
     * If the path is a file, its modification time is stored for comparison.
     * If a directory, the modification times for each sub-directory and file
     * are recursively stored.
     *
     * @param string $path A valid path to a file or directory
     */
    public function watchPath($path)
    {
        if (is_null($this->inotify)) {
            $this->paths[] = $path;
            $this->addModifiedTimes($path);
        } else {        
            $mask = IN_MODIFY | IN_ATTRIB | IN_CLOSE_WRITE | IN_MOVE | IN_CREATE | IN_DELETE | IN_DELETE_SELF | IN_MOVE_SELF;
            $elementList = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(realpath($path)));
            foreach ($elementList as $element) {
                if ($element->isDir()) {
                    $watchDescriptor = inotify_add_watch($this->inotify, $element->getRealPath(), $mask);
                    $this->paths[$watchDescriptor] = $element->getRealPath();
                }
            }
        }
    }

    /**
     * Starts the watcher, monitoring the paths for any changes.
     */
    public function watch()
    {
        while (true) {
            if (is_null($this->inotify)) {
                $this->watchTimeBased();
            } else {
                $this->watchInotify();
            }

            sleep(1);
        }
    }

    /**
     * Monitor the paths for any changes based on the modify time information. When a change
     * is detected, all listeners are invoked, and the list of paths is once
     * again traversed to acquire updated modification timestamps.
     */
    private function watchTimeBased()
    {
        clearstatcache();
        $modified = false;

        // Loop over stored modified timestamps and compare them to their
        // current value
        foreach ($this->modifiedTimes as $path => $modifiedTime) {
            if ($modifiedTime != stat($path)['mtime']) {
                $modified = true;
                break;
            }
        }

        if ($modified) {
            $this->runListeners();

            // Clear modified times and re-traverse paths
            $this->modifiedTimes = [];
            foreach ($this->paths as $path) {
                $this->addModifiedTimes($path);
            }
        }
    }

    /**
     * Monitor the paths for any changes based on the modify time information. When a change
     * is detected, all listeners are invoked, and the list of paths is once
     * again traversed to acquire updated modification timestamps.
     */
    private function watchInotify()
    {
        $modified = false;

        if (false !== ($eventList = inotify_read($this->inotify))) {
            $modified = true;
            foreach ($eventList as $event) {
                switch (true) {
                    case ($event['mask'] & IN_DELETE_SELF):
                    case ($event['mask'] & IN_MOVE_SELF):
                        $watchDescriptor = $event['wd'];
                        if (true === inotify_rm_watch($this->inotify, $watchDescriptor)) {
                            unset($this->paths[$watchDescriptor]);
                        }
                        break;
                }
            }
        }

        if ($modified) {
            $this->runListeners();
        }
    }

    /**
     * Given the path to a file or directory, recursively adds the modified
     * times of any nested folders to the modifiedTimes property.
     *
     * @param string $path A valid path to a file or directory
     */
    private function addModifiedTimes($path)
    {
        if (is_file($path)) {
            $stat = stat($path);
            $this->modifiedTimes[realpath($path)] = $stat['mtime'];

            return;
        }

        $path = realpath($path);
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

        foreach ($files as $file) {
            $modifiedTime = $file->getMTime();
            $this->modifiedTimes[$file->getRealPath()] = $modifiedTime;
        }
    }

    /**
     * Invokes the listeners in the order in which they were added.
     */
    private function runListeners()
    {
        foreach ($this->listeners as $listener) {
            $listener();
        }
    }
}
