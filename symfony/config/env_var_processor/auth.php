<?php

return [
    'type'           => 'service_account',
    'project_id'     => $_ENV['GOOGLE_PROJECT_ID'] ?? 'my-project-id',
    'private_key_id' => $_ENV['GOOGLE_PRIVATE_KEY_ID'] ?? 'key-id',
    'private_key'    => str_replace('\n', "\n", $_ENV['GOOGLE_PRIVATE_KEY'] ?? ''),
    'client_email'   => $_ENV['GOOGLE_CLIENT_EMAIL'] ?? 'service-account@project.iam.gserviceaccount.com',
];
