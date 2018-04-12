<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 06/04/18
 * Time: 07:41 AM
 */

namespace App\Controller;


use App\ControllerHelpers\DataControllerHelper;
use Cake\Log\Log;

define("DATA_CONTROLLER_NAME_SPACE", "Data");

class DataController extends GE3PController
{

    public function getAll()
    {
        $result = null;
        try {
            //Variables esperadas por el servicio
            $arrayToBeTested = array();
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, DATA_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $dataControllerHelper = new DataControllerHelper($this);

                $data = $dataControllerHelper->getAllData();

                $result = parent::setSuccessfulResponseWithObject($result, $data);
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
            $arrayToBeTested = array('data_id');
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, DATA_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $dataControllerHelper = new DataControllerHelper($this);

                $data = $dataControllerHelper->getById($jsonObject['data_id']);

                $result = parent::setSuccessfulResponseWithObject($result, array($data));
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
            $arrayToBeTested = array('data' => array('data_name', 'data_type_id'));

            //Ejemplo para almacenar un obteto tipo Data con data values
            //se recive tambien los dataValues a asociar de la siguiente manera
            // $arrayToBeTested = array('data' => array('data_name', 'data_type_id'),
            // 'data_values'=>array(array('data_value_id'),array('data_value_id')));

            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, DATA_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $dataControllerHelper = new DataControllerHelper($this);

                $savedDataValue = $dataControllerHelper->saveTransactional($jsonObject);

                $result = parent::setSuccessfulSaveResponseWithObject($result,$savedDataValue);
            }
        } catch (\Exception $e) {
            Log::info("Error, " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            $result = parent::setExceptionResponse($result, $e);
        }
        parent::returnAJson($result);
    }

    public function getAllDataTypes()
    {
        $result = null;
        try {
            //Variables esperadas por el servicio
            $arrayToBeTested = array();
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, DATA_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $dataControllerHelper = new DataControllerHelper($this);

                $data = $dataControllerHelper->getAllDataTypes();

                $result = parent::setSuccessfulResponseWithObject($result, $data);
            }
        } catch (\Exception $e) {
            Log::info("Error, " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            $result = parent::setExceptionResponse($result, $e);
        }
        parent::returnAJson($result);
    }

}