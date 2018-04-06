<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 06/04/18
 * Time: 08:52 AM
 */

namespace App\Model\Table;


use Cake\ORM\Table;

class DataValuesRelTable extends Table
{

    public $actsAs = array('Containable');

    public function initialize(array $config)

    {

        parent::initialize($config);

        $this->setTable('data_values_rel');

        $this->setPrimaryKey('data_values_id_rel');

        $this->belongsTo('Data');

        $this->belongsTo('DataValue');

    }

}