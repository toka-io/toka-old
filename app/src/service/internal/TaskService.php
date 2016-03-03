<?php
require_once('repo/internal/TaskRepo.php');

/*
 * @note: Should we check whether a user exists when making the request? Double check...
 */
class TaskService
{
    public static function getAllTasks() {
        $taskRepo = new TaskRepo(false);
        
        $data = $taskRepo->getAllTasks();
        
        return $data;
    }
}