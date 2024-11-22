<?php

require_once("report.php");

class Summary_sales extends Report {

    function __construct() {
        parent::__construct();
    }

    public function getDataColumns() {
//        return array($this->lang->line('reports_date'), $this->lang->line('reports_subtotal'), $this->lang->line('reports_total'), $this->lang->line('reports_tax'), $this->lang->line('reports_profit'));
//        return array($this->lang->line('reports_date'), $this->lang->line('reports_subtotal'), $this->lang->line('reports_total'), $this->lang->line('reports_tax'), $this->lang->line('reports_profit'));
        return array($this->lang->line('reports_date'), $this->lang->line('reports_consumo_medidor'), $this->lang->line('reports_valor_cuota'), $this->lang->line('reports_cargo'),$this->lang->line('reports_interes'), $this->lang->line('reports_total'));
    }

    public function getData(array $inputs) {
//        $this->db->select('sale_date, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax,sum(profit) as profit');
        $this->db->select('sale_date, sum(consumo_medidor) as consumo_medidor, sum(valor_cuota) as valor_cuota,  sum(cargo) as cargo,sum(interes) as interes, sum(total) as total');
        $this->db->from('sales_items_temp');
        
        $this->db->group_by('sale_date');
        $this->db->having('sale_date BETWEEN "' . $inputs['start_date'] . '" and "' . $inputs['end_date'] . '"');
        $this->db->order_by('sale_date');
        return $this->db->get()->result_array();
    }

    public function getSummaryData(array $inputs) {
//        $this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax,sum(profit) as profit');
        $this->db->select('sum(consumo_medidor) as consumo_medidor, sum(valor_cuota) as valor_cuota, sum(cargo) as cargo, sum(interes) as interes, sum(total) as total');
        $this->db->from('sales_items_temp');
        $this->db->where('sale_date BETWEEN "' . $inputs['start_date'] . '" and "' . $inputs['end_date'] . '"');
        
        return $this->db->get()->row_array();
    }

    /*public function getAlmacenes(array $inputs) {
        $this->db->select('sale_date, sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax,sum(profit) as profit, almacen');
        $this->db->from('sales_items_temp');
        $this->db->group_by('sale_date', 'almacen');
        $this->db->having('sale_date BETWEEN "' . $inputs['start_date'] . '" and "' . $inputs['end_date'] . '"');
        $this->db->order_by('almacen', 'sale_date');
        return $this->db->get()->result_array();
    }

    public function getSummaryAlmacenes(array $inputs) {
        $this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax,sum(profit) as profit, almacen');
        $this->db->from('sales_items_temp');
        $this->db->group_by('almacen');
        $this->db->where('sale_date BETWEEN "' . $inputs['start_date'] . '" and "' . $inputs['end_date'] . '"');
        $this->db->order_by('almacen');
        return $this->db->get()->result_array();
    }*/

}
