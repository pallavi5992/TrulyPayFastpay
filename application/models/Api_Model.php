<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Api_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function init_log($usrid, $ip, $url, $rspns, $type) {
        $this->db->insert('apbox_logs', array('user_id' => $usrid, 'request_ip' => $ip, 'request' => $url, 'request_for' => $type, 'response' => $rspns, 'datetime' => date('Y-m-d H:i:s')));
    }

}
