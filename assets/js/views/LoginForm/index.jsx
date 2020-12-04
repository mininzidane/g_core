import React, { useState } from "react";
import Modal from "../Modal";
import AuthService from "../../api/AuthService";
import BaseApiService from "../../api/BaseApiService";

function LoginForm({ setIsLoading }) {
  const [loginFormVisible, setLoginFormVisible] = useState(false);
  const authToken = localStorage.getItem(BaseApiService.AUTH_TOKEN_STORAGE_KEY);

  const authService = new AuthService();

  function submitLoginForm(event) {
    setIsLoading(true);
    event.preventDefault();
    const formData = new FormData(event.target);
    const payload = {};
    for (let [key, value] of formData.entries()) {
      payload[key] = value;
    }
    authService.login(payload)
        .then(response => {
          localStorage.setItem(BaseApiService.AUTH_TOKEN_STORAGE_KEY, response.data.token);
          location.reload();
        })
        .finally(() => setIsLoading(false));
    setLoginFormVisible(false);
  }

  return (
      <>
        {(!authToken || loginFormVisible) && <Modal title="Login form" onClose={() => setLoginFormVisible(false)}>
          <form onSubmit={submitLoginForm}>
            <div className="form-group">
              <input className="form-control" type="text" placeholder="username" name="username"/>
            </div>
            <div className="form-group">
              <input className="form-control" type="password" placeholder="password" name="password"/>
            </div>
            <button className="btn btn-success">Login</button>
          </form>
        </Modal>}
      </>
  );
}

export default LoginForm;