<?php
/*
 * Model Class
 *
 * @access public
 * @author lionheart.co.jp
 * @version 2.0.2
 */

class Model
{
    // Fegg Instance
    protected $_app       = null;
    // DB_pdo Class
    protected $_db        = null;
    // Administration Flag
    protected $_is_admin  = false;
    // Pager Result
    protected $_pager     = array();
    // Avaibable Bind Data Types
    protected $_bindTypes = array('FLOAT', 'INTEGER', 'DATE', 'DATETIME');

    // Overwrite Parameters
    protected
        $_table   = '',      // Table name
        $_id      = null,    // Primary ID column name
        $_visible = null,    // Visbile flag column name
        $_bind    = array(); // Bind Parameter types

    /**
     * Constructor
     *
     * @param boolean $is_admin Administration Flag (Get rows, evenif visible flag is false)
     * @param array   $options
     */
    public function __construct($is_admin = false, $options = array())
    {
        // Fegg Instance
        $this->_app      = FEGG_getInstance();
        // DB_pdo Class
        $this->_db       = $this->_app->getClass('DB_pdo');
        // Administration Flag
        $this->_is_admin = (boolean)$is_admin;

        // Model Where
        $this->_model_where  = new ModelWhere();
        // Model Having
        $this->_model_having = new ModelHaving();
        // Model Order
        $this->_model_order  = new ModelOrder();
        // Model Join
        $this->_model_join   = new ModelJoin();
        // Model Group
        $this->_model_group  = new ModelGroup();

        // Set Options
        if(is_array($options)) {
            foreach($options as $key => $val) {
                switch($key) {
                    case 'table':
                        $this->setTableName($val);
                        break;
                    case 'id':
                        $this->setPrimary($val);
                        break;
                    case 'visible':
                        $this->setVisible($val);
                        break;
                    case 'bind':
                        $this->setBindParam($val);
                        break;
                }
            }
        }
    }

    /**
     * Set Table Name
     *
     * @param string $table Table Name
     * @return Model
     */
    public function setTableName($table)
    {
        $this->_table = $table;

        return $this;
    }

    /**
     * Get Table name
     *
     * @return string Table name
     */
    public function getTableName()
    {
        return $this->_table;
    }

    /**
     * Set Primary ID name
     *
     * @param string $id Primary ID name
     * @return Model
     */
    public function setPrimary($id)
    {
        $this->_id = $id;

        return $this;
    }

    /**
     * Get Primary ID name
     *
     * @return string Primary ID name
     */
    public function getPrimary()
    {
        return $this->_id;
    }

    /**
     * Set Visible flag name
     *
     * @param string $visible Visible flag name
     * @return Model
     */
    public function setVisible($visible)
    {
        $this->_visible = $visible;

        return $this;
    }

    /**
     * Get Visible flag name
     *
     * @return string Get Visible flag name
     */
    public function getVisible()
    {
        return $this->_visible;
    }

    /**
     * Set Bind Parameter types
     *
     * @param array $bind Bind Parameter types
     * @return Model
     */
    public function setBindParam($bind)
    {
        if(is_array($bind)) {
            $this->_bind = array();
            foreach($bind as $column => $type) {
                $this->addBindParam($column, $type);
            }
        }

        return $this;
    }

    /**
     * Add Bind Parameter types
     *
     * @param string $column Bind Column
     * @param string $type   Bind Type
     * @return Model
     */
    public function addBindParam($column, $type)
    {
        if(in_array($type, $this->_bindTypes)) {
            $this->_bind[$column] = $type;
        }

        return $this;
    }

    /**
     * Get Bind Parameter types
     *
     * @return array Bind Parameter types
     */
    public function getBindParam()
    {
        return $this->_bind;
    }

    /**
     * Option parameters
     */
    private $_options
        = array(
            'select'    => '*',
            'list_max'  => 10,
            'current'   => 1,
        );

    /**
     * Set Select Parameter
     *
     * @param string $select Select columns
     * @return Model
     */
    public function select($select)
    {
        $this->_options['select'] = $select;

        return $this;
    }

    /**
     * Set ListMax Parameter
     *
     * @param number $listMax LIMIT number
     * @return Model
     */
    public function listMax($listMax = 10)
    {
        $this->_options['list_max'] = $listMax;

        return $this;
    }

