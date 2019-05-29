<?php

namespace App\Models;

use System\Model;

class SettingsModel extends Model
{
    protected $table = 'settings';

    private $settings;

    public function loadAll()
    {
        foreach($this->all() as $setting) {

            $this->settings[$setting->key] = $setting->value;
        }
    }
    
    public function get($key)
    {
        return array_get($this->settings, $key);
    }

    public function updateSettings()
    {

    }
}