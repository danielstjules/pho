<?php

namespace pho\Watcher;

class Watcher
{
    private $paths;

    private $modifiedTimes;

    private $listeners;

    /**
     * Creates a new instance of Watcher.
     */
    public function __construct()
    {
        $this->modifiedTimes = [];
        $this->listeners = [];
        $this->paths = [];
    }

    /**
     * Adds a callable to be invoked when a path being watched is modified.
     * All listeners are invoked in the order in which they were added.
     *
     * @param callbable $listener The listener to invoke on change
     */
    public function addListener(callable $listener)
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
        $this->paths[] = $path;
        $this->addModifiedTimes($path);
    }

    /**
     * Starts the watcher, monitoring the paths for any changes. When a change
     * is detected, all listeners are invoked, and the list of paths is once
     * again traversed to acquire updated modification timestamps.
     */
    public function watch()
    {
        while (true) {
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

            sleep(1);
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