    /**
     * Set Current Page Parameter
     *
     * @param number $current Page number
     * @return Model
     */
    public function current($current = 1)
    {
        $this->_options['current'] = $current;

        return $this;
    }

    /**
     * Set Where Parameter
     *
     * @param string $query Where query
     * @param array $data Where query parameters
     * @param string $andOr Chain condition
     * @return Model
     */
    public function where($query, $data = array(), $andOr = 'AND')
    {
        $this->_model_where->addQuery($query, $data, $andOr);

        return $this;
    }

    /**
     * Add open bracket on Where query
     *
     * @param string $andOr Chain condition
     * @return Model
     */
    public function whereOpen($andOr = 'AND')
    {
        $this->_model_where->openBracket($andOr);

        return $this;
    }

    /**
     * Add close bracket on Where query
     *
     * @return Model
     */
    public function whereClose()
    {
        $this->_model_where->closeBracket();

        return $this;
    }

    /**
     * Set Having Parameter
     *
     * @param string $query Having query
     * @param array $data Having query parameters
     * @param string $andOr Chain condition
     * @return Model
     */
    public function having($query, $data = array(), $andOr = 'AND')
    {
        $this->_model_having->addQuery($query, $data, $andOr);

        return $this;
    }

    /**
     * Add open bracket on Having query
     *
     * @param string $andOr Chain condition
     * @return Model
     */
    public function havingOpen($andOr = 'AND')
    {
        $this->_model_having->openBracket($andOr);

        return $this;
    }

    /**
     * Add close bracket on Having query
     *
     * @return Model
     */
    public function havingClose()
    {
        $this->_model_having->closeBracket();

        return $this;
    }

    /**
     * Set Order Parameter
     *
     * @param string $order Ordering target column
     * @param string $duration Set the ordering duration
     * @return Model
     */
    public function order($order, $duration = 'ASC')
    {
        $this->_model_order->addQuery($order, $duration);

        return $this;
    }

    /**
     * Set Join Parameter
     *
     * @param string $table Target table
     * @param string $type Join mode
     * @param string $on On query
     * @param string $data On query parameters
     * @return Model
     */
    public function join($table, $type = 'INNER', $on = null, $data = array())
    {
        $this->_model_join->addQuery($table, $type, $on, $data);

        return $this;
    }

    /**
     * Set Group Parameter
     *
     * @param string $group Group column
     * @return Model
     */
    public function group($group)
    {
        $this->_model_group->addQuery($group);

        return $this;
    }

    /**
     * Get rows designated count
     *
     * @param array $options Option parameters
     *   'select'   Selecting columun names
     *   'list_max' The Number of each page
     *   'current'  Current page number
     * @return array Get rows
     */
    public function find($options = array())
    {
        // Extract Option data
        $options = $this->setOptions($options);
        extract($options);

        // Disabled initialize flag
        $this->_db->unsetInitQueryFlag();

        /**
         * First, Get count of rows
         */

        // Setting columns
        $this->_db->setItem($select);

        // Setting where query and parameters
        $where = $this->_model_where->getQuery();
        $this->_setWhere($where['query'], $where['where']);

        // 表示フラグがTRUEのレコードだけ取得する
        // 管理フラグがFALSE、かつ、$this->_visibleが指定されている時のみ追加
        if(! $this->_is_admin && ! empty($this->_visible)) {
            $this->_db->setWhere('AND ' . $this->_table . '.' . $this->_visible . ' = ?', '1');
        }

        // Setting having query and parameters
        $having = $this->_model_having->getQuery();
        $this->_setHaving($having['query'], $having['having']);

        // テーブル結合
        $joins = $this->_model_join->getQuery();
        foreach($joins as $join) {
            $this->_setJoin($join['table'], $join['type'], $join['on'], $join['data']);
        }

        // Group By
        $group = $this->_model_group->getQuery();
        $this->_setGroup($group);

        // 件数
        $record = $this->_db->count($this->_table)->execute()->one();
        $maxPage = ceil($record['number_of_records'] / $list_max);

        /**
         * 以降は一覧取得
         */

        // ページャー計算
        $this->_pager['current_page'] = is_numeric($options['current']) && 0 < $options['current'] && $options['current'] <= $maxPage ? $options['current'] : 1;
        $this->_pager['page_min'] = $this->_pager['current_page'] - 4 > 0 ? $this->_pager['current_page'] - 4 : 1;
        $this->_pager['page_max'] = $this->_pager['current_page'] + 4 <= $maxPage ? $this->_pager['current_page'] + 4 : $maxPage;
        if ($this->_pager['current_page'] > 1) {
            $this->_pager['previous_page'] = $this->_pager['current_page'] - 1;
        }
        if ($this->_pager['current_page'] < $maxPage) {
            $this->_pager['next_page'] = $this->_pager['current_page'] + 1;
        }
        $this->_pager['last_page'] = $maxPage;
        $this->_pager['number_of_records'] = $record['number_of_records'];

        // 件数指定
        $this->_db->setLimit($list_max, $list_max*($this->_pager['current_page']-1));

        // 表示順指定
        $order = $this->_model_order->getQuery();
        if(! empty($order)) {
            $this->_db->setOrder($order);
        }

        // 初期化フラグを戻しておく
        $this->_db->setInitQueryFlag();

        // データ取得
        $result = $this->_db->select($this->_table)->execute()->all();

        $this->reset();
        return $result;
    }

