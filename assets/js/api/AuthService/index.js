import BaseApiService from "../BaseApiService";

class AuthService extends BaseApiService {
  login(payload) {
    return this.post(this.buildRequestPath('login'), payload);
  }
}

export default AuthService;
