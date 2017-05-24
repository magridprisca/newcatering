<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('menu_model');
		$this->load->helper('text');
		$this->load->helper('url_helper');
	}
	public function index(){
		$apa['judul'] ='Menu';
		$this->load->view('admin/head_view',$apa);
		$data['menu'] = $this->menu_model->getAll();
		$this->load->view('admin/menu_view',$data);
	}
	
	public function add(){
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('paket', 'Paket', 'required');
		$this->form_validation->set_rules('jenis', 'Jenis', 'required');
		$this->form_validation->set_rules('isi', 'Isi', 'required');
		$this->form_validation->set_rules('harga', 'Harga', 'required');
		$this->form_validation->set_rules('keterangan', 'Keterangan');
		$this->form_validation->set_rules('foto', 'Foto');

		if ($this->form_validation->run() == FALSE){
			$apa['judul'] ='Menu';
			$this->load->view('admin/head_view',$apa);
			$data['err_message']="";
			$this->load->view('admin/menu_tambah_view',$data);
		}
		else {
			$url = $this->do_upload();
			$data = array(
				'paket'			=> set_value('paket'),
				'jenis'			=> set_value('jenis'),
				'isi'			=> set_value('isi'),
				'harga'			=> set_value('harga'),
				'keterangan'	=> set_value('keterangan'),
				'foto'			=> $url
			);
			$res=$this->menu_model->create($data);
			redirect('menu');
		}
	}
	function do_upload(){
		$type = explode(".", $_FILES['gambar']['name']);
		$type = $type [count($type)-1];
		$url ='img/menu/'.uniqid(rand()).'.'.$type;
		if(in_array($type, array('jpg','jpeg','gif','png','JPG','PNG','JPEG')))
			if(is_uploaded_file($_FILES['gambar']['tmp_name']))
				if(move_uploaded_file($_FILES['gambar']['tmp_name'], $url))
					return $url;
			return "";
	}
	public function edit($id){
		$this->form_validation->set_rules('paket', 'Paket', 'required');
		$this->form_validation->set_rules('jenis', 'Jenis', 'required');
		$this->form_validation->set_rules('isi', 'Isi', 'required');
		$this->form_validation->set_rules('harga', 'Harga', 'required');
		$this->form_validation->set_rules('keterangan', 'Keterangan');

		if ($this->form_validation->run() == FALSE){
			$apa['judul'] ='admin';
			$this->load->view('admin/head_view',$apa);
			$data['menu'] = $this->menu_model->findDetail($id);
			$this->load->view('admin/menu_edit_view',$data);
		}
		else {
			$url = $this->do_upload();
			if($url==""){
					$data = array(
					'paket'			=> set_value('paket'),
					'jenis'			=> set_value('jenis'),
					'isi'			=> set_value('isi'),
					'harga'			=> set_value('harga'),
					'keterangan'	=> set_value('keterangan')
				);

			}else{
				$data = array(
					'paket'			=> set_value('paket'),
					'jenis'			=> set_value('jenis'),
					'isi'			=> set_value('isi'),
					'harga'			=> set_value('harga'),
					'keterangan'	=> set_value('keterangan'),
					'foto'			=> $url
				);
			}
			$this->menu_model->update($id,$data);
			redirect('menu');
		}
	}
	
	public function delete($id){
		$this->menu_model->delete($id);
		redirect('menu');
	}
}
?>
