<?php
/**
 * Created by PhpStorm.
 * User: luttinger
 * Date: 05/04/18
 * Time: 11:16 AM
 */

namespace App\Model\Table;


use Cake\ORM\Table;

class LanguageTable extends Table
{

    public function initialize(array $config)

    {

        parent::initialize($config);

        $this->setTable('language');

        $this->setPrimaryKey('language_id');

    }


}