<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 10:48 AM
 */

namespace App\Model\Table;

use Cake\ORM\Table;

class DataTable extends Table
{

    public $actsAs = array('Containable');

    public function initialize(array $config)

    {

        parent::initialize($config);

        $this->setTable('data');

        $this->setPrimaryKey('data_id');

        $this->belongsTo("DataType", array('foreignKey' => 'data_type_id'));

        $this->belongsToMany('DataValue',
            array(
                'targetForeignKey' => 'data_value_id',
                'foreignKey' => 'data_id',
                'joinTable' => 'data_values_rel',
                'fields' => 'data_value_id', 'data_value_type_id', 'data_value_name', 'data_value'));


    }


}