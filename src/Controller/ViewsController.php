<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 11:35 AM
 */

namespace App\Controller;

use Cake\Log\Log;
use Cake\ORM\TableRegistry;

define("VIEW_CONTROLLER_NAME_SPACE", "Views");

class ViewsController extends GE3PController
{


    public function getAll()
    {
        try {
            //Variables esperadas por el servicio
            $arrayToBeTested = array();
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, VIEW_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $viewsTable = TableRegistry::get("Views");

                $queryResult = $viewsTable->find()
                    ->contain(array('Data' => array('DataValue' => array('Attributes' => array(
                        'fields' => array(
                        'DataValueAttributeRel.data_value_id',
                        'DataValueAttributeRel.attribute_id',
                        'DataValueAttributeRel.attribute_value',
                        'Attributes.attribute_name')
                    )))))->enableHydration(false);



                if (!parent::isTheCursorEmpty($queryResult)) {
                    $queryResult = $queryResult->toArray();
                } else {
                    $queryResult = array();
                }

                $queryResult = $this->deleteJoinMetaDataFromResult($queryResult);

                $result = parent::setSuccessfulResponseWithObject($result, $queryResult);
            }
        } catch (\Exception $e) {
            Log::info($e);
            $result = parent::setExceptionResponse($result, $e);
        }
        parent::returnAJson($result);
    }


    public function deleteJoinMetaDataFromResult($viewsValues)
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