<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 06:55 PM
 */

namespace App\Controller;

use App\ControllerHelpers\AttributesControllerHelper;
use Cake\Log\Log;

define("ATTRIBUTE_CONTROLLER_NAME_SPACE", "Attributes");

class AttributesController extends GE3PController
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

                $attributeControlHelper = new AttributesControllerHelper($this);

                $attributes = $attributeControlHelper->getAllAttributes();

                $result = parent::setSuccessfulResponseWithObject($result, $attributes);
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
            $arrayToBeTested = array('attribute_id');
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, ATTRIBUTE_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $attributeControlHelper = new AttributesControllerHelper($this);

                $attribute = $attributeControlHelper->getById($jsonObject['attribute_id']);

                $result = parent::setSuccessfulResponseWithObject($result, array($attribute));
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
            $arrayToBeTested = array('attribute_name');
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, ATTRIBUTE_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $attributeControlHelper = new AttributesControllerHelper($this);

                $attribute = $attributeControlHelper->save($jsonObject);

                $result = parent::setSuccessfulSaveResponseWithObject($result, array($attribute));
            }
        } catch (\Exception $e) {
            Log::info("Error, " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            $result = parent::setExceptionResponse($result, $e);
        }
        parent::returnAJson($result);
    }




}