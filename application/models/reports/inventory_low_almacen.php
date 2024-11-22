<?php
require_once("report.php");
class Inventory_low_almacen extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array($this->lang->line('reports_item_name'), $this->lang->line('reports_item_number'), $this->lang->line('reports_description'), $this->lang->line('reports_count'), $this->lang->line('reports_reorder_level'));
	}
	
	public function getData(array $inputs)
	{
		$this->db->select('name, item_number, stock_almacenes.cantidad as quantity, reorder_level, description, nombre');
		$this->db->from('items');
		$this->db->where('stock_almacenes.cantidad <= reorder_level and phppos_items.deleted=0');
		$this->db->join('stock_almacenes', 'stock_almacenes.item_id = items.item_id');
		$this->db->join('almacenes', 'stock_almacenes.almacen_id = almacenes.almacen_id');
		if($inputs['almacen_id'])
			$this->db->where('almacenes.almacen_id', $inputs['almacen_id']);
		$this->db->order_by('name');
		
		return $this->db->get()->result_array();

	}
	
	public function getSummaryData(array $inputs)
	{
		return array();
	}
	
	//yop
	/*
	Get search suggestions to find customers
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();
		
		$this->db->from('items');
		$this->db->where("(name LIKE '%".$this->db->escape_like_str($search)."%' or 
		description LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`name`,' ',`description`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0 AND quantity <= reorder_level");
		$this->db->order_by("name", "asc");		
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->first_name.' '.$row->last_name;
		}
		
		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
	
	}

	/*
	Preform a search on inventory
	*/
	function search($search)
	{
		$this->db->from('ITEMS');
		//$this->db->join('items','inventory.trans_items=items.item_id');	
		$this->db->where("(name LIKE '%".$this->db->escape_like_str($search)."%' or 
		description LIKE '%".$this->db->escape_like_str($search)."%' or 
		CONCAT(`name`,' ',`description`) LIKE '%".$this->db->escape_like_str($search)."%') and deleted=0 AND quantity <= reorder_level");		
		$this->db->order_by("name", "asc");
		
		//return $this->db->get();	
		return $this->db->get();
	}
	
	/*
	get the width for the add/edit form
	*/
	function get_form_width()
	{
		return 360;
	}
}
?>