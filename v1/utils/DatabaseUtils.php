<?php

namespace Api\Utils;

use PDO;
use PDOException;
use Exception;
use Api\Handlers\ResponseHandler;

final class DatabaseUtils {
    private $_db;
    
    private $_hostname;
    private $_dbname;
    private $_charset;
    private $_port;
    private $_username;
    private $_password;
    
    public function __construct()
    {
        $config = require_once(__DIR__ . '/../config/database.php');
        $this->_hostname = $config['hostname'];
        $this->_dbname = $config['dbname'];
        $this->_charset = $config['charset'];
        $this->_port = $config['port'];
        $this->_username = $config['username'];
        $this->_password = $config['password'];
        
        $dsn = "mysql:host={$this->_hostname};port={$this->_port};dbname={$this->_dbname};charset={$this->_charset}";
        
        try {
            $this->_db = new PDO($dsn, $this->_username, $this->_password);
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        
        catch (PDOException $e) {
            $responseHandler = new ResponseHandler('500');
        }
        
        catch (Exception $e) {
            $responseHandler = new ResponseHandler('500');
        }
    }
    
    public function connect()
    {
        return $this->_db;
    }
}