<?php

require_once ("secure_area.php");

class Config extends Secure_area {

    function __construct() {
        parent::__construct('config');
    }

    function index() {
        //$this->load->view("config");
        $this->twiggy->set(array("time_zone" => date_default_timezone_get()));
        $this->twiggy->display("config");
    }

    function save() {
        $batch_save_data = array(
            'company' => $this->input->post('company'),
            'address' => $this->input->post('address'),
            'phone' => $this->input->post('phone'),
            'email' => $this->input->post('email'),
            'fax' => $this->input->post('fax'),
            'website' => $this->input->post('website'),
            'factura_apocope' => $this->input->post('factura_apocope'),
            'default_tax_1_rate' => $this->input->post('default_tax_1_rate'),
            'default_tax_1_name' => $this->input->post('default_tax_1_name'),
            'default_tax_2_rate' => $this->input->post('default_tax_2_rate'),
            'default_tax_2_name' => $this->input->post('default_tax_2_name'),
            'interest' => $this->input->post('interest'),
            'return_policy' => $this->input->post('return_policy'),
            'language' => $this->input->post('language'),
            'timezone' => $this->input->post('timezone'),
            'print_after_sale' => $this->input->post('print_after_sale')
        );

        if ($this->Appconfig->batch_save($batch_save_data)) {
            echo json_encode(array('success' => true, 'message' => $this->lang->line('config_saved_successfully')));
        } else {
            echo json_encode(array('success' => false, 'message' => $this->lang->line('config_saved_unsuccessfully')));
        }
    }

}

?>