<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 06/04/18
 * Time: 07:41 AM
 */

namespace App\Controller;


use App\ControllerHelpers\DataValueControllerHelper;
use Cake\Log\Log;

class DataValueController extends GE3PController
{

    public function getAll()
    {
        $result = null;
        try {
            //Variables esperadas por el servicio
            $arrayToBeTested = array();
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, ATTRIBUTE_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $dataValueControllerHelper = new DataValueControllerHelper($this);

                $dataValue = $dataValueControllerHelper->getAllDataValues();

                $result = parent::setSuccessfulResponseWithObject($result, $dataValue);
            }
        } catch (\Exception $e) {
            Log::info("Error, " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            $result = parent::setExceptionResponse($result, $e);
        }
        parent::returnAJson($result);
    }

    public function getById()
    {
        $result = null;
        try {
            //Variables esperadas por el servicio
            $arrayToBeTested = array('data_value_id');
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, ATTRIBUTE_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $dataValueControllerHelper = new DataValueControllerHelper($this);

                $dataValue = $dataValueControllerHelper->getById($jsonObject['data_value_id']);

                $result = parent::setSuccessfulResponseWithObject($result, array($dataValue));
            }
        } catch (\Exception $e) {
            Log::info("Error, " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            $result = parent::setExceptionResponse($result, $e);
        }
        parent::returnAJson($result);
    }


    public function save()
    {
        $result = null;
        try {
            //Variables esperadas por el servicio
            $arrayToBeTested = array('data_value_name', 'language_id', 'data_value_type_id', 'data_value');


            //se recive tambien los atributos a asociar de la siguiente manera
            //$arrayToBeTested = array('data_value_name', 'language_id', 'data_value_type_id', 'data_value',
            // 'attributes'=>array(array('attribute_id','attribute_value')));

            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, ATTRIBUTE_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $dataValueControllerHelper = new DataValueControllerHelper($this);

                $savedDataValue = $dataValueControllerHelper->saveTransactional($jsonObject);

                $result = parent::setSuccessfulResponseWithObject($result, array($savedDataValue));
            }
        } catch (\Exception $e) {
            Log::info("Error, " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            $result = parent::setExceptionResponse($result, $e);
        }
        parent::returnAJson($result);
    }

}