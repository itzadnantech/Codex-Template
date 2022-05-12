<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Management extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        if ($this->userInfo->type != 'Staff' && empty($this->userInfo->twilioNumber)) {
            // redirect(base_url('twilioPurchaseNumber'));
        }
    }
    public function index()
    {
        // echo "info";die;
        //  print_arr($this->userInfo);die;
        $view_page = $this->uri->segment(1);

        if ($this->userInfo->type == 'staff') {
            if ($view_page == 'manage_numbers' || $view_page == 'manage_staff') {
                redirect(base_url('dashboard'));
            }
        }
        $this->recsGroup = getByWhere('lead_groups', '*', $this->memberId, array('groupId', 'DESC'));
        if ($view_page == 'dialer') {
            $this->leadId = 0;
            $this->sectionName = 'sms';
            if ($_REQUEST['leadId']) {
                $this->leadId = $_REQUEST['leadId'];
            }
            if ($_REQUEST['type']) {
                $this->sectionName = $_REQUEST['type'];
            }
            $where['memberId'] = $this->memberId;
            if ($this->userInfo->type == 'Staff') {
                $where['userId'] = $this->userId;
            }
            $this->tempEmail = getByWhere('email_templates', '*', $where, array('emailTempId', 'DESC'));
            $this->tempSms = getByWhere('sms_templates', '*', $where, array('smsTempId', 'DESC'));
            $this->tempVoice = getByWhere('voice_templates', '*', $where, array('voiceTempId', 'DESC'));
        }
        $this->load->view($view_page);
    }

    public function dialer()
    {
        $this->load->view('dialer');
    }
    public function inbox()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'dated') {
                    $Crec['data'] = 'tb1.dated';
                }
                if ($Crec['data'] == 'checkbox' || $Crec['data'] == 'Action') {
                } else {
                    $whereLike[$Crec['data']] = $searchVal;
                }
            }
        }
        $where = array('tb1.type' => 'SMS', 'tb1.direction' => 'Inbound');
        if ($this->userInfo->type == 'Staff') {
            $where['tb1.userId'] = $this->userId;
        }
        $tableSelect = 'tb1.*,tb2.phoneNumber,tb3.twilioNumber';
        $tableInfo = "$tableInfo tb1,$tbLeads tb2-tb2.leadId=tb1.leadId-Left, users tb3-tb3.userId=tb1.userId-Left";
        // print_arr($tableInfo); die;
        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);

        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                $msgDelete = "'Are you sure?'";
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'checkbox') {
                        $smsRead = $rec['smsRead'];
                        $twLogId = $rec['twLogId'];
                        if ($smsRead == 'Yes') {
                            $checkInfo = 'checked';
                        } else {
                            $checkInfo = '';
                        }
                        $smsRead = "'" . $smsRead . "'";
                        $returnCont[$key][$colRec['data']] = '<input type="checkbox" onclick="changeSMSStatus(' . $smsRead . ',' . $twLogId . ')" ' . $checkInfo . '>';
                    } elseif ($colRec['data'] == 'Action') {
                        $leadId = $rec['leadId'];
                        $Button = '';
                        $Button .= '<a href="' . base_url() . 'dialer?leadId=' . $leadId . '&type=sms"><i class="fas fa-comments"></i></a>&nbsp;';
                        $returnCont[$key][$colRec['data']] = $Button;
                    } elseif ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        //print_r($return);die;
        echo json_encode($return);
    }
    public function outbox()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'dated') {
                    $Crec['data'] = 'tb1.dated';
                } elseif ($Crec['data'] == 'status') {
                    $Crec['data'] = 'tb1.status';
                }
                if ($Crec['data'] == 'Action') {
                } else {
                    $whereLike[$Crec['data']] = $searchVal;
                }
            }
        }
        $where = array('tb1.type' => 'SMS', 'tb1.direction' => 'Outbound');
        if ($this->userInfo->type == 'Staff') {
            $where['tb1.userId'] = $this->userId;
        }
        $tableSelect = 'tb1.*,tb2.phoneNumber,tb3.twilioNumber';
        $tableInfo = "$tableInfo tb1,$tbLeads tb2-tb2.leadId=tb1.leadId-Left, users tb3-tb3.userId=tb1.userId-Left";

        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);

        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                $msgDelete = "'Are you sure?'";
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'checkbox') {
                        $smsRead = $rec['smsRead'];
                        $twLogId = $rec['twLogId'];
                        if ($smsRead == 'Yes') {
                            $checkInfo = 'checked';
                        } else {
                            $checkInfo = '';
                        }
                        $smsRead = "'" . $smsRead . "'";
                        $returnCont[$key][$colRec['data']] = '<input type="checkbox" onclick="changeSMSStatus(' . $smsRead . ',' . $twLogId . ')" ' . $checkInfo . '>';
                    } elseif ($colRec['data'] == 'Action') {
                        $leadId = $rec['leadId'];
                        $Button = '';
                        $Button .= '<a href="' . base_url() . 'dialer?leadId=' . $leadId . '&type=sms"><i class="fas fa-comments"></i></a>&nbsp;';
                        $returnCont[$key][$colRec['data']] = $Button;
                    } elseif ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        echo json_encode($return);
    }
    public function sms_keywords()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        $orderBy = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'dated') {
                    $Crec['data'] = 'tb1.dated';
                }
                if ($Crec['data'] == 'Action') {
                } else {
                    $whereLike[$Crec['data']] = $searchVal;
                }
            }
        }
        $where = array('tb1.memberId' => $this->memberId);
        if ($this->userInfo->type == 'Staff') {
            $where['tb1.userId'] = $this->userId;
        }
        if (!empty($order)) {
            $orderBy = array($columns[$order[0]['column']]['data'], $order[0]['dir']);
        }
        $tableSelect = 'tb1.*';
        $tableInfo = "$tableInfo tb1, users tb2-tb2.userId=tb1.userId-Left";

        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);

        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                $msgDelete = "'Are you sure?'";
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'Action') {
                        $keyId = $rec['id'];
                        $Button = '';

                        $Button .= '<a href="javascript:void(0);" data-toggle="modal" data-target="#actionPerform" onclick="ActionRec(' . $keyId . ')"><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp';
                        $Button .= '<a href="' . base_url() . 'delete/auto_responder/id/' . $keyId . '" style="color: red;" onclick="return confirm(' . $msgDelete . ')"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                        $returnCont[$key][$colRec['data']] = $Button;
                    } elseif ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        echo json_encode($return);
    }
    public function email_log()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'dated') {
                    $Crec['data'] = 'tb1.dated';
                }
                $whereLike[$Crec['data']] = $searchVal;
            }
        }
        if ($this->userInfo->type == 'Staff') {
            $where = array('tb1.userId' => $this->userId);
        }
        $tableSelect = 'tb1.*,tb2.email as toEmail,tb3.smsForwardEmail as fromEmail';
        $tableInfo = "$tableInfo tb1,$tbLeads tb2-tb2.leadId=tb1.leadId-Left, users tb3-tb3.userId=tb1.userId-Left";
        //print_arr($tableInfo);die;



        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);

        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);

        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        // echo $this->db->last_query($return);die;

        echo json_encode($return);
    }
    public function incoming_call()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'dated') {
                    $Crec['data'] = 'tb1.dated';
                }
                $whereLike[$Crec['data']] = $searchVal;
            }
        }
        $where = array('tb1.type' => 'Call', 'tb1.direction' => 'Inbound');
        if ($this->userInfo->type == 'Staff') {
            $where['tb1.userId'] = $this->userId;
        }
        $tableSelect = 'tb1.*,tb2.phoneNumber,tb3.twilioNumber';
        $tableInfo = "$tableInfo tb1,$tbLeads tb2-tb2.leadId=tb1.leadId-Left, users tb3-tb3.userId=tb1.userId-Left";
        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);

        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                foreach ($columns as $colKey => $colRec) {
                    $rec = (array) $rec;
                    // print_arr($rec);die;
                    if ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        echo json_encode($return);
    }
    public function outgoing_call()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'dated') {
                    $Crec['data'] = 'tb1.dated';
                }
                $whereLike[$Crec['data']] = $searchVal;
            }
        }
        $where = array('tb1.type' => 'Call', 'tb1.direction' => 'Outbound');
        if ($this->userInfo->type == 'Staff') {
            $where['tb1.userId'] = $this->userId;
        }
        $tableSelect = 'tb1.*,tb2.phoneNumber,tb3.twilioNumber';
        $tableInfo = "$tableInfo tb1,$tbLeads tb2-tb2.leadId=tb1.leadId-Left, users tb3-tb3.userId=tb1.userId-Left";
        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);

        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        echo json_encode($return);
    }
    public function groups()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'dated') {
                    $Crec['data'] = 'tb1.dated';
                }
                if ($Crec['data'] == 'Action' || $Crec['data'] == 'Count') {
                } else {
                    $whereLike[$Crec['data']] = $searchVal;
                }
            }
        }
        if ($this->userInfo->type == 'Staff') {
            $where = array('tb1.userId' => $this->userId);
        }
        if (!empty($order)) {
            $orderBy = array($columns[$order[0]['column']]['data'], $order[0]['dir']);
        }
        $tableSelect = "tb1.*,CONCAT(tb2.firstname,' ',tb2.lastname) as firstname";
        $tableInfo = "$tableInfo tb1, users tb2-tb2.userId=tb1.userId-Left";
        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);

        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                $msgDelete = "'Are you sure?'";
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'Count') {
                        $groupId = $rec['groupId'];
                        $where =  array('groupId' => $groupId);
                        $Count = getByWhereCount('leads1', $where);
                        $returnCont[$key][$colRec['data']] = $Count;
                    } elseif ($colRec['data'] == 'Action') {
                        $groupId = $rec['groupId'];
                        $Button = '';
                        $Button .= '<a href="' . base_url() . 'importGroupContacts/' . $groupId . '"><i class="fas fa-upload"></i></a>&nbsp;';
                        if ($rec['status'] != 'Default') {
                            $Button .= '<a href="javascript:void(0);" data-toggle="modal" data-target="#addNewGroup" onclick="groupActionRec(' . $groupId . ')"><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp';
                            $Button .= '<a href="' . base_url() . 'delete/lead_groups/groupId/' . $groupId . '" style="color: red;" onclick="return confirm(' . $msgDelete . ')"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                        }
                        $returnCont[$key][$colRec['data']] = $Button;
                    } elseif ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        echo json_encode($return);
    }
    public function importGroupContacts()
    {
        $groupId = base64_decode($this->uri->segment(2));
        $this->groupRec = getByWhere('lead_groups', '*', array('groupId' => $groupId))[0];
        $this->load->view('importGroupContacts');
    }
    public function groupImportActionManagement()
    {
        extract($_POST);
        $insertQarr['userId'] = $this->userId;
        $insertQarr['dated'] = time();
        $insertQarr['groupId'] = $groupId;
        if (isset($_FILES['importFile']) && ($_FILES['importFile']['size'] != 0)) {
            // $filename      = time().'_'.$_FILES['importFile']['name'];
            // $tempname      = $_FILES['importFile']['tmp_name'];
            // $upload_dir = 'media/temp_files/'.$filename;
            // if(move_uploaded_file($tempname, $upload_dir))
            // {  }
            ini_set('auto_detect_line_endings', true);
            $file = $_FILES['importFile']['tmp_name'];
            $handle = fopen($file, "r");
            while ($data = fgetcsv($handle)) {
                if ($counter > 0) {
                    $data1  = phoneFormat(addslashes($data[0]));
                    $data2  = addslashes($data[1]);
                    $data3  = addslashes($data[2]);
                    $data4  = addslashes($data[3]);
                    $data5  = addslashes($data[4]);
                    $data6  = addslashes($data[5]);
                    $data7  = addslashes($data[6]);
                    $chkNumber = getByWhere($this->leadTable, '*', array('phoneNumber' => $data1));
                    if (empty($chkNumber)) {
                        $insertQarr['phoneNumber'] = $data1;
                        $insertQarr['firstname'] = $data2;
                        $insertQarr['lastname'] = $data3;
                        $insertQarr['email'] = $data4;
                        $insertQarr['propertyAddress'] = $data5;
                        $insertQarr['city'] = $data6;
                        $insertQarr['state'] = $data7;
                        $res = addNew($this->leadTable, $insertQarr);
                    }
                }
                $counter++;
            }
            fclose($file);
            echo "success";
            die;
        }
        echo "1";
        die;
    }
    public function groupActionManagement()
    {
        extract($_POST);
        unset($_POST['groupId']);
        $groupArr = $_POST;
        if ($groupId == '0') {
            $titleChk = getByWhere('lead_groups', 'groupId', array('title' => $title, 'userId' => $userId));
            if ($titleChk) {
                echo "1";
                die;
            }
            $groupArr['memberId'] = $this->memberId;
            $groupArr['dated'] = time();
            $res = addNew('lead_groups', $groupArr);
        } else {
            $titleChk = getByWhere('lead_groups', 'groupId', array('groupId<>' => $groupId, 'title' => $title, 'userId' => $userId));
            if ($titleChk) {
                echo "1";
                die;
            }
            $res = updateByWhere('lead_groups', $groupArr, array('groupId' => $groupId));
        }
        if ($res) {
            echo "success";
            die;
        }
    }
    public function leads()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'firstname') {
                    $Crec['data'] = 'tbl1.firstname';
                } elseif ($Crec['data'] == 'lastname') {
                    $Crec['data'] = 'tbl1.lastname';
                } elseif ($Crec['data'] == 'staffName') {
                    $Crec['data'] = 'tbl3.firstname';
                } elseif ($Crec['data'] == 'email') {
                    $Crec['data'] = 'tbl1.email';
                } elseif ($Crec['data'] == 'title') {
                    $Crec['data'] = 'tbl2.title';
                } elseif ($Crec['data'] == 'dated') {
                    $Crec['data'] = 'tb1.dated';
                }
                if ($Crec['data'] == 'Action') {
                } else {
                    $whereLike[$Crec['data']] = $searchVal;
                }
            }
        }
        if ($this->userInfo->type == 'Staff') {
            $where = array('tbl1.userId' => $this->userId);
        }
        if (!empty($order)) {
            $orderBy = array($columns[$order[0]['column']]['data'], $order[0]['dir']);
        }
        $tableSelect = 'tbl1.*,tbl3.firstname as staffName,tbl2.title';
        $tableInfo = "$tableInfo tbl1,$tbLeads tbl2-tbl2.groupId=tbl1.groupId-Left, users tbl3-tbl3.userId=tbl1.userId-Left";
        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);

        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                $msgDelete = "'Are you sure?'";
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'Action') {
                        $leadId = $rec['leadId'];
                        $Button = '';
                        $Button .= '<a href="javascript:void(0);" data-toggle="modal" data-target="#addNewLead" onclick="leadActionRec(' . $leadId . ')"><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp';
                        $Button .= '<a href="' . base_url() . 'dialer?leadId=' . $leadId . '&type=sms' . '"><i class="fas fa-comments"></i></a>&nbsp;';
                        $Button .= '<a href="' . base_url() . 'delete/leads1/leadId/' . $leadId . '" style="color: red;" onclick="return confirm(' . $msgDelete . ')"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                        $returnCont[$key][$colRec['data']] = $Button;
                    } elseif ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        echo json_encode($return);
    }

    public function dns_leads()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'firstname') {
                    $Crec['data'] = 'tbl2.firstname';
                } elseif ($Crec['data'] == 'lastname') {
                    $Crec['data'] = 'tbl2.lastname';
                }
                if ($Crec['data'] == 'dated' || $Crec['data'] == 'Action') {
                } else {
                    $whereLike[$Crec['data']] = $searchVal;
                }
            }
        }
        if (!empty($order)) {
            $orderBy = array($columns[$order[0]['column']]['data'], $order[0]['dir']);
        }
        $tableSelect = '*';
        $tableInfo = "$tableInfo tbl1, users tbl2-tbl2.userId=tbl1.userId-Left";
        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);
        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                $msgDelete = "'Are you sure?'";
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'Action') {
                        $dncId = $rec['dncId'];
                        $Button = '';
                        $Button .= '<a href="javascript:void(0);" data-toggle="modal" data-target="#addNewLead" onclick="leadActionRec(' . $dncId . ')"><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp';
                        $returnCont[$key][$colRec['data']] = $Button;
                    } elseif ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        echo json_encode($return);
    }

    public function leadActionManagement()
    {
        $leadArr = $_POST;
        $leadArr = array_filter($_POST, function ($value) {
            return $value !== '';
        });
        extract($leadArr);
        unset($leadArr['leadId']);
        if (strlen($phoneNumber) == 10 || strlen($phoneNumber) == 11) {
            $res = "";
            $phoneNumber = phoneFormat($phoneNumber);
            $leadArr['phoneNumber'] = $phoneNumber;
            if ($leadId == '0') {
                $titleChk = getByWhere($this->leadTable, 'leadId', array('phoneNumber' => $phoneNumber, 'userId' => $userId));
                if ($titleChk) {
                    echo "1";
                    die;
                }
                $leadArr['dated'] = time();
                $res = addNew($this->leadTable, $leadArr);
            } else {
                $titleChk = getByWhere($this->leadTable, 'leadId', array('leadId<>' => $leadId, 'phoneNumber' => $phoneNumber, 'userId' => $userId));
                if ($titleChk) {
                    echo "1";
                    die;
                }
                $res = updateByWhere($this->leadTable, $leadArr, array('leadId' => $leadId));
            }
            if ($res) {
                echo "success";
                die;
            }
        } else {
            echo "2";
            die;
        }
    }
    public function DNCActionManagement()
    {
        $table = 'dncList' . $this->memberId;
        $DNCArr = $_POST;
        $DNCArr = array_filter($_POST, function ($value) {
            return $value !== '';
        });
        extract($DNCArr);
        unset($DNCArr['dncId']);
        if (strlen($phoneNumber) == 10 || strlen($phoneNumber) == 11) {
            $res = "";
            $phoneNumber = phoneFormat($phoneNumber);
            $DNCArr['phoneNumber'] = $phoneNumber;
            if ($dncId == '0') {
                $phoneNumberChk = getByWhere($table, 'dncId', array('phoneNumber' => $phoneNumber, 'userId' => $userId));
                if ($phoneNumberChk) {
                    echo "1";
                    die;
                }
                $DNCArr['dated'] = time();
                $res = addNew($table, $DNCArr);
            }
            if ($res) {
                echo "success";
                die;
            }
        } else {
            echo "2";
            die;
        }
    }

    public function keywordActionManagement()
    {
        $keyArr = $_POST;
        $keyArr = array_filter($_POST, function ($value) {
            return $value !== '';
        });
        extract($keyArr);
        unset($keyArr['id']);
        $keyArr['userId'] = $this->userId;
        $keyArr['memberId'] = $this->memberId;
        $keyArr['groupId'] = 0;
        $keyArr['addDnc'] = 'No';
        $keyArr['deleteContact'] = 'No';
        if ($operation == 'Subscribe' && !empty($groupId)) {
            $keyArr['groupId'] = $groupId;
        }
        if ($operation == 'UnSubscribe' && !empty($addDnc)) {
            $keyArr['addDnc'] = $addDnc;
        }
        if ($operation == 'UnSubscribe' && !empty($deleteContact)) {
            $keyArr['deleteContact'] = $deleteContact;
        }
        if ($id == '0') {
            $keyWordChk = getByWhere('auto_responder', 'id', array('keyword' => $keyword, 'userId' => $this->userId));
            if ($keyWordChk) {
                echo "1";
                die;
            }
            $keyArr['dated'] = time();
            $res = addNew('auto_responder', $keyArr);
        } else {
            $keyWordChk = getByWhere('auto_responder', 'id', array('id<>' => $id, 'keyword' => $keyword, 'userId' => $this->userId));
            if ($keyWordChk) {
                echo "1";
                die;
            }
            $res = updateByWhere('auto_responder', $keyArr, array('id' => $id));
        }
        if ($res) {
            echo "success";
            die;
        }
    }
    public function lead_forms()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'dated') {
                    $Crec['data'] = 'tb1.dated';
                } elseif ($Crec['data'] == 'title') {
                    $Crec['data'] = 'tb1.title';
                } elseif ($Crec['data'] == 'group') {
                    $Crec['data'] = 'tb2.title';
                } elseif ($Crec['data'] == 'status') {
                    $Crec['data'] = 'tb1.status';
                }
                if ($Crec['data'] == 'Action' || $Crec['data'] == 'Count') {
                } else {
                    $whereLike[$Crec['data']] = $searchVal;
                }
            }
        }
        if ($this->userInfo->type == 'Staff') {
            $where = array('tb1.userId' => $this->userId);
        }
        if (!empty($order)) {
            $orderBy = array($columns[$order[0]['column']]['data'], $order[0]['dir']);
        }
        $tableSelect = 'tb1.*,tb3.firstname,tb2.title as group,tb2.status as groupStatus';
        $tableInfo = "$tableInfo tb1,$tbLeads tb2-tb2.groupId=tb1.groupId-Left, users tb3-tb3.userId=tb1.userId-Left";
        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);

        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                $msgDelete = "'Are you sure?'";
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'Action') {
                        $formId = $rec['formId'];
                        $Button = '<a class="ActionRecWidgetsMod" href="javascript:void(0);" data-toggle="modal" data-target="#ActionRecWidgets" data-title="' . $rec['title'] . '" data-scr="' . base_url('leadFormWidget') . '/' . base64_encode(encrypt($rec['formId'])) . '"><i class="fas fa-code" aria-hidden="true"></i></a>&nbsp';
                        $Button .= '<a href="javascript:void(0);" data-toggle="modal" data-target="#addNewForm" onclick="ActionRec(' . $formId . ')"><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp';
                        $Button .= '<a href="' . base_url() . 'delete/lead_forms/formId/' . $formId . '" style="color: red;" onclick="return confirm(' . $msgDelete . ')"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                        $returnCont[$key][$colRec['data']] = $Button;
                    } elseif ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        echo json_encode($return);
    }
    public function LeadFormActionManagement()
    {
        extract($_POST);
        unset($_POST['formId']);
        $InsertArr = $_POST;
        $InsertArr['formdata'] = json_encode($formdata);
        if ($formId == '0') {
            $titleChk = getByWhere('lead_forms', 'formId', array('title' => $title, 'userId' => $this->userId));
            if ($titleChk) {
                echo "1";
                die;
            }
            $InsertArr['memberId'] = $this->memberId;
            $InsertArr['userId'] = $this->userId;
            $InsertArr['dated'] = time();
            $res = addNew('lead_forms', $InsertArr);
        } else {
            $titleChk = getByWhere('lead_forms', 'formId', array('formId<>' => $formId, 'title' => $title, 'userId' => $this->userId));
            if ($titleChk) {
                echo "1";
                die;
            }
            $res = updateByWhere('lead_forms', $InsertArr, array('formId' => $formId));
        }
        if ($res) {
            echo "success";
            die;
        }
    }
    public function auto_responders()
    {
        $this->recs = getByWhere('auto_responders', '*', $this->commonWhere, array('arId', 'DESC'));
        $this->grouprecs = getByWhere('lead_groups', '*', $this->commonWhere, array('groupId', 'DESC'));
        $this->load->view('auto_responders');
    }
    public function autoResponderActionManagement()
    {
        extract($_POST);
        unset($_POST['arId']);
        $autoResArr = $_POST;
        if ($arId == '0') {
            $titleChk = getByWhere('auto_responders', 'arId', array('response' => $response, 'groupId' => $groupId));
            if ($titleChk) {
                echo "1";
                die;
            }
            $autoResArr['memberId'] = $this->memberId;
            $autoResArr['userId'] = $this->userId;
            $autoResArr['dated'] = time();
            $res = addNew('auto_responders', $autoResArr);
        } else {
            $titleChk = getByWhere('auto_responders', 'groupId', array('arId<>' => $arId, 'response' => $response, 'userId' => $this->userId));
            if ($titleChk) {
                echo "1";
                die;
            }
            $res = updateByWhere('auto_responders', $autoResArr, array('arId' => $arId));
        }
        if ($res) {
            echo "success";
            die;
        }
    }

    public function sms_templates()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'Action') {
                } elseif ($Crec['data'] == 'dated') {
                    $Crec['data'] = 'tb1.dated';
                } else {
                    $whereLike[$Crec['data']] = $searchVal;
                }
            }
        }
        if ($this->userInfo->type == 'Staff') {
            $where['tb1.userId'] = $this->userId;
        }
        if (!empty($order)) {
            $orderBy = array($columns[$order[0]['column']]['data'], $order[0]['dir']);
        }
        $tableSelect = 'tb1.*';
        $tableInfo = "$tableInfo tb1";
        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);

        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                $msgDelete = "'Are you sure?'";
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'Action') {
                        $smsTempId = $rec['smsTempId'];
                        $Button = '';
                        $Button .= '<a href="javascript:void(0);" data-toggle="modal" data-target="#actionPerform" onclick="ActionRec(' . $smsTempId . ')"><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp';
                        $Button .= '<a href="' . base_url() . 'delete/sms_templates/smsTempId/' . $smsTempId . '" style="color: red;" onclick="return confirm(' . $msgDelete . ')"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                        $returnCont[$key][$colRec['data']] = $Button;
                    } elseif ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        echo json_encode($return);
    }

    public function voice_templates()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'dated') {
                    $Crec['data'] = 'tb1.dated';
                }
                if ($Crec['data'] == 'Action' || $Crec['data'] == 'message') {
                } else {
                    $whereLike[$Crec['data']] = $searchVal;
                }
            }
        }
        if ($this->userInfo->type == 'Staff') {
            $where['tb1.userId'] = $this->userId;
        }
        if (!empty($order)) {
            $orderBy = array($columns[$order[0]['column']]['data'], $order[0]['dir']);
        }
        $tableSelect = 'tb1.*';
        $tableInfo = "$tableInfo tb1";
        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);
        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                $msgDelete = "'Are you sure?'";
                foreach ($columns as $colKey => $colRec) {
                    $voiceTempId = $rec['voiceTempId'];
                    $audio = $rec['audio'];
                    $text = $rec['text'];
                    if ($colRec['data'] == 'Action') {
                        $Button = '';
                        $Button .= '<a href="#" data-toggle="modal" data-target="#actionPerform" onclick="ActionRec(' . $voiceTempId . ')"><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp';

                        $Button .= '<a href="' . base_url() . 'delete/voice_templates/voiceTempId/' . $voiceTempId . '" style="color: red;" onclick="return confirm(' . $msgDelete . ')"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                        $returnCont[$key][$colRec['data']] = $Button;
                    } elseif ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } elseif ($colRec['data'] == 'message') {
                        $data = '';
                        if ($audio) {
                            $data = '<audio id="audio" controls="" style="height: 25px;"><source src="' . base_url($audio) . '" type="audio/mpeg"> Your browser does not support the audio element. </audio>';
                        } else {
                            $data = substr($text, 0, 30);
                        }
                        $returnCont[$key][$colRec['data']] = $data;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        echo json_encode($return);
    }

    public function smsTemplateActionManagement()
    {
        extract($_POST);
        unset($_POST['smsTempId']);
        $autoResArr = $_POST;
        if ($smsTempId == '0') {
            $titleChk = getByWhere('sms_templates', 'smsTempId', array('title' => $title, 'userId' => $this->userId));
            if ($titleChk) {
                echo "1";
                die;
            }
            $autoResArr['memberId'] = $this->memberId;
            $autoResArr['userId'] = $this->userId;
            $autoResArr['dated'] = time();
            $res = addNew('sms_templates', $autoResArr);
        } else {
            $titleChk = getByWhere('sms_templates', 'title', array('smsTempId<>' => $smsTempId, 'title' => $title, 'userId' => $this->userId));
            if ($titleChk) {
                echo "1";
                die;
            }
            $res = updateByWhere('sms_templates', $autoResArr, array('smsTempId' => $smsTempId));
        }
        if ($res) {
            echo "success";
            die;
        }
    }
    public function email_templates()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'dated') {
                    $Crec['data'] = 'tb1.dated';
                }
                if ($Crec['data'] == 'Action') {
                } else {
                    $whereLike[$Crec['data']] = $searchVal;
                }
            }
        }
        if ($this->userInfo->type == 'Staff') {
            $where['tb1.userId'] = $this->userId;
        }
        if (!empty($order)) {
            $orderBy = array($columns[$order[0]['column']]['data'], $order[0]['dir']);
        }
        $tableSelect = 'tb1.*';
        $tableInfo = "$tableInfo tb1";
        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);

        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                $msgDelete = "'Are you sure?'";
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'Action') {
                        $emailTempId = $rec['emailTempId'];
                        $Button = '';
                        $Button .= '<a href="javascript:void(0);" data-toggle="modal" data-target="#actionPerform" onclick="ActionRec(' . $emailTempId . ')"><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp';
                        $Button .= '<a href="' . base_url() . 'delete/email_templates/emailTempId/' . $emailTempId . '" style="color: red;" onclick="return confirm(' . $msgDelete . ')"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                        $returnCont[$key][$colRec['data']] = $Button;
                    } elseif ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        echo json_encode($return);
    }
    public function emailTemplateActionManagement()
    {
        extract($_POST);
        unset($_POST['emailTempId']);
        $emailTemArr = $_POST;
        if ($emailTempId == '0') {
            $titleChk = getByWhere('email_templates', 'emailTempId', array('title' => $title, 'userId' => $this->userId));

            if ($titleChk) {
                echo "1";
                die;
            }
            $emailTemArr['memberId'] = $this->memberId;
            $emailTemArr['userId'] = $this->userId;
            $emailTemArr['dated'] = time();
            $res = addNew('email_templates', $emailTemArr);
        } else {
            $titleChk = getByWhere('email_templates', 'title', array('emailTempId<>' => $emailTempId, 'title' => $title, 'userId' => $this->userId));
            if ($titleChk) {
                echo "1";
                die;
            }
            $res = updateByWhere('email_templates', $emailTemArr, array('emailTempId' => $emailTempId));
        }
        if ($res) {
            echo "success";
            die;
        }
    }
    public function voiceTemplateActionManagement()
    {
        extract($_POST);
        unset($_POST['voiceTempId']);
        unset($_POST['type']);
        $insertQarr = $_POST;
        if ($voiceTempId == '0') {
            $titleChk = getByWhere('voice_templates', 'voiceTempId', array('title' => $title, 'userId' => $this->userId));
            if ($titleChk) {
                echo "1";
                die;
            }
            $insertQarr['memberId'] = $this->memberId;
            $insertQarr['userId'] = $this->userId;
            $insertQarr['dated'] = time();
            $res = addNew('voice_templates', $insertQarr);
            $voiceTempId = $res;
        } else {
            $titleChk = getByWhere('voice_templates', 'title', array('voiceTempId<>' => $voiceTempId, 'title' => $title, 'userId' => $this->userId));
            if ($titleChk) {
                echo "1";
                die;
            }
            if ($type == 'onlytext') {
                $insertQarr['audio'] = '';
            } else {
                $insertQarr['text'] = '';
            }
            unset($insertQarr['type']);
            $res = updateByWhere('voice_templates', $insertQarr, array('voiceTempId' => $voiceTempId));
        }
        if (isset($_FILES['audio']) && ($_FILES['audio']['size'] != 0)) {
            $filename      = time() . '_' . $_FILES['audio']['name'];
            $tempname      = $_FILES['audio']['tmp_name'];
            $folderName = 'media/voice_template/' . $this->memberId;
            makeDirectory($folderName);
            $upload_dir = $folderName . '/' . $filename;
            if (move_uploaded_file($tempname, $upload_dir)) {
                $audioArr = array('audio' => $upload_dir);
                updateByWhere('voice_templates', $audioArr, array('voiceTempId' => $voiceTempId));
            }
        }
        if ($res) {
            echo "success";
            die;
        }
    }
    public function manage_numbers()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'Action') {
                } else {
                    $whereLike[$Crec['data']] = $searchVal;
                }
            }
        }
        if (!empty($order)) {
            $orderBy = array($columns[$order[0]['column']]['data'], $order[0]['dir']);
        }
        $where = array('tb1.memberId' => $this->memberId);
        $tableSelect = "tb1.*,CONCAT(tb2.firstname,' ',tb2.lastname) as firstname";
        $tableInfo = "$tableInfo tb1, users tb2-tb2.userId=tb1.userId-Left";
        // print_arr($tableInfo);die;
        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);
        // print_r($this->db->last_query());
        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                $msgDelete = "'Are you sure?'";
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'Action') {
                        $twilioId = $rec['twilioId'];
                        $Button = '';
                        $Button .= '<a href="javascript:void(0);" data-toggle="modal" data-target="#actionPerform" onclick="ActionRec(' . $twilioId . ')"><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp';
                        $Button .= '<a href="' . base_url() . 'management/deleteTwilioNumber/user_numbers/twilioId/' . $twilioId . '" style="color: red;" onclick="return confirm(' . $msgDelete . ')"><i class="fas fa-trash-alt"></i></a>&nbsp;';
                        $returnCont[$key][$colRec['data']] = $Button;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        echo json_encode($return);
    }

    public function manage_staff()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'Action') {
                } else {
                    $whereLike[$Crec['data']] = $searchVal;
                }
            }
        }
        if (!empty($order)) {
            $orderBy = array($columns[$order[0]['column']]['data'], $order[0]['dir']);
        }
        $where = array('type' => 'staff', 'memberId' => $this->memberId);
        $tableSelect = "tb1.*,CONCAT(tb1.firstname,' ',tb1.lastname) as firstname";
        //print_arr($tableSelect);die;
        $tableInfo = "$tableInfo tb1";
        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);

        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                $msgDelete = "'Are you sure?'";
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'Action') {
                        $userId = $rec['userId'];
                        $status = $rec['status'];
                        $permission = explode(',', $rec['permission']);
                        $Button = '';
                        $Button .= '<a href="javascript:void(0);" data-toggle="modal" data-target="#addNewMember" onclick="userActionRec(' . $userId . ')"><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp';
                        $Button .= '<a href="' . base_url() . 'deleteUser/users/userId/' . $userId .
                            '"style="color: red;" onclick="return confirm(' . $msgDelete . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i></a>&nbsp';
                        // if ($userId != 1 && $status != "Terminated") {
                        // }
                        if (in_array("sms", $permission) && $status == "Active") {
                            $Button .= '<a href="javascript:void(0);" data-toggle="modal"
                            data-target="#showWidgetCode">Widget</a>&nbsp;';
                        }
                        $returnCont[$key][$colRec['data']] = $Button;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }


        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        // echo $this->db->last_query();die;
        echo json_encode($return);
    }
    public function fetch_userInfo()
    {
        $info = userInfo($_POST['userId']);
        echo json_encode($info);
    }
    public function fetch_userInfoDetail()
    {
        $tableSelect = "*";
        $tableInfo = "users tb1, users_limits tb2-tb2.memberId=tb1.userId-Left";
        $info = getByWhere($tableInfo, $tableSelect, array('userId' => $_POST['userId']))[0];
        echo json_encode($info);
    }
    public function sendSMS()
    {
        extract($_POST);
        $InsertArr = array();
        $InsertArr['memberId'] = $this->memberId;
        $InsertArr['userId'] = $this->userId;
        $InsertArr['title'] = 'Send From Dialer';
        $InsertArr['data'] = $msg;
        $InsertArr['type'] = 'SMS';
        $InsertArr['execution'] = time();
        $InsertArr['status'] = 'Pending';
        $InsertArr['dated'] = time();
        $leadIds = array();
        if ($toArr) {
            foreach ($toArr as $key => $rec) {
                $rec_raw = explode('_', $rec);
                $type = $rec_raw[0];
                $id = $rec_raw[1];
                if ($type == 'leadId') {
                    $leadIds[] = $id;
                } elseif ($type == 'groupId') {
                    $leadinfo = getByWhere($this->leadTable, 'leadId', array('groupId' => $id));
                    foreach ($leadinfo as $key => $recL) {
                        $leadIds[] = $recL->leadId;
                    }
                } else {
                    $phoneNumber = phoneFormat($rec);
                    $leadinfo = getByWhere($this->leadTable, 'leadId', array('phoneNumber' => $phoneNumber));
                    if (empty($leadinfo)) {
                        $insertLeadQ = array();
                        $insertLeadQ['userId'] = $this->userId;
                        $insertLeadQ['groupId'] = $this->defaultGroupId;
                        $insertLeadQ['phoneNumber'] = $phoneNumber;
                        $insertLeadQ['dated'] = time();
                        $InsertleadId = addNew($this->leadTable, $insertLeadQ);
                        $leadIds[] = $InsertleadId;
                    } else {
                        $leadIds[] = $leadinfo[0]->leadId;
                    }
                }
            }
        }
        $leadIds = array_unique($leadIds);
        $InsertArr['leadIds'] = implode(',', $leadIds);
        addNew('broadcast', $InsertArr);
        echo "success";
    }
    public function sendEmails()
    {
        extract($_POST);
        $InsertArr = array();
        $InsertArr['memberId'] = $this->memberId;
        $InsertArr['userId'] = $this->userId;
        $InsertArr['title'] = 'Send From Dialer';
        $data = array(
            'fromEmail' => $emailFromEmail,
            'fromName' => $emailFromName,
            'template' => $emailTemplate,
            'subject' => $emailSubject,
            'message' => $emailMessage
        );
        $InsertArr['data'] = json_encode($data);
        $InsertArr['type'] = 'Email';
        $InsertArr['execution'] = time();
        $InsertArr['status'] = 'Pending';
        $InsertArr['dated'] = time();
        $leadIds = array();
        if ($contacts_groups) {
            foreach ($contacts_groups as $key => $rec) {
                $rec_raw = explode('_', $rec);
                $type = $rec_raw[0];
                $id = $rec_raw[1];
                if ($type == 'leadId') {
                    $leadIds[] = $id;
                } elseif ($type == 'groupId') {
                    $leadinfo = getByWhere($this->leadTable, 'leadId', array('groupId' => $id));
                    foreach ($leadinfo as $key => $recL) {
                        $leadIds[] = $recL->leadId;
                    }
                } else {
                    $email = $rec;
                    $leadinfo = getByWhere($this->leadTable, 'leadId', array('email' => $email));
                    if (empty($leadinfo)) {
                        $insertLeadQ = array();
                        $insertLeadQ['userId'] = $this->userId;
                        $insertLeadQ['groupId'] = $this->defaultGroupId;
                        $insertLeadQ['email'] = $email;
                        $insertLeadQ['dated'] = time();
                        $InsertleadId = addNew($this->leadTable, $insertLeadQ);
                        $leadIds[] = $InsertleadId;
                    } else {
                        $leadIds[] = $leadinfo[0]->leadId;
                    }
                }
            }
        }
        $leadIds = array_unique($leadIds);
        $InsertArr['leadIds'] = implode(',', $leadIds);
        addNew('broadcast', $InsertArr);
        echo "success";
    }
    public function widgets()
    {
        extract($_REQUEST);
        $searchVal = $search['value'];
        $where = array();
        $whereLike = array();
        if ($searchVal) {
            foreach ($columns as $Ckey => $Crec) {
                if ($Crec['data'] == 'Action') {
                } else {
                    $whereLike[$Crec['data']] = $searchVal;
                }
            }
        }
        if (!empty($order)) {
            $orderBy = array($columns[$order[0]['column']]['data'], $order[0]['dir']);
        }
        $tableSelect = "tb1.*";
        $tableInfo = "$tableInfo tb1";
        $totalRecords = getByWhereCount($tableInfo, $where, $whereLike);
        $recs = getByWhere($tableInfo, $tableSelect, $where, $orderBy, $length, $start, $whereLike);

        $returnCont = array();
        if ($recs) {
            foreach ($recs as $key => $rec) {
                $rec = (array) $rec;
                $msgDelete = "'Are you sure?'";
                foreach ($columns as $colKey => $colRec) {
                    if ($colRec['data'] == 'Action') {
                        $widgetCTCId = $rec['widgetCTCId'];
                        $status = $rec['status'];
                        $Button = '';
                        if ($status != 'Default') {
                            $Button .= '<a data-toggle="modal" data-target="#codewidgetClickToCall" onclick="ActionRecWidgetCall(' . $widgetCTCId . ')"><i class="fas fa-code" aria-hidden="true"></i></a>&nbsp';
                            $Button .= '<a data-toggle="modal" data-target="#addNewMember" onclick="ActionRec(' . $widgetCTCId . ')"><i class="fas fa-edit" aria-hidden="true"></i></a>&nbsp';
                            $Button .= '<a href="' . base_url() . 'delete/widget_click_to_call/widgetCTCId/' . $widgetCTCId . '" style="color: red;" onclick="return confirm(' . $msgDelete . ')"><i class="fas fa-trash-alt" aria-hidden="true"></i></a>&nbsp';
                            $returnCont[$key][$colRec['data']] = $Button;
                        }
                    } elseif ($colRec['data'] == 'dated') {
                        $date = timeFormatSystem($rec['dated']);
                        $returnCont[$key][$colRec['data']] = $date;
                    } else {
                        $returnCont[$key][$colRec['data']] = ($rec[$colRec['data']]) ? $rec[$colRec['data']] : '';
                    }
                }
            }
        }
        $return['draw'] = $draw;
        $return['recordsTotal'] = $totalRecords;
        $return['recordsFiltered'] = $totalRecords;
        $return['data'] = $returnCont;
        echo json_encode($return);
    }
    public function widget_clickToCall()
    {
        extract($_POST);
        unset($_POST['widgetCTCId']);
        if (strlen($callTo) == 10 || (strlen($callTo) == 11)) {
            $res = "";
            $callTo = phoneFormat($callTo);
            $InsertArr = $_POST;
            $InsertArr = array('callTo' => $callTo, 'title' => $title);
            $InsertArr['widget'] = json_encode($widget);
            // $InsertArr['callto'] = $callTo;
            if ($widgetCTCId == '0') {
                $titleChk = getByWhere('widget_click_to_call', 'widgetCTCId', array('title' => $title, 'userId' => $this->userId));
                if ($titleChk) {
                    echo "1";
                    die;
                }
                $InsertArr['memberId'] = $this->memberId;
                $InsertArr['userId'] = $this->userId;

                $InsertArr['dated'] = time();
                $res = addNew('widget_click_to_call', $InsertArr);
            } else {
                $titleChk = getByWhere('widget_click_to_call', 'widgetCTCId', array('widgetCTCId<>' => $widgetCTCId, 'title' => $title, 'userId' => $this->userId));
                if ($titleChk) {
                    echo "1";
                    die;
                }
                $res = updateByWhere('widget_click_to_call', $InsertArr, array('widgetCTCId' => $widgetCTCId));
            }
            if ($res) {
                echo "success";
                die;
            }
        } else {
            echo "2";
            die;
        }
    }
    public function OutboundCall()
    {
        extract($_POST);
        twilioOutboundCall($this->memberId, $this->accountSid, $this->authToken, $this->fromNumber, $toNumber);
    }
    public function fetchSingleRec()
    {
        extract($_POST);
        $rec = getByWhere($table, '*', array($field => $value))[0];
        echo json_encode($rec);
    }

    public function fetchAllRec()
    {
        extract($_POST);
        $rec = getByWhere($table, '*', array());
        echo json_encode($rec);
    }

    public function fetchRecsWhere()
    {
        extract($_POST);
        $where = json_decode($where, true);
        $recs = getByWhere($table, '*', $where, array($field, 'DESC'));
        echo json_encode($recs);
    }
    public function fetchDailerConversation()
    {
        extract($_POST);
        $where = json_decode($where, true);
        $recs = getByWhere($table, '*', $where, array($field, 'DESC'));
        $liHtml = '';
        if ($recs) {
            foreach ($recs as $key => $rec) {
                if ($rec->direction == 'Outbound') {
                    $liHtml .= '<li class="sent">';
                } else {
                    $liHtml .= '<li class="replies">';
                }
                $liHtml .= '<div class="chat_time">' . date('m/d/Y H:i:s', $rec->dated);
                $liHtml .= '</div><p>' . json_decode($rec->message);
                $liHtml .= '</p></li>';
            }
        }
        echo json_encode($liHtml);
    }
    public function changeSMSStatus()
    {
        extract($_POST);
        $cStatus = ($status == 'Yes') ? 'No' : 'Yes';
        $InsertArr = array('smsRead' => $cStatus);
        if ($twLogId != '0') {
            $whereTable = array('twLogId' => $twLogId);
        } else {
            $whereTable = array('userId' => $this->userId);
        }
        $ck = updateByWhere('user_logs' . $this->memberId, $InsertArr, $whereTable);
        if ($ck) {
            $return = array('status' => 'success');
        } else {
            $return = array('status' => 'error');
        }
        echo json_encode($return);
    }
    public function fetchRecs()
    {
        extract($_POST);
        $recs = getByWhere($table, '*', array(), array($field, 'DESC'), $limit, $offset);
        echo json_encode($recs);
    }
    public function deleteUser()
    {
        $table = $this->uri->segment(2);
        $field = $this->uri->segment(3);
        $userId = $this->uri->segment(4);
        $userInfo = userInfo($userId);

        deleteRecordWhere($table, array($field => $userId));
        if ($userInfo->type != 'Staff') {
            twilioSubaccountAction($this->accountSid, $this->authToken, $userInfo->accountSid, 'closed');
            deleteDbTable('email_logs' . $userId);
            deleteDbTable('leads' . $userId);
            deleteDbTable('user_logs' . $userId);
            $where = array('memberId' => $userId);
            deleteRecordWhere('auto_responders', $where);
            deleteRecordWhere('broadcast', $where);
            deleteRecordWhere('email_templates', $where);
            deleteRecordWhere('lead_groups', $where);
            deleteRecordWhere('sms_templates', $where);
            deleteRecordWhere('user_numbers', $where);
            deleteRecordWhere('voice_templates', $where);
            deleteRecordWhere('manage_staff', $where);
        } else {
            $where = array('userId' => $userId);
            updateByWhere('user_numbers', array('userId' => '0'), $where);
            deleteRecordWhere('email_logs' . $this->memberId, $where);
            deleteRecordWhere('user_logs' . $this->memberId, $where);
        }
        redirect($this->agent->referrer());
    }
    public function deleteTwilioNumber()
    {
        $table = $this->uri->segment(3);
        $field = $this->uri->segment(4);
        $value = $this->uri->segment(5);
        $user = getByWhere($table, 'userId', array($field => $value))[0];
        $res = deleteRecordWhere($table, array($field => $value));
        if ($res) {
            updateByWhere('users', array('twilioNumber' => ''), array('userId' => $user->userId));
        }
        redirect($this->agent->referrer());
    }
    public function delete()
    {
        $table = $this->uri->segment(2);
        $field = $this->uri->segment(3);
        $value = $this->uri->segment(4);
        deleteRecordWhere($table, array($field => $value));
        if ($table == 'lead_groups') {
            deleteRecordWhere($this->leadTable, array($field => $value));
        }
        redirect($this->agent->referrer());
    }
    public function deleteFile()
    {
        $table = $this->uri->segment(2);
        $field = $this->uri->segment(3);
        $value = $this->uri->segment(4);
        $filename = base64_decode($this->uri->segment(5));
        deleteRecordWhere($table, array($field => $value));
        if ($filename) {
            unlink($filename);
        }
        redirect($this->agent->referrer());
    }

    public function dncImport()
    {
        $table = 'dncList' . $this->memberId;
        $data['dncList'] = getByWhere($table, '*', array());
        // print_arr($data);die;
        $this->load->view('importDncList', $data);
    }

    public function dncImportActionManagement()
    {
        $table = 'dncList' . $this->memberId;
        extract($_POST);
        $insertQarr['userId'] = $this->userId;
        $insertQarr['dated'] = time();
        if (isset($_FILES['importFile']) && ($_FILES['importFile']['size'] != 0)) {
            ini_set('auto_detect_line_endings', true);
            $file = $_FILES['importFile']['tmp_name'];
            $handle = fopen($file, "r");
            while ($data = fgetcsv($handle)) {
                if ($counter > 0) {
                    $data1  = phoneFormat(addslashes($data[0]));
                    // print_arr($data1);
                    $chkNumber = getByWhere($table, '*', array('phoneNumber' => $data1));
                    if (empty($chkNumber)) {
                        $insertQarr['phoneNumber'] = $data1;
                        $res = addNew($table, $insertQarr);
                    }
                }
                $counter++;
            }
            fclose($file);
            echo "success";
            die;
        }
    }
    public function reportsStats()
    {
        if ($this->input->is_ajax_request()) {
            if (isset($_POST['get_campaigns'])) {
                switch ($_POST['type']) {
                    case 'sms':
                        echo json_encode(array(
                            'status' => 'success',
                            'message' => getByWhere('sms_compaigns', 'c_name, vcId', null, null, null, null, null, null, null, array('userId' => $this->userId), 'c_name')
                        ));
                        break;
                    case 'voice':
                        echo json_encode(array(
                            'status' => 'success',
                            'message' => getByWhere('voice_compaigns', 'c_name, vcId', null, null, null, null, null, null, null, array('userId' => $this->userId), 'c_name')
                        ));
                        break;
                    default:
                        echo json_encode(array('status' => 'error', 'message' => 'Campaign type is required!'));
                        break;
                }
            } elseif (isset($_POST['filterReports'])) {
                $query = "";
                if (isset($_POST["service_type"])) {
                    switch ($_POST["service_type"]) {
                        case 'sms':
                            $query = "SELECT
                                SUM(case when (status like 'Sent' OR status like 'Delivered') then 1 else 0 end) AS successful,
                                SUM(case when (status not like 'Sent' AND status not like 'Delivered') then 1 else 0 end) AS failed,
                                COUNT(*) AS total_sent
                                FROM ic_user_logs1 WHERE direction = 'outbound' AND (type = 'SMS' OR type = 'MMS')";
                            break;
                        case 'voice':
                            $query = "SELECT
                                SUM(case when (status like 'Completed') then 1 else 0 end) AS successful,
                                SUM(case when (status not like 'Completed') then 1 else 0 end) AS failed,
                                COUNT(*) AS total_sent
                                FROM ic_user_logs1 WHERE direction = 'outbound' AND (type = 'Voice' OR type = 'Call')";
                            break;
                        default:
                            $query = "SELECT COUNT(*) AS total_sent
                                FROM ic_user_logs1 WHERE direction = 'outbound' AND (type = 'SMS' OR type = 'MMS')";
                            break;
                    }
                    $query .= " AND userId = " . $this->userId;
                    if ($_POST['campaign'] != 'all') {
                        $query .= " AND sCampId = " . $this->db->escape($_POST['campaign']);
                    }
                    if (isset($_POST['dateRange']) && $_POST['dateRange'] != 'All') {
                        switch ($_POST['dateRange']) {
                            case "Today":
                                $query .= " AND (dated BETWEEN " . strtotime("today") . " AND " . strtotime('tomorrow') . ")";
                                break;
                            case "Yesterday":
                                $query .= " AND (dated BETWEEN " . strtotime("yesterday") . " AND " . strtotime('today') . ")";
                                break;
                            case "This Week":
                                $query .= " AND (dated BETWEEN " . strtotime("this week") . " AND " . strtotime('next week') . ")";
                                break;
                            case "Last Week":
                                $query .= " AND (dated BETWEEN " . strtotime("-2 week") . " AND " . strtotime('last week') . ")";
                                break;
                            case "Last Month":
                                $query .= " AND (dated BETWEEN " . strtotime("-2 month") . " AND " . strtotime('this month') . ")";
                                break;
                            case "Custom":
                                if (!empty($_POST['startdate']) || !empty($_POST['enddate'])) {
                                    if (!empty($_POST['startdate'])) {
                                        $query .= " AND dated >= " . strtotime($_POST['startdate']);
                                    }
                                    if (!empty($_POST['enddate'])) {
                                        $query .= " AND dated <= " . strtotime($_POST['enddate']);
                                    }
                                } else {
                                    echo json_encode(array(
                                        'status' => 'error',
                                        'message' => 'Date fields cannot be empty when Custom range is selected'
                                    ));
                                    die;
                                }
                                break;
                            default:
                                echo json_encode(array(
                                    'status' => 'error',
                                    'message' => $_POST['dateRange']
                                ));
                                die;
                                break;
                        }
                    }
                    $query .= ";";
                    echo json_encode(array(
                        'status' => 'success',
                        'message' => $this->db->query($query)->row()
                    ));
                } else {
                    echo json_encode(array(
                        'status' => 'error',
                        'message' => 'invalid request'
                    ));
                }
            } else {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'invalid request'
                ));
            }
            die;
        } else {
            show_404();
        }
    }
    public function reportAsCSV()
    {
        $table = "";
        $where = array();
        $whereIn = array();
        $csvHeader = "";
        $fileTitle = "dashboard_log_";

        //$userTimezone = str_replace(':', '', $this->timezoneOffset);
        if (isset($_GET["service_type"])) {
            switch ($_GET["service_type"]) {
                case 'sms':
                    $table = "user_logs1 tb1, leads1 tb2-tb2.leadId=tb1.leadId-Left, sms_compaigns tb3-tb3.vcId=tb1.sCampId-Left";
                    $whereIn = array(
                        'tb1.type=>1' => 'SMS',
                        'tb1.type=>2' => 'MMS'
                    );
                    $csvHeader = array("Dated", "Status", "SMS To", "Message", "First Name", "Last Name", "Address", "City", "State");
                    break;
                case 'voice':
                    $table = "user_logs1 tb1, leads1 tb2-tb2.leadId=tb1.leadId-Left, voice_compaigns tb3-tb3.vcId=tb1.vCampId-Left";
                    $whereIn = array(
                        'tb1.type=>1' => 'Voice',
                        'tb1.type=>2' => 'Call'
                    );
                    $csvHeader = array("Dated", "Status", "To Number", "Call Duration", "First Name", "Last Name", "Address", "City", "State");
                    break;
                default:
                    die('invalid service type');
                    break;
            }
            if (isset($_GET['dateRange']) && $_GET['dateRange'] != 'All') {
                switch ($_GET['dateRange']) {
                    case "Today":
                        $where = array('tb1.dated>=' => strtotime("today"), 'tb1.dated<=' => strtotime('tomorrow'));
                        break;
                    case "Yesterday":
                        $where = array('tb1.dated>=' => strtotime("yesterday"), 'tb1.dated<=' => strtotime('today'));
                        break;
                    case "This Week":
                        $where = array('tb1.dated>=' => strtotime("this week"), 'tb1.dated<=' => strtotime('next week'));
                        break;
                    case "Last Week":
                        $where = array('tb1.dated>=' => strtotime("-2 week"), 'tb1.dated<=' => strtotime('last week'));
                        break;
                    case "Last Month":
                        $where = array('tb1.dated>=' => strtotime("-2 month"), 'tb1.dated<=' => strtotime('this month'));
                        break;
                    case "Custom":
                        if (!empty($_GET['startdate']) || !empty($_GET['enddate'])) {
                            if (!empty($_GET['startdate'])) {
                                $where = array('tb1.dated>=' => strtotime($_GET['startdate'] . ' 00:00:00'));
                            }
                            if (!empty($_GET['enddate'])) {
                                $where = array('tb1.dated>=' => strtotime($_GET['enddate'] . ' 23:59:59'));
                            }
                        } else {
                            die('Date fields cannot be empty when Custom range is selected');
                        }
                        break;
                    default:
                        die(" Invalid date range" . print_r($_GET['dateRange']));
                        break;
                }
            }

            $rows = getByWhere(
                $table,
                "*, tb1.status AS log_status",
                array_merge($where, array(
                    'tb1.userId' => $this->userId,
                    'tb1.direction' => 'Outbound'
                )),
                null,
                null,
                null,
                $whereIn
            );

            if ($rows) {
                /* CSV Handling */
                $fileName = $fileTitle . date('Y-m-d') . ".csv";
                $output_handle = fopen("php://output", 'w') or die("Can't open php://output");
                header("Content-type: application/octet-stream");
                header("Content-Disposition: attachment; filename=$fileName");
                header("Pragma: no-cache");
                header("Expires: 0");
                fputcsv($output_handle, $csvHeader);

                switch ($_GET["service_type"]) {
                    case 'sms':
                        foreach ($rows as $row) {
                            $csvRow = [];
                            $csvRow[0]   = timeFormatSystem($row->dated);
                            $csvRow[1]   = $row->log_status;
                            $csvRow[2]   = $row->phoneNumber;
                            $csvRow[3]   = $row->message;
                            $csvRow[4]   = $row->firstname;
                            $csvRow[5]   = $row->lastname;
                            $csvRow[6]   = $row->propertyAddress;
                            $csvRow[7]   = $row->city;
                            $csvRow[8]   = $row->state;

                            fputcsv($output_handle, $csvRow);
                        }
                        break;
                    case 'voice':
                        foreach ($rows as $row) {
                            $csvRow = [];
                            $csvRow[0]   = timeFormatSystem($row->dated);
                            $csvRow[1]   = $row->log_status;
                            $csvRow[2]   = $row->phoneNumber;
                            $csvRow[3]   = $row->callDuration;
                            $csvRow[4]   = $row->firstname;
                            $csvRow[5]   = $row->lastname;
                            $csvRow[6]   = $row->propertyAddress;
                            $csvRow[7]   = $row->city;
                            $csvRow[8]   = $row->state;

                            fputcsv($output_handle, $csvRow);
                        }
                        break;
                    default:
                        die('invalid service type');
                        break;
                }
                fclose($output_handle) or die("Can't close php://output");
            } else {
                die('No data available to download');
            }
        } else {
            redirect('dashboard');
        }
    }
    public function leadsSearch()
    {
        extract($_POST);
        $whereIn = array();
        $srchExp = explode(' ', $search);
        if ($srchExp && count($srchExp) > 1) {
            $whereIn = array(
                'firstname' => $srchExp[0],
                'lastname' => $srchExp[1],
                'phoneNumber=>' => $srchExp[0],
                'phoneNumber' => $srchExp[1]
            );
        } else {
            $whereIn = array('firstname' => $search, 'lastname' => $search, 'phoneNumber' => $search);
        }
        echo json_encode(getByWhere($table, '*', null, null, $limit, null, $whereIn));
        die;
    }
}
