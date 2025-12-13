<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{



    public string $protocol = 'smtp';
public string $SMTPHost = 'mail.smtp2go.com';
public string $SMTPUser = 'your_smtp2go_username';
public string $SMTPPass = 'your_smtp2go_password';
public int $SMTPPort = 2525; // OR 587
public string $SMTPCrypto = 'tls';
public string $mailType = 'html';

}
