<?php
return array(
    'enable' => Config::get('app.debug'),

    'prefix' => 'api-docs',

    'paths' => 'app',
    'output' => 'docs',
    'exclude' => null,
    'default-base-path' => null,
    'default-api-version' => null,
    'default-swagger-version' => null,
    'api-doc-template' => null,
    'suffix' => '.{format}',

    'title' => 'Swagger UI'
);