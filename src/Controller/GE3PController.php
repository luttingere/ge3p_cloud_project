<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 11:19 AM
 */

namespace App\Controller;


use App\Util\GE3PApiMessages;
use App\Util\GE3PUtil;
use Cake\Log\Log;

define("WEB_SERVICE_REQUEST_SIGNATURE", "GE3PRequest");
define("WEB_SERVICE_RESPONSE_SIGNATURE", "GE3PResponse");

class GE3PController extends AppController
{


    /**
     * Firma y objeto default que se envia a las aplicaciones
     *
     * @var array
     */
    private $GE3PResponseObject = array(WEB_SERVICE_RESPONSE_SIGNATURE => array("code" => 0, "message" => 'Successful Request', "object" => array()));


    /**
     *
     * Cambia la cabecera http antes de efectuar el handshake
     *
     *
     * @param Event $event
     * @return response object modified
     */
//    public function beforeFilter(Event $event)
//    {
//        parent::beforeFilter($event);
////        array_push($this->response->getHeaders(), array('Access-Control-Allow-Origin' => '*'));
////        array_push($this->response->getHeaders(), array('Access-Control-Allow-Headers', 'Content-Type, x-xsrf-token'));
//    }


    /**
     * Metodo para atrapar todos los errores en PHP desde los servicios
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @throws \ErrorException
     */
    function exception_error_handler($errno, $errstr, $errfile, $errline)
    {
        throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);
    }


    /**
     * Valida si el resultado de la consulta en base de datos trajo resultados
     * @param $cursor
     * @return bool
     */
    public function isTheCursorEmpty($cursor)
    {
        $isTheCursorEmpty = true;
        if ($cursor->count() > 0) {
            $isTheCursorEmpty = false;
        }
        return $isTheCursorEmpty;
    }

    /**
     *
     * Inicializa las configuraciones necesarias para que el servicio web haga render de las respuestas en JSON
     * Valida la cabecera de la llamada (Agregar validacion de apitoken)
     * Toma el objeto json que se recibe de la llamada y valida que en el existan los parametros esperados
     *
     *
     * @param $parametersToBeTested array de parametros que se esperan en la llamada
     * @param $namespace String (nombre) del controlador "en literal" de donde se recibe la llamada
     * @param $webServiceMethodName "nombre del metodo que se expone como servicio"
     * @return array con el codigo y mensajes del resultado de las validaciones iniciales con el objeto json recibido
     */
    public function runWebServiceInitialConfAndValidations($parametersToBeTested, $namespace, $webServiceMethodName)
    {
        Log::info("Running the webservice method " . $webServiceMethodName);
        $this->setResultAsAJson();
        $jsonObject = $this->getJsonReceived();
        $result = $this->getDefaultGE3PMessage();
        Log::debug("Object Received: " . json_encode($jsonObject));
        if ($this->validGE3PJsonHeader($jsonObject)) {
            if (isset($jsonObject[WEB_SERVICE_REQUEST_SIGNATURE][$namespace])) {
                $resultValidation = GE3PUtil::validateParameters($parametersToBeTested, $jsonObject[WEB_SERVICE_REQUEST_SIGNATURE][$namespace]);
                $result[WEB_SERVICE_RESPONSE_SIGNATURE]['code'] = $resultValidation['code'];
                $result[WEB_SERVICE_RESPONSE_SIGNATURE]['message'] = $resultValidation['message'];
                $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'] = $jsonObject[WEB_SERVICE_REQUEST_SIGNATURE][$namespace];
            } else {
                $result = $this->setInvalidJsonHeader($result);
            }
        } else {
            $result = $this->setInvalidJsonHeader($result);
        }
        set_error_handler(array($this, 'exception_error_handler'));
        return $result;
    }


    /**
     * Convierte el metodo dentro de un controlador en un json response service
     */
    public function setResultAsAJson()
    {
        $this->autoRender = false;
        $this->response->type('json');
    }

    /**
     * Obtiene de la llamada recibida el objeto json
     *
     * @return mixed
     */
    public function getJsonReceived()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, TRUE);
        return $input;
    }


    /**
     *
     * Inicializa las configuraciones necesarias para que el servicio web haga render de las respuestas en JSON
     * Toma el objeto json que se recibe de la llamada y valida que en el existan los parametros esperados
     *
     * @param $parametersToBeTested
     * @param $webServiceMethodName
     * @return array
     */
    public function runWebServiceInitialConfAndValidationsFormStyle($parametersToBeTested, $webServiceMethodName)
    {
        Log::info("Running the webservice method " . $webServiceMethodName);
        $this->setResultAsAJson();
        $jsonObject = $_POST;
        $result = $this->getDefaultGE3PMessage();
        Log::debug("Object Received: " . json_encode($jsonObject));
        $resultValidation = GE3PUtil::validateParameters($parametersToBeTested, $jsonObject);
        $result[WEB_SERVICE_RESPONSE_SIGNATURE]['code'] = $resultValidation['code'];
        $result[WEB_SERVICE_RESPONSE_SIGNATURE]['message'] = $resultValidation['message'];
        $result[WEB_SERVICE_RESPONSE_SIGNATURE]['object'] = $jsonObject;
        set_error_handler(array($this, 'exception_error_handler'));
        return $result;
    }


    /**
     *
     * Valida si la respuesta es exitosa
     *
     * @param $result
     * @return bool
     */
    public function isASuccessfulResult($result)
    {
        $isSuccessFull = false;
        if (isset($result['code'])) {
            if ($result['code'] == GE3PApiMessages::$SUCCESS_CODE) {
                $isSuccessFull = true;
            }
        }
        return $isSuccessFull;
    }

    /**
     * Convierta la respuesta y el tipo de respuesta en JSON
     * @param $result
     */
    public function returnAJson($result)
    {
        Log::debug("Json Result: " . json_encode($result));
        $this->response->type('json');
        $this->response->body(json_encode($result));
    }

    /**
     *
     * Valida la firma del objeto json recibido
     *
     * @param $jsonObject
     * @return bool
     */
    public function validGE3PJsonHeader($jsonObject)
    {
        $isValid = false;
        if (isset($jsonObject[WEB_SERVICE_REQUEST_SIGNATURE])) {
            $isValid = true;
        }
        return $isValid;
    }

    /**
     *
     *
     * @return array
     */
    public function getDefaultGE3PMessage()
    {
        $GE3PMessage = $this->GE3PResponseObject;
        return $GE3PMessage;
    }

    public function setInvalidJsonHeader($GE3PMessage)
    {
        $GE3PMessage[WEB_SERVICE_RESPONSE_SIGNATURE]['code'] = GE3PApiMessages::$INVALID_JSON_HEADER_CODE;
        $GE3PMessage[WEB_SERVICE_RESPONSE_SIGNATURE]['message'] = GE3PApiMessages::$INVALID_JSON_HEADER_MESSAGE;
        return $GE3PMessage;
    }

    public function setEmptySuccessfulResponse($GE3PMessage)
    {
        $GE3PMessage[WEB_SERVICE_RESPONSE_SIGNATURE]['code'] = GE3PApiMessages::$SUCCESS_CODE;
        $GE3PMessage[WEB_SERVICE_RESPONSE_SIGNATURE]['message'] = GE3PApiMessages::$SUCCESS_MESSAGE;
        $GE3PMessage[WEB_SERVICE_RESPONSE_SIGNATURE]['object'] = array();
        return $GE3PMessage;
    }

    public function setSuccessfulResponseWithObject($GE3PMessage, $object)
    {
        $GE3PMessage[WEB_SERVICE_RESPONSE_SIGNATURE]['code'] = GE3PApiMessages::$SUCCESS_CODE;
        $GE3PMessage[WEB_SERVICE_RESPONSE_SIGNATURE]['message'] = GE3PApiMessages::$SUCCESS_MESSAGE;
        $GE3PMessage[WEB_SERVICE_RESPONSE_SIGNATURE]['object'] = $object;
        return $GE3PMessage;
    }

    public function setExceptionResponse($GE3PMessage, $e)
    {
        $GE3PMessage[WEB_SERVICE_RESPONSE_SIGNATURE]['code'] = GE3PApiMessages::$GENERAL_ERROR_CODE;
        $GE3PMessage[WEB_SERVICE_RESPONSE_SIGNATURE]['message'] = $e->getMessage();
        $GE3PMessage[WEB_SERVICE_RESPONSE_SIGNATURE]['object'] = array();
        return $GE3PMessage;
    }


}