<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use CodeIgniter\Email\Email;

class Services extends BaseService
{
    public static function sendEmail($email, $asunto, $cuerpo) {
        $obj = \Config\Services::email();
        $obj->setTo($email);
        $obj->setSubject($asunto);
        $obj->setMessage($cuerpo);
    
        return $obj->send();
    }
}