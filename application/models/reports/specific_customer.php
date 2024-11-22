<?php

require_once("report.php");

class Specific_customer extends Report {

    function __construct() {
        parent::__construct();
    }

    public function getDataColumns() {
	
        return array('summary' => array($this->lang->line('reports_sale_id'), $this->lang->line('reports_date'), $this->lang->line('reports_items_purchased'), $this->lang->line('reports_sold_by'), $this->lang->line('reports_consumo_medidor'), $this->lang->line('reports_valor_cuota'), $this->lang->line('reports_cargo'), $this->lang->line('reports_total'), $this->lang->line('reports_payment_type'), $this->lang->line('reports_comments')),
            'details' => array($this->lang->line('reports_name'), $this->lang->line('reports_category'), $this->lang->line('reports_serial_number'), $this->lang->line('reports_description'), $this->lang->line('reports_quantity_purchased'), $this->lang->line('reports_subtotal'), $this->lang->line('reports_total'), $this->lang->line('reports_tax'), $this->lang->line('reports_profit'), $this->lang->line('reports_discount'))
        );
    }

    public function getData(array $inputs) {
//		$this->db->select('sale_id, sale_date, sum(quantity_purchased) as items_purchased, CONCAT(first_name," ",last_name) as employee_name, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit, sales_items_temp.payment_type, comment', false);
//        $this->db->select('sale_id, sale_date, count(*) as items_purchased, CONCAT(first_name," ",last_name) as employee_name, sum(subtotal) as subtotal, sum(total) as total,  sum(profit) as profit, sales_items_temp.payment_type, comment', false);
        $this->db->select('sale_id, sale_date, count(*) as items_purchased, CONCAT(first_name," ",last_name) as employee_name, sum(consumo_medidor) as consumo_medidor,  sum(valor_cuota) as valor_cuota,  sum(cargo) as cargo, sum(total) as total, sales_items_temp.payment_type, comment', false);

        $this->db->from('sales_items_temp');
        $this->db->join('people', 'sales_items_temp.employee_id = people.person_id');
        //$this->db->join('payments', 'sales_items_temp.payment_id = payments.payment_id');
        $this->db->where('sale_date BETWEEN "' . $inputs['start_date'] . '" and "' . $inputs['end_date'] . '" and customer_id=' . $inputs['customer_id']);
        $this->db->group_by('sale_id');
        $this->db->order_by('sale_id');

        $data = array();
        $data['summary'] = $this->db->get()->result_array();
        $data['details'] = array();

        foreach ($data['summary'] as $key => $value) {
            //$this->db->select('name, category, serialnumber, sales_items_temp.description, quantity_purchased, subtotal,total, tax, profit, discount_percent');
//			$this->db->select('consumo.fecha_consumo, consumo.valor_cuota, consumo.registro_medidor, consumo.consumo_medidor, consumo.valor_a_pagar, subtotal,total, profit, consumo.cargo, consumo.detalle_cargo');
//            $this->db->select('consumo.fecha_consumo, consumo.valor_cuota, consumo.registro_medidor, consumo.consumo_medidor, consumo.valor_a_pagar, subtotal, consumo.valor_cuota, consumo.cargo, total, consumo.detalle_cargo');
            $this->db->select('consumo.fecha_consumo, consumo.valor_cuota, consumo.registro_medidor, consumo.consumo_medidor, consumo.valor_a_pagar, subtotal, consumo.valor_cuota, consumo.cargo, total, consumo.detalle_cargo');
            $this->db->from('sales_items_temp');
            $this->db->join('consumo', 'sales_items_temp.consumo_id = consumo.id');
            $this->db->where('sale_id = ' . $value['sale_id']);
            $data['details'][$key] = $this->db->get()->result_array();
        }

        return $data;
    }

    public function getSummaryData(array $inputs) {
        $this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(profit) as profit');
//		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit');
        $this->db->from('sales_items_temp');
        $this->db->where('sale_date BETWEEN "' . $inputs['start_date'] . '" and "' . $inputs['end_date'] . '" and customer_id=' . $inputs['customer_id']);

        return $this->db->get()->row_array();
    }

}