<?php
declare(strict_types = 1);


namespace Tests\Unit;

use ConstructionStagesCreate;
use ConstructionStagesUpdate;
use PHPUnit\Framework\TestCase;


class ConstructionStagesTest extends TestCase
{

    protected $constructionStages;

    protected function setUp(): void
    {
        $this->constructionStages = new \ConstructionStages();
    }

    public function testGetAll()
    {
        $allStages = $this->constructionStages->getAll();
        $this->assertIsArray($allStages);
        $this->assertNotEmpty($allStages);
    }

    public function testGetSingle()
    {
        $stageId = 1; // Mevcut bir yapım aşama ID'si girin
        $singleStage = $this->constructionStages->getSingle($stageId);
        $this->assertIsArray($singleStage);
        $this->assertArrayHasKey('id', $singleStage);
        $this->assertEquals($stageId, $singleStage['id']);
    }

    public function testPost()
    {
        $newStageData = new ConstructionStagesCreate();
        $newStageData->name = "Yeni Aşama";
        $newStageData->startDate = "2023-05-16T10:00:00Z";
        $newStageData->endDate = "2023-05-20T15:00:00Z";
        $newStageData->duration = 5;
        $newStageData->durationUnit = "DAYS";
        $newStageData->color = "#FF0000";
        $newStageData->externalId = "ABC123";
        $newStageData->status = "NEW";
        $createdStage = $this->constructionStages->post($newStageData);
        $this->assertIsArray($createdStage);
        $this->assertArrayHasKey('id', $createdStage);
    }

    public function testPatch()
    {
        $updateStageId = 2; // Güncellenmek istenen yapım aşama ID'si girin
        $updateStageData = new ConstructionStagesUpdate();
        $updateStageData->name = "Güncellenmiş Aşama";
        $updateStageData->startDate = "2023-05-18T10:00:00Z";
        $updateStageData->endDate = "2023-05-25T15:00:00Z";
        $updateStageData->duration = 7;
        $updateStageData->durationUnit = "DAYS";
        $updateStageData->color = "#00FF00";
        $updateStageData->externalId = "XYZ789";
        $updateStageData->status = "PLANNED";
        $this->constructionStages->patch($updateStageId, $updateStageData);
        $updatedStage = $this->constructionStages->getSingle($updateStageId);
        $this->assertIsArray($updatedStage);
        $this->assertArrayHasKey('name', $updatedStage);
        $this->assertEquals($updateStageData->name, $updatedStage['name']);
    }

    public function testDelete()
    {
        $deleteStageId = 3; // Silinmesi istenen yapım aşama ID'si girin
        $this->constructionStages->delete($deleteStageId);
        $deletedStage = $this->constructionStages->getSingle($deleteStageId);
        $this->assertNull($deletedStage);
    }
}