    /**
     * ページャーの情報を取得
     * findを実行するとデータが入る
     *
     * @return array ページャーの情報
     */
    public function getPager()
    {
        return $this->_pager;
    }

    /**
     * データ一件取得
     *
     * @param string $item 取得カラム名（カンマ区切り）
     * @param array $options
     *   'select'   Selecting columun names
     * @return array 取得データ
     */
    public function one($options = array())
    {
        // Extract Option data
        $options = $this->setOptions($options);
        extract($options);

        // 取得カラムを指定
        $this->_db->setItem($select);

        // 検索条件を指定
        $where = $this->_model_where->getQuery();
        $this->_setWhere($where['query'], $where['where']);

        // 表示フラグがTRUEのレコードだけ取得する
        // 管理フラグがFALSE、かつ、$this->_visibleが指定されている時のみ追加
        if(! $this->_is_admin && ! empty($this->_visible)) {
            $this->_db->setWhere('AND ' . $this->_table . '.' . $this->_visible . ' = ?', '1');
        }

        // Setting having query and parameters
        $having = $this->_model_having->getQuery();
        $this->_setHaving($having['query'], $having['having']);

        // テーブル結合
        $joins = $this->_model_join->getQuery();
        foreach($joins as $join) {
            $this->_setJoin($join['table'], $join['type'], $join['on'], $join['data']);
        }

        // Group By
        $group = $this->_model_group->getQuery();
        $this->_setGroup($group);

        // 1件取得
        $this->_db->setLimit(1, 0);

        // 表示順指定
        $order = $this->_model_order->getQuery();
        if(! empty($order)) {
            $this->_db->setOrder($order);
        }

        // データ取得
        $result = $this->_db->select($this->_table)->execute()->one();

        $this->reset();
        return $result;
    }

    /**
     * 件数取得
     *
     * @return array 取得データ
     */
    public function count()
    {
        // 取得カラムを指定
        $this->_db->setItem('count('.$this->_id.') AS get_cnt');

        // 検索条件を指定
        $where = $this->_model_where->getQuery();
        $this->_setWhere($where['query'], $where['where']);

        // 表示フラグがTRUEのレコードだけ取得する
        // 管理フラグがFALSE、かつ、$this->_visibleが指定されている時のみ追加
        if(! $this->_is_admin && ! empty($this->_visible)) {
            $this->_db->setWhere('AND ' . $this->_table . '.' . $this->_visible . ' = ?', '1');
        }

        // Setting having query and parameters
        $having = $this->_model_having->getQuery();
        $this->_setHaving($having['query'], $having['having']);

        // テーブル結合
        $joins = $this->_model_join->getQuery();
        foreach($joins as $join) {
            $this->_setJoin($join['table'], $join['type'], $join['on'], $join['data']);
        }

        // Group By
        $group = $this->_model_group->getQuery();
        $this->_setGroup($group);

        // 表示順指定
        $order = $this->_model_order->getQuery();
        if(! empty($order)) {
            $this->_db->setOrder($order);
        }

        // データ取得
        $result = $this->_db->select($this->_table)->execute()->one();
        $this->reset();

        if($result) {
            return (int)$result['get_cnt'];
        } else {
            return 0;
        }
    }

