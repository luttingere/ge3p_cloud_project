<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 11:35 AM
 */

namespace App\Controller;


use App\ControllerHelpers\ViewControllerHelper;
use Cake\Log\Log;

define("VIEW_CONTROLLER_NAME_SPACE", "Views");

class ViewsController extends GE3PController
{


    public function getAll()
    {
        $result = null;
        try {
            //Variables esperadas por el servicio
            $arrayToBeTested = array();
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, VIEW_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $viewControllerHelper = new ViewControllerHelper($this);

                $views = $viewControllerHelper->getAllViews();

                $result = parent::setSuccessfulResponseWithObject($result, $views);
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
            $arrayToBeTested = array('view_id');
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, VIEW_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $viewControllerHelper = new ViewControllerHelper($this);

                $views = $viewControllerHelper->getById($jsonObject['view_id']);

                $result = parent::setSuccessfulResponseWithObject($result, $views);
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
            $arrayToBeTested = array('view' => array('view_name', 'view_path', 'json_path'));
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, VIEW_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $viewControllerHelper = new ViewControllerHelper($this);

                $views = $viewControllerHelper->saveTransactional($jsonObject);

                $result = parent::setSuccessfulSaveResponseWithObject($result, $views);
            }
        } catch (\Exception $e) {
            Log::info("Error, " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            $result = parent::setExceptionResponse($result, $e);
        }
        parent::returnAJson($result);
    }

    public function generateViewJSON()
    {

        $result = null;
        try {
            //Variables esperadas por el servicio
            $arrayToBeTested = array("view_id");
            $result = parent::runWebServiceInitialConfAndValidations($arrayToBeTested, VIEW_CONTROLLER_NAME_SPACE, __FUNCTION__);
            if (parent::isASuccessfulResult($result[WEB_SERVICE_RESPONSE_SIGNATURE])) {

                $jsonObject = $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'];

                $viewControllerHelper = new ViewControllerHelper($this);

                $data = $viewControllerHelper->generateViewJSON($jsonObject["view_id"]);

                if(!$viewControllerHelper->setViewChanges($jsonObject["view_id"], true))
                {
                    $result = parent::setExceptionResponse($result, "Los cambios en esta vista ya han sido publicados.");
                }
                else
                {
                    $result = parent::setSuccessfulResponseWithObject($result, $data);
                }
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