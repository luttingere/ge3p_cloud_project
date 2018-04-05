<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 11:01 AM
 */

namespace App\Model\Table;


use Cake\ORM\Table;

class AttributesTable extends Table
{

    public $actsAs = array('Containable');

    public function initialize(array $config)

    {

        parent::initialize($config);

        $this->setTable('attributes');

        $this->setPrimaryKey('attribute_id');

    }



}