    /**
     * IDからデータ一件取得
     *
     * @param integer $id 指定ID
     * @return array 取得データ
     */
    public function id($id)
    {
        $result =
            $this
                ->where($this->_id.' = ?', $id)
                ->one();

        $this->reset();
        return $result;
    }

    /**
     * Setting option parameter
     *
     * @param array $options Option parameter
     * @return array Converted Option parameter
     */
    private function setOptions($options)
    {
        // Option paramter structure
        $result = $this->_options;

        // Check $options type
        if(! is_array($options)) {
            throw new Exception('Options parameter must be array parameter');
        }

        // Reset Parameter
        $this->resetOptions();

        foreach($options as $key => $val) {
            if(array_key_exists($key, $result)) {
                switch($key) {
                    default:
                        $result[$key] = $val;
                        break;
                }
            } else {
                throw new Exception(sprintf('Unknown Parameter : "%s"', $key));
            }
        }

        return $result;
    }

    /**
     * Reset Options Data
     *
     * @return Model
     */
    protected function resetOptions()
    {
        $this->_options
            = array(
                'select'    => '*',
                'list_max'  => 10,
                'current'   => 1,
            );

        return $this;
    }

    /**
     * データ登録
     *
     * @param  string $item カラム名
     * @param  mixed  $data 登録するデータ
     * @return integer      登録ID
     */
    public function insert($item, $data)
    {
        // データ変換
        $this->dataBind($data);
        // データ登録
        $this->_db->setItem($item, $data)->insert($this->_table)->execute();

        $this->reset();
        return $this->_db->getLastIndexId();
    }

    /**
     * データ更新
     *
     * @param string $item    カラム名
     * @param mixed  $data    登録するデータ
     * @param string $where   検索クエリ（?を入れておくと$dataから参照）
     * @param mixed  $wh_data 検索クエリのパラメータ
     */
    public function updateWhere($item, $data, $where, $wh_data)
    {
        // データ変換
        $this->dataBind($data);
        // 検索条件を指定
        $this->_setWhere($where, $wh_data);
        // データ登録
        $this->_db->setItem($item, $data)->update($this->_table)->execute();

        $this->reset();
    }

    /**
     * IDを指定して更新
     *
     * @param string $item カラム名
     * @param mixed  $data 登録するデータ
     * @param string $id   指定ID
     */
    public function update($item, $data, $id) {
        $this->updateWhere($item, $data, $this->_id.' = ?', $id);

        $this->reset();
    }

    /**
     * データ削除
     *
     * @param string $where 検索クエリ（?を入れておくと$dataから参照）
     * @param mixed  $data  検索クエリのパラメータ
     */
    public function deleteWhere($where, $data)
    {
        // 検索条件を指定
        $this->_setWhere($where, $data);
        // データ削除
        $this->_db->setItem('valid', array('valid' => 0))->update($this->_table)->execute();

        $this->reset();
    }

    /**
     * IDを指定して削除
     *
     * @param string $id 指定ID
     */
    public function delete($id) {
        $this->deleteWhere($this->_id.' = ?', $id);

        $this->reset();
    }

    /**
     * データを物理的に削除
     *
     * @param string $where 検索クエリ（?を入れておくと$dataから参照）
     * @param mixed  $data  検索クエリのパラメータ
     */
    public function eraseWhere($where, $data)
    {
        // 検索条件を指定
        $this->_setWhere($where, $data);
        // データ削除
        $this->_db->delete($this->_table)->execute();

        $this->reset();
    }

    /**
     * IDを指定して物理的に削除
     *
     * @param string $id 指定ID
     */
    public function erase($id) {
        $this->eraseWhere($this->_id.' = ?', $id);

        $this->reset();
    }

    /**
     * Execute Create Table
     *
     * @param array $fields Fields information
     */
    public function create($fields)
    {
        $this->_db->createTable($this->_table, $fields)->execute();
    }

    /**
     * Execute Drop table
     */
    public function dropTable()
    {
        $this->_db->dropTable($this->_table)->execute();
    }

