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

class DataValueControllerHelper
{


    private $GE3PController;

    private $dataValueContainAssociation = array('Attributes' => array(
        'fields' => array(
            'DataValueAttributeRel.data_value_id',
            'DataValueAttributeRel.attribute_id',
            'DataValueAttributeRel.attribute_value',
            'Attributes.attribute_name')));

    /**
     * AttributesControllerHelper constructor.
     * @param GE3PController $GE3PController
     */
    public function __construct(GE3PController $GE3PController)
    {
        $this->GE3PController = $GE3PController;
    }

    public function getAllDataValues()
    {
        $result = null;
        try {
            $dataValueTable = TableRegistry::get("DataValue");
            $queryResult = $dataValueTable->find()
                ->contain($this->dataValueContainAssociation)
                ->enableHydration(false);

            if (!$this->GE3PController->isTheCursorEmpty($queryResult)) {
                $result = $queryResult->toArray();
            } else {
                throw new \Exception("No DataValues found");
            }

            $result = $this->deleteJoinMetaDataFromDataValueResult($result);

        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception("Problems getting the system DataValues");
        }
        return $result;
    }

    public function getById($dataValueId)
    {
        $result = null;
        try {
            $dataValueTable = TableRegistry::get("DataValue");
            $queryResult = $dataValueTable->find()
                ->where(array('data_value_id' => $dataValueId))
                ->contain($this->dataValueContainAssociation)
                ->enableHydration(false);

            if (!$this->GE3PController->isTheCursorEmpty($queryResult)) {
                $result = $queryResult->toArray();
            } else {
                throw new \Exception("No DataValues found");
            }

            $result = $this->deleteJoinMetaDataFromDataValueResult($result);

        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception("Problems getting the system DataValues");
        }
        return $result;
    }


    public function saveTransactional($object)
    {
        try {

            $dataValueTable = TableRegistry::get("DataValue");
            $dataValueAttributeRelTable = TableRegistry::get("DataValueAttributeRel");
            $connection = $dataValueTable->getConnection();
            $savedObject = $connection->transactional(function () use ($dataValueTable, $dataValueAttributeRelTable, $object) {

                $savedObject = array();

                if (isset($object) && isset($object['data_value'])) {

                    $dataValueSaved = $this->save($object['data_value'], $dataValueTable);

                    array_push($savedObject, array('data_value' => $dataValueSaved));

                    if (isset($object['attributes']) && is_array($object['attributes']) && sizeof($object['attributes'] > 0)) {

                        $attributesSuccessfullyAssociated = $this->associateAttributes($object['attributes'], $dataValueSaved['data_value_id'], $dataValueAttributeRelTable);

                        array_push($savedObject, array('attributes' => $attributesSuccessfullyAssociated));

                    } else {

                        //se eliminan primero todos los attributos previamente asociados, a fin de no repetir atributos en un data value
                        $dataValueAttributeRelTable->deleteAll(array('data_value_id' => $dataValueSaved['data_value_id']));

                        Log::warning("Data value saved without attributes");
                    }

                } else {

                    throw new \Exception("the data value transactional object to save is not in a valid format or is null");

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


    private function save($dataValue, $dataValueTable)
    {
        try {
            if (!isset($dataValueTable)) {
                $dataValueTable = TableRegistry::get("DataValue");
            }
            $dataValueEntity = $dataValueTable->newEntity($dataValue);
            $saveResult = $dataValueTable->save($dataValueEntity);
            if (!$saveResult) {
                throw new \Exception("Problem Saving the DataValue: " . json_encode($dataValue));
            }
            return $saveResult;
        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }
    }

    private function associateAttributes($attributes, $dataValueId, $dataValueAttributeRelTable)
    {
        try {
            if (!isset($dataValueAttributeRelTable)) {
                $dataValueAttributeRelTable = TableRegistry::get("DataValueAttributeRel");
            }
            if (is_array($attributes)) {

                //se eliminan primero todos los attributos previamente asociados, a fin de no repetir atributos en un data value
                $dataValueAttributeRelTable->deleteAll(array('data_value_id' => $dataValueId));

                // por cada atributo recibido se asocian al data_value indicado
                $associationsStored = array();
                foreach ($attributes as $attribute) {

                    $dataValueAttributeAssociationEntity = array(
                        'data_value_id' => $dataValueId,
                        'attribute_id' => $attribute['attribute_id'],
                        'attribute_value' => $attribute['attribute_value']);

                    $dataValueAttributeAssociationEntity = $dataValueAttributeRelTable->newEntity($dataValueAttributeAssociationEntity);

                    $associationSaved = $dataValueAttributeRelTable->save($dataValueAttributeAssociationEntity);

                    array_push($associationsStored, array($associationSaved));

                }

                return $associationsStored;

            } else {
                throw new \Exception("No attributes to associate to the dataValue " . $dataValueId);
            }
        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }

    }


    private function deleteJoinMetaDataFromDataValueResult($dataValues)
    {
        foreach ($dataValues as &$dataValue) {

            foreach ($dataValue['attributes'] as &$attributes) {

                if (isset($attributes['_joinData'])) {

                    unset($attributes['_joinData']);
                }

            }

        }
        return $dataValues;
    }


}