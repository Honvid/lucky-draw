<?php
/**
 * @author Honvid
 * @time: 2017/4/1  上午9:19
 */
define('ERROR_LEV','ERR:');
define('WARNING_LEV','WARNING:');
define('DEBUG_LEV','DEBUG:');
define('NOTICE_LEV','NOTICE:');
class Log{
    private $logfile;
    function __construct($file=""){
        if(empty($file)){
            $date = date("Y-m-d");
            $this->logfile = "./data/logs/php_" . $date . ".log";
        }
        else{
            $this->logfile = $file;
        }
    }
    function log($msg, $level = ERROR_LEV){
        $env_msg = "";
        $data_time = date("[Y-m-d H:i:s]");
        if($level == DEBUG_LEV || $level == ERROR_LEV){
            $env_msg = $this->get_debuginfo();
        }
        $log_msg = $data_time . $level . $env_msg . " >>" . $msg . "\n";
        error_log($log_msg, 3, $this->logfile);
    }
    private function get_debuginfo(){
        $debug = debug_backtrace();
        $trace = "";
        foreach($debug as $key => $val){
            extract($val);
            $trace.="FILE=$file, LINE=$line, FUNC=$function\n";
        }
        return $trace;
    }

}
?>