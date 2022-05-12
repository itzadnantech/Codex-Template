<?php
// error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

if (!function_exists('sendGridEmail')) {
    function sendGridEmail($data)
    {
        $info = readCredentials('email_sendgrid');
        if ($info) {
            extract($data);
            require APPPATH . '/libraries/sendgrid/vendor/autoload.php';
            $sendgrid = new \SendGrid($info->sendgrid_api_key);
            $email = new \SendGrid\Mail\Mail();
            $email->setFrom($info->smtp_fromemail,  $info->smtp_fromname);
            $email->setSubject($subject);
            $email->addTo($to, $to);
            $email->addContent("text/html", $msg);
            try {
                $response = $sendgrid->send($email);
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }
            $mesgId = $response->headers()[5];
            $mesgId = str_replace('X-Message-Id:', '', $mesgId);
            return trim($mesgId);
        }
    }
}
if (!function_exists('sendEmailSMTP')) {
    function sendEmailSMTP($data)
    {
        $info = readCredentials('email_custom');
        if ($info) {
            extract($data);
            $CI = &get_instance();
            $CI->load->library('email');
            if ($info->smtp_username && $info->smtp_password) {
                $config['protocol']   = "smtp";
                $config['smtp_host']  = $info->smtp_host;
                $config['smtp_port']  = $info->smtp_port;
                $config['smtp_user']  = $info->smtp_username;
                $config['smtp_pass']  = $info->smtp_password;
                $config['charset']    = "iso-8859-1";
                $config['mailtype']   = "html";
                $config["smtp_crypto"] = "ssl";
                $config['newline']    = "\r\n";
                // print_arr($config); die;
                $html_body = $msg;
                $CI->email->initialize($config);
                $CI->email->from($info->smtp_fromemail, $info->smtp_fromname);
                //('softeye17@gmail.com', 'FormBlaze Team');
                $CI->email->to($to);
                $CI->email->subject($subject);
                $CI->email->message($msg);
                if ($CI->email->send()) {
                    return true;
                } else {
                    return $CI->email->print_debugger();
                    // return true;
                }
            }
        }
    }
}
