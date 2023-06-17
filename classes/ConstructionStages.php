<?php

class ConstructionStages
{
	private $db;

	public function __construct()
	{
		$this->db = Api::getDb();
	}

	public function getAll()
	{
		$stmt = $this->db->prepare("
			SELECT
				ID as id,
				name, 
				strftime('%Y-%m-%dT%H:%M:%SZ', start_date) as startDate,
				strftime('%Y-%m-%dT%H:%M:%SZ', end_date) as endDate,
				duration,
				durationUnit,
				color,
				externalId,
				status
			FROM construction_stages
		");
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getSingle($id)
	{
		$stmt = $this->db->prepare("
			SELECT
				ID as id,
				name, 
				strftime('%Y-%m-%dT%H:%M:%SZ', start_date) as startDate,
				strftime('%Y-%m-%dT%H:%M:%SZ', end_date) as endDate,
				duration,
				durationUnit,
				color,
				externalId,
				status
			FROM construction_stages
			WHERE ID = :id
		");
		$stmt->execute(['id' => $id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	public function post(ConstructionStagesCreate $data)
	{
		$stmt = $this->db->prepare("
			INSERT INTO construction_stages
			    (name, start_date, end_date, duration, durationUnit, color, externalId, status)
			    VALUES (:name, :start_date, :end_date, :duration, :durationUnit, :color, :externalId, :status)
			");
		$stmt->execute([
			'name' => $data->name,
			'start_date' => $data->startDate,
			'end_date' => $data->endDate,
			'duration' => $data->duration,
			'durationUnit' => $data->durationUnit,
			'color' => $data->color,
			'externalId' => $data->externalId,
			'status' => $data->status,
		]);
		return $this->getSingle($this->db->lastInsertId());
	}


    public function patch($id, ConstructionStagesUpdate $data)
    {
        $constructionStage = $this->getSingle($id);
        foreach ($data as $key => $value) {
            if ($key === 'status') {
                if (!in_array($value, ['NEW', 'PLANNED', 'DELETED'])) {
                    http_response_code(400);
                    echo json_encode(array('message' => 'Invalid status value'));
                    return;
                }
                $constructionStage[0][$key] = $value;
            } else {
                $constructionStage[0][$key] = $value;
            }
        }

        $stmt = $this->db->prepare("
        UPDATE construction_stages
        SET name = :name,
            start_date = :start_date,
            end_date = :end_date,
            duration = :duration,
            durationUnit = :durationUnit,
            color = :color,
            externalId = :externalId,
            status = :status
        WHERE ID = :id
    ");
        $stmt->execute([
            'id' => $id,
            'name' => $constructionStage[0]['name'],
            'start_date' => $constructionStage[0]['startDate'],
            'end_date' => $constructionStage[0]['endDate'],
            'duration' => $constructionStage[0]['duration'],
            'durationUnit' => $constructionStage[0]['durationUnit'],
            'color' => $constructionStage[0]['color'],
            'externalId' => $constructionStage[0]['externalId'],
            'status' => $constructionStage[0]['status'],
        ]);

        // Return a success response
        http_response_code(200);
        echo json_encode(array('message' => 'Construction stage updated successfully'));
    }

    public function delete($id)
    {
        $constructionStage = $this->getSingle($id);
        if (empty($constructionStage)) {
            http_response_code(404);
            echo json_encode(array('message' => 'Construction stage not found'));
            return;
        }
        $constructionStage['status'] = 'DELETED';
        $this->patch($id, $constructionStage);

        // Başarılı yanıtı döndür
        http_response_code(200);
        echo json_encode(array('message' => 'Construction stage deleted successfully'));
    }





}