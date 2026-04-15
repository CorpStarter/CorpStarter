import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://127.0.0.1:8000', 
  //baseURL: 'http://corpstarterapi/', 
  headers: {
    'Content-Type': 'application/json',
  },
});

apiClient.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token');
    if (token) {
      config.params = { 
        ...config.params, 
        token: token 
      };
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

export default apiClient;