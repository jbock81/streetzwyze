<?php

/**
 * Description of EntityMvaRelationship
 *
 * @author JosephT
 */
class EntityMvaRelationship extends EntityRelationshipSpec
{

    const DEFAULT_MVA_LIMIT = 512;

    /**
     *
     * @param mixed $managerClass an EntityManager object or class or a ModelEntity class
     * @param string $fkField foreign key field
     * @param string $referencedField primary key field or referenced field in referenced table
     * @param string $order the order to sort MVA
     * @param int $limit
     * @param array $config This variable holds several configuration properties
     * of a relationship, some config include:
     *  order: order by which relashionship should be sorted
     *  limit: the limit
     *  inherit: the properties to inherit from the parent, a mapping of parent field to child field
     *   or just a list of attributes to inherit, e.g.
     *      `['xyz_id', 'xyz_in_parent' => 'xyz_in_child']`
     *  morph: polymorphic information, extra field values basically,
     *   they will be appended to the child. Should be a mapping of 'fkEntityField' => 'LiteralValue'
     *  e.g.  `['obj_type' => 'photo']`
     */
    public function __construct($managerClass, $fkField, $referencedField = null, $order = null, $limit = self::DEFAULT_MVA_LIMIT, $config = array())
    {
        parent::__construct($managerClass, $fkField, $referencedField, array_merge($config, array(
            'limit' => $limit,
            'order' => $order,
        )));
    }

    public function getOrder()
    {
        return $this->getConfig('order', null);
    }

    public function getLimit()
    {
        return $this->getConfig('limit', self::DEFAULT_MVA_LIMIT);
    }

    public function setOrder($order)
    {
        return $this->setConfig('order', $order);
    }

    public function setLimit($limit)
    {
        return $this->setConfig('limit', $limit);
    }

    public function getFkWhereClause(Entity $pkEntity)
    {
        $where = DbTableWhere::get();

        $data = $this->getFkData($pkEntity);
        foreach ($data as $key => $value) {
            $where->where($key, $value);
        }

        return $where;
    }

    public function getFkData(Entity $pkEntity)
    {
        $base = [];

        //add foreign key ref
        $base[$this->fkField] = $pkEntity->{$this->referencedField};

        //process inheritance
        if (count(($inherit = (array)$this->getConfig('inherit', []))) > 0) {
            foreach ($inherit as $parent => $target) {
                //list mode
                $parent = is_numeric($parent) ? $target : $parent;
                $base[$target] = $pkEntity->{$parent};
            }
        }

        //process polymorphic
        if (count(($morph = (array)$this->getConfig('morph', []))) > 0) {
            foreach ($morph as $field => $value) {
                $base[$field] = $value;
            }
        }

        return $base;
    }

    public function count(Entity $pkEntity)
    {
        return $this->managerClass->getCardinalityWhere($this->getFkWhereClause($pkEntity));
    }

    public function delete(Entity $pkEntity)
    {
        return $this->managerClass
            ->getEntityTable()
            ->delete($this->getFkWhereClause($pkEntity)->getWhereString());
    }

    /**
     * Checks if a given fkEntity belongs to a given pkEntity
     * @param Entity $pkEntity
     * @param Entity $fkEntity
     * @return boolean
     */
    public function checkBelongsToEntity(Entity $pkEntity, Entity $fkEntity)
    {
        $data = $this->getFkData($pkEntity);
        foreach ($data as $key => $value) {
            if ($fkEntity->$key != $value) {
                return false;
            }
        }
        return true;
    }

}
