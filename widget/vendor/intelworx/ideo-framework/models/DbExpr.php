<?php

/**
 * Description of DbExpr
 *
 * @author JosephT
 */
class DbExpr extends DbTableFunction
{

    /**
     *
     * @param string $expr
     * @return \self
     */
    public static function make($expr)
    {
        return new self($expr);
    }

}
