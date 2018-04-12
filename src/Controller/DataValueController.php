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


define("DATA_VALUE_CONTROLLER_NAME_SPACE", "DataValue");
class DataValueController extends GE3PController
{

    public function getAll()
    {
        $result = null;
        try {
            //Variables esperadas por el servicio
            $arrayToBeTested = array();
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, DATA_VALUE_CONTROLLER_NAME_SPACE, __FUNCTION__);
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
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, DATA_VALUE_CONTROLLER_NAME_SPACE, __FUNCTION__);
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
            $arrayToBeTested = array('data_value' => array('data_value_name', 'language_id', 'data_value_type_id', 'data_value'));

            //se recive tambien los atributos a asociar de la siguiente manera
            //$arrayToBeTested = array('data_value' => array('data_value_name', 'language_id', 'data_value_type_id', 'data_value'),
            // 'attributes'=>array(array('attribute_id','attribute_value')));

            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, DATA_VALUE_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $dataValueControllerHelper = new DataValueControllerHelper($this);

                $savedDataValue = $dataValueControllerHelper->saveTransactional($jsonObject);

                $result = parent::setSuccessfulSaveResponseWithObject($result, array($savedDataValue));
            }
        } catch (\Exception $e) {
            Log::info("Error, " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            $result = parent::setExceptionResponse($result, $e);
        }
        parent::returnAJson($result);
    }

    public function getAllDataValueTypes()
    {
        $result = null;
        try {
            //Variables esperadas por el servicio
            $arrayToBeTested = array();
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, DATA_VALUE_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $dataValueControllerHelper = new DataValueControllerHelper($this);

                $dataValue = $dataValueControllerHelper->getAllDataValueTypes();

                $result = parent::setSuccessfulResponseWithObject($result, $dataValue);
            }
        } catch (\Exception $e) {
            Log::info("Error, " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            $result = parent::setExceptionResponse($result, $e);
        }
        parent::returnAJson($result);
    }

    public function getAllLanguages()
    {
        $result = null;
        try {
            //Variables esperadas por el servicio
            $arrayToBeTested = array();
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, DATA_VALUE_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $dataValueControllerHelper = new DataValueControllerHelper($this);

                $dataValue = $dataValueControllerHelper->getAllLanguages();

                $result = parent::setSuccessfulResponseWithObject($result, $dataValue);
            }
        } catch (\Exception $e) {
            Log::info("Error, " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            $result = parent::setExceptionResponse($result, $e);
        }
        parent::returnAJson($result);
    }

    public function test()
    {
        $result = null;
        try {
            //Variables esperadas por el servicio
            $arrayToBeTested = array('data_value' => array('data_value_name', 'language_id', 'data_value_type_id', 'data_value'));

            //se recive tambien los atributos a asociar de la siguiente manera
            //$arrayToBeTested = array('data_value' => array('data_value_name', 'language_id', 'data_value_type_id', 'data_value'),
            // 'attributes'=>array(array('attribute_id','attribute_value')));

            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, DATA_VALUE_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $dataValueControllerHelper = new DataValueControllerHelper($this);

                //$savedDataValue = $dataValueControllerHelper->saveTransactional($jsonObject);

//                if(!empty($_FILES['image'])){
//                    $ext = pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION);
//                    $image = time().'.'.$ext;
//                    move_uploaded_file($_FILES["image"]["tmp_name"], 'images/'.$image);
//                    echo "Image uploaded successfully as ".$image;
//                }else{
//                    echo "Image Is Empty";
//                }

                $savedDataValue = array("Files" => $jsonObject['data_value']['data_value']);

                $result = parent::setSuccessfulSaveResponseWithObject($result, array($savedDataValue));
            }
        } catch (\Exception $e) {
            Log::info("Error, " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            $result = parent::setExceptionResponse($result, $e);
        }
        parent::returnAJson($result);
    }

}