    /**
     * Execute Drop index
     *
     * @param string $index Index name
     */
    public function dropIndex($index)
    {
        $this->_db->dropIndex($index)->execute();
    }

    /**
     * Execute Alter Add column
     *
     * @param string $field Adding column name
     */
    public function alterAddColumn($field)
    {
        $this->_db->alterAddColumn($this->_table, $field)->execute();
    }

    /**
     * Execute Alter Rename table
     *
     * @param string $rename Rename to table name
     */
    public function alterRenameTo($rename)
    {
        $this->_db->alterRenameTo($this->_table, $rename)->execute();
    }

    /**
     * Execute Pragma
     *
     * @param string $mode Pragma mode
     */
    public function pragma($mode)
    {
        return $this->_db->pragma($this->_table, $mode)->execute()->all();
    }

    /**
     * 指定されたデータ形式に変換
     *
     * @param array $data 変換データ
     */
    protected function dataBind(&$data)
    {
        foreach ($this->_bind as $key => $val) {
            if(isset($data[$key])) {
                switch($val) {
                    case 'FLOAT':
                        $data[$key] = (float)$data[$key];
                        break;
                    case 'INTEGER':
                        $data[$key] = (int)$data[$key];
                        break;
                    case 'DATE':
                        if(is_numeric($data[$key])) {
                            $data[$key] = date('Y-m-d', $data[$key]);
                        } else {
                            $data[$key] = date('Y-m-d', strtotime($data[$key]));
                        }
                        break;
                    case 'DATETIME':
                        if(is_numeric($data[$key])) {
                            $data[$key] = date('Y-m-d H:i:s', $data[$key]);
                        } else {
                            $data[$key] = date('Y-m-d H:i:s', strtotime($data[$key]));
                        }
                        break;
                }
            }
        }
    }

    /**
     * HAVING句生成ヘルパ
     *
     * @param  string  $having    検索クエリ（?を入れておくと$dataから参照）
     * @param  mixed   $data     検索クエリのパラメータ
     */
    private function _setHaving($having = NULL, $data = NULL)
    {
        if(! empty($having)) {
            if(! empty($data)) {
                if(! is_array($data)) {
                    $this->_db->setHaving($having, $data);
                } else {
                    array_unshift($data, $having);
                    call_user_func_array(array($this->_db, 'setHaving'), $data);
                }
            } else {
                $this->_db->setHaving($having);
            }
        }
    }

    /**
     * WHERE句生成ヘルパ
     *
     * @param  string  $where    検索クエリ（?を入れておくと$dataから参照）
     * @param  mixed   $data     検索クエリのパラメータ
     */
    private function _setWhere($where = NULL, $data = NULL)
    {
        if(! empty($where)) {
            if(! empty($data)) {
                if(! is_array($data)) {
                    $this->_db->setWhere($where, $data);
                } else {
                    array_unshift($data, $where);
                    call_user_func_array(array($this->_db, 'setWhere'), $data);
                }
            } else {
                $this->_db->setWhere($where);
            }
        } else {
            $this->_db->setWhere('1 = 1');
        }
    }

    /**
     * ON句生成ヘルパ
     *
     * @param  string $join    テーブル結合クエリ
     * @param  string $dur     テーブル結合方向
     * @param  string $on      テーブル結合条件クエリ
     * @param  mixed  $on_data テーブル結合条件クエリのパラメータ
     */
    private function _setJoin($join = NULL, $dur = NULL, $on = NULL, $on_data = NULL)
    {
        if(! empty($join)) {
            $this->_db->setJoin($join, $dur);

            if(! empty($on)) {
                if(! empty($on_data)) {
                    if(! is_array($on_data)) {
                        $this->_db->setOn($on, $on_data);
                    } else {
                        array_unshift($on_data, $on);
                        call_user_func_array(array($this->_db, 'setOn'), $on_data);
                    }
                } else {
                    $this->_db->setOn($on);
                }
            }
        }
    }

    /**
     * Group query helper
     *
     * @param string $group
     */
    private function _setGroup($group)
    {
        if(! empty($group)) {
            $this->_db->setGroup($group);
        }
    }

