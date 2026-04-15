<?php

return [
    'disk_name' => env('MEDIA_DISK', env('FILESYSTEM_DISK', 'public')),
    'max_file_size' => 10 * 1024 * 1024,
];
