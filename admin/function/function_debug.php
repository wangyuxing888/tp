<?php

function error_handler($errno, $errstr, $errfile, $errline) {
    //ob_clean();
    $zbe = TQBlogException::GetInstance();
    $zbe->ParseError($errno, $errstr, $errfile, $errline);
    $zbe->Display();
    die();
}

function exception_handler($exception) {
    //ob_clean();
    $zbe = TQBlogException::GetInstance();
    $zbe->ParseException($exception);
    $zbe->Display();
    die();
}

function shutdown_error_handler() {
    if ($error = error_get_last()) {
        //ob_clean();
        $zbe = TQBlogException::GetInstance();
        $zbe->ParseShutdown($error);
        $zbe->Display();
        die();
    }
}

class TQBlogException {

    static private $_zbe = null;
    public $type;
    public $message;
    public $file;
    public $line;

    static public function GetInstance() {
        if (!isset(self::$_zbe)) {
            self::$_zbe = new TQBlogException;
        }
        return self::$_zbe;
    }

    static public function SetErrorHook() {
        set_error_handler('error_handler');
        set_exception_handler('exception_handler');
        register_shutdown_function('shutdown_error_handler');
    }

    static public function ClearErrorHook() {
        set_error_handler(create_function('', ''));
        set_exception_handler(create_function('', ''));
        register_shutdown_function(create_function('', ''));
    }

    static public function Trace($s) {
        Logs($s);
    }

    function ParseError($type, $message, $file, $line) {
        $this->type = $type;
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
    }

    function ParseShutdown($error) {
        $this->type = $error['type'];
        $this->message = $error['message'];
        $this->file = $error['file'];
        $this->line = $error['line'];
    }

    function ParseException($exception) {

        $this->message = $exception->getMessage();
        $this->type = $exception->getCode();
        $this->file = $exception->getFile();
        $this->line = $exception->getLine();
        if (TQBlogPHP::$error_file !== null)
            $this->file = TQBlogPHP::$error_file;
        if (TQBlogPHP::$error_line !== null)
            $this->line = TQBlogPHP::$error_line;
    }

    function Display() {
        Http500();
        ob_end_clean();
        $tqb = TQBlogPHP::GetInstance();
        $tqb->StartGzip();
        require $GLOBALS['blogpath'] . 'admin/admin/error.php';
    }

    function get_code($file, $line) {
        $aFile = array_slice(file($file), max(0, $line - 5), 10, true);
        foreach ($aFile as &$sData) { //&$ = ByRef
            $sData = htmlspecialchars($sData);
        }
        unset($sData);
        return $aFile;
    }

}
