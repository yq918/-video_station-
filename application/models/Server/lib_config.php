<?php
/**
 * ����������ȫ�ֶ���$php�Ĺ���
 * @package SwooleSystem
 * @author �����
 */
define("LIBPATH", __DIR__);
if (PHP_OS == 'WINNT')
{
    define("NL", "\r\n");
}
else
{
    define("NL", "\n");
}
define("BL", "<br />" . NL);


require_once __DIR__ . '/Loader.php';
/**
 * ע�ᶥ�������ռ䵽�Զ�������
 */
spl_autoload_register('\\models\\Server\\Loader::autoload');

/**
 * ��������ȫ�ֱ���
 */
global $php;
$php = Swoole::getInstance();

function createModel($model_name)
{
    return model($model_name);
}

/**
 * ����һ��model�ӿڣ�ģ����ע������Ϊ����
 * @param $model_name
 * @param $db_key
 * @return Swoole\Model
 */
function model($model_name, $db_key = 'master')
{
    return Swoole::getInstance()->model->loadModel($model_name, $db_key);
}

/**
 * ����һ�����ݿ������һ����װ�˱��Model�ӿ�
 * @param $table_name
 * @param $db_key
 * @return Swoole\Model
 */
function table($table_name, $db_key = 'master')
{
    return Swoole::getInstance()->model->loadTable($table_name, $db_key);
}

/**
 * �����Ự
 * @param $readonly
 */
function session($readonly = false)
{
    Swoole::getInstance()->session->start($readonly);
}

/**
 * �������ݣ���ֹ���������
 */
function debug()
{
    $vars = func_get_args();
    foreach ($vars as $var)
    {
        if (php_sapi_name() == 'cli')
        {
            var_export($var);
        }
        else
        {
            highlight_string("<?php\n" . var_export($var, true));
            echo '<hr />';
        }
    }
    exit;
}
/**
 * ����һ������
 * @param $error_id
 * @param $stop
 */
function error($error_id, $stop = true)
{
    global $php;
    $error = new \Swoole\Error($error_id);
    if (isset($php->error_call[$error_id]))
    {
        call_user_func($php->error_call[$error_id], $error);
    }
    elseif ($stop)
    {
        exit($error);
    }
    else
    {
        echo $error;
    }
}

/**
 * ������Ϣ�������
 */
function swoole_error_handler($errno, $errstr, $errfile, $errline)
{
    $info = '';
    switch ($errno)
    {
        case E_USER_ERROR:
            $level = 'User Error';
            break;
        case E_USER_WARNING:
            $level = 'Warnning';
            break;
        case E_USER_NOTICE:
            $level = 'Notice';
            break;
        default:
            $level = 'Unknow';
            break;
    }

    $title = 'Swoole '.$level;
    $info .= '<b>File:</b> '.$errfile."<br />\n";
    $info .= '<b>Line:</b> '.$errline."<br />\n";
    $info .= '<b>Info:</b> '.$errstr."<br />\n";
    $info .= '<b>Code:</b> '.$errno."<br />\n";
    echo Swoole\Error::info($title, $info);
}
