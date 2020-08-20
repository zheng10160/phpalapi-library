<?php
/**
 * PhalApi_Helper_ApiDesc - 在线接口描述查看 - 辅助类
 *
 * @package     PhalApi\Helper
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author      dogstar <chanzonghuang@gmail.com> 2015-05-30
 */

class PhalApi_Helper_ApiDesc extends PhalApi_Helper_ApiOnline {

    public function render() {
        $service = DI()->request->getService();

        $rules = array();
        $returns = array();
        $description = '';
        $descComment = '//请使用@desc 注释';
        $exceptions = array();

        try {
            $api = PhalApi_ApiFactory::generateService(FALSE);
            $rules = $api->getApiRules();
        } catch (PhalApi_Exception $ex){
            $service .= ' - ' . $ex->getMessage();
            include dirname(__FILE__) . '/api_desc_tpl.php';
            return;
        }

        list($className, $methodName) = explode('.', $service);
        $className = 'Api_' . $className;

        // 整合需要的类注释，包括父类注释
        $rClass = new ReflectionClass($className);
        $classDocComment = $rClass->getDocComment();
        while ($parent = $rClass->getParentClass()) {
            if ($parent->getName() == 'PhalApi_Api') {
                break;
            }
            $classDocComment = $parent->getDocComment() . "\n" . $classDocComment;
            $rClass = $parent;
        }
        $needClassDocComment = '';
        foreach (explode("\n", $classDocComment) as $comment) {
            if (stripos($comment, '@exception') !== FALSE 
                || stripos($comment, '@return') !== FALSE) {
                $needClassDocComment .=  "\n" . $comment;
            }
        }

        // 方法注释
        $rMethod = new ReflectionMethod($className, $methodName);
        $docCommentArr = explode("\n", $needClassDocComment . "\n" . $rMethod->getDocComment());

        foreach ($docCommentArr as $comment) {
            $comment = trim($comment);

            //标题描述
            if (empty($description) && strpos($comment, '@') === FALSE && strpos($comment, '/') === FALSE) {
                $description = substr($comment, strpos($comment, '*') + 1);
                continue;
            }

            //@desc注释
            $pos = stripos($comment, '@desc');
            if ($pos !== FALSE) {
                $descComment = substr($comment, $pos + 5);
                continue;
            }

            //@exception注释
            $pos = stripos($comment, '@exception');
            if ($pos !== FALSE) {
                $exArr = explode(' ', trim(substr($comment, $pos + 10)));
                $exceptions[$exArr[0]] = $exArr;
                continue;
            }

            //@return注释
            $pos = stripos($comment, '@return');
            if ($pos === FALSE) {
                continue;
            }

            $returnCommentArr = explode(' ', substr($comment, $pos + 8));
            //将数组中的空值过滤掉，同时将需要展示的值返回
            $returnCommentArr = array_values(array_filter($returnCommentArr));
            if (count($returnCommentArr) < 2) {
                continue;
            }
            if (!isset($returnCommentArr[2])) {
                $returnCommentArr[2] = '';	//可选的字段说明
            } else {
                //兼容处理有空格的注释
                $returnCommentArr[2] = implode(' ', array_slice($returnCommentArr, 2));
            }

            //以返回字段为key，保证覆盖
            $returns[$returnCommentArr[1]] = $returnCommentArr; 
        }

        $projectName = $this->projectName;

        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) ? 'https://' : 'http://';
        $url = $url . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost');
        $url .= substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/') + 1);
        $menu = $this->vget($url . 'listAllApis.php?type=json');
        $menus = json_decode($menu, true);
        
        // 运行模式
        $env = (PHP_SAPI == 'cli') ? TRUE : FALSE;
        $webRoot = '';
        if ($env) {
            $trace = debug_backtrace();
            $listFilePath = $trace[0]['file'];
            $webRoot = substr($listFilePath, 0, strrpos($listFilePath, D_S));
        }
        
        include dirname(__FILE__) . '/api_desc_tpl.php';
    }
}
