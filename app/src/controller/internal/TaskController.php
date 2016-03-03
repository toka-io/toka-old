<?php
require_once('service/internal/TaskService.php');

class TaskController extends Controller
{
    public function get($request, $response) {

        if (RequestMapping::map('task', $request['uri'], $match)) {
		
			$tasks = TaskService::getAllTasks();
		
            include("internal/task/home.php");
        } else
            parent::redirect404();
    }
	
	public function post($request, $response) {
		
	}
}