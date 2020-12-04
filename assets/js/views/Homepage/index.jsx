import React, { useState, useEffect } from "react";
import ReactDOM from "react-dom";
import TaskList from "../TaskList";
import Modal from "../Modal";
import TaskService from "../../api/TaskService";
import Spinner from "../Spinner";
import LoginForm from "../LoginForm";

function Homepage() {
  const [isLoading, setIsLoading] = useState(false);
  const [tasks, setTasks] = useState([]);
  const [createFormVisible, setCreateFormVisible] = useState(false);

  const taskService = new TaskService;

  useEffect(() => {
    setIsLoading(true);
    taskService
        .getList()
        .then(response => setTasks(response.data.data))
        .finally(() => setIsLoading(false))
    ;
  }, []);

  function setTaskStatus(id, status) {
    setIsLoading(true);
    taskService
        .setStatus(id, status)
        .then(response => updateTask(response.data.data))
        .finally(() => setIsLoading(false))
    ;
  }

  function updateTask(taskData) {
    const taskIndex = tasks.findIndex((task) => task.id === taskData.id);
    tasks[taskIndex] = taskData;
    setTasks(tasks);
  }

  function addTask(taskData) {
    tasks.push(taskData);
    setTasks(tasks);
  }

  function submitCreateTaskForm(event) {
    setIsLoading(true);
    event.preventDefault();
    const formData = new FormData(event.target);
    const payload = {};
    for (let [key, value] of formData.entries()) {
      payload[key] = value;
    }

    taskService
        .createTask(payload)
        .then(response => addTask(response.data.data))
        .finally(() => setIsLoading(false))
    ;
    setCreateFormVisible(false);
  }

  return (
      <>
        {isLoading ? (
            <Spinner/>
        ) : (
            <>
              <TaskList tasks={tasks} setTaskStatus={setTaskStatus}
                        setCreateFormVisible={setCreateFormVisible}/>

              {createFormVisible && <Modal title="Create task" onClose={() => setCreateFormVisible(false)}>
                <form onSubmit={submitCreateTaskForm}>
                  <div className="form-group">
                    <input className="form-control"  type="text" placeholder="title" name="title"/>
                  </div>
                  <div className="form-group">
                    <textarea className="form-control"  placeholder="description" name="description"/>
                  </div>
                  <button className="btn btn-success">Create</button>
                </form>
              </Modal>}

              <LoginForm setIsLoading={setIsLoading}/>
            </>
        )}
      </>
  );
}

const $el = document.getElementById("homepage");
ReactDOM.render(
    <Homepage/>,
    $el
);