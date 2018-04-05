<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 10:55 AM
 */

namespace App\Model\Table;

use Cake\ORM\Table;

class DataValueTable extends Table
{


    public function initialize(array $config)

    {

        parent::initialize($config);

        $this->setTable('data_value');

        $this->setPrimaryKey('data_value_id');

        $this->belongsTo("DataValueType", array('foreignKey' => 'data_value_type_id'));

        $this->belongsTo("Language", array('foreignKey' => 'language_id'));

        $this->BelongsToMany('Views', array('joinTable' => 'views_data_rel'));

        $this->belongsToMany('Data', array('joinTable' => 'data_values_rel'));

        $this->belongsToMany('Attributes', array('joinTable' => 'data_value_attribute_rel'));
//
        $this->belongsToMany('ChildDataValue', array(
            'className' => 'DataValue',
            'targetForeignKey' => 'data_value_child_id',
            'foreignKey' => 'data_value_parent_id',
            'bindingKey' => 'data_value_id',
            'joinTable' => 'data_value_parent_child'
        ));




    }


}