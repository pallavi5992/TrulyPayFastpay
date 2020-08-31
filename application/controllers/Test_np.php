<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_np extends CI_Controller {
    
    function test()
    {
        print_r($_SERVER);
    }
}
?>