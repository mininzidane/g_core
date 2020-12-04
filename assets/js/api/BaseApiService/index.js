import * as axios from "axios";

class BaseApiService {
  constructor() {
    this.apiBase = '/api/v1/';
    this.httpClient = axios.create({
      headers: {
        common: {
          'X-AUTH-TOKEN': localStorage.getItem(BaseApiService.AUTH_TOKEN_STORAGE_KEY)
        }
      }
    });
  }

  buildRequestPath(requestPath) {
    return this.apiBase + requestPath;
  }

  get(url, params = {}) {
    return this.httpClient.get(url, params);
  }

  post(url, payload = {}) {
    return this.httpClient.post(url, payload);
  }
}

BaseApiService.AUTH_TOKEN_STORAGE_KEY = "authTokenStorageKey";

export default BaseApiService;
