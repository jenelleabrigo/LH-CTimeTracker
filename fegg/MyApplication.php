<?php
/**
 * MyApplicationクラス
 *
 * 拡張Applicationクラス
 *
 * @access public
 * @author lionheart.co.jp
 * @version 1.0.0
 */

class MyApplication extends Application
{
    protected $_model = NULL;

    /**
     *  constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Load Extension Class
        require( FEGG_DIR.'/Model.php' );
        require( FEGG_DIR.'/Modifire.php' );

        // User Modifire
        require( FEGG_CODE_DIR.'/lib/Modifire.php' );
    }

    /**
     * Initialize
     */
    public function __init()
    {
        // Load Category Inforamtion
        $this->loadConfig( 'category_info' );

        // Load Site Information
        $this->loadConfig( 'site_info' );
        foreach( $this->config[ 'site_info' ] as $key => $val ) {
            $this->setSiteInfo( $key, $val );
        }
    }

    /**
     * モデル読込
     */
    public function loadModel( $file )
    {
        $segments = explode('/', $file);
        $tempPath = '';
        $fileName = '';
        $nameSpace = 'Model_';
        $className = '';

        // パラメータ
        $parameter = func_get_args();
        // 頭は取り除く
        array_shift( $parameter );

        foreach ($segments as $key => $value) {

            // 同一階層に同一のフォルダ名とファイル名が存在する場合はファイルを優先する
            if (file_exists(FEGG_CODE_DIR . '/model/' . $tempPath . ucwords($value) . '.php')) {
                $fileName = ucwords($value);
                break;
            }
            $tempPath .= $value . '/';
            $nameSpace .= ucwords($value) . '_';
        }

        if ($fileName) {
            require_once(FEGG_CODE_DIR . "/model/$file.php");
            $className = $nameSpace . $fileName;

            if( func_num_args() <= 1 ) {
                return new $className;
            } else {
                $reflection = new ReflectionClass( $className );
                return $reflection->newInstanceArgs( $parameter );
            }
        } else {
            return null;
        }
    }
}
/* End of file MyApplication.php */