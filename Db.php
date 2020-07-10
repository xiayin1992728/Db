<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2020/7/10 0010
 * Time: 20:51
 */

class Db
{
    protected $connect; // 数据库连接
    protected $tables; // 表
    protected $sql; // sql

    protected $options = [
        'where' => '',
        'groupBy' => '',
        'orderBy' => '',
        'join' => '',
        'max' => '',
        'sum' => '',
        'min' => '',
        'column' => ''
    ];


    public function __construct(PDO $connect)
    {
        $this->connect = $connect;
    }

    public function table($table)
    {
        $this->tables = $table;
    }

    // 初始化 options
    protected function initOptions()
    {
        foreach ($this->options as $k => $option) {
                   $this->options[$k] = null;
        }
    }

    // 添加 insert
    public function insert(array $column)
    {
        $this->sql = "INSERT INTO `{$this->tables}` (";
        $keys = array_keys($column);
        $values = array_keys($column);

        foreach ($keys as $key) {
            $this->sql .= "'".$key."'".',';
        }
        $this->sql = trim($this->sql,',').") VALUES (";
        foreach ($values as $value) {
            $this->sql .= "'".$value."'".',';
        }
        $this->sql = trim($this->sql,',').")";
        return $this->connect->exec($this->sql);
    }

    // 修改 update
    public function update(array $column, array $where)
    {
        $this->sql = "UPDATE `{$this->tables}` SET ";
        foreach ($column as $key => $value) {
            $this->sql .= "`{$key}`='{$value}',";
        }
        $new_where = '';
        foreach ($where as $k => $v) {
          $new_where = "{$k}=$v AND";
        }
        $new_where = trim($new_where,'AND');
        $this->sql = trim($this->sql,',')." WHERE {$new_where}";
        return $this->connect->exec($this->sql);
    }

    // 查询 all
    public function all()
    {
       return $this->connect->query($this->sql)->fetchAll(PDO::FETCH_CLASS);
    }

    // 查询 一条
    public function find()
    {
        return $this->connect->query($this->sql)->fetch(PDO::FETCH_CLASS);
    }
    // 删除 delete
    public function delete(array $where)
    {
        $new_where = '';
        foreach ($where as $k => $v) {
            $new_where .= "{$k}={$v} AND ";
        }
        $new_where = trim($new_where,'AND');
        $this->sql = "DELETE FROM `{$this->tables}` WHERE {$new_where}";
    }

    // 执行
    public function exec($sql)
    {
        $this->sql = $sql;
        return $this->connect->exec($this->sql);
    }

    // 查询
    public function select($sql,$one = false)
    {
        $this->sql = $sql;
        if ($one) {
            return $this->connect->query($this->sql)->fetch(PDO::FETCH_CLASS);
        } else {
            return $this->connect->query($this->sql)->fetchAll(PDO::FETCH_CLASS);
        }
    }

    public function where(array $where)
    {
        $new_where = '';
        foreach ($where as $k => $v) {
            $new_where .= "{$k}={$v} AND";
        }
        $new_where = trim($new_where,'AND');
        $this->options['where'] = $new_where;
    }
    // 关联 join
    // 排序 orderBy
    // 求和 sum
    // 最大 max
    // 最小 min
}