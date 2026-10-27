<?php
return [
 'name'=>env('APP_NAME','Ayons le Cœur sur la Main'), 'env'=>env('APP_ENV','production'),
 'debug'=>(bool)env('APP_DEBUG',false), 'url'=>env('APP_URL','http://localhost'),
 'timezone'=>'Africa/Douala', 'locale'=>'fr', 'fallback_locale'=>'fr', 'faker_locale'=>'fr_FR',
 'cipher'=>'AES-256-CBC','key'=>env('APP_KEY'),'previous_keys'=>[],
 'maintenance'=>['driver'=>'file'],
];
