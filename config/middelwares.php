<?php

return [
    'auth'        => 'System\\Http\\Middelwares\\AuthenticateMiddelware',
    'permissions' => 'System\\Http\\Middelwares\\PermissionsMiddelware',
    'ajax'        => 'System\\Http\\Middelwares\\AjaxMiddelware',
    'redirect'    => 'System\\Http\\Middelwares\\RedirectMiddelware',
];