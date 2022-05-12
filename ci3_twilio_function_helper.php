<?php
use Twilio\Rest\Client;
use Twilio\Jwt\ClientToken;
use Twilio\TwiML;
if (!function_exists('twilioClient')) {
    function twilioClient($accountSid, $authToken)
    {
        require_once APPPATH.'/libraries/Twilio/Twilio/autoload.php';
        try {
            $client = new Client($accountSid, $authToken);
        } catch (Exception $e) {
            $client = $e->getMessage();
        }
        return $client;
    }
}
if (!function_exists('twilioXml')) {
    function twilioXml()
    {
        require_once APPPATH.'/libraries/Twilio/Twilio/autoload.php';
        return new TwiML;
    }
}
if (!function_exists('twilioCreateSubAccount')) {
    function twilioCreateSubAccount($accountSid, $authToken, $friendlyName)
    {
        $client = twilioClient($accountSid, $authToken);
        try {
            $subaccount = $client->api->v2010->accounts
            ->create(array("friendlyName" => $friendlyName));
        } catch (Exception $e) {
            $subaccount = $e->getMessage();
        }
        return $subaccount;
    }
}
if (!function_exists('twilioPurchaseNumber')) {
    function twilioAvailableNumber($accountSid, $authToken, $areaCode)
    {
        $client = twilioClient($accountSid, $authToken);
        $numbers = $client->availablePhoneNumbers('US')->local->read(array("areaCode" => $areaCode));
        return $numbers;
    }
}
if (!function_exists('twilioPurchaseNumber')) {
    function twilioPurchaseNumber($accountSid, $authToken, $phoneNumber)
    {
        $client = twilioClient($accountSid, $authToken);
        try {
            // Purchase the first number on the list.
            $number = $client->incomingPhoneNumbers
            ->create(
                array(
                    "phoneNumber" => $phoneNumber
                )
            );
        } catch (Exception $e) {
            $number = $e->getMessage();
        }
        return $number;
    }
}
if (!function_exists('synchronizeTwilioNumbers')) {
    function synchronizeTwilioNumbers($accountSid, $authToken, $memberId)
    {
        $client = twilioClient($accountSid, $authToken);
        try {
            $phoneNumbers = $client->incomingPhoneNumbers->read();
            foreach ($phoneNumbers as $rec) {
                $number    = $rec->phoneNumber;
                $phoneSid  = $rec->sid;
                sleep(1);
                $twilioArr = array(
                    'memberId'  => $memberId,
                    'number'  => $number,
                    'phoneSid'  => $phoneSid,
                    'dated'   => time()
                );
                $exist = getByWhere('user_numbers', 'twilioId', array('number' => $number));
                if (empty($exist)) {
                    addNew('user_numbers', $twilioArr);
                }
            }
            $return = true;
        } catch (Exception $e) {
            $return = $e->getMessage();
        }
        return $return;
    }
}
if (!function_exists('twilioUpdateUrls')) {
    function twilioUpdateUrls($accountSid, $authToken, $twilioId)
    {
        $twilio = getByWhere('user_numbers', 'phoneSid', array('twilioId' => $twilioId));
        $phoneSid = $twilio[0]->phoneSid;
        $client = twilioClient($accountSid, $authToken);
        $SmsUrl = base_url() . 'webhook/twilio/incomingSms';
        $VoiceUrl = base_url() . 'webhook/twilio/outgoingCall';
        $StatusCallbackUrl = base_url() . "webhook/twilio/callStatusCallback";
        $VoiceFallbackUrl = base_url() . "webhook/twilio/fallback";
        $urlArr = array(
            'SmsUrl'          => $SmsUrl,
            'VoiceUrl'        => $VoiceUrl,
            'VoiceFallbackUrl' => $VoiceFallbackUrl,
            'StatusCallback' => $StatusCallbackUrl
        );
        $return = $client->incomingPhoneNumbers($phoneSid)->update($urlArr);
        $updateArr = array('SmsUrl' => $SmsUrl, 'VoiceUrl' => $VoiceUrl);
        updateByWhere('user_numbers', $updateArr, array('twilioId' => $twilioId));
        return $return;
    }
}
if (!function_exists('twilioSubaccountAction')) {
    function twilioSubaccountAction($accountSid, $authToken, $subAccSid, $status)
    {
        // suspended --- closed --- active
        $client = twilioClient($accountSid, $authToken);
        $subAccount = $client->api->v2010->accounts($subAccSid)
        ->update(array("status" => $status));

        return $subAccount->friendlyName;
    }
}
if (!function_exists('twilioSendSMS')) {
    function twilioSendSMS($memberId, $accountSid, $authToken, $fromNumber, $toNumber, $msg)
    {
        $statusCallback = base_url() . 'webhook/twilio/smsStatusCallback?memberId=' . $memberId;
        $client = twilioClient($accountSid, $authToken);
        $message = $client->messages
        ->create(
                $toNumber, // to
                array(
                    "body" => $msg,
                    "from" => $fromNumber,
                    "statusCallback" => $statusCallback
                )
            );
        return $message->sid;
    }
}
if (!function_exists('twilioOutboundCall')) {
    function twilioOutboundCall($memberId, $accountSid, $authToken, $fromNumber, $toNumber)
    {
        $url = base_url() . 'webhook/twilio/voicemail?memberId=' . $memberId;
        $client = twilioClient($accountSid, $authToken);
        $call = $client->calls
        ->create(
                $toNumber, // to
                $fromNumber, // from
                array("url" => $url)
            );
        return $call->sid;
    }
}
if (!function_exists('twilioSetupToken')) {
    function twilioSetupToken($accountSid, $authToken, $userId)
    {
        $appSid = twilioCreateApp($accountSid, $authToken);
        require_once APPPATH.'/libraries/Twilio/Twilio/autoload.php';
        $capability = new ClientToken($accountSid, $authToken);
        $capability->allowClientOutgoing($appSid);
        $capability->allowClientIncoming($userId);
        $token = $capability->generateToken();
        return $token;
    }
}
if (!function_exists('twilioCreateApp')) {
    function twilioCreateApp($accountSid, $authToken, $name = 'Dailer')
    {
        $SmsUrl = base_url() . 'webhook/twilio/incomingSms';
        $VoiceUrl = base_url() . 'webhook/twilio/outgoingCall';
        $VoiceFallbackUrl = base_url() . 'webhook/twilio/callStatusfallback';
        // $VoiceFallbackUrl='http://zamsol.com/HP_Twilio/fallback.php';
        $statusCallBack = base_url() . 'webhook/twilio/callStatusCallback';
        $client = twilioClient($accountSid, $authToken);
        $chkSID = $client->applications
        ->read(array("friendlyName" => $name));
        $appSid = $chkSID[0]->sid;
        if (empty($appSid)) {
            $application = $client->applications
            ->create(
                    "Dailer", // friendlyName
                    array(
                        "voiceMethod" => "GET",
                        "smsUrl" => $SmsUrl,
                        "voiceUrl" => $VoiceUrl,
                        'VoiceFallbackUrl' => $VoiceFallbackUrl,
                        'statusCallback' => $statusCallBack
                    )
                );
            $appSid = $application->sid;
        }
        return $appSid;
    }
}
if (!function_exists('twilioDeleteRecording')) {
    function twilioDeleteRecording($twilioArr)
    {
        require_once 'twilio/twilio2/vendor/autoload.php';
        $CI = &get_instance();
        $CI->load->model('twilio/incoming_call_model');
        extract($twilioArr);
        $twilio_info = $CI->incoming_call_model->twilio_record($teamId);
        $sid   = $twilio_info->twilio_accountId;
        $token = $twilio_info->twilio_authtoken;
        $RecAccInfo = explode('/', explode('Accounts/', $RecordingUrl)[1])[0];
        if ($sid != $RecAccInfo) {
            return 'Not exist';
        }
        $client = new Client($sid, $token);
        $recordingsID = @end(explode('/', $RecordingUrl));
        return $client->recordings($recordingsID)->delete();
    }
}
