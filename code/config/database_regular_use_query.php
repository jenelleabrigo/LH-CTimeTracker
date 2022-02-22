<?php
/**
 * 自動的付加クエリー
 * Automatic append query
 * @see Database.php
 *
 * DBクラスのselect, insert, update, deleteメソッド実行時に自動的付加するクエリー
 * （必要に応じて拡張していく予定）
 *
 * Automaticaly append query that "select", "insert", "update", "delete" method in DB Class.
 */
$database_regular_use_query = array(
    // テーブルに関わらず付加されるクエリー
    // Appended query regardless of table
    'regular_use' => array(
        'select' => array(
            'where' => 'CURRENT_TABLE.valid = 1',
            'order' => '',
        ),
        'count' => array(
            'where' => 'CURRENT_TABLE.valid = 1',
            'order' => '',
        ),
        'insert' => array(
            'item' => 'modified = now(), created = now(), valid = 1',
        ),
        'update' => array(
            'item' => "modified = now()",
            'where' => '',
        ),
    ),

    /*
    // テーブルに応じて付加されるクエリー
    // Appended query depending on table
    'table' => array(
        'table_name' => array(
            'select' => array(
                'where' => 'show_flag = 0',
                'order' => '',
            ),
            'insert' => array(
                'item' => 'update_time = now(), create_datetime = now()',
            ),
            'update' => array(
                'item' => 'update_time = now()',
                'where' => '',
            ),
        ),
    ),
    */
);
/* End of file database_regular_use_query.php */