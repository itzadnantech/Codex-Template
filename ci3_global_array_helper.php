<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING));

if (!function_exists('stateNameOfCountry')) {
    function stateNameOfCountry($country)
    {
        if ($country == 'US') {
            return array("S" => "*Select", "AL" => "Alabama", "AK" => "Alaska", "AZ" => "Arizona",  "AR" => "Arkansas",  "CA" => "California", "CO" => "Colorado",  "DC" => "Columbia",  "CT" => "Connecticut",  "DE" => "Delaware",  "FL" => "Florida", "GA" => "Georgia", "ID" => "Idaho", "IL" => "Illinois", "IN"  => "Indiana",  "IA" => "Iowa",  "KS" => "Kansas", "KY" => "Kentucky",  "LA" => "Louisiana", "ME" => "Maine", "MD" => "Maryland", "MA" => "Massachusetts", "MI" => "Michigan",  "MN" => "Minnesota", "MS" => "Mississippi", "MO" => "Missouri", "MT" => "Montana",  "NE" => "Nebraska", "NV" => "Nevada",  "NH" => "New Hampshire", "NJ" => "New Jersey", "NM" => "New Mexico", "NY" => "New York", "NC" => "North Carolina",  "ND" => "North Dakota", "OH" => "Ohio", "OK" => "Oklahoma", "OR" => "Oregon", "PA" => "Pennsylvania",  "RI" => "Rhode Island",  "SC" => "South Carolina", "SD" => "South Dakota", "TN" => "Tennessee",  "TX" => "Texas", "UT"  => "Utah",  "VT" => "Vermont", "VA" => "Virginia", "WA" => "Washington", "WV" => "West Virginia", "WI" => "Wisconsin", "WY" => "Wyoming");
        }
    }
}
if (!function_exists('allTablesNameDb')) {
    function allTablesNameDb()
    {
        $arr = array(
            'area_codesTableFieldsDb' => 'area_codes',
            'auto_responderTableFieldsDb' => 'auto_responder',
            'broadcastTableFieldsDb' => 'broadcast',
            'usersTableFieldsDb' => 'users',
            'lead_formsTableFieldsDb' => 'lead_forms',
            'voice_compaignsTableFieldsDb' => 'voice_compaigns',
            'voice_templatesTableFieldsDb' => 'voice_templates',
            'widget_clickToCallTableFieldsDb' => 'widget_click_to_call',
            'user_numbersTableFieldsDb' => 'user_numbers',
            'timezonesTableFieldsDb' => 'timezones',
            'sms_templatesTableFieldsDb' => 'sms_templates',
            'sms_compaignsTableFieldsDb' => 'sms_compaigns',
            'lead_groupsTableFieldsDb' => 'lead_groups',
            'debugTableFieldsDb' => 'debug',
            'email_templatesTableFieldsDb' => 'email_templates',
            'email_logsTableFieldsDb' => 'email_logs',
            'dncListTableFieldsDb' => 'dncList',
            'leadsTableFieldsDb' => 'leads',
            'user_logsTableFieldsDb' => 'user_logs'
        );
        return $arr;
    }
}

