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

class AttributesControllerHelper
{


    private $GE3PController;

    /**
     * AttributesControllerHelper constructor.
     * @param GE3PController $GE3PController
     */
    public function __construct(GE3PController $GE3PController)
    {
        $this->GE3PController = $GE3PController;
    }

    public function getAllAttributes()
    {
        $result = null;
        try {
            $attributesTable = TableRegistry::get("Attributes");
            $queryResult = $attributesTable->find()
                ->enableHydration(false);

            if (!$this->GE3PController->isTheCursorEmpty($queryResult)) {
                $result = $queryResult->toArray();
            } else {
                throw new \Exception("No Attributes found");
            }

        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }
        return $result;
    }

    public function getById($attributesId)
    {
        $result = null;
        try {
            $attributesTable = TableRegistry::get("Attributes");
            $queryResult = $attributesTable->find()
                ->where(array('attribute_id' => $attributesId))
                ->enableHydration(false);

            if (!$this->GE3PController->isTheCursorEmpty($queryResult)) {
                $result = $queryResult->toArray();
            } else {
                throw new \Exception("No Attributes found");
            }

        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }
        return $result;
    }


    public function save($attribute)
    {
        try {

            $attributesTable = TableRegistry::get("Attributes");
            $attributeEntity = $attributesTable->newEntity($attribute);
            $saveResult = $attributesTable->save($attributeEntity);
            if (!$saveResult) {
                throw new \Exception("Problem Saving the Attribute: " . json_encode($attribute));
            }

            return $saveResult;

        } catch (\Exception $e) {
            Log::info("Error en " . __FUNCTION__ . " cause: " . $e->getMessage());
            Log::error(__FUNCTION__, $e);
            throw new \Exception($e->getMessage());
        }
    }



}