<?php

/**
 * @package lib.common
 * @author peijun.ypj
 */
class InitActiveRecord extends CActiveRecord
{
    /**
     * @author: peijun.ypj
     * @date:2015/7/16
     * 输入：属性函数
     * 输出：根据各属性值，构造的criteria
     */
    public function createCriteria($attribute)
    {
        $criteria = new CDbCriteria;

        //查询条件. 这是指一个SQL语句中的WHERE子句. 例如, age>31 AND team=1.
        if (isset($attribute['condition'])) {
            $criteria->condition = $attribute['condition'];
        }

        //只是否选择不同的数据行. 如果定义为 true, SELECT子句会改为SELECT DISTINCT.
        if (isset($attribute['distinct'])) {
            $criteria->distinct = $attribute['distinct'];
        }

        //如何组查询结果. 这里指在一个SQL语句中的 GROUP BY 子句. 例如, 'projectID, teamID'
        if (isset($attribute['group'])) {
            $criteria->group = $attribute['group'];
        }

        //如GROUP-BY 子句应用的条件. 例如, 'SUM(revenue)<50000'.
        if (isset($attribute['having'])) {
            $criteria->having = $attribute['having'];
        }

        //AR属性的名称，其值作为查询结果数组的索引. 默认为null，这意味着结果数组是从零开始的整数.
        if (isset($attribute['index'])) {
            $criteria->index = $attribute['index'];
        }

        //如何连接另一个表. 这代表SQL语句的一个 JOIN 子句. 例如, 'LEFT JOIN users ON users.id=authorID'.
        if (isset($attribute['join'])) {
            $criteria->join = $attribute['join'];
        }

        //返回的最大记录数. 如果小于0，意味着没有限制.
        if (isset($attribute['limit'])) {
            $criteria->limit = $attribute['limit'];
        }

        //返回基于0偏移的记录数. 如果小于0, 意味着从开始返回.
        if (isset($attribute['offset'])) {
            $criteria->offset = $attribute['offset'];
        }

        //如何排序查询结果. 这代表一个SQL语句的ORDER BY 子句.
        if (isset($attribute['order'])) {
            $criteria->order = $attribute['order'];
        }

        //用参数占位符索引列表参数值. 例如, array(':name'=>'Dan', ':age'=>31).
        if (isset($attribute['params'])) {
            $criteria->params = $attribute['params'];
        }

        return $criteria;
    }

    public function getId()
    {
        return $this->tableName() . uniqid();
    }

}
