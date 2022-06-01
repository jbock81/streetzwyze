<?php


/**
 * Description of QueryTargetI
 *
 * @author JosephT
 */
interface QueryTargetI
{

    /**
     * Must return an array of results.
     * @param DbTableWhere $where
     * @return array results.
     */
    public function results(DbTableWhere $where);

    /**
     * Returns number of items matched without considering limit.
     * @param DbTableWhere $where
     * @return int number of items.
     */
    public function resultsCount(DbTableWhere $where);
}