    /**
     * Reset Model
     *
     * @return Model
     */
    public function reset()
    {
        $this->_model_where->resetQuery();
        $this->_model_having->resetQuery();
        $this->_model_order->resetQuery();
        $this->_model_join->resetQuery();
        $this->_model_group->resetQuery();
        $this->resetOptions();
        return $this;
    }

    /**
     * Debug
     *
     * @return string
     */
    public function debug()
    {
        echo $this->_db->getLastQuery();
    }

    /**
     * Unset regular query
     *
     * @return Model
     */
    public function unsetRegularUseQuery()
    {
        $this->_db->unsetRegularUseQuery();

        return $this;
    }

    /**
     * Unset regular query for table
     *
     * @return Model
     */
    public function unsetRegularUseQueryForTable()
    {
        $this->_db->unsetRegularUseQueryForTable();

        return $this;
    }

    /**
     * Disabled regular query
     *
     * @return Model
     */
    public function disabledRegularUseQuery()
    {
        $this->_db->disabledRegularUseQuery();

        return $this;
    }
}

class ModelWhere
{
    protected $_queries   = array();
    protected $_andOrFlag = false;

    /**
     * Add Where Query
     *
     * @param string $query
     * @param mixed  $data
     * @param string $andOr
     */
    public function addQuery($query, $data = array(), $andOr = 'AND')
    {
        if(! is_array($data)) {
            if($data !== null || $data !== false) {
                $data = array($data);
            }
        }

        if($this->_andOrFlag) {
            $andOr = strtoupper(trim($andOr));
            if($andOr !== 'OR') {
                $andOr = 'AND';
            }
            $andOr = ' ' . $andOr . ' ';
        } else {
            $andOr = '';
            $this->_andOrFlag = true;
        }

        $this->_queries[] = array(
            'query' => $query,
            'data'  => $data,
            'andOr' => $andOr,
        );
    }

    /**
     * Get Where Query
     *
     * @param array
     */
    public function getQuery()
    {
        $result = array(
            'query' => '',
            'where' => array(),
        );

        foreach($this->_queries as $row) {
            $result['query'] .= $row['andOr'] . $row['query'];

            if(is_array($row['data'])) {
                foreach($row['data'] as $data) {
                    $result['where'][] = $data;
                }
            }
        }

        return $result;
    }

    /**
     * Open Bracket
     *
     * @param string $andOr
     */
    public function openBracket($andOr = 'AND')
    {
        if($this->_andOrFlag) {
            $andOr = strtoupper(trim($andOr));
            if($andOr !== 'OR') {
                $andOr = 'AND';
            }
            $andOr = ' ' . $andOr . ' ';
        } else {
            $andOr = '';
        }

        $this->_queries[] = array(
            'query' => ' (',
            'data'  => array(),
            'andOr' => $andOr,
        );

        $this->_andOrFlag = false;
    }

    /**
     * Close Bracket
     */
    public function closeBracket()
    {
        $this->_queries[] = array(
            'query' => ') ',
            'data'  => array(),
            'andOr' => '',
        );

        $this->_andOrFlag = true;
    }

    /**
     * Reset Query
     */
    public function resetQuery()
    {
        $this->_queries = array();
        $this->_andOrFlag = false;
    }
}

class ModelHaving
{
    protected $_queries   = array();
    protected $_andOrFlag = false;

    /**
     * Add Having Query
     *
     * @param string $query
     * @param mixed  $data
     * @param string $andOr
     */
    public function addQuery($query, $data = array(), $andOr = 'AND')
    {
        if(! is_array($data)) {
            if($data !== null || $data !== false) {
                $data = array($data);
            }
        }

        if($this->_andOrFlag) {
            $andOr = strtoupper(trim($andOr));
            if($andOr !== 'OR') {
                $andOr = 'AND';
            }
            $andOr = ' ' . $andOr . ' ';
        } else {
            $andOr = '';
            $this->_andOrFlag = true;
        }

        $this->_queries[] = array(
            'query' => $query,
            'data'  => $data,
            'andOr' => $andOr,
        );
    }

