<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 10:43 AM
 */

namespace App\Model\Table;


use Cake\ORM\Table;

class ViewsTable extends Table
{


    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('views');

        $this->setPrimaryKey('view_id');

        //$this->belongsTo("ViewType", array('foreignKey' => 'view_type_id'));

        $this->BelongsToMany('Data', array('joinTable' => 'views_data_rel'));


    }


}