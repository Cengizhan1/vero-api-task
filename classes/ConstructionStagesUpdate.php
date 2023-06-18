<?php

class ConstructionStagesUpdate
{
    public $name;
    public $startDate;
    public $endDate;
    public $duration;
    public $durationUnit;
    public $color;
    public $externalId;
    public $status;

    public function __construct($data)
    {

        if (is_object($data)) {
            $vars = get_object_vars($this);
            foreach ($vars as $name => $value) {
                if (isset($data->$name)) {
                    $this->$name = $data->$name;
                }
            }
            var_dump($this);
        }else{
            $this->name=$data['name'];
            $this->startDate=$data['startDate'];
            $this->endDate=$data['endDate'];
            $this->duration=$data['duration'];
            $this->durationUnit=$data['durationUnit'];
            $this->color=$data['color'];
            $this->externalId=$data['externalId'];
            $this->status=$data['status'];
            var_dump($this);

        }
    }

    public function validate()
    {
        return ConstructionStagesValidation::validate($this);
    }
}
