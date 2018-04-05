<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 10:53 AM
 */

namespace App\Model\Table;

use Cake\ORM\Table;

class DataTypeTable extends Table
{

    public function initialize(array $config)

    {

        parent::initialize($config);

        $this->setTable('data_type');

        $this->setPrimaryKey('data_type_id');

    }

}