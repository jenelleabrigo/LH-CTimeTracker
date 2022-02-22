<?php
/**
 * Install extension library cli class
 *
 * @access public
 * @author LH&Creatives Inc.
 * @version 0.0.1
 */

class Install extends CommandApplication
{

    public function help()
    {
        $messages = array(
            'Fegg install class',
            'fegg-cli install:class [repo] : Install Fegg library',
        );
        FEGG_print($messages);
    }

    public function class($path)
    {
        // Git Clone
        FEGG_print('Start cloning repository');
        $dirPath = $this->gitClone($path);

        // Check Installed files
        $srcDirectory = $dirPath.'/src';
        if(! file_exists($srcDirectory) || ! is_dir($srcDirectory)) {
            $this->removeDirectory($dirPath);
            FEGG_commandError('This repository is not Fegg Library');
        }

        // Copy Data
        FEGG_print('');
        FEGG_print('Start copying files');
        $libraryDirectory = FEGG_CODE_DIR.'/lib';
        $this->copyDirectory($dirPath.'/src', $libraryDirectory, true);
        FEGG_print('Finished copying files');

        // Delete Cloned data
        $this->removeDirectory($dirPath);
        FEGG_print('Deleted cloned files');
        FEGG_print('');

        FEGG_print(sprintf('Finished installing %s', $path));
    }

    /**
     * Clone from Git
     *
     * @param string $path
     * @return void
     */
    protected function gitClone($path)
    {
        $gitPath = $path;
        $dirName = null;

        // Get Git command's full path
        exec('whereis git', $output);
        if(! isset($output[0])) {
            FEGG_commandError('git not found');
        }
        $gitCommand = $output[0];

        // If not absolute path, clone from GitHub
        if(! preg_match('/^(https?|git):/', $path)) {
            $gitPath = 'https://github.com/' . $path . '.git';

            $pathArray = explode('/', $path);
            $dirName = array_pop($pathArray);
        } else {
            $pathArray = explode('/', $path);
            $dirName = array_pop($pathArray);

            $pathArray = explode('.', $dirName);
            $dirName = array_shift($pathArray);
        }

        // Clone target
        $dirPath = FEGG_CODE_DIR.'/data/cache/install/' . $dirName;

        // Make Git clone command
        $command = sprintf('%s clone %s %s', $gitCommand, $gitPath, $dirPath);
        exec($command);

        return $dirPath;
    }

    /**
     * ディレクトリコピー
     * @param string $fromDirectory コピー元ディレクトリ
     * @param string $toDirectory コピー先のディレクトリ（要作成）
     * @param boolean $recursiveCallFlag True: サブディレクトりも処理
     */
    protected function copyDirectory($fromDirectory, $toDirectory, $recursiveCallFlag = false)
    {
        if (is_dir($fromDirectory)) {

            if ($handle = opendir($fromDirectory)) {
                while (($item = readdir($handle)) !== false) {

                    if ($item == "." || $item == "..") {
                        continue;
                    }

                    if (is_dir($fromDirectory . "/" . $item)) {
                        if ($recursiveCallFlag) {
                            // コピー先のディレクトリ作成（存在していれば処理されない）と再帰呼出
                            $this->createDirectory($toDirectory . "/" . $item);
                            $this->copyDirectory($fromDirectory . "/" . $item, $toDirectory . "/" . $item, true);
                        }
                    } else {
                        copy($fromDirectory . "/" . $item, $toDirectory . "/" . $item);
                        FEGG_print(sprintf('.. Create %s', $toDirectory . "/" . $item));
                    }
                }
                closedir($handle);
            }
        }
    }

    /**
     * ディレクトリ作成
     * @param string $directory
     * @param string $permission パーミッション（chmodのパラメーター）
     */
    protected function createDirectory($directory, $permission = '0777')
    {
        if (!file_exists($directory)) {
            if (mkdir($directory)) {
                $permission = sprintf("%04d", "777");
                chmod($directory, octdec($permission));
            }
        }
    }

    /**
     * ディレクトリ削除
     * @param string $directory
     */
    protected function removeDirectory($directory)
    {
        if (is_dir($directory)) {
            if ($handle = opendir($directory)) {
                while (($item = readdir($handle)) !== false) {

                    if ($item == "." || $item == "..") {
                        continue;
                    }

                    if (is_dir($directory . "/" . $item)) {
                        // ディレクトリであれば自身を再帰呼出する
                        $this->removeDirectory($directory . "/" . $item);
                    } else {
                        unlink($directory . "/" . $item);
                    }
                }
                closedir($handle);
            }
            rmdir($directory);
        }
    }

}