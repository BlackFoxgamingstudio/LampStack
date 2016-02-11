<?php

class Task extends CI_Model{

	public function get_task($task_id = NULL)
	{
		if($task_id == NULL)
		{
			$sql = "SELECT * FROM tasks";

			return $this->db->query($sql)->result_array();			
		}
		else
		{
			$sql = "SELECT * FROM tasks WHERE id = ?";
			return $this->db->query($sql, array($task_id))->row_array();
		}
	}

	public function add_task($task_info)
	{
		$sql = "INSERT INTO tasks(name, created_at, status) VALUES(?, ?, ?)";
		return $this->db->query($sql, $task_info);
	}

	public function update($task_info)
	{
		if(isset($task_info['status']))
			$sql = "UPDATE tasks SET status = ?, updated_at = ? WHERE id = ?";
		else
			$sql = "UPDATE tasks SET name = ?, updated_at = ? WHERE id = ?";
		
		return $this->db->query($sql, $task_info);
	}

}