if (!function_exists('dncListTableFieldsDb')) {
    function dncListTableFieldsDb()
    {
        $arr = array(
            'dncId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'phoneNumber' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
            'dated' => array(
                'type' => 'VARCHAR',
                'constraint' => 20
            )
        );
        return $arr;
    }
}
if (!function_exists('email_logsTableFieldsDb')) {
    function email_logsTableFieldsDb()
    {
        $arr = array(
            'emailLogId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'leadId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'subject' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'message' => array(
                'type' => 'longtext',
            ),
            'direction' => array(
                'type' => 'ENUM("Inbound","Outbound")',
                'default' => 'Inbound',
                'null' => 'NOT NULL'
            ),
            'readStatus' => array(
                'type' => 'ENUM("Yes","No")',
                'default' => 'No',
                'null' => 'NOT NULL'
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => 20
            )
        );
        return $arr;
    }
}
if (!function_exists('area_codesTableFieldsDb')) {
    function area_codesTableFieldsDb()
    {
        $arr = array(
            'Id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'area_code' => array(
                'type' => 'VARCHAR',
                'constraint' => 11,
                'unique' => TRUE,
            ),
            'state' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'state_code' => array(
                'type' => 'VARCHAR',
                'constraint' => '11',
            ),
            'offset' => array(
                'type' => 'VARCHAR',
                'constraint' => '11',
            ),
            'timezone' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'timezone_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            )
        );
        return $arr;
    }
}
if (!function_exists('auto_responderTableFieldsDb')) {
    function auto_responderTableFieldsDb()
    {
        $arr = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'memberId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'user_created' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'groupId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'keyword' => array(
                'type' => 'MEDIUMTEXT'
            ),
            'response_statement' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'addDnc' => array(
                'type' => 'ENUM("Yes","No")',
                'default' => 'No',
                'null' => 'NOT NULL'
            ),
            'deleteContact' => array(
                'type' => 'ENUM("Yes","No")',
                'default' => 'No',
                'null' => 'NOT NULL'
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => '20',
            ),
            'operation' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            )
        );
        return $arr;
    }
}
if (!function_exists('broadcastTableFieldsDb')) {
    function broadcastTableFieldsDb()
    {
        $arr = array(
            'bId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'memberId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'sCampId' => array(
                'type' => 'INT',
                'constraint' => 100,
            ),
            'vCampId' => array(
                'type' => 'INT',
                'constraint' => 100,
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'data' => array(
                'type' => 'LONGTEXT'
            ),
            'phoneNumber' => array(
                'type' => 'INT',
                'constraint' => 100,
            ),
            'type' => array(
                'type' => 'ENUM("SMS", "Email", "Voice")',
                'default' => 'SMS',
                'null' => 'NOT NULL'
            ),
            'accountType' => array(
                'type' => 'ENUM("Twilio", "Plivo")',
                'default' => 'Plivo',
                'null' => 'NOT NULL'
            ),
            'execution' => array(
                'type' => 'INT',
                'constraint' => '20',
            ),
            'leadIds' => array(
                'type' => 'LONGTEXT'
            ),
            'status' => array(
                'type' => 'ENUM("Pending", "Processed")',
                'default' => 'Pending',
                'null' => 'NOT NULL'
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => '20',
            ),
        );
        return $arr;
    }
}
if (!function_exists('usersTableFieldsDb')) {
    function usersTableFieldsDb()
    {
        $arr = array(
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'apiKey' => array(
                'type' => 'VARCHAR',
                'constraint' => 16,
            ),
            'type' => array(
                'type' => 'ENUM("Admin", "Member", "Trial Member", "Staff")',
                'default' => 'Staff',
                'null' => 'NOT NULL'
            ),
            'status' => array(
                'type' => 'ENUM("Active", "Inactive", "Expired", "Suspended")',
                'default' => 'Active',
                'null' => 'NOT NULL'
            ),
            'memberId' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'firstname' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'lastname' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'password' => array(
                'type' => 'TEXT'
            ),
            'twilioNumber' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
            'accountType' => array(
                'type' => 'ENUM("Plivo", "Twilio")',
                'default' => 'Plivo',
                'null' => 'NOT NULL'
            ),
            'mailType' => array(
                'type' => 'ENUM("Custom", "SendGrid")',
                'default' => 'Custom',
                'null' => 'NOT NULL'
            ),
            'accountSid' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'authToken' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'appId' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'endpointId' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'endpoint_username' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'endpoint_password' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'smsForward' => array(
                'type' => 'ENUM("Yes", "No")',
                'default' => 'No',
                'null' => 'NOT NULL'
            ),
            'smsForwardEmail' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'callForward' => array(
                'type' => 'ENUM("Yes", "No")',
                'default' => 'No',
                'null' => 'NOT NULL'
            ),
            'callForwardNumber' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
            ),
            'tzId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => 20,
            ),
            'company' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            )
        );
        return $arr;
    }
}
if (!function_exists('voice_compaignsTableFieldsDb')) {
    function voice_compaignsTableFieldsDb()
    {
        $arr = array(
            'vcId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'memberId' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'c_name' => array(
                'type' => 'VARCHAR',
                'constraint' => 255,
            ),
            'callerId' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'liveAnswering' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'answeringMachine' => array(
                'type' => 'ENUM("Yes", "No")',
                'default' => 'No',
                'null' => 'NOT NULL'
            ),
            'voicemailId' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'c_start_dateTime' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'c_stop_dateTime' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'daysOfweeks' => array(
                'type' => 'MEDIUMTEXT'
            ),
            'c_throttle' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'recordCall' => array(
                'type' => 'ENUM("Yes", "No")',
                'default' => 'No',
                'null' => 'NOT NULL'
            ),
            'totalContacts' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'oderByDate' => array(
                'type' => 'DATETIME'
            ),
            'status' => array(
                'type' => 'ENUM("Pending", "Pause", "Start", "Stop", "Completed")',
                'default' => 'Pending',
                'null' => 'NOT NULL'
            ),
            'deleteStatus' => array(
                'type' => 'ENUM("Yes", "No")',
                'default' => 'No',
                'null' => 'NOT NULL'
            ),
            'dated' => array(
                'type' => 'DATETIME'
            )
        );
        return $arr;
    }
}

