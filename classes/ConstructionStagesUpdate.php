<?php

/**
 * Class ConstructionStagesUpdate
 *
 * This class represents the data structure used to update an existing construction stage.
 */
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

    /**
     * ConstructionStagesUpdate constructor.
     *
     * @param object|array $data An object or array containing the update data.
     */
    public function __construct($data)
    {
        if (is_object($data)) {
            $vars = get_object_vars($this);

            foreach ($vars as $name => $value) {
                if (isset($data->$name)) {
                    $this->$name = $data->$name;
                }
            }
        } else {
            $this->name = $data['name'];
            $this->startDate = $data['startDate'];
            $this->endDate = $data['endDate'];
            $this->duration = $data['duration'];
            $this->durationUnit = $data['durationUnit'];
            $this->color = $data['color'];
            $this->externalId = $data['externalId'];
            $this->status = $data['status'];
        }
    }

    /**
     * Validates the construction stage data.
     *
     * @return array True if the data is valid, false otherwise.
     */
    public function validate()
    {
        return ConstructionStagesValidation::validate($this);
    }
}
