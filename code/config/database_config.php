<?php
/**
 * データベース接続先設定
 * Database connect information
 * @see Database.php
 */
// 本番環境
// Production environment
if (false) {

    // Database(Master)設定（１台を想定）
    // Database for Master
    $database_config['master'] = array(
        'dsn' => 'sqlite:'.FEGG_CODE_DIR.'/data/db/db.db',
        'username' => '',
        'password' => ''
    );

    // Database(Slave)設定（複数台を想定）
    // Database for Slave
    $database_config['slave'][] = array(
        'dsn' => 'sqlite:'.FEGG_CODE_DIR.'/data/db/db.db',
        'username' => '',
        'password' => ''
    );

// 開発環境
// Development Environment
} else {

    // Database(Master)設定（１台を想定）
    // Database for Master
    $database_config['master'] = array(
        'dsn' => 'sqlite:'.FEGG_CODE_DIR.'/data/db/db.db',
        'username' => '',
        'password' => ''
    );

    // Database(Slave)設定（複数台を想定）
    // Database for Slave
    $database_config['slave'][] = array(
        'dsn' => 'sqlite:'.FEGG_CODE_DIR.'/data/db/db.db',
        'username' => '',
        'password' => ''
    );

}
/* End of file database_config.php */
