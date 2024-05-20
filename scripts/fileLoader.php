<?php // provide function to list files in uploads directory

declare(strict_types=1);

function uploadsList()
{
    $files = scandir(__DIR__.'/../uploads');
    $files = array_diff($files, ['.', '..', '.git-keep']);
    return $files;
}
