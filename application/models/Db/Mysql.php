<?php
namespace Db;
use Base\Base;
/**
 * Class Db_Mysql
 *
 * [���ݿ����]
 */
class Mysql{
    /**
     * ���ݿ�����
     * @var PDO
     */
    protected $db;

    /**
     * ���ݱ���
     * @var string
     */
    public $tablename;

    /**
     * ����
     * @var string
     */
    public $pk = 'id';

    /**
     * ��ѯ����
     * @var array
     */
    public $options = array();

    /**
     * PDO ʵ��������
     *
     * @var object
     */
    static $instance = array();

    /**
     * ����
     * @var string
     */
    protected $_config;

    /**
     * ������Ϣ
     */
    public $error = array();

    /**
     * �������
     * @var string
     */
    protected $_lock = '';

    /**
     * ����ʼ
     * @var bool
     */
    private $_begin_transaction = false;

    /**
     * ���캯��
     * @param string $pConfig ����
     */
    function __construct($pConfig = 'default'){
        $this->_config = $pConfig;
        $this->tablename || $this->tablename = strtolower(substr(get_class($this), 0, -5));
    }

    /**
     * ���ⷽ��ʵ��
     * @param string $pMethod
     * @param array $pArgs
     * @return mixed
     */
    function __call($pMethod, $pArgs){
# ���������ʵ��
        if(in_array($pMethod, array('field', 'table', 'where', 'order', 'limit', 'page', 'having', 'group', 'distinct'), true)){
            $this->options[$pMethod] = $pArgs[0];
            return $this;
        }
# ͳ�Ʋ�ѯ��ʵ��
        if(in_array($pMethod, array('count', 'sum', 'min', 'max', 'avg'))){
            $field = isset($pArgs[0])? $pArgs[0]: '*';
            return $this->fOne("$pMethod($field)");
        }
# ����ĳ���ֶλ�ȡ��¼
        if('ff' == substr($pMethod, 0, 2)){
            return $this->where(strtolower(substr($pMethod, 2)) . "='{$pArgs[0]}'")->fRow();
        }
    }

