<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 06:56 PM
 */

namespace App\ControllerHelpers;


use App\Controller\GE3PController;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

class DataControllerHelper
{


    private $GE3PController;

    private $dataContainAssociation = array('DataValue' => array('Attributes' => array(
        'fields' => array(
            'DataValueAttributeRel.data_value_id',
            'DataValueAttributeRel.attribute_id',
            'DataValueAttributeRel.attribute_value',
            'Attributes.attribute_name'))));

    /**
     * AttributesControllerHelper constructor.
     * @param GE3PController $GE3PController
     */
    public function __construct(GE3PController $GE3PController)
    {
        $this->GE3PController = $GE3PController;
    }

    public function getAllData()
    {
        $result = null;
        try {
            $dataTable = TableRegistry::get("Data");
            $queryResult = $dataTable->find()
                ->contain($this->dataContainAssociation)
                ->enableHydration(false);

            if (!$this->GE3PController->isTheCursorEmpty($queryResult)) {
                $result = $queryResult->toArray();
            } else {
                throw new \Exception("No Data found");
            }

            $result = $this->deleteJoinMetaDataFromDataResult($result);

        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }
        return $result;
    }

    public function getById($dataId)
    {
        $result = null;
        try {
            $dataTable = TableRegistry::get("Data");
            $queryResult = $dataTable->find()
                ->where(array('data_id' => $dataId))
                ->contain($this->dataContainAssociation)
                ->enableHydration(false);

            if (!$this->GE3PController->isTheCursorEmpty($queryResult)) {
                $result = $queryResult->toArray();
            } else {
                throw new \Exception("No Data found");
            }


            $result = $this->deleteJoinMetaDataFromDataResult($result);

        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }
        return $result;
    }


    public function saveTransactional($object)
    {
        try {
            $dataTable = TableRegistry::get("Data");
            $dataValuesRelTable = TableRegistry::get("DataValuesRel");
            $connection = $dataTable->getConnection();
            $savedObject = $connection->transactional(function () use ($dataTable, $dataValuesRelTable, $object) {

                $savedObject = array();

                if (isset($object) && isset($object['data'])) {

                    $dataSaved = $this->save($object['data'], $dataTable);

                    array_push($savedObject, array('data' => $dataSaved));

                    if (isset($object['data_values']) && is_array($object['data_values']) && sizeof($object['data_values']) > 0) {

                        $DataValuesSuccessfullyAssociated = $this->associateDataValues($object['data_values'], $dataSaved['data_id'], $dataValuesRelTable);

                        array_push($savedObject, array('data_values' => $DataValuesSuccessfullyAssociated));

                    } else {

                        //se eliminan primero todos los data values previamente asociados
                        $dataValuesRelTable->deleteAll(array('data_id' => $dataSaved['data_id']));

                        Log::warning("Data saved without data values");
                    }

                } else {

                    throw new \Exception("the data transactional object to save is not in a valid format or is null");

                }

                return $savedObject;
            });

            return $savedObject;

        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }
    }


    private function save($data, $dataTable)
    {
        try {
            if (!isset($dataTable)) {
                $dataTable = TableRegistry::get("Data");
            }
            $dataEntity = $dataTable->newEntity($data);
            $saveResult = $dataTable->save($dataEntity);
            if (!$saveResult) {
                throw new \Exception("Problem Saving the Data: " . json_encode($data));
            }
            return $saveResult;
        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }
    }

    private function associateDataValues($dataValues, $dataId, $dataValuesRelTable)
    {
        try {
            if (!isset($dataValuesRelTable)) {
                $dataValuesRelTable = TableRegistry::get("DataValuesRel");
            }
            if (is_array($dataValues)) {

                //se eliminan primero todos los data values previamente asociados, a fin de no repetir datos para un objeto tipo data
                $dataValuesRelTable->deleteAll(array('data_id' => $dataId));

                // por cada data_value recibido se asocian al data indicado
                $associationsStored = array();
                foreach ($dataValues as $dataValue) {

                    $dataValueRelAssociationEntity = array(
                        'data_id' => $dataId,
                        'data_value_id' => $dataValue['data_value_id']);

                    $dataValueRelAssociationEntity = $dataValuesRelTable->newEntity($dataValueRelAssociationEntity);

                    $associationSaved = $dataValuesRelTable->save($dataValueRelAssociationEntity);

                    array_push($associationsStored, array($associationSaved));

                }

                return $associationsStored;

            } else {
                throw new \Exception("No data values to associate to the object data " . $dataId);
            }
        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }

    }

    private function deleteJoinMetaDataFromDataResult($data)
    {
        foreach ($data as &$row) {

            foreach ($row['data_value'] as &$dataValues) {

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
        return $data;
    }


}