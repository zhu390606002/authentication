<?php


namespace qimao\authentication\logic;

use Yii;
abstract class AbstractBaseLogic
{
    /**
     * 入参
     */
    public $params;

    /**
     * 场景
     */
    public $scenario;

    public function __construct($params = null , $scenario = '')
    {
        $this->params = $params;
        $this->scenario = $scenario;
    }

    /**
     * 添加分页信息
     *
     * @param yii\db\ActiveQuery $query
     * @param int $count
     *
     * @return yii\db\ActiveQuery
     */
    public function addPaginate($query,$count)
    {
        $pagenate = $this->getPaginate($count);
        return $query->limit($pagenate['pageSize'])->offset($pagenate['offset']);
    }

    /**
     * 获取分页信息
     *
     * @return array
     */
    public function getPaginate($count)
    {
        $page = $this->params['page']??1;
        $pageSize = $this->params['page_size']??100;
        $totalPage = ceil($count / $pageSize);
        if ($page > $totalPage){
            $page = $totalPage;
            $this->params['page'] = $totalPage;
        }
        $offset = ($page - 1) *$pageSize;
        return ['page'=>$page,'pageSize'=>$pageSize,'offset'=>$offset];
    }

    /**
     * 格式化列表
     *
     * @param array $list   列表数据
     * @param int $count    列表总数
     * @param array $tableHead  表头
     * @param int $page     页码
     * @param int $pageSize 每页条数
     *
     * @return array
     */
    public function formatList($list,$count,$tableHead,$page = null,$pageSize = null)
    {
        $paginate = $this->getPaginate($count);
        return [
            'page_data' =>[
                'count' => $count,
                'page' => $page ?? $paginate['page'],
                'page_size' => $pageSize ?? $paginate['pageSize'],
            ],
            'table_header' => $tableHead,
            'table_list' => $list
        ];
    }
}