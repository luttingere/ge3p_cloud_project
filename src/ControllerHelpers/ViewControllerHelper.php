<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 06:16 PM
 */

namespace App\ControllerHelpers;

use App\Controller\GE3PController;
use Cake\Core\Exception\Exception;
use Cake\Filesystem\File;
use Cake\Log\Log;
use Cake\ORM\Table;
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
            throw new \Exception($e->getMessage());
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
            throw new \Exception($e->getMessage());
        }
        return $result;
    }


    public function saveTransactional($object)
    {
        try {

            $viewsTable = TableRegistry::get("Views");
            $viewsDataRelTable = TableRegistry::get("ViewsDataRel");
            $connection = $viewsTable->getConnection();
            $savedObject = $connection->transactional(function () use ($viewsTable, $viewsDataRelTable, $object) {

                $savedObject = array();

                if (isset($object) && isset($object['view'])) {

                    $viewSaved = $this->save($object['view'], $viewsTable);

                    array_push($savedObject, array('view' => $viewSaved));

                    if (isset($object['data']) && is_array($object['data']) && sizeof($object['data'] > 0)) {

                        $dataSuccessfullyAssociated = $this->associateData($object['data'], $viewSaved['view_id'], $viewsDataRelTable);

                        array_push($savedObject, array('data' => $dataSuccessfullyAssociated));

                    } else {

                        //se eliminan primero todos los attributos previamente asociados, a fin de no repetir atributos en un data value
                        $viewsDataRelTable->deleteAll(array('view_id' => $viewSaved['view_id']));

                        Log::warning("View saved without Data");
                    }

                } else {

                    throw new \Exception("the views transactional object to save is not in a valid format or is null");

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


    private function save($viewObject, $viewsTable)
    {
        try {
            if (!isset($viewsTable)) {
                $viewsTable = TableRegistry::get("Views");
            }
            $viewsEntity = $viewsTable->newEntity($viewObject);
            $saveResult = $viewsTable->save($viewsEntity);
            if (!$saveResult) {
                throw new \Exception("Problem Saving the View: " . json_encode($viewObject));
            }
            return $saveResult;
        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }
    }

    private function associateData($data, $viewId, $viewsDataRelTable)
    {
        try {
            if (!isset($viewsDataRelTable)) {
                $viewsDataRelTable = TableRegistry::get("ViewsDataRel");
            }
            if (is_array($data)) {

                //se eliminan primero todos los objetos tipo data previamente asociados, a fin de no repetir Data en un view object
                $viewsDataRelTable->deleteAll(array('view_id' => $viewId));

                // por cada data recibida se asocian al view indicado
                $associationsStored = array();
                foreach ($data as $row) {

                    $viewDataAssociationEntity = array(
                        'view_id' => $viewId,
                        'data_id' => $row['data_id']
                    );

                    $viewDataAssociationEntity = $viewsDataRelTable->newEntity($viewDataAssociationEntity);

                    $associationSaved = $viewsDataRelTable->save($viewDataAssociationEntity);

                    array_push($associationsStored, array($associationSaved));
                }
                return $associationsStored;

            } else {
                throw new \Exception("No Data to associate to the View " . $viewId);
            }
        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }

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

    public function generateViewJSON($viewId)
    {
        try
        {
            $data = $this->getById($viewId);
            $finalPath = $data[0]["json_path"] . $data[0]["view_name"] . ".json";
            $filePath = "/home/pablo_sierra/Cloud_Projects/ge3p_cms_project/" . $finalPath;
            $data = json_encode($data);
            $file = new File($filePath, true);
            $file->write($data);
            $file->close();
            $data = array("path" => $finalPath, "full_path" => $filePath);
            return $data;
        }
        catch (\Exception $e)
        {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }
    }

    public function checkIfChangesArePublished($viewId)
    {
        try
        {
            $data = $this->getById($viewId);
            if($data[0]["published_dev"] == 0)
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        catch (\Exception $e)
        {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }
    }

    public function setViewChanges($viewId, $publish)
    {
        try
        {

            $viewsTable = TableRegistry::get("Views");
            $viewObject = $viewsTable->newEntity();
            $viewObject->view_id = $viewId;
            if($publish)
            {
                if(!$this->checkIfChangesArePublished($viewId))
                {
                    $viewObject->published_dev = 1;
                    $file = file_get_contents("/home/pablo_sierra/Cloud_Projects/ge3p_cms_project/_index.html");
                    $version = new \DateTime();
                    $editedFile = str_replace("@VERSION",$version->getTimestamp(), $file);
                    file_put_contents("/home/pablo_sierra/Cloud_Projects/ge3p_cms_project/index.html", $editedFile);
                }
                else
                {
                    return false;
                }
            }
            else
            {
                $viewObject->published_dev = 0;
            }
            $viewsTable->save($viewObject);
            return true;
        }
        catch (\Exception $e)
        {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }
    }

}