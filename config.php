<?php

return [
  'website' => [
    'lang'         => 'de',
    'charset'      => 'UTF-8',
    'decsription'  => 'Nice Framework',
    'keywords'     => 'framework',
    'auth'         => 'Refat Alsakka',
    'name'         => 'framework',
  ],
  'db' => [
    'server'  => 'localhost',
    'dbname'  => 'mvc',
    'dbuser'  => 'root',
    'dbpass'  => '',
  ],
  'hash' => [
    'password' => PASSWORD_BCRYPT,
    'main'     => 'sha256',
  ],
  'mode' => 'dev',
];
