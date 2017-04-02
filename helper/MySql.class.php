<?php
/**
 * @author Honvid
 * @time: 2017/4/1  上午9:18
 */

require_once 'Log.class.php';

class MySQL {

    private static $instance__ = null;
    private $_server;
    private $_conn;
    private $_main_stmt;
    private $_db;
    private $_last_sql;
    private $_max_retry = 1;
    private $_retry_times = 0;
    private $_logfile;
    private $_transaction = false;
    private static $_phpversion = null;

    public function &instance (){
        if (is_null(self::$instance__)) {
            $class = __CLASS__;
            self::$instance__ = new $class();
        }
        return self::$instance__;
    }

    function __construct($db) {
        if(self::$_phpversion == null){
            self::$_phpversion = phpversion();
        }
        if(empty($db)){
            return false;
        }else{
            $this->_db = $db;
        }
        if(defined('MYSQL_ERR_LOG')){
            $this->_logfile = new Log(MYSQL_ERR_LOG);
        }else{
            $this->_logfile = new Log();
        }
        global $Mysql;
        if(empty($Mysql)){
            $this->_logfile->log('db set not found!',ERROR_LEV);
            return false;
        }
        if(!empty($Mysql['main']))$this->_server = $Mysql['main'];
        if(empty($this->_server)){
            $this->_logfile->log('db connection set is error!',ERROR_LEV);
            return false;
        }
    }

    function __destruct(){
        $this->close();
    }

    public function prepare($sql) {
        $this->_last_sql = $sql;
        $begintimes =microtime(true);
        $this->init();//1初始化写
        if( !isset($this->_conn) || null == $this->_conn )return;
        $this->_main_stmt = $this->_conn->prepare($sql);

        $costs = microtime(true)-$begintimes;
        if($costs > 2){//慢sql记日志
            $this->_logfile->log('prepare sql cost: '.$costs.' seconds.SQL:'.$sql, NOTICE_LEV);
        }
    }

    /**
     * 初始化连接
     * type 0 从库 1 主库
     */
    private function init() {
        if( isset($this->_conn) && $this->isConnectionOK($this->_conn) ) return ;
        //链接没有设置过
        $dbs = $this->_server;
        if(empty($dbs)){
            $this->_logfile->log('Database set is error!', ERROR_LEV);
            return false;
        }
        if(empty($dbs[$this->_db])){
            $this->_logfile->log("The database {$this->_db} is not found!", ERROR_LEV);
            return false;
        }
        $host = $dbs[$this->_db]['host'];
        if(!empty($dbs[$this->_db]['port'])){
            $port = $dbs[$this->_db]['port'];
        }else{
            $port = 3306;
        }
        if(empty($dbs[$this->_db]['dbname']))return false;
        $dbname = $dbs[$this->_db]['dbname'];

        if(!empty($dbs[$this->_db]['user']))
            $user = $dbs[$this->_db]['user'];
        else{
            $user = '';
        }

        if(!empty($dbs[$this->_db]['pass']))
            $pass = $dbs[$this->_db]['pass'];
        else
            $pass = '';

        if(!empty($dbs[$this->_db]['charset']))
            $charset = $dbs[$this->_db]['charset'];
        else{
            $charset = '';
        }

        $url = "mysql:host=$host;port=$port;dbname=$dbname";

        if($charset != ''){
            $url .= ';charset='.$charset;
        }

        if(!defined('PERSISTENT')){
            define('PERSISTENT','persistent');
        }

        if(!empty($dbs[$this->_db][PERSISTENT]))
            $persistent = true;
        else{
            $persistent = false;
        }

        $this->_conn = $this->get_connection($url,$user,$pass,$charset,$persistent);
    }

    private function isConnectionOK($dbh) {
        if( !isset($dbh) ) return false;

        if( !method_exists ( $dbh , 'prepare' )){
            $this->_logfile->log('The function ConnectionOK found connection failed.', WARNING_LEV);
            return false;
        }
        return true;
    }