if (!function_exists('voice_templatesTableFieldsDb')) {
    function voice_templatesTableFieldsDb()
    {
        $arr = array(
            'voiceTempId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'memberId' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'audio' => array(
                'type' => 'VARCHAR',
                'constraint' => 250,
            ),
            'text' => array(
                'type' => 'TEXT'
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => 20,
            )
        );
        return $arr;
    }
}
if (!function_exists('widget_clickToCallTableFieldsDb')) {
    function widget_clickToCallTableFieldsDb()
    {
        $arr = array(
            'widgetCTCId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'memberId' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11
            ),
            'callTo' => array(
                'type' => 'VARCHAR',
                'constraint' => 20,
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => 100,
            ),
            'widget' => array(
                'type' => 'LONGTEXT'
            ),
            'status' => array(
                'type' => 'ENUM("Active", "Inactive", "Default")',
                'default' => 'Active',
                'null' => 'NOT NULL'
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => 21
            )
        );
        return $arr;
    }
}
if (!function_exists('user_numbersTableFieldsDb')) {
    function user_numbersTableFieldsDb()
    {
        $arr = array(
            'twilioId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'memberId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'number' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
            'smsUrl' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'voiceUrl' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'phoneSid' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => 20,
            )
        );
        return $arr;
    }
}
if (!function_exists('timezonesTableFieldsDb')) {
    function timezonesTableFieldsDb()
    {
        $arr = array(
            'tzId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'location' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'value' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'name_info' => array(
                'type' => 'VARCHAR',
                'constraint' => 50,
            ),
            'offset' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
            )
        );
        return $arr;
    }
}
if (!function_exists('sms_templatesTableFieldsDb')) {
    function sms_templatesTableFieldsDb()
    {
        $arr = array(
            'smsTempId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'memberId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'message' => array(
                'type' => 'TEXT'
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => 20
            )

        );
        return $arr;
    }
}

