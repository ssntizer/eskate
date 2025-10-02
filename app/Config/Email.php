<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    // --- Configuración de Remitente ---
    public string $fromEmail  = 'eskatevz@gmail.com'; // Correo de Gmail
    public string $fromName   = 'E-Skate'; // Nombre que aparecerá en los correos
    public string $recipients = '';

    // --- Protocolo General ---
    public string $userAgent = 'CodeIgniter';
    public string $protocol = 'smtp';
    public string $mailPath = '/usr/sbin/sendmail';

    // --- Configuración SMTP 
    public string $SMTPHost = 'smtp.gmail.com';
    public string $SMTPUser = 'eskatevz@gmail.com'; // Tu correo de Gmail
    
    public string $SMTPPass = 'vwzq kzkh wqkg peoi'; 
    
    // Configuración recomendada: Puerto 587 con cifrado TLS
    public int $SMTPPort = 587; 
    public string $SMTPCrypto = 'tls'; 

    public int $SMTPTimeout = 10;
    public bool $SMTPKeepAlive = false;

    // --- Formato de Correo ---
    public bool $wordWrap = true;
    public int $wrapChars = 76;
    public string $mailType = 'html'; 
    public string $charset = 'UTF-8';

    // --- Varios ---
    public bool $validate = false;
    public int $priority = 3;
    public string $CRLF = "\r\n";
    public string $newline = "\r\n";
    public bool $BCCBatchMode = false;
    public int $BCCBatchSize = 200;
    public bool $DSN = false;
}
