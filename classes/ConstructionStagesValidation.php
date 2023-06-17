<?php

class ConstructionStagesValidation
{
    public static function validate($data)
    {
        $errors = [];

        if (!empty($data->name) && strlen($data->name) > 255) {
            $errors[] = "Geçersiz 'name' alanı. En fazla 255 karakter uzunluğunda olmalıdır.";
        }

        if (!empty($data->startDate) && !preg_match("/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$/", $data->startDate)) {
            $errors[] = "Geçersiz 'startDate' alanı. ISO 8601 formatına uygun tarih ve saat olmalıdır (örn. 2022-12-31T14:59:00Z).";
        }

        if ($data->endDate !== null && !preg_match("/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z$/", $data->endDate)) {
            $errors[] = "Geçersiz 'endDate' alanı. Null veya geçerli bir ISO 8601 formatında tarih ve saat olmalıdır.";
        }

        if ($data->durationUnit !== null && !in_array($data->durationUnit, ['HOURS', 'DAYS', 'WEEKS'])) {
            $errors[] = "Geçersiz 'durationUnit' alanı. HOURS, DAYS veya WEEKS değerlerinden birini içermelidir.";
        }

        if ($data->color !== null && !preg_match("/^#[a-fA-F0-9]{6}$/", $data->color)) {
            $errors[] = "Geçersiz 'color' alanı. Null veya geçerli bir HEX renk kodu (#FF0000 gibi) olmalıdır.";
        }

        if ($data->externalId !== null && strlen($data->externalId) > 255) {
            $errors[] = "Geçersiz 'externalId' alanı. En fazla 255 karakter uzunluğunda bir dize olmalıdır.";
        }

        if ($data->status !== null && !in_array($data->status, ['NEW', 'PLANNED', 'DELETED'])) {
            $errors[] = "Geçersiz 'status' alanı. NEW, PLANNED veya DELETED değerlerinden birini içermelidir.";
        }

        return $errors;
    }
}

