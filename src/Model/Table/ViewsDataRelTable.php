<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 06/04/18
 * Time: 08:53 AM
 */

namespace App\Model\Table;


use Cake\ORM\Table;

class ViewsDataRelTable extends Table
{

    public $actsAs = array('Containable');

    public function initialize(array $config)

    {

        parent::initialize($config);

        $this->setTable('views_data_rel');

        $this->setPrimaryKey('views_data_id');

        $this->belongsTo('Data');

        $this->belongsTo('Views');

    }

}