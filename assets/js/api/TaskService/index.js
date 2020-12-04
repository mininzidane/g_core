import BaseApiService from "../BaseApiService";

class TaskService extends BaseApiService {
  getList() {
    return this.get(this.buildRequestPath('tasks'));
  }

  setStatus(id, status) {
    return this.get(this.buildRequestPath(`tasks/${id}/set-status/${status}`));
  }

  createTask(payload) {
    return this.post(this.buildRequestPath(`tasks`), payload);
  }
}

export default TaskService;
