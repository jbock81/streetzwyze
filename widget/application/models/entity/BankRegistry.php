<?php
/**
 * Created by PhpStorm.
 * User: intelWorx
 * Date: 03/11/2015
 * Time: 9:41 PM
 */

namespace models\entity;


use models\BankEntry;

class BankRegistry extends \ModelEntity implements BankEntry
{

    /**
     * @param $code
     * @return static
     */
    public static function findByQtCode($code)
    {
        return self::findOne($code, 'qt_code');
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->_data['name'];
    }

    /**
     * @return mixed
     */
    public function getCBNCode()
    {
        return $this->_data['cbn_code'];
    }


}