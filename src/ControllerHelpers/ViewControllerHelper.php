<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 06:16 PM
 */

namespace App\ControllerHelpers;

use App\Controller\GE3PController;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

class ViewControllerHelper
{

    private $GE3PController;

    private $viewModelContainAssociation = array('Data' => array('DataValue' => array('Attributes' => array(
        'fields' => array(
            'DataValueAttributeRel.data_value_id',
            'DataValueAttributeRel.attribute_id',
            'DataValueAttributeRel.attribute_value',
            'Attributes.attribute_name')))));


    function __construct(GE3PController $GE3PController)
    {
        $this->GE3PController = $GE3PController;
    }

    public function getAllViews()
    {
        $result = null;
        try {
            $viewsTable = TableRegistry::get("Views");
            $queryResult = $viewsTable->find()
                ->contain($this->viewModelContainAssociation)
                ->enableHydration(false);

            if (!$this->GE3PController->isTheCursorEmpty($queryResult)) {
                $result = $queryResult->toArray();
            } else {
                throw new \Exception("No views found");
            }

            $result = $this->deleteJoinMetaDataFromViewsResult($result);
        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception("Problems getting the system views");
        }
        return $result;
    }


    public function getById($viewId)
    {
        $result = null;
        try {

            $viewsTable = TableRegistry::get("Views");
            $queryResult = $viewsTable->find()
                ->where(array('Views.view_id' => $viewId))
                ->contain($this->viewModelContainAssociation)
                ->enableHydration(false);

            if (!$this->GE3PController->isTheCursorEmpty($queryResult)) {
                $result = $queryResult->toArray();
            } else {
                throw new \Exception("No views found");
            }

            $result = $this->deleteJoinMetaDataFromViewsResult($result);
        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception("Problems getting the system view by the id " . $viewId);
        }
        return $result;
    }





    private function deleteJoinMetaDataFromViewsResult($viewsValues)
    {
        foreach ($viewsValues as &$views) {

            foreach ($views['data'] as &$data) {

                if (isset($data['_joinData'])) {
                    unset($data['_joinData']);
                }


                foreach ($data['data_value'] as &$dataValues) {

                    if (isset($dataValues['_joinData'])) {
                        unset($dataValues['_joinData']);
                    }

                    foreach ($dataValues['attributes'] as &$attributes) {

                        if (isset($attributes['_joinData'])) {

                            unset($attributes['_joinData']);
                        }

                    }

                }

            }

        }
        return $viewsValues;
    }

}