    /**
     * 创建数据库连接
     */
    private function get_connection($url,$user='',$pass='',$charset='',$persistent=false) {
        $pdo = null;
        try{
            $attr = array();
            $attr[PDO::ATTR_TIMEOUT] = 5;
            $attr[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

            if(version_compare(self::$_phpversion,'5.3.6','<')){
                $php_version_flag = 1;
            }else{
                $php_version_flag = 2;
            }
            if($php_version_flag === 1){
                if(defined('PDO::MYSQL_ATTR_INIT_COMMAND')){
                    if(!empty($charset)){
                        $attr[PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES '$charset'";
                    }
                }
            }
            //使用长链接
            if($persistent == true){
                $attr[PDO::ATTR_PERSISTENT] = true;
            }
            $begintimes =microtime(true);
            $pdo = new PDO($url, $user, $pass, $attr);
            if($php_version_flag === 1){
                if(!defined('PDO::MYSQL_ATTR_INIT_COMMAND')){
                    if(!empty($charset) && $pdo){
                        $pdo->exec("SET NAMES '$charset'");
                    }
                }
            }
            $costs = microtime(true)-$begintimes;
            if($costs > 2){
                $this->_logfile->log('PDO connection cost: '.$costs.' seconds.db driver:'.$url, NOTICE_LEV);
            }
        }catch (PDOException $e) {
            $error  = "connection lost!\n";
            $error .= 'Caught exception: '.$e->getMessage()."\n";
            $error .= $url;
            $this->_logfile->log($error, WARNING_LEV); //进error_log
//			throw $e;
            // 重试机制
            sleep(1);
            if($this->_retry_times>=$this->_max_retry) throw $e;
            $this->_retry_times++;
            return $this->get_connection($url,$user,$pass,$charset,$persistent);
        }
        return $pdo;
    }

    public function bind($param,$var) {
        $data_type = null;
        if(is_numeric($var) && substr($var,0,1)!='0'){
            $data_type = PDO::PARAM_INT;
        }
        if(0 == $var){
            $data_type = PDO::PARAM_INT;
        }
        if( !isset( $this->_main_stmt)) return ;
        if($data_type === null){
            $this->_main_stmt->bindParam($param, $var);
        }else{
            $this->_main_stmt->bindParam($param, $var, $data_type);
        }
    }

    public function execute() {
        $begintimes =microtime(true);
        try{
            if( !isset($this->_conn) )$this->init();
            if( !isset($this->_main_stmt))return;
            $this->_main_stmt->execute();

        }catch (PDOException $e){
            $this->_logfile->log($e, WARNING_LEV);
            $this->_logfile->log($this->_last_sql, WARNING_LEV);
            throw $e;
        }
        $costs = microtime(true)-$begintimes;
        if($costs > 2){
            $this->_logfile->log('execute cost: '.$costs.' seconds.SQL:'.$this->_last_sql, WARNING_LEV);
        }
    }

    /**
     * style=0 以key为数组下标
     * style=1 以数字为下标
     * style=2 以上两者的集合
     */
    public function getall($sql='',$style=0) {
        if(!empty($sql)){
            $this->prepare($sql);
            $this->execute();
        }
        switch ($style){
            case 0:$fetch_style=PDO::FETCH_NAMED;break;
            case 1:$fetch_style=PDO::FETCH_NUM;break;
            case 2:$fetch_style=PDO::FETCH_BOTH;break;
            default:$fetch_style=PDO::FETCH_NAMED;
        }
        if(!isset($this->_main_stmt))return null;
        return $this->_main_stmt->fetchAll($fetch_style);
    }

    public function getsingle($sql='',$style=0) {
        if(!empty($sql)){
            $this->prepare($sql);
            $this->execute();
        }
        switch ($style){
            case 0:$fetch_style=PDO::FETCH_NAMED;break;
            case 1:$fetch_style=PDO::FETCH_NUM;break;
            case 2:$fetch_style=PDO::FETCH_BOTH;break;
            default:$fetch_style=PDO::FETCH_NAMED;
        }

        if(!isset($this->_main_stmt))return null;
        return $this->_main_stmt->fetch($fetch_style);
    }

    public function exec($sql){
        $this->_last_sql = $sql;
        $begintimes =microtime(true);
        try{
            if(!isset($this->_conn))$this->init();
            $this->prepare($sql);
            $this->execute();
        }catch (PDOException $e){
            $this->_logfile->log($e, WARNING_LEV);
            throw $e;//抛出异常
        }
        $costs = microtime(true)-$begintimes;
        if($costs > 2){//慢sql记日志
            $this->_logfile->log('prepare sql cost: '.$costs.' seconds.SQL:'.$sql,NOTICE_LEV);
        }
    }

    public function lastInsertID() {
        return $this->_conn->lastInsertId();
    }

    public function getrowcount() {
        return $this->_main_stmt->rowCount();
    }

    public function close() {
        $this->_conn = null;
        $this->_main_stmt = null;
    }

    /**
     * 以下为事务相关
     * 开启事务
     */
    public function begintrans() {
        $this->_transaction = true;
        $this->init();//1初始化写
        if( !isset($this->_conn) || null == $this->_conn )return;
        $this->_conn->beginTransaction();
    }
    public function commit(){
        if( !isset($this->_conn) || null == $this->_conn )return;
        $this->_conn->commit();
    }
    public function rollback(){
        if( !isset($this->_conn) || null == $this->_conn )return;
        $this->_conn->rollBack();
    }
}
?>
