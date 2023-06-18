<?php

/**
 * Class ConstructionStagesCreate
 *
 * This class represents the data structure used to create a new construction stage.
 */
class ConstructionStagesCreate
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
     * ConstructionStagesCreate constructor.
     *
     * @param object|null $data An object containing the creation data.
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
