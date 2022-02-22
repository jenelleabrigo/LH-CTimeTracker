<?php
/**
 * Command Application class
 *
 * CLI Command abstract class
 *
 * @access public
 * @author LH & Creatives
 * @version 0.0.1
 */

class CommandApplication
{

    public function __construct()
    {
        // Fegg設定ファイルを取得
        require(FEGG_DIR . '/settings.php');
        $this->_settings = $settings;

        require( FEGG_DIR.'/Model.php' );

        // 開発モード
        define('FEGG_DEVELOPER', '1');
        ini_set('display_errors', 1);

        if (version_compare(PHP_VERSION, '5.3.0') >= 0) {
            set_error_handler(array(&$this, "errorHandler"), E_ALL ^E_NOTICE ^E_DEPRECATED);
        } else {
            set_error_handler(array(&$this, "errorHandler"), E_ALL ^E_NOTICE);
        }
    }

    public function __init()
    {
    }

    /**
     * アプリケーションの設定値取得
     * @param string $name 設定名
     * @return string 設定値
     */
    function getSetting($name)
    {
        return isset($this->_settings[$name]) ? $this->_settings[$name] : '';
    }

    /**
     * インスタンス取得
     * @return Application このクラスのインスタンス
     */
    function &getInstance()
    {
        return $this;
    }

    /**
     * コンフィグファイル読み込み
     * @param string $name コンフィグファイル名（.phpは不要）
     * @param string $languageCode
     * @return コンフィグ（配列）
     */
    function loadConfig($name, $languageCode = '')
    {
        // 既に読み込み済みの場合はその値を返す
        if (isset($this->config[$name])) { return $this->config[$name]; }

        $configFile = "$name.php";

        // グローバルコンフィグ
        if ($this->_settings['global_config_dir'] && file_exists($this->_settings['global_config_dir'] . $configFile)) {
            require($this->_settings['global_config_dir'] . $configFile);
        }

        // コンフィグ
        if (file_exists(FEGG_CODE_DIR . "/config/$configFile")) {
            require(FEGG_CODE_DIR . "/config/$configFile");
        }

        // 読み込み完了確認
        if (isset($$name)) {
            $this->config[$name] = $$name;
            return $this->config[$name];
        } else {
            echo "Can't Read Config File: $name";
            exit;
        }
    }

    /**
     * クラスを読み込みインスタンスを返す
     * @param string $file ファイル
     * @param array $parameter
     * @return mixed 正常時：クラスインスタンス 異常時：null
     */
    function getClass($file, $parameter = '')
    {
        $segments = explode('/', $file);
        $tempPath = '';
        $fileName = '';
        $nameSpace = '';

        foreach ($segments as $key => $value) {

            // 同一階層に同一のフォルダ名とファイル名が存在する場合はファイルを優先する
            if (file_exists(FEGG_CODE_DIR . '/lib/' . $tempPath . ucwords($value) . '.php')) {
                $fileName = ucwords($value);
                break;
            }
            $tempPath .= $value . '/';
            $nameSpace .= ucwords($value) . '_';
        }

        if ($fileName) {
            require_once(FEGG_CODE_DIR . "/lib/$file.php");
            $className = $nameSpace . $fileName;
            return new $className($parameter);
        } else {
            return null;
        }
    }

    /**
     * エラー時の例外発生
     * @param int $errorNo
     * @param string $errorMessage
     * @param string $errorFile
     * @param int $errorLine
     */
    function errorHandler($errorNo, $errorMessage, $errorFile, $errorLine)
    {
        // 開発モードでは詳細を表示してから例外を発生させる
        if (FEGG_DEVELOPER) {
            $error   = array();
            $error[] = "Error File: $errorFile";
            $error[] = "Error Line: $errorLine";
            $error[] = "Error Message: \033[0;31m$errorMessage\033[0m";

            // エラー対象ファイルの該当行の表示
            if(file_exists($errorFile)) {
                $file = file_get_contents($errorFile);
                $line = explode("\n", $file);
                for ($i = $errorLine - 10; $i <= $errorLine + 10; $i++) {
                    if ($i > 0 && isset($line[$i - 1])) {
                        $rowText = '';
                        if ($i == $errorLine) { $rowText .= "\033[0;31m"; }
                        $rowText .= $i . ': ' . $line[$i - 1];
                        if ($i == $errorLine) { $rowText .= "\033[0m"; }
                        $error[] = $rowText;
                    }
                }
            }

            FEGG_commandError($error);
        }

        // 例外発生
        throw new ErrorException($errorMessage, 0, $errorNo, $errorFile, $errorLine);
    }

}

/**
 * アプリケーションの致命的エラー処理
 */
function shutdownHandler()
{
    $error = error_get_last();
    if (defined('FEGG_DEVELOPER') && FEGG_DEVELOPER && $error) {
        $errorLogs = array();

        $errorLogs[] = "Debug Information (Developer Only) by Application::shutdownHandler()";
        $errorLogs[] = "Error File: " . $error['file'];
        $errorLogs[] = "Error Line: " . $error['line'];
        $errorLogs[] = "Error Message: \033[0;31m" . $error['message'] . "\033[0m";
        if (file_exists($error['file'])) {
            $source = explode("\n", htmlspecialchars(file_get_contents($error['file'])));
            $maxRow = '%0' . strlen((string)($error['line'] + 10)) . 'd';

            for ($key = $error['line'] - 10; $key <= $error['line'] + 10; $key++) {
                if ($key > 0 && isset($source[$key])) {
                    $value = $source[$key];

                    if ($key + 1 == $error['line']) {
                        $errorLogs[] = "\033[0;31m" . sprintf($maxRow, ($key + 1)) . ": $value\033[0m";
                    } else {
                        $errorLogs[] = sprintf($maxRow, ($key + 1)) . ": $value";
                    }
                }
            }
        }

        FEGG_commandError($errorLogs);
    }
}
register_shutdown_function('shutdownHandler');