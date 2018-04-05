<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 05:13 PM
 */

namespace App\Model\Table;


use Cake\ORM\Table;

class DataValueAttributeRelTable extends Table
{

    public $actsAs = array('Containable');

    public function initialize(array $config)

    {

        parent::initialize($config);

        $this->setTable('data_value_attribute_rel');

        $this->setPrimaryKey('data_value_attribute_id_rel');

        $this->belongsTo('DataValue');

        $this->belongsTo('Attributes');


    }


}