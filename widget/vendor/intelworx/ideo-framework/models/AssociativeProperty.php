<?php


/**
 * AssociativeProperty
 *
 * @package
 * @author MyFw
 * @copyright Taiwo J
 * @version 2010
 * @access public
 */
abstract class AssociativeProperty extends DbTable
{

    /**
     * The value of teh foreign key
     */
    protected $_id;

    /**
     * The key field which is the foreign key field
     */
    protected $_fkey_field;

    /**
     * AssociativeProperty::__construct()
     *
     * @param mixed $propertyTable representing the name of the table
     * @param mixed $id the ID
     * @param string $fkey_field the foreign key field
     */

    public function __construct($propertyTable, $id, $fkey_field = "candidate_id")
    {
        $this->_id = (int)$id;
        $this->_fkey_field = $fkey_field;
        parent::__construct($propertyTable);
    }


    /**
     * AssociativeProperty::setKeyField()
     *
     * @param mixed $keyField
     * @return void
     */
    public function setKeyField($keyField = "")
    {
        $this->_key_field = $keyField;
    }

    /**
     * AssociativeProperty::setID()
     * Set ID
     * @param integer $id
     * @return void
     */
    public function setID($id = 0)
    {
        $this->_id = $id;
    }

    /**
     * AssociativeProperty::getAll()
     *
     * @param mixed $fields
     * @return
     */
    public function getAll($fields = null, $where = "", $order = "", $limit = 0, $offset =
    0)
    {
        if ($where && $this->_id) {
            $where .= " AND ";
        }
        $where .= "`{$this->_fkey_field}` = {$this->_id}";
        return $this->fetch($fields, $where, $order, $limit, $offset);
    }

    /**
     * AssociativeProperty::update()
     *
     * @param mixed $data
     * @param string $where
     * @return
     */
    public function update($data, $where = "")
    {
        if ($this->_id) {
            if ($where) {
                $where .= " AND ";
            }
            $where .= "`{$this->_fkey_field}` = {$this->_id}";
        }

        return parent::update($data, $where);
    }

    /**
     * AssociativeProperty::insert()
     *
     * @param mixed $data
     * @return
     */
    public function insert($data)
    {
        if (!isset($data[$this->_fkey_field])) {
            $data[$this->_fkey_field] = $this->_id;
        }
        return parent::insert($data);
    }
}
