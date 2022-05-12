<?php
require_once APPPATH.'/libraries/Plivo/vendor/autoload.php';
use Plivo\RestClient;
use Plivo\Exceptions\PlivoRestException;

if (!function_exists('plivoSuperClient')) {
    function plivoSuperClient()
    {
        $authId = 'MAYZI2NJQ2OGZHMDGXMD';
        $authToken = 'YTQyMTAzMGVjNjI5NzM3ZTA5MWZlZmMzYjhkN2E1';
        return new RestClient($authId, $authToken);
        // $info = readCredentials('plivo'); 
        // return new RestClient($info->authId, $info->authToken);
    }
}
if (!function_exists('plivoClient')) {
    function plivoClient($authId, $authToken)
    {
        return new RestClient($authId, $authToken);
    }
}
if (!function_exists('plivoSearchNumbers')) {
    function plivoSearchNumbers($pattern = '')
    {
        $psc = plivoSuperClient();
        $type = 'any';
        $services = 'voice,sms';
        $params = array('type' => $type, 'services' => $services, 'limit' => '2', 'offset' => '0');
        $result = array();
        try {
            $response = $psc->phonenumbers->list('US', $params);
            foreach ($response->resources as $res) {
                $result[] = $res->properties;
            }
        } catch (PlivoRestException $ex) {
            $result[] = $ex;
        }
        return $result;
    }
}
if (!function_exists('plivoUpdateNumbers')) {
    function plivoUpdateNumbers($arr)
    {
        extract($arr);
        $psc = plivoSuperClient();
        $params=array();
        if ($appId) {
            $params['app_id']=$appId;
        }
        if ($subaccount) {
            $params['subaccount']=$subaccount;
        }
        if ($alias) {
            $params['alias']=$alias;
        }
        $result = array();
        try {
            $response = $psc->numbers->update(
                $number,
                $params
            );
            $result=$response;
        } catch (PlivoRestException $ex) {
            $result[] = $ex;
        }
        return $result;
    }
}
if (!function_exists('plivoCreateSubAccount')) {
    function plivoCreateSubAccount($accountName)
    {
        $psc = plivoSuperClient();
        $result = array('status' => 'error');
        try {
            $response = $psc->subaccounts->create($accountName, True);
            if ($response->_message == "created") {
                $result['status'] = 'success';
                $result['authId'] = $response->authId;
                $result['authToken'] = $response->authToken;
            } else {
                $result['errorInfo'] = 'Account Already Exist.';
            }
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}
if (!function_exists('plivoValidateSubAccount')) {
    function plivoValidateSubAccount($subAuthId)
    {
        $psc = plivoSuperClient();
        $result = array('status' => 'error');
        try {
            $response = $psc->subaccounts->get($subAuthId);
            if ($response->authId) {
                $result['status'] = 'success';
                $result['subAccountInfo'] = $response->properties;
            } else {
                $result['errorInfo'] = "Account Doesn't Exist.";
            }
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}


if (!function_exists('plivoUpdateSubAccount')) {
    function plivoUpdateSubAccount($subAuthId, $subAccountName, $status)
    {
        $psc = plivoSuperClient();
        $result = array('status' => 'error');
        if ($status == 'enabled') {
            $action = true;
        } else {
            $action = false;
        }
        try {
            $response = $psc->subaccounts->update($subAuthId, $subAccountName, $action);
            if ($response->_message == "changed") {
                $result['status'] = 'success';
            } else {
                $result['errorInfo'] = "Account Updation Error.";
            }
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}


if (!function_exists('plivoDeleteSubAccount')) {
    function plivoDeleteSubAccount($subAuthId)
    {
        $psc = plivoSuperClient();
        $result = array('status' => 'error');
        try {
            $response = $psc->subaccounts->delete($subAuthId, true);
            if ($response->message == 204) {
                $result['status'] = 'success';
            } else {
                $result['errorInfo'] = "Account Deletion Error.";
            }
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}


if (!function_exists('plivoListSubAccount')) {
    function plivoListSubAccount()
    {
        $psc = plivoSuperClient();
        $result = array();
        try {
            $response = $psc->subaccounts->list();
            foreach ($response->resources as $res) {
                $result[] = $res->properties;
            }
        } catch (PlivoRestException $ex) {
            $result[] = $ex;
        }
        return $result;
    }
}

if (!function_exists('plivoCreateAppEndpoint')) {
    function plivoCreateAppEndpoint($memberId)
    {
        $res=userInfo($memberId);
        $accIdentity=$res->userId.'_'.$res->email;
        $authId=$res->accountSid;
        $authToken=$res->authToken;
        $updateUserArr=array();
        $appRes=plivoCreateApplication(array('appName'=>$accIdentity,'subaccount'=>$authId));
        if ($appRes['status']=='success') {
            $updateUserArr['appId']=$appRes['appId'];
            $resEnd=plivoCreateEndPoint($authId, $authToken,$accIdentity,$appRes['appId']);
            if ($resEnd['status']=='success') {
                $updateUserArr['endpointId']=$resEnd['endpointId'];
                $updateUserArr['endpoint_username']=$resEnd['username'];
                $updateUserArr['endpoint_password']=$resEnd['password'];
                updateByWhere('users', $updateUserArr, array('userId' => $memberId));
            }
        }
    }
}
if (!function_exists('plivoCreateApplication')) {
    function plivoCreateApplication($arr)
    {
        // appName, subaccount,$permission
        $subaccount='';
        extract($arr);
        $answer_url=base_url().'webhook/plivo/answer_url';
        $hangup_url=base_url().'webhook/plivo/hangup_url';
        $message_url=base_url().'webhook/plivo/incomingSms';
        $fallback_answer_url=base_url().'webhook/plivo/fallbackUrl';
        $params=array(
            'answer_url'=>$answer_url,
            'hangup_url'=>$hangup_url,
            'message_url'=>$message_url,
            'fallback_answer_url'=>$fallback_answer_url,
        );
        if ($subaccount) {
            $params['subaccount']=$subaccount;
        }
        $psc = plivoSuperClient();
        $result = array('status' => 'error');
        try {
            $response = $psc->applications->create($appName, $params);
            if ($response->_message == "created") {
                $result['status'] = 'success';
                $result['appId'] = $response->appId;
            } else {
                $result['errorInfo'] = 'Application Name Already Exist and updated.';
                try {
                    $appRes = $psc->applications->list();
                    foreach ($appRes->resources as $key => $rec) {
                        if ($appName==$rec->properties['appName']) {
                            $appId=$rec->properties['appId'];
                            break;
                        }
                    }
                    try {
                        $appResUpdate = $psc->applications->update($appId,$params);
                        if ($appResUpdate->_message == "changed") {
                            $result['status'] = 'success';
                            $result['appId'] = $appId;
                        } else {
                            $result['errorInfo'] = 'Application Name Already Exist.And unable to update.';
                        }
                    } catch (PlivoRestException $ex) {
                        $result['errorInfo'] = $ex;
                    }
                } catch (PlivoRestException $ex) {
                    $result['errorInfo'] = $ex;
                }
            }
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}


if (!function_exists('plivoValidateApplication')) {
    function plivoValidateApplication($appId)
    {
        $psc = plivoSuperClient();
        $result = array('status' => 'error');
        try {
            $response = $psc->applications->get($appId);
            // print_arr($response);die;
            if ($response->appId) {
                $result['status'] = 'success';
                $result['appInfo'] = $response->properties;
            } else {
                $result['errorInfo'] = 'Application Existed.';
            }
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}


if (!function_exists('plivoUpdateApplication')) {
    function plivoUpdateApplication($appId, $params)
    {
        $psc = plivoSuperClient();
        $result = array('status' => 'error');
        try {
            $response = $psc->applications->update($appId, $params);
            if ($response->_message == "changed") {
                $result['status'] = 'success';
            } else {
                $result['errorInfo'] = 'Application Updation Error.';
            }
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}

if (!function_exists('plivoDeleteApplication')) {
    function plivoDeleteApplication($appId)
    {
        $psc = plivoSuperClient();
        $result = array('status' => 'error');
        try {
            $response = $psc->applications->delete($appId);
            // print_arr($response);die;
            if ($response->message == 204) {
                $result['status'] = 'success';
            } else {
                $result['errorInfo'] = 'Application Deletion Error.';
            }
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}

if (!function_exists('plivoListApplication')) {
    function plivoListApplication()
    {
        $psc = plivoSuperClient();
        $result = array();
        try {
            $response = $psc->applications->list();
            foreach ($response->resources as $res) {
                $result[] = $res->properties;
            }
        } catch (PlivoRestException $ex) {
            $result[] = $ex;
        }
        return $result;
    }
}


if (!function_exists('plivoCreateEndPoint')) {
    function plivoCreateEndPoint($authId, $authToken,$alias,$appId='')
    {   
        $userName=randomKey();
        $password=randomKey();
        $result = array('status' => 'error');
        $pc = plivoClient($authId, $authToken);
        try {
            $response = $pc->endpoints->list(
            );
            $chkEndPoint=$response->meta();
            if ($chkEndPoint['total_count']==0) {
                createEndPoint:
                try {
                    $response = $pc->endpoints->create($userName, $password, $alias, $appId);
                    if ($response->_message == "created") {
                        $result['status'] = 'success';
                        $result['endpointId'] = $response->endpointId;
                        $result['username'] = $response->username;
                        $result['password'] = $password;
                        $result['alias'] = $response->alias;
                    } else {
                        $result['errorInfo'] = 'EndPoint Already Exist.';
                    }
                } catch (PlivoRestException $ex) {
                    $result['errorInfo'] = $ex;
                }
            }else{
                $matchEndpoint='No';
                foreach ($response->resources as $key => $recInfo) {
                    $rec=$recInfo->properties;
                    if ($rec['alias']==$alias) {
                        $endpointId=$rec['endpointId'];
                        plivoUpdateEndPoint(array('authId'=>$authId,'authToken'=>$authToken,'endpointId'=>$endpointId,'password'=>$password, 'appId'=>$appId));
                        $result['status'] = 'success';
                        $result['endpointId'] = $endpointId;
                        $result['username'] = $rec['username'];
                        $result['password'] = $password;
                        $result['alias'] = $rec['alias'];
                        $matchEndpoint='Yes';
                    }
                }
                if ($matchEndpoint=='No') {
                    goto createEndPoint;
                }
            }
        }
        catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}
if (!function_exists('plivoUpdateEndPoint')) {
    function plivoUpdateEndPoint($arr)
    {
        $password='';
        $alias='';
        $appId='';
        extract($arr);
        $params=array();
        if ($password) {
            $params['password']=$password;
        }
        if ($alias) {
            $params['alias']=$alias;
        }
        if ($appId) {
            $params['app_id']=$appId;
        }
        $pc = plivoClient($authId, $authToken);
        $result = array('status' => 'error');
        try {
            $response = $pc->endpoints->update($endpointId, $params);
            if ($response->_message == "changed") {
                $result['status'] = 'success';
            } else {
                $result['errorInfo'] = 'EndPoint Updation Error.';
            }
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}
if (!function_exists('plivoValidateEndPoint')) {
    function plivoValidateEndPoint($endpointId)
    {
        $psc = plivoSuperClient();
        $result = array('status' => 'error');
        try {
            $response = $psc->endpoints->get($endpointId);
            // print_arr($response);die;
            if ($response->endpointId) {
                $result['status'] = 'success';
                $result['endpointsInfo'] = $response->properties;
            } else {
                $result['errorInfo'] = 'EndPoint Existed.';
            }
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}

if (!function_exists('plivoDeleteEndPoint')) {
    function plivoDeleteEndPoint($endpointId)
    {
        $psc = plivoSuperClient();
        $result = array('status' => 'error');
        try {
            $response = $psc->endpoints->delete($endpointId);
            $result['status'] = 'success';
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}

if (!function_exists('plivoListEndPoint')) {
    function plivoListEndPoint()
    {
        $psc = plivoSuperClient();
        $result = array();
        try {
            $response = $psc->endpoints->list();
            foreach ($response->resources as $res) {
                $result[] = $res->properties;
            }
        } catch (PlivoRestException $ex) {
            $result[] = $ex;
        }
        return $result;
    }
}

if (!function_exists('synchronizePlivoNumbers')) {
    function synchronizePlivoNumbers($authId, $authToken, $memberId)
    {
     /* print_arr(array($authId, $authToken, $memberId));die; */
     $pc = plivoClient($authId, $authToken);
     try {
        $phoneNumbers = $pc->numbers->list(
            [
                'limit' => 20,
                'offset' => 0
            ]
        );
        $numbersInfo=$phoneNumbers->meta();
        $appId=userInfo($memberId)->appId;
        foreach ($phoneNumbers->resources as $recInfo) {
            $rec=$recInfo->properties;
            $number=$rec['number'];
            plivoUpdateNumbers(array('number'=>$number,'appId'=>$appId,'alias'=>'Update At '.date('Y-m-d H:i:s')));
            $number = phoneFormat($number);
            $twilioArr = array(
                'memberId'  => $memberId,
                'number'  => $number,
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

if (!function_exists('plivoCreateCall')) {
    function plivoCreateCall($arr)
    {
        // $from, $to, $answer_url,$machine_detection,$voicemailUrl
        $voicemailUrl = '';
        extract($arr);
        if ($answeringMachine=='Yes') {
            $machine_detection='true';
        }else{
            $machine_detection='false';
        }
        $pc = plivoClient($authId, $authToken);
        $result = array('status' => 'error');
        try {
            $response = $pc->calls->create(
                $from, 
                [$to], 
                $answer_url, 
                'GET',
                [
                    'machine_detection' => $machine_detection,
                    'machine_detection_time' => "5000",
                    'machine_detection_url' => $voicemailUrl, 
                    'machine_detection_method' => "GET" 
                ]
            );
            $result = array('status' => 'error' , 'response' => $response);
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}
if (!function_exists('plivoTransferCall')) {
    function plivoTransferCall($arr)
    {
        // authId,authToken,CallUUID,legs,aleg_url
        extract($arr);
        $pc = plivoClient($authId, $authToken);
        $result = array('status' => 'error');
        $params=array();
        if ($legs) {
            $params['legs']=$legs;
            $params['aleg_url']=$aleg_url;
        }
        try {
            $response = $client->calls->transfer($CallUUID, $params);
            print_arr($response);
            die;
            if ($response->_message == "created") {
                $result['status'] = 'success';
                $result['username'] = $response->username;
                $result['alias'] = $response->alias;
                $result['endpointId'] = $response->endpointId;
            } else {
                $result['errorInfo'] = 'Call Connecting Error.';
            }
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}

if (!function_exists('plivoSendSMS')) {
    function plivoSendSMS($memberId, $authId, $authToken, $from_number, $to_number, $text)
    {
        $pc = plivoClient($authId, $authToken);
        $url = base_url().'webhook/plivo/smsStatusCallback?memberId=' . $memberId;
        $result = array('status' => 'error');
        $powerpack_uuid = null;
        if (strlen($from_number) > 14) {
            $powerpack_uuid = $from_number;
            $from_number = null;
        }
        if (strlen($to_number) == 10) $to_number = '+1' . $to_number;
        try {
            $response = $pc->messages->create(
                $from_number,
                [$to_number],
                $text,
                ["url" => $url],
                $powerpack_uuid
            );
            if ($response->getmessageUuid(0)) {
                $result['status'] = 'success';
                $result['msgId'] = $response->getmessageUuid(0);
            } else {
                $result['errorInfo'] = 'Sending Failed.';
            }
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}

if (!function_exists('plivoSingleSMS')) {
    function plivoFetchSingleSMS($authId, $authToken, $msgId)
    {
        $result = array('status' => 'error');
        $pc = plivoClient($authId, $authToken);
        try {
            $response = $pc->messages->get($msgId);
            if ($response) {
                $result['status'] = 'success';
                $result['msgInfo'] = $response->properties;
            } else {
                $result['errorInfo'] = 'Message Not Found.';
            }
        } catch (PlivoRestException $ex) {
            $result['errorInfo'] = $ex;
        }
        return $result;
    }
}

if (!function_exists('plivoAllSMS')) {
    function plivoFetchAllSMS($subAuthId)
    {
        $result = array();
        $psc = plivoSuperClient();
        try {
            $response = $psc->messages->list(
                [
                    'subaccount' => $subAuthId
                ]
            );
            foreach ($response->resources as $res) {
                $result[] = $res->properties;
            }
        } catch (PlivoRestException $ex) {
            $result[] = $ex;
        }
        return $result;
    }
}