    /**
     * ���ݿ�����
     * @param string $pConfig ����
     * @return PDO
     */
    static function instance($pConfig = 'default'){
        if(empty(self::$instance[$pConfig])){
            $tDB = Base::parseIni('../config/config.ini');
            $master = $tDB['master'];
            self::$instance[$pConfig] = new \PDO($master['dsn'], $master['username'], $master['password'], array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        }
        return self::$instance[$pConfig];
    }

    /**
     * ��������
     * @param array $datas ��������
     * @return bool
     */
    private function _filter(&$datas){
        $tFields = $this->getFields();
        foreach($datas as $k1 => &$v1){
            if(isset($tFields[$k1])){
                $v1 = strtr($v1, array('\\' => '', "'" => "'"));
            } else {
                unset($datas[$k1]);
            }
        }
        return $datas? true: false;
    }

    /**
     * ��ѯ����
     * @param array $pOpt ����
     * @return array
     */
    private function _options($pOpt = array()){
# �ϲ���ѯ����
        $tOpt = $pOpt? array_merge($this->options, $pOpt): $this->options;
        $this->options = array();
# ���ݱ�
        empty($tOpt['table']) && $tOpt['table'] = $this->tablename;
        empty($tOpt['field']) && $tOpt['field'] = '*';
        return $tOpt;
    }

    /**
     * ִ��SQL
     * @param string $sql ��ѯ���
     * @return int
     */
    function exec($sql){
        $this->db || $this->db = self::instance($this->_config);
        if($tReturn = $this->db->exec($sql)){
            $this->error = array();
        }
        else{
            $this->error = $this->db->errorInfo();
            isset($this->error[1]) || $this->error = array();
        }
        return $tReturn;
    }

    /**
     * ���ó�����Ϣ
     * @param string $msg ��Ϣ
     * @param int $code ������
     * @param string $state ״̬��
     * @return bool
     */
    function setError($msg, $code = 1, $state = 'UNKNOW'){
        $this->error = array($state, $code, $msg);
        return false;
    }

    /**
     * ִ��SQL�������ؽ������
     * @param string $sql ��ѯ���
     * @return array
     */
    function query($sql){
        $this->db || $this->db = self::instance($this->_config);
# �����ѯ
        if($this->_lock) {
            $sql.= ' '.$this->_lock;
            $this->_lock = '';
        }
        if(!$query = $this->db->query($sql)){
            $this->error = $this->db->errorInfo();
            isset($this->error[1]) || $this->error = array();
            return array();
        }
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * ��Ӽ�¼
     */
    function insert($datas, $pReplace = false){ 
        if($this->_filter($datas)){
            if($this->exec(($pReplace? "REPLACE": "INSERT") . " INTO `$this->tablename`(`".join('`,`', array_keys($datas))."`) VALUES ('".join("','", $datas)."')")){
                return $this->db->lastInsertId();
            }
        }
        return 0;
    }

    /**
     * ���¼�¼
     */
    function update($datas){
# ����
        if(!$this->_filter($datas)) return false;
# ����
        $tOpt = array();
        if(isset($datas[$this->pk])){
            $tOpt = array('where' => "$this->pk='{$datas[$this->pk]}'");
        }
        $tOpt = $this->_options($tOpt);
# ����
        if($datas && !empty($tOpt['where'])){
            foreach($datas as $k1 => $v1) $tSet[] = "`$k1`='$v1'";
            return $this->exec("UPDATE `" . $tOpt['table'] . "` SET " . join(',', $tSet) . " WHERE " . $tOpt['where']);
        }
        return false;
    }

    /**
     * ɾ����¼
     */
    function del(){
        if($tArgs = func_get_args()){
# ����ɾ��
            $tSql = "DELETE FROM `$this->tablename` WHERE ";
            if(intval($tArgs[0]) || count($tArgs) > 1){
                return $this->exec($tSql . $this->pk . ' IN(' . join(',', array_map("intval", $tArgs)) . ')');
            }
# ����ɾ������
            return $this->exec($tSql . $tArgs[0]);
        }
# ����ɾ��
        $tOpt = $this->_options();
        if(empty($tOpt['where'])) return false;
        return $this->exec("DELETE FROM `" . $tOpt['table'] . "` WHERE " . $tOpt['where']);
    }

    /**
     * ����һ��
     */
    function fRow($pId = 0){
        if(false === stripos($pId, 'SELECT')){
            $tOpt = $pId? $this->_options(array('where' => $this->pk . '=' . abs($pId))): $this->_options();
            $tOpt['where'] = empty($tOpt['where'])? '': ' WHERE ' . $tOpt['where'];
            $tOpt['order'] = empty($tOpt['order'])? '': ' ORDER BY ' . $tOpt['order'];
            $tSql = "SELECT {$tOpt['field']} FROM `{$tOpt['table']}` {$tOpt['where']} {$tOpt['order']}  LIMIT 0,1";
        }
        else{
            $tSql = & $pId;
        }
        if($tResult = $this->query($tSql)){
            return $tResult[0];
        }
        return array();
    }

    /**
     * ����һ�ֶ� ( ���� fRow )
     *
     * @param string $pField
     * @return string
     */
    function fOne($pField){
        $this->field($pField);
        if(($tRow = $this->fRow()) && isset($tRow[$pField])){
            return $tRow[$pField];
        }
        return false;
    }



    /**
     * ���Ҷ���
     */
    function fList($pOpt = array()){
        if(!is_array($pOpt)){
            $pOpt = array('where' => $this->pk . (strpos($pOpt, ',')? ' IN(' . $pOpt . ')': '=' . $pOpt));
        }
        $tOpt = $this->_options($pOpt);
        $tSql = "SELECT {$tOpt['field']} FROM  `{$tOpt['table']}`";
        $this->join && $tSql .= implode(' ', $this->join);
        empty($tOpt['where']) || $tSql .= ' WHERE ' . $tOpt['where'];
        empty($tOpt['group']) || $tSql .= ' GROUP BY ' . $tOpt['group'];
        empty($tOpt['order']) || $tSql .= ' ORDER BY ' . $tOpt['order'];
        empty($tOpt['having']) || $tSql .= ' HAVING ' . $tOpt['having'];
        empty($tOpt['limit']) || $tSql .= ' LIMIT ' . $tOpt['limit'];

        // echo $tSql;
        return $this->query($tSql);
    }

    /**
     * ��ѯ������Ϊ�������� ( ���� fList )
     *
     * @param string $pField
     * @return array
     */
    function fHash($pField){
        $this->field($pField);
        $tList = array();
        $tField = explode(',', $pField);
        if(2 == count($tField)) {
            foreach($this->fList() as $v1) {
                $tList[$v1[$tField[0]]] = $v1[$tField[1]];
            }
        }
        else {
            foreach($this->fList() as $v1) {
                $tList[$v1[$tField[0]]] = $v1;
            }
        }
        return $tList;
    }

    /**
     * ���ݱ���
     * @return array
     */
    function getTables(){
        $this->db || $this->db = self::instance($this->_config);
        return $this->db->query("SHOW TABLES")->fetchAll(3);
    }

    /**
     * ���ݱ��ֶ�
     * @param string $table ����
     * @return mixed
     */
    function getFields($table = ''){
        static $fields = array();
        $table || $table = $this->tablename;
# ��̬ ��ȡ���ֶ�
        if(empty($fields[$table])){
# ���� ��ȡ���ֶ�
            if(is_file($tFile = APPLICATION_PATH.'/cache/db/fields/'.$table)){
                $fields[$table] = unserialize(file_get_contents($tFile, true));
            }
# ���ݿ� ��ȡ���ֶ�
            else {
                $fields[$table] = array();
                $this->db || $this->db = self::instance($this->_config);
                if($tQuery = $this->db->query("SHOW FULL FIELDS FROM `$table`")){
                    foreach($tQuery->fetchAll(2) as $v1){
                        $fields[$table][$v1['Field']] = array('type' => $v1['Type'], 'key' => $v1['Key'], 'null' => $v1['Null'], 'default' => $v1['Default'], 'comment' => $v1['Comment']);
                    }
                    file_put_contents($tFile, serialize($fields[$table]));
                }
            }
        }
        return $fields[$table];
    }

    /**
     * �������
     * @var array
     */
    public $join = array();

    /**
     * �����ѯ
     * @param string $table ������
     * @param string $where ��������
     * @param string $prefix INNER|LEFT|RIGHT ����ʽ
     * @return $this
     */
    function join($table, $where, $prefix = ''){
        $this->join[] = " $prefix JOIN `$table` ON $where ";
        return $this;
    }

    /**
     * ����ʼ
     */
    function begin(){
        $this->db || $this->db = self::instance($this->_config);
# �Ѿ��������˳�����
        $this->back();
        if(!$this->db->beginTransaction()){
            return false;
        }
        return $this->_begin_transaction = true;
    }

    /**
     * �����ύ
     */
    function commit(){
        if($this->_begin_transaction) {
            $this->_begin_transaction = false;
            $this->db->commit();
        }
        return true;
    }

    /**
     * ����ع�
     */
    function back(){
        if($this->_begin_transaction) {
            $this->_begin_transaction = false;
            $this->db->rollback();
        }
        return false;
    }

    /**
     * ����
     */
    function lock($sql = 'FOR UPDATE'){
        $this->_lock = $sql;
        return $this;
    }
}
