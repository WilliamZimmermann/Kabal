<?php
namespace Application\Services;

use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use User\Services\UserService;

class SystemLog
{
    public $logger;
    public $userId;
   public function __construct(){
        //LOGGER
        $this->logger = new Logger();
        $userService = new UserService();
        $userData = $userService->getUserSession();
        $this->userId = $userData["idUser"];
    }
    
    /**
     * ADD A LOG
     * -- EMERG   = 0;  // Emergency: system is unusable
     * -- ALERT   = 1;  // Alert: action must be taken immediately
     * -- CRIT    = 2;  // Critical: critical conditions
     * -- ERR     = 3;  // Error: error conditions
     * -- WARN    = 4;  // Warning: warning conditions
     * -- NOTICE  = 5;  // Notice: normal but significant condition
     * -- INFO    = 6;  // Informational: informational messages
     * -- DEBUG   = 7;  // Debug: debug messages
     * @param boolean $system (is a system message or a action made from a user?)
     * @param string $priority
     * @param string $message
     */
    public function addLog($system=false, $message, $priority){
        if($system==true){
            $this->userId = 0;
        }
        $writer = new Stream('logs/user_'.$this->userId.'.log');
        $this->logger->addWriter($writer);
        if(is_array($message)){
            $message = "USER ID: ".$this->userId.". MESSAGE: ".$message["message"];
        }else{
            $message = "USER ID: ".$this->userId.". MESSAGE: ".$message;
        }
        $this->logger->log($priority, $message);
    }
}

