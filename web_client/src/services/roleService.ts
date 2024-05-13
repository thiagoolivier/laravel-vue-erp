import api from '@/plugins/axios';
import type { Permission } from '@/types/permission';
import type { Role, RoleCreateForm, RoleEditForm } from '@/types/role';
import { type AxiosPromise } from 'axios';

export const getRoles = async (): Promise<Array<Role>> => {
  try {
    const response = await api.get(`/roles/`);
    return response.data.roles;
  } catch (error) {
    throw error;
  }
};

export const createRole = async (role: RoleCreateForm): Promise<string> => {
  try {
    const response = await api.post('/roles/', {
      name: role.name,
      description: role.description,
    });
    return response.data.message; // Gets the server message for role created.
  } catch (e) {
    throw e;
  }
};

export const showRole = async (id: number): Promise<Role> => {
  try {
    const response = await api.get(`/roles/${id}`);
    return response.data;
  } catch (error) {
    throw error;
  }
};

export const editRole = async (role: RoleEditForm): Promise<string> => {
  try {
    const res = await api.put(`/roles/${role.id}`, {
      name: role.name,
      description: role.description,
    });
    
    return res.data.message;
  } catch (error) {
    throw error;
  }
};

export const deleteRole = async (id: number): Promise<AxiosPromise> => {
  try {
    const res = await api.delete(`/roles/${id}`);
    
    return res;
  } catch (error) {
    throw error;
  }
};

export const getRolePermissions = async (
  id: number
): Promise<Array<Permission>> => {
  try {
    const res = await api.get(`/roles/${id}/permissions`);
    return res.data.permissions;
  } catch (error) {
    throw error;
  }
};

export const editRolePermissions = async (
  roleId: number,
  permissionList: Array<number>
): Promise<string> => {
  try {
    const response = await api.post(`/roles/${roleId}/permissions`, {
      permissions: permissionList,
    });
    return response.data.message; // Gets the server message for permissions synced.
  } catch (e) {    
    throw e;
  }
};
