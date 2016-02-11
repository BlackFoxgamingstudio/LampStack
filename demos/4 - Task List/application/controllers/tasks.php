<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tasks extends CI_Controller {

	protected $view_data = array();

	public function __construct() {
		parent::__construct();
		$this->load->model('Task');
	}


	public function index()
	{
		$this->view_data['tasks'] = $this->Task->get_task();
		$this->load->view('task_view', $this->view_data);
	}



	public function update()
	{
		$post_data = $this->input->post();

		if(isset($post_data['action']))
		{
			if($post_data['action'] == "update_status")
			{
				$status = ($post_data['status'] == "false") ? 0 : 1;
				$task_data = array(
					"status" => $status,
					"updated_at" => date("Y-m-d H:i:s"),
					"id" => $post_data['task_id']
				);				
			}

			if($post_data['action'] == "update_name")
			{
				$task_data = array(
					"name" => $post_data['name'],
					"updated_at" => date("Y-m-d H:i:s"),
					"id" => $post_data['task_id']
				);
			}

			$update = $this->Task->update($task_data);
			$task = $this->Task->get_task($task_data['id']);

			if($update)
			{
				$class =  ($task['status'] == 0) ? "" : "checked";

				$data['status'] = TRUE;
				$data['task_id'] = $task_data['id'];

				$this->view_data = array(
					'class' => $class,
					'id' => $task_data['id'],
					'name' => $task['name']
				);

				$data['html'] = $this->load->view('partials/task_update', $this->view_data, TRUE);

			}
			else
			{
				$data['status'] = FALSE;
				$data['errors'] = "Oops! Something went wrong!";				
			}
		}
		else
		{
			$data['status'] = FALSE;
			$data['errors'] = "Please fill up all the fields.";			
		}

		echo json_encode($data);
	}

	public function add_task()
	{
		$post_data = $this->input->post();

		if(isset($post_data['action']) && $post_data['action'] == "add_task")
		{
			$task_data = array(
				"name" => $post_data['name'],
				"created_at" => date("Y-m-d H:i:s"),
				"status" => 0
			);

			$add_task = $this->Task->add_task($task_data);

			if($task_data)
			{
				$data['status'] = TRUE;

				$this->view_data = array(
					'class' => "",
					'id' => $this->db->insert_id(),
					'name' => $post_data['name']
				);

				$data['html'] = $this->load->view('partials/task_update', $this->view_data, TRUE);
			}
			else
			{
				$data['status'] = FALSE;
				$data['errors'] = "Oops! Something went wrong!";
			}		
		}
		else
		{
			$data['status'] = FALSE;
			$data['errors'] = "Please fill up all the fields.";
		}

		echo json_encode($data);
	}
}

/* End of file home.php */
/* Location: ./application/controllers/home.php */