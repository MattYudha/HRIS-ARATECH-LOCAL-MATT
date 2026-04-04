<?php

namespace App\Models;

class KPIRecordProxy
{
    private $data;
    public $kpi;

    public function __construct($data, $kpi = null)
    {
        $this->data = $data;
        $this->kpi = $kpi;
    }

    public function __get($name)
    {
        return $this->data->$name ?? null;
    }

    public function __set($name, $value)
    {
        $this->data->$name = $value;
    }

    public function __call($name, $arguments)
    {
        if ($name === 'getAchievementPercentage') {
            if ($this->data->target_value == 0) {
                return 0;
            }
            return ($this->data->actual_value / $this->data->target_value) * 100;
        }
        if ($name === 'getVariance') {
            return $this->data->actual_value - $this->data->target_value;
        }
        if ($name === 'getPercentageChange') {
            if (is_null($this->data->previous_value) || $this->data->previous_value == 0) {
                return 0;
            }
            return (($this->data->actual_value - $this->data->previous_value) / $this->data->previous_value) * 100;
        }
    }
}
