import apiClient from './axios';

export const getProjects = async () => {
  const response = await apiClient.get('/projects');
  return response.data; 
};

export const createProject = async (projectData) => {
  const response = await apiClient.post('/projects', projectData);
  return response.data;
};

export const upvoteProject = async (projectId) => {
  const response = await apiClient.post(`/projects/${projectId}/upvote`);
  return response.data;
};

export const allocateBudget = async (projectId, amount) => {
  const response = await apiClient.post(`/projects/${projectId}/allocate`, { amount: parseFloat(amount) });
  return response.data;
};

export const updateProjectStatus = async (projectId, statusId) => {
  const response = await apiClient.patch(`/projects/${projectId}/status`, { status_id: statusId });
  return response.data;
};

export const updateProject = async (id, projectData) => {
  const response = await apiClient.put(`/projects/${id}`, projectData);
  return response.data;
};

export const deleteProject = async (id) => {
  const response = await apiClient.delete(`/projects/${id}`);
  return response.data;
};