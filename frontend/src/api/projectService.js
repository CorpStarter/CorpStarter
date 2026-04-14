import apiClient from './axios';

export const getProjects = async () => {
  const response = await apiClient.get('/projects');
  return response.data; 
};

export const createProject = async (projectData) => {
  const response = await apiClient.post('/projects', projectData, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  });
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


export const joinProject = async (projectId) => {
  const response = await apiClient.post(`/projects/${projectId}/join`);
  return response.data;
};

export const getJoinedUsers = async (projectId) => {
  const response = await apiClient.get(`/projects/${projectId}/joined-users`);
  return response.data;
};

export const updateProjectAdmin = async (projectId, statusName, allocatedBudget) => {
  const payload = {};
  if (statusName) payload.status = statusName;
  if (allocatedBudget) payload.allocated_budget = allocatedBudget.toString();
  
  const response = await apiClient.patch(`/admin/projects/${projectId}`, payload);
  return response.data;
};

export const getProjectStatuses = async () => {
  const response = await apiClient.get('/projects/status');
  return response.data;
};