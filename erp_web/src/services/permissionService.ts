import api from '@/plugins/axios';
import type { Permission, PermissionCreateForm, PermissionEditForm } from '@/types/permission';
import { type AxiosPromise } from 'axios';

export const getPermissions = async (): Promise<Array<Permission>> => {
  try {
    const response = await api.get(`/permissions/`);    
    return response.data.permissions;
  } catch (error) {
    throw error;
  }
};

export const createPermission = async (permission: PermissionCreateForm): Promise<string> => {
  try {
    const response = await api.post('/permission/', {
      name: permission.name,
      description: permission.description,
    });    
    return response.data.message; // Gets the server message for permission created.
  } catch (e) {
    throw e;
  }
};

export const showPermission = async (id: number): Promise<Permission> => {
  try {
    const response = await api.get(`/permission/${id}`);
    return response.data;
  } catch (error) {
    throw error;
  }
};

export const editPermission = async (permission: PermissionEditForm): Promise<AxiosPromise> => {
  try {
    const response = await api.put(`/permission/${permission.id}`, {
      name: permission.name,
      description: permission.description,
    });
    return response;
  } catch (error) {
    throw error;
  }
};

export const deletePermission = async (id: number): Promise<AxiosPromise> => {
  try {
    const response = await api.delete(`/permission/${id}`);
    return response;
  } catch (error) {
    throw error;
  }
};
