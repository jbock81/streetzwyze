<?php

/**
 * This variable holds several configuration properties of a relationship
 * Some config include:
 *  order: order by which the relationship should be sorted,
 *   first entity using this order will be matched
 *  restrictions: other restrictions that must be matched, should be of
 *   the form: ['field_name_in_pk' => 'literalValue'] or a where clause
 *
 * @author intelWorX
 */
class EntityFkRelationship extends EntityRelationshipSpec
{

    public function getPkWhereClause(Entity $fkEntity)
    {
        $where = DbTableWhere::get()
            ->where($this->referencedField, $fkEntity->{$this->fkField});

        $restrictions = $this->getConfig('restrictions', []);
        if (!empty($restrictions)) {
            if (DbTableWhere::aClassOf($restrictions)) {
                $where = $where->combineWith($restrictions);
            } else {
                $restrictions = (array)$restrictions;
                foreach ($restrictions as $key => $value) {
                    $where->where($key, $value);
                }
            }
        }

        if (($order = $this->getConfig('order'))) {
            $where->setOrderBy(DbExpr::make($order), '');
        }

        return $where;
    }

}
