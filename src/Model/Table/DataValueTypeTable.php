<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 11:00 AM
 */

namespace App\Model\Table;

use Cake\ORM\Table;

class DataValueTypeTable extends Table
{

    public function initialize(array $config)

    {

        parent::initialize($config);

        $this->setTable('data_value_type');

        $this->setPrimaryKey('data_value_type_id');

    }


}