if (!function_exists('sms_compaignsTableFieldsDb')) {
    function sms_compaignsTableFieldsDb()
    {
        $arr = array(
            'vcId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'memberId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'c_name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'callerId' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'sms' => array(
                'type' => 'MEDIUMTEXT'
            ),
            'c_start_dateTime' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'c_stop_dateTime' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
            ),
            'daysOfweeks' => array(
                'type' => 'MEDIUMTEXT'
            ),
            'c_throttle' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'totalContacts' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'oderByDate' => array(
                'type' => 'DATETIME'
            ),
            'status' => array(
                'type' => 'ENUM("Pending", "Pause", "Start", "Stop", "Completed")',
                'default' => 'Pending',
                'null' => 'NOT NULL'
            ),
            'deleteStatus' => array(
                'type' => 'ENUM("Yes", "No")',
                'default' => 'No',
                'null' => 'NOT NULL'
            ),
            'dated' => array(
                'type' => 'DATETIME'
            )

        );
        return $arr;
    }
}
if (!function_exists('lead_groupsTableFieldsDb')) {
    function lead_groupsTableFieldsDb()
    {
        $arr = array(
            'groupId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'memberId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'description' => array(
                'type' => 'TEXT'
            ),
            'status' => array(
                'type' => 'ENUM("Active", "Inactive", "Default")',
                'default' => 'Active',
                'null' => 'NOT NULL'
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => '20',
            )
        );
        return $arr;
    }
}
if (!function_exists('lead_formsTableFieldsDb')) {
    function lead_formsTableFieldsDb()
    {
        $arr = array(
            'formId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'memberId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'groupId' => array(
                'type' => 'INT',
                'constraint' => 20,
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'formdata' => array(
                'type' => 'LONGTEXT'
            ),
            'status' => array(
                'type' => 'ENUM("Active", "Inactive", "Default")',
                'default' => 'Active',
                'null' => 'NOT NULL'
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => '20',
            )
        );
        return $arr;
    }
}
if (!function_exists('debugTableFieldsDb')) {
    function debugTableFieldsDb()
    {
        $arr = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'data' => array(
                'type' => 'LONGTEXT'
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => '20',
            )
        );
        return $arr;
    }
}
if (!function_exists('email_templatesTableFieldsDb')) {
    function email_templatesTableFieldsDb()
    {
        $arr = array(
            'emailTempId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'memberId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'title' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'fromName' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'fromEmail' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'subject' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'body' => array(
                'type' => 'LONGTEXT'
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => '20',
            )
        );
        return $arr;
    }
}
if (!function_exists('leadsTableFieldsDb')) {
    function leadsTableFieldsDb()
    {
        $arr = array(
            'leadId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'groupId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'firstname' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'lastname' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'phoneNumber' => array(
                'type' => 'VARCHAR',
                'constraint' => 20
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => 100
            ),
            'propertyAddress' => array(
                'type' => 'text',
            ),
            'state' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'city' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'status' => array(
                'type' => 'ENUM("New","Active","Inactive","Subscribe","Unsubscribe")',
                'default' => 'New',
                'null' => 'NOT NULL'
            ),
            'status' => array(
                'type' => 'ENUM("Yes","No")',
                'default' => 'No',
                'null' => 'NOT NULL'
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => 20
            )
        );
        return $arr;
    }
}
if (!function_exists('user_logsTableFieldsDb')) {
    function user_logsTableFieldsDb()
    {
        $arr = array(
            'twLogId' => array(
                'type' => 'INT',
                'constraint' => 11,
                'auto_increment' => TRUE
            ),
            'userId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'leadId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'sCampId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'vCampId' => array(
                'type' => 'INT',
                'constraint' => 11,
            ),
            'message' => array(
                'type' => 'longtext'
            ),
            'sId' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'type' => array(
                'type' => 'ENUM("SMS","MMS","Voice","Call")',
                'default' => 'SMS',
                'null' => 'NOT NULL'
            ),
            'direction' => array(
                'type' => 'ENUM("Inbound","Outbound")',
                'default' => 'Inbound',
                'null' => 'NOT NULL'
            ),
            'status' => array(
                'type' => 'ENUM("Sent","Delivered","Received","Ringing","Completed","In-Progress","Failed")',
                'default' => 'Sent',
                'null' => 'NOT NULL'
            ),
            'smsRead' => array(
                'type' => 'ENUM("Yes","No")',
                'default' => 'No',
                'null' => 'NOT NULL'
            ),
            'callDuration' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'dated' => array(
                'type' => 'INT',
                'constraint' => 20
            )
        );
        return $arr;
    }
}


