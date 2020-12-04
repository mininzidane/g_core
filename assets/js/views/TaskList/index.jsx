import React from "react";

function TaskList({ tasks, setTaskStatus, setCreateFormVisible }) {

  function getRowClassByStatus(status) {
    switch (status) {
      case "done":
        return "table-success";
      case "rejected":
        return "table-danger";
      default:
        return "";
    }
  }

  return (
      <>
        <div>
          <h2>Tasks for today</h2>
          <div className="form-group">
            <button className="btn btn-info btn-lg" onClick={() => setCreateFormVisible(true)}>Create</button>
          </div>
          <table className="table table-striped table-hover">
            <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Description</th>
              <th/>
            </tr>
            </thead>
            <tbody>
            {tasks.map((value, index) => {
              return (
                  <tr key={index} className={getRowClassByStatus(value.status)}>
                    <td>{value.id}</td>
                    <td>{value.title}</td>
                    <td>{value.description}</td>
                    <td>
                      <button className="btn btn-success" onClick={() => setTaskStatus(value.id, 'done')}>It's
                        done
                      </button>
                      <button className="btn btn-danger"
                              onClick={() => setTaskStatus(value.id, "rejected")}>Reject
                      </button>
                    </td>
                  </tr>
              );
            })}
            </tbody>
          </table>
        </div>
      </>
  );
}

export default TaskList;
