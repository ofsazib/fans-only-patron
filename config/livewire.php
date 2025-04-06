<?php
$max_upload = min((int)ini_get('post_max_size'), (int)ini_get('upload_max_filesize'));

$max_upload = $max_upload * 1024;

return [
    'temporary_file_upload' => 
        [
            'directory' => sys_get_temp_dir(),
            'rules' => 'mimes:jpeg,png,jpg,gif,mp3,ogg,wav,mp4,ogg,webm,mov,qt,zip|max:' . $max_upload
        ]
];