if (!function_exists('timezoneTableData')) {
    function timezoneTableData()
    {
        $ic_timezones = array(
            array('tzId' => '1', 'location' => 'America', 'value' => 'Adak', 'name' => 'Adak', 'name_info' => 'Hawaii-Aleutian Standard Time', 'offset' => '-10:00'),
            array('tzId' => '2', 'location' => 'America', 'value' => 'Anchorage', 'name' => 'Anchorage', 'name_info' => 'Alaska Standard Time', 'offset' => '-09:00'),
            array('tzId' => '3', 'location' => 'America', 'value' => 'Anguilla', 'name' => 'Anguilla', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '4', 'location' => 'America', 'value' => 'Antigua', 'name' => 'Antigua', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '5', 'location' => 'America', 'value' => 'Araguaina', 'name' => 'Araguaina', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '6', 'location' => 'America', 'value' => 'Argentina/Buenos_Aires', 'name' => 'Argentina/Buenos Aires', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '7', 'location' => 'America', 'value' => 'Argentina/Catamarca', 'name' => 'Argentina/Catamarca', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '8', 'location' => 'America', 'value' => 'Argentina/ComodRivadavia', 'name' => 'Argentina/ComodRivadavia', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '9', 'location' => 'America', 'value' => 'Argentina/Cordoba', 'name' => 'Argentina/Cordoba', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '10', 'location' => 'America', 'value' => 'Argentina/Jujuy', 'name' => 'Argentina/Jujuy', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '11', 'location' => 'America', 'value' => 'Argentina/La_Rioja', 'name' => 'Argentina/La Rioja', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '12', 'location' => 'America', 'value' => 'Argentina/Mendoza', 'name' => 'Argentina/Mendoza', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '13', 'location' => 'America', 'value' => 'Argentina/Rio_Gallegos', 'name' => 'Argentina/Rio Gallegos', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '14', 'location' => 'America', 'value' => 'Argentina/Salta', 'name' => 'Argentina/Salta', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '15', 'location' => 'America', 'value' => 'Argentina/San_Juan', 'name' => 'Argentina/San Juan', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '16', 'location' => 'America', 'value' => 'Argentina/San_Luis', 'name' => 'Argentina/San Luis', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '17', 'location' => 'America', 'value' => 'Argentina/Tucuman', 'name' => 'Argentina/Tucuman', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '18', 'location' => 'America', 'value' => 'Argentina/Ushuaia', 'name' => 'Argentina/Ushuaia', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '19', 'location' => 'America', 'value' => 'Aruba', 'name' => 'Aruba', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '20', 'location' => 'America', 'value' => 'Asuncion', 'name' => 'Asuncion', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '21', 'location' => 'America', 'value' => 'Atikokan', 'name' => 'Atikokan', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '22', 'location' => 'America', 'value' => 'Atka', 'name' => 'Atka', 'name_info' => 'Hawaii-Aleutian Standard Time', 'offset' => '-10:00'),
            array('tzId' => '23', 'location' => 'America', 'value' => 'Bahia', 'name' => 'Bahia', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '24', 'location' => 'America', 'value' => 'Bahia_Banderas', 'name' => 'Bahia Banderas', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '25', 'location' => 'America', 'value' => 'Barbados', 'name' => 'Barbados', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '26', 'location' => 'America', 'value' => 'Belem', 'name' => 'Belem', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '27', 'location' => 'America', 'value' => 'Belize', 'name' => 'Belize', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '28', 'location' => 'America', 'value' => 'Blanc-Sablon', 'name' => 'Blanc-Sablon', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '29', 'location' => 'America', 'value' => 'Boa_Vista', 'name' => 'Boa Vista', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '30', 'location' => 'America', 'value' => 'Bogota', 'name' => 'Bogota', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '31', 'location' => 'America', 'value' => 'Boise', 'name' => 'Boise', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00'),
            array('tzId' => '32', 'location' => 'America', 'value' => 'Buenos_Aires', 'name' => 'Buenos Aires', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '33', 'location' => 'America', 'value' => 'Cambridge_Bay', 'name' => 'Cambridge Bay', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00'),
            array('tzId' => '34', 'location' => 'America', 'value' => 'Campo_Grande', 'name' => 'Campo Grande', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '35', 'location' => 'America', 'value' => 'Cancun', 'name' => 'Cancun', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '36', 'location' => 'America', 'value' => 'Caracas', 'name' => 'Caracas', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '37', 'location' => 'America', 'value' => 'Catamarca', 'name' => 'Catamarca', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '38', 'location' => 'America', 'value' => 'Cayenne', 'name' => 'Cayenne', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '39', 'location' => 'America', 'value' => 'Cayman', 'name' => 'Cayman', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '40', 'location' => 'America', 'value' => 'Chicago', 'name' => 'Chicago', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '41', 'location' => 'America', 'value' => 'Chihuahua', 'name' => 'Chihuahua', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00'),
            array('tzId' => '42', 'location' => 'America', 'value' => 'Coral_Harbour', 'name' => 'Coral Harbour', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '43', 'location' => 'America', 'value' => 'Cordoba', 'name' => 'Cordoba', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '44', 'location' => 'America', 'value' => 'Costa_Rica', 'name' => 'Costa Rica', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '45', 'location' => 'America', 'value' => 'Creston', 'name' => 'Creston', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00'),
            array('tzId' => '46', 'location' => 'America', 'value' => 'Cuiaba', 'name' => 'Cuiaba', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '47', 'location' => 'America', 'value' => 'Curacao', 'name' => 'Curacao', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '48', 'location' => 'America', 'value' => 'Danmarkshavn', 'name' => 'Danmarkshavn', 'name_info' => '', 'offset' => '00:00'),
            array('tzId' => '49', 'location' => 'America', 'value' => 'Dawson', 'name' => 'Dawson', 'name_info' => 'Pacific Standard Time', 'offset' => '-08:00'),
            array('tzId' => '50', 'location' => 'America', 'value' => 'Dawson_Creek', 'name' => 'Dawson Creek', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00'),
            array('tzId' => '51', 'location' => 'America', 'value' => 'Denver', 'name' => 'Denver', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00'),
            array('tzId' => '52', 'location' => 'America', 'value' => 'Detroit', 'name' => 'Detroit', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '53', 'location' => 'America', 'value' => 'Dominica', 'name' => 'Dominica', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '54', 'location' => 'America', 'value' => 'Edmonton', 'name' => 'Edmonton', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00'),
            array('tzId' => '55', 'location' => 'America', 'value' => 'Eirunepe', 'name' => 'Eirunepe', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '56', 'location' => 'America', 'value' => 'El_Salvador', 'name' => 'El Salvador', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '57', 'location' => 'America', 'value' => 'Ensenada', 'name' => 'Ensenada', 'name_info' => 'Pacific Standard Time', 'offset' => '-08:00'),
            array('tzId' => '58', 'location' => 'America', 'value' => 'Fort_Wayne', 'name' => 'Fort Wayne', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '59', 'location' => 'America', 'value' => 'Fortaleza', 'name' => 'Fortaleza', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '60', 'location' => 'America', 'value' => 'Glace_Bay', 'name' => 'Glace Bay', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '61', 'location' => 'America', 'value' => 'Godthab', 'name' => 'Godthab', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '62', 'location' => 'America', 'value' => 'Goose_Bay', 'name' => 'Goose Bay', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '63', 'location' => 'America', 'value' => 'Grand_Turk', 'name' => 'Grand Turk', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '64', 'location' => 'America', 'value' => 'Grenada', 'name' => 'Grenada', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '65', 'location' => 'America', 'value' => 'Guadeloupe', 'name' => 'Guadeloupe', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '66', 'location' => 'America', 'value' => 'Guatemala', 'name' => 'Guatemala', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '67', 'location' => 'America', 'value' => 'Guayaquil', 'name' => 'Guayaquil', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '68', 'location' => 'America', 'value' => 'Guyana', 'name' => 'Guyana', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '69', 'location' => 'America', 'value' => 'Halifax', 'name' => 'Halifax', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '70', 'location' => 'America', 'value' => 'Havana', 'name' => 'Havana', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '71', 'location' => 'America', 'value' => 'Hermosillo', 'name' => 'Hermosillo', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00'),
            array('tzId' => '72', 'location' => 'America', 'value' => 'Indiana/Indianapolis', 'name' => 'Indiana/Indianapolis', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '73', 'location' => 'America', 'value' => 'Indiana/Knox', 'name' => 'Indiana/Knox', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '74', 'location' => 'America', 'value' => 'Indiana/Marengo', 'name' => 'Indiana/Marengo', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '75', 'location' => 'America', 'value' => 'Indiana/Petersburg', 'name' => 'Indiana/Petersburg', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '76', 'location' => 'America', 'value' => 'Indiana/Tell_City', 'name' => 'Indiana/Tell City', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '77', 'location' => 'America', 'value' => 'Indiana/Vevay', 'name' => 'Indiana/Vevay', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '78', 'location' => 'America', 'value' => 'Indiana/Vincennes', 'name' => 'Indiana/Vincennes', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '79', 'location' => 'America', 'value' => 'Indiana/Winamac', 'name' => 'Indiana/Winamac', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '80', 'location' => 'America', 'value' => 'Indianapolis', 'name' => 'Indianapolis', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '81', 'location' => 'America', 'value' => 'Inuvik', 'name' => 'Inuvik', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00'),
            array('tzId' => '82', 'location' => 'America', 'value' => 'Iqaluit', 'name' => 'Iqaluit', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '83', 'location' => 'America', 'value' => 'Jamaica', 'name' => 'Jamaica', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '84', 'location' => 'America', 'value' => 'Jujuy', 'name' => 'Jujuy', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '85', 'location' => 'America', 'value' => 'Juneau', 'name' => 'Juneau', 'name_info' => 'Alaska Standard Time', 'offset' => '-09:00'),
            array('tzId' => '86', 'location' => 'America', 'value' => 'Kentucky/Louisville', 'name' => 'Kentucky/Louisville', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '87', 'location' => 'America', 'value' => 'Kentucky/Monticello', 'name' => 'Kentucky/Monticello', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '88', 'location' => 'America', 'value' => 'Knox_IN', 'name' => 'Knox IN', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '89', 'location' => 'America', 'value' => 'Kralendijk', 'name' => 'Kralendijk', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '90', 'location' => 'America', 'value' => 'La_Paz', 'name' => 'La Paz', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '91', 'location' => 'America', 'value' => 'Lima', 'name' => 'Lima', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '92', 'location' => 'America', 'value' => 'Los_Angeles', 'name' => 'Los Angeles', 'name_info' => 'Pacific Standard Time', 'offset' => '-08:00'),
            array('tzId' => '93', 'location' => 'America', 'value' => 'Las_Vegas', 'name' => 'Las Vegas', 'name_info' => 'Pacific Standard Time', 'offset' => '-08:00'),
            array('tzId' => '94', 'location' => 'America', 'value' => 'Louisville', 'name' => 'Louisville', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '95', 'location' => 'America', 'value' => 'Lower_Princes', 'name' => 'Lower Princes', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '96', 'location' => 'America', 'value' => 'Maceio', 'name' => 'Maceio', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '97', 'location' => 'America', 'value' => 'Managua', 'name' => 'Managua', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '98', 'location' => 'America', 'value' => 'Manaus', 'name' => 'Manaus', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '99', 'location' => 'America', 'value' => 'Marigot', 'name' => 'Marigot', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '100', 'location' => 'America', 'value' => 'Martinique', 'name' => 'Martinique', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '101', 'location' => 'America', 'value' => 'Matamoros', 'name' => 'Matamoros', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '102', 'location' => 'America', 'value' => 'Mazatlan', 'name' => 'Mazatlan', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00'),
            array('tzId' => '103', 'location' => 'America', 'value' => 'Mendoza', 'name' => 'Mendoza', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '104', 'location' => 'America', 'value' => 'Menominee', 'name' => 'Menominee', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '105', 'location' => 'America', 'value' => 'Merida', 'name' => 'Merida', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '106', 'location' => 'America', 'value' => 'Metlakatla', 'name' => 'Metlakatla', 'name_info' => 'Alaska Standard Time', 'offset' => '-09:00'),
            array('tzId' => '107', 'location' => 'America', 'value' => 'Mexico_City', 'name' => 'Mexico City', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '108', 'location' => 'America', 'value' => 'Miquelon', 'name' => 'Miquelon', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '109', 'location' => 'America', 'value' => 'Moncton', 'name' => 'Moncton', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '110', 'location' => 'America', 'value' => 'Monterrey', 'name' => 'Monterrey', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '111', 'location' => 'America', 'value' => 'Montevideo', 'name' => 'Montevideo', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '112', 'location' => 'America', 'value' => 'Montreal', 'name' => 'Montreal', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '113', 'location' => 'America', 'value' => 'Montserrat', 'name' => 'Montserrat', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '114', 'location' => 'America', 'value' => 'Nassau', 'name' => 'Nassau', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '115', 'location' => 'America', 'value' => 'New_York', 'name' => 'New York', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '116', 'location' => 'America', 'value' => 'Nipigon', 'name' => 'Nipigon', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '117', 'location' => 'America', 'value' => 'Nome', 'name' => 'Nome', 'name_info' => 'Alaska Standard Time', 'offset' => '-09:00'),
            array('tzId' => '118', 'location' => 'America', 'value' => 'Noronha', 'name' => 'Noronha', 'name_info' => '', 'offset' => '-02:00'),
            array('tzId' => '119', 'location' => 'America', 'value' => 'North_Dakota/Beulah', 'name' => 'North Dakota/Beulah', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '120', 'location' => 'America', 'value' => 'North_Dakota/Center', 'name' => 'North Dakota/Center', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '121', 'location' => 'America', 'value' => 'North_Dakota/New_Salem', 'name' => 'North Dakota/New Salem', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '122', 'location' => 'America', 'value' => 'Ojinaga', 'name' => 'Ojinaga', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00'),
            array('tzId' => '123', 'location' => 'America', 'value' => 'Panama', 'name' => 'Panama', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '124', 'location' => 'America', 'value' => 'Pangnirtung', 'name' => 'Pangnirtung', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '125', 'location' => 'America', 'value' => 'Paramaribo', 'name' => 'Paramaribo', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '126', 'location' => 'America', 'value' => 'Phoenix', 'name' => 'Phoenix', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00'),
            array('tzId' => '127', 'location' => 'America', 'value' => 'Port-au-Prince', 'name' => 'Port-au-Prince', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '128', 'location' => 'America', 'value' => 'Port_of_Spain', 'name' => 'Port of Spain', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '129', 'location' => 'America', 'value' => 'Porto_Acre', 'name' => 'Porto Acre', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '130', 'location' => 'America', 'value' => 'Porto_Velho', 'name' => 'Porto Velho', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '131', 'location' => 'America', 'value' => 'Puerto_Rico', 'name' => 'Puerto Rico', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '132', 'location' => 'America', 'value' => 'Rainy_River', 'name' => 'Rainy River', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '133', 'location' => 'America', 'value' => 'Rankin_Inlet', 'name' => 'Rankin Inlet', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '134', 'location' => 'America', 'value' => 'Recife', 'name' => 'Recife', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '135', 'location' => 'America', 'value' => 'Regina', 'name' => 'Regina', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '136', 'location' => 'America', 'value' => 'Resolute', 'name' => 'Resolute', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '137', 'location' => 'America', 'value' => 'Rio_Branco', 'name' => 'Rio Branco', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '138', 'location' => 'America', 'value' => 'Rosario', 'name' => 'Rosario', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '139', 'location' => 'America', 'value' => 'Santa_Isabel', 'name' => 'Santa Isabel', 'name_info' => 'Pacific Standard Time', 'offset' => '-08:00'),
            array('tzId' => '140', 'location' => 'America', 'value' => 'Santarem', 'name' => 'Santarem', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '141', 'location' => 'America', 'value' => 'Santiago', 'name' => 'Santiago', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '142', 'location' => 'America', 'value' => 'Santo_Domingo', 'name' => 'Santo Domingo', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '143', 'location' => 'America', 'value' => 'Sao_Paulo', 'name' => 'Sao Paulo', 'name_info' => '', 'offset' => '-03:00'),
            array('tzId' => '144', 'location' => 'America', 'value' => 'Scoresbysund', 'name' => 'Scoresbysund', 'name_info' => '', 'offset' => '-01:00'),
            array('tzId' => '145', 'location' => 'America', 'value' => 'Shiprock', 'name' => 'Shiprock', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00'),
            array('tzId' => '146', 'location' => 'America', 'value' => 'Sitka', 'name' => 'Sitka', 'name_info' => 'Alaska Standard Time', 'offset' => '-09:00'),
            array('tzId' => '147', 'location' => 'America', 'value' => 'St_Barthelemy', 'name' => 'St Barthelemy', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '148', 'location' => 'America', 'value' => 'St_Johns', 'name' => 'St Johns', 'name_info' => '', 'offset' => '-03:30'),
            array('tzId' => '149', 'location' => 'America', 'value' => 'St_Kitts', 'name' => 'St Kitts', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '150', 'location' => 'America', 'value' => 'St_Lucia', 'name' => 'St Lucia', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '151', 'location' => 'America', 'value' => 'St_Thomas', 'name' => 'St Thomas', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '152', 'location' => 'America', 'value' => 'St_Vincent', 'name' => 'St Vincent', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '153', 'location' => 'America', 'value' => 'Swift_Current', 'name' => 'Swift Current', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '154', 'location' => 'America', 'value' => 'Tegucigalpa', 'name' => 'Tegucigalpa', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '155', 'location' => 'America', 'value' => 'Thule', 'name' => 'Thule', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '156', 'location' => 'America', 'value' => 'Thunder_Bay', 'name' => 'Thunder Bay', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '157', 'location' => 'America', 'value' => 'Tijuana', 'name' => 'Tijuana', 'name_info' => 'Pacific Standard Time', 'offset' => '-08:00'),
            array('tzId' => '158', 'location' => 'America', 'value' => 'Toronto', 'name' => 'Toronto', 'name_info' => 'Eastern Standard Time', 'offset' => '-05:00'),
            array('tzId' => '159', 'location' => 'America', 'value' => 'Tortola', 'name' => 'Tortola', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '160', 'location' => 'America', 'value' => 'Vancouver', 'name' => 'Vancouver', 'name_info' => 'Pacific Standard Time', 'offset' => '-08:00'),
            array('tzId' => '161', 'location' => 'America', 'value' => 'Virgin', 'name' => 'Virgin', 'name_info' => 'Atlantic Standard Time', 'offset' => '-04:00'),
            array('tzId' => '162', 'location' => 'America', 'value' => 'Whitehorse', 'name' => 'Whitehorse', 'name_info' => 'Pacific Standard Time', 'offset' => '-08:00'),
            array('tzId' => '163', 'location' => 'America', 'value' => 'Winnipeg', 'name' => 'Winnipeg', 'name_info' => 'Central Standard Time', 'offset' => '-06:00'),
            array('tzId' => '164', 'location' => 'America', 'value' => 'Yakutat', 'name' => 'Yakutat', 'name_info' => 'Alaska Standard Time', 'offset' => '-09:00'),
            array('tzId' => '165', 'location' => 'America', 'value' => 'Yellowknife', 'name' => 'Yellowknife', 'name_info' => 'Mountain Standard Time', 'offset' => '-07:00')
        );
        return $ic_timezones;
    }
}
