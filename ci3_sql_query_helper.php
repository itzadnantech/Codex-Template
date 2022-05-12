<?php
if (!function_exists('loginPost')) {
  function loginPost($email, $password)
  {
    $CI = &get_instance();
    $info = $CI->db->select('userId,password')->where('email', $email)->get('users')->row();
    if ($info) {
      if ($password == decrypt($info->password)) {
        return $info->userId;
      }
    }
    return false;
  }
}
if (!function_exists('createDbTable')) {
  function createDbTable($tableFields, $key, $tableName)
  {
    $CI = &get_instance();
    $CI->load->dbforge();
    $CI->dbforge->add_field($tableFields);
    $CI->dbforge->add_key($key, TRUE);
    $attributes = array('ENGINE' => 'InnoDB');
    $res = $CI->dbforge->create_table($tableName, TRUE, $attributes);
    return $res;
  }
}
if (!function_exists('alterDbTable')) {
  function alterDbTable($tableFields, $tableName)
  {
    $CI = &get_instance();
    $CI->load->dbforge();
    foreach ($tableFields as $fName => $fInfo) {
      $field = array();
      $field[$fName] = $fInfo;
      if (!$CI->db->field_exists($fName, $tableName)) {
        $CI->dbforge->add_column($tableName, $field);
      }
    }
    return true;
  }
}
if (!function_exists('deleteDbTable')) {
  function deleteDbTable($table)
  {
    $CI = &get_instance();
    $CI->load->dbforge();
    $res = $CI->dbforge->drop_table($table, TRUE);
    return $res;
  }
}
if (!function_exists('emptyDbTable')) {
  function emptyDbTable($table)
  {
    $CI = &get_instance();
    return $CI->db->truncate($table);
  }
}
if (!function_exists('getByWhere')) {
  function getByWhere($table, $select = '*', $where = array(), $orderBy = array(), $limit = 0, $offset = 0, $whereLike = array(), $whereIn = array(), $groupBy = '', $distinct = '')
  {
    $CI = &get_instance();
    $CI->db->select($select);
    $tableArr = explode(',', $table);
    $CI->db->from($tableArr[0]);
    unset($tableArr[0]);
    if (count($tableArr) > 0) {
      foreach ($tableArr as $key => $tbStr) {
        $tbArr = explode('-', $tbStr);
        $CI->db->join($tbArr[0], $tbArr[1], $tbArr[2]);
      }
    }
    // if (count($where) > 0) {
    //   foreach ($where as $key => $val) {
    //     $key = explode('=>', $key)[0];
    //     $CI->db->where($key, $val);
    //   }
    // }
    if(count($where)> 0){
      foreach($where as $key => $val){
        $key=explode('=>', $key)[0];
        if ($val=='DATE_FORMAT') {
          $CI->db->where($key);
        }else{
          $CI->db->where($key, $val);
        }
      }
    }
    if (count($whereLike) > 0) {
      $likeCount = 1;
      $CI->db->group_start();
      foreach ($whereLike as $keyLike =>  $likeRec) {
        $keyLike=explode('=>', $keyLike)[0];
        if ($likeCount == 1) {
          $CI->db->like($keyLike, $likeRec, 'both', false);
        } else {
          $CI->db->or_like($keyLike, $likeRec, 'both', false);
        }
        $likeCount++;
      }
      $CI->db->group_end();
    }
    if (count($whereIn) > 0) {
      $CI->db->where_in($whereIn[0], $whereIn[1]);
    }
    if ($limit > 0) {
      $CI->db->limit($limit, $offset);
    }
    if ($groupBy) {
      $CI->db->group_by($groupBy);
    }
    if (count($orderBy)) {
      $CI->db->order_by($orderBy[0], $orderBy[1]);
    }
    if (!empty($distinct)) {
      $CI->db->distinct($distinct);
    }
    $query = $CI->db->get();
    if ($query->num_rows()) {
      return $query->result();
    } else {
      return false;
    }
  }
}
if (!function_exists('getByWhereCount')) {
  function getByWhereCount($table, $where = array(), $whereLike = array())
  {
    $CI = &get_instance();
    $CI->db->select('COUNT(*) as count');
    $tableArr = explode(',', $table);
    $CI->db->from($tableArr[0]);
    unset($tableArr[0]);
    if (count($tableArr) > 0) {
      foreach ($tableArr as $key => $tbStr) {
        $tbArr = explode('-', $tbStr);
        $CI->db->join($tbArr[0], $tbArr[1], $tbArr[2]);
      }
    }
    if (count($where) > 0) {
      foreach ($where as $key => $val) {
        $key = explode('=>', $key)[0];
        $CI->db->where($key, $val);
      }
    }
    if (count($whereLike) > 0) {
      $likeCount = 1;
      $CI->db->group_start();
      foreach ($whereLike as $keyLike =>  $likeRec) {
        if ($likeCount == 1) {
          $CI->db->like($keyLike, $likeRec, 'both', false);
        } else {
          $CI->db->or_like($keyLike, $likeRec, 'both', false);
        }
        $likeCount++;
      }
      $CI->db->group_end();
    }
    $query = $CI->db->get();
    return $query->row()->count;
  }
}

if (!function_exists('addNew')) {
  function addNew($table, $data)
  {
    $CI = &get_instance();
    $CI->db->insert($table, $data);
    $result = $CI->db->insert_id();
    if (!$result) {
      $result = $CI->db->error();
    }
    return $result;
  }
}
if (!function_exists('updateByWhere')) {
  function updateByWhere($table, $data, $where)
  {
    $CI = &get_instance();
    if (count($where) > 0) {
      foreach ($where as $key => $val) {
        $CI->db->where($key, $val);
      }
      $result = $CI->db->update($table, $data);
      if (!$result) {
        $result = $CI->db->error();
      }
      return $result;
    } else {
      return false;
    }
  }
}
if (!function_exists('updateByWhereLike')) {
  function updateByWhereLike($table, $data, $whereLike)
  {
    $CI = &get_instance();
    if (count($whereLike) > 0) {
      $likeCount = 1;
      foreach ($whereLike as $keyLike =>  $likeRec) {
        $keyLike = explode('=>', $keyLike);
        $keyField = $keyLike[0];
        if ($keyLike[1]) {
          $fieldSide = $keyLike[1];
        } else {
          $fieldSide = 'both';
        }
        if ($likeCount == 1) {
          $CI->db->like($keyField, $likeRec, $fieldSide, false);
        } else {
          $CI->db->or_like($keyField, $likeRec, $fieldSide, false);
        }
        $likeCount++;
      }
      $result = $CI->db->update($table, $data);
      if (!$result) {
        $result = $CI->db->error();
      }
      return $result;
    } else {
      return false;
    }
  }
}
if (!function_exists('deleteRecords')) {
  function deleteRecords($table, $field, $where = array())
  {
    $CI = &get_instance();
    if (count($where) > 0) {
      $CI->db->where_in($field, $where);
      $result = $CI->db->delete($table);
      if (!$result) {
        $result = $CI->db->error();
      }
      return $result;
    } else {
      return false;
    }
  }
}
if (!function_exists('deleteRecordWhere')) {
  function deleteRecordWhere($table, $where = array())
  {
    $CI = &get_instance();
    if (count($where) > 0) {
      foreach ($where as $key => $val) {
        $CI->db->where($key, $val);
      }
      $result = $CI->db->delete($table);
      if (!$result) {
        $result = $CI->db->error();
      }
      return $result;
    } else {
      return false;
    }
  }
}