    /**
     * Get Where Query
     *
     * @param array
     */
    public function getQuery()
    {
        $result = array(
            'query'  => '',
            'having' => array(),
        );

        foreach($this->_queries as $row) {
            $result['query'] .= $row['andOr'] . $row['query'];

            if(is_array($row['data'])) {
                foreach($row['data'] as $data) {
                    $result['having'][] = $data;
                }
            }
        }

        return $result;
    }

    /**
     * Open Bracket
     *
     * @param string $andOr
     */
    public function openBracket($andOr = 'AND')
    {
        if($this->_andOrFlag) {
            $andOr = strtoupper(trim($andOr));
            if($andOr !== 'OR') {
                $andOr = 'AND';
            }
            $andOr = ' ' . $andOr . ' ';
        } else {
            $andOr = '';
        }

        $this->_queries[] = array(
            'query' => ' (',
            'data'  => array(),
            'andOr' => $andOr,
        );

        $this->_andOrFlag = false;
    }

    /**
     * Close Bracket
     */
    public function closeBracket()
    {
        $this->_queries[] = array(
            'query' => ') ',
            'data'  => array(),
            'andOr' => '',
        );

        $this->_andOrFlag = true;
    }

    /**
     * Reset Query
     */
    public function resetQuery()
    {
        $this->_queries = array();
        $this->_andOrFlag = false;
    }
}

class ModelOrder
{
    protected $_queries = array();

    /**
     * Add Order Query
     *
     * @param string $order
     * @param string $duration
     */
    public function addQuery($order, $duration = 'ASC')
    {
        if($order === 'random') {
            $this->_queries[] = 'RANDOM()';
        } else {
            $duration = strtoupper(trim($duration));
            if($duration !== 'DESC') {
                $duration = 'ASC';
            }
            $this->_queries[] = $order . ' ' . $duration;
        }
    }

    /**
     * Get Group Query
     *
     * @param string
     */
    public function getQuery()
    {
        return implode(',', $this->_queries);
    }

    /**
     * Reset Query
     */
    public function resetQuery()
    {
        $this->_queries = array();
    }
}

class ModelJoin
{
    protected $_queries = array();

    /**
     * Add Join Query
     *
     * @param string $table
     * @param string $type
     * @param string $on
     * @param array  $data
     */
    public function addQuery($table, $type = 'INNER', $on = null, $data = array())
    {
        $type = strtoupper(trim($type));
        switch($type) {
            case 'LEFT':
            case 'RIGHT':
                break;
            default:
                $type = 'INNER';
                break;
        }

        if(! is_array($data)) {
            if($data !== null || $data !== false) {
                $data = array($data);
            }
        }

        $this->_queries[] = array(
            'table' => $table,
            'type'  => $type,
            'on'    => $on,
            'data'  => $data,
        );
    }

    public function getQuery()
    {
        return $this->_queries;
    }

    /**
     * Reset Query
     */
    public function resetQuery()
    {
        $this->_queries = array();
    }
}

class ModelGroup
{
    protected $_queries = array();

    /**
     * Add Group Query
     *
     * @param string $group
     */
    public function addQuery($group)
    {
        $this->_queries[] = $group;
    }

    /**
     * Get Group Query
     *
     * @param string
     */
    public function getQuery()
    {
        return implode(',', $this->_queries);
    }

    /**
     * Reset Query
     */
    public function resetQuery()
    {
        $this->_queries = array();
    }
}

/**
 * カテゴリーラベルを取得
 *
 * @param string $id
 * @param string $key
 * @return string
 */
function feggCategoryLabel($id, $key)
{
    $app = FEGG_getInstance();

    if(! isset($app->config['category_info'])) {
        throw new Exception('No loaded category information config');
    }

    if(! isset($app->config['category_info'][$key])) {
        throw new Exception(sprintf('Unknown key : %s', $key));
    }

    if(isset($app->config['category_info'][$key][$id])) {
        return $app->config['category_info'][$key][$id];
    }
    return '';
}

/**
 * カテゴリーリストを取得
 *
 * @param string $key
 * @return array
 */
function feggCategories($key)
{
    $app = FEGG_getInstance();

    if(! isset($app->config['category_info'])) {
        throw new Exception('No loaded category information config');
    }

    if(! isset($app->config['category_info'][$key])) {
        throw new Exception(sprintf('Unknown key : %s', $key));
    }

    return $app->config['category_info'][$key];
}
