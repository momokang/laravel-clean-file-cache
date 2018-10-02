<?php

namespace momokang\CleanFileCache;

use Config;
use Storage;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanFileCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:cleanup {hours?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired laravel cache files if system is using file as cache driver';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $diffHours = 24; // Default: delete cache file over 24 hours

        $hours = $this->argument('hours');
        if ($hours) {
            $diffHours = $hours;
        }

        // Set cache file config
        Config::set("filesystems.disks.cachefile", [
            'driver' => 'local',
            'root' => storage_path('framework/cache')
        ]);

        $file_removed = 0;
        $folder_removed = 0;

        // Get cache files
        $cachefiles = Storage::disk('cachefile')->allFiles();

        foreach ($cachefiles as $cachefile) {
            // Ignore that file
            if ($cachefile == '.gitignore') {
                continue;
            }

            // Get last modified from the file
            $time = Storage::disk('cachefile')->lastModified($cachefile);

            $now = Carbon::now('UTC');
            $time = Carbon::parse('@' . $time);

            // Compare with time
            if ($time->diffInHours($now) > $diffHours) {
                Storage::disk('cachefile')->delete($cachefile);
                $file_removed++;
            }
        }

        $folder_removed = $this->_deleteEmptyDir();

        $this->line('Total expired cache files removed: ' . $file_removed);
        $this->line('Total folder removed: ' . $folder_removed);
    }

    /**
     * Remove empty directory of path
     * @param string $path
     */
    protected function _deleteEmptyDir($path = '/')
    {
        $folder_removed = 0;
        $directories = Storage::disk('cachefile')->directories($path);
        foreach ($directories as $directory) {
            if (Storage::disk('cachefile')->directories($directory)) {
                $this->_deleteEmptyDir($directory);
            }

            if (Storage::disk('cachefile')->files($directory) ||
                Storage::disk('cachefile')->directories($directory)) {
                continue;
            }

            Storage::disk('cachefile')->deleteDirectory($directory);
            $folder_removed++;
        }
        return $folder_removed;
    }
}
