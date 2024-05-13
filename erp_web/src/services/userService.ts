import api from '@/plugins/axios';
import type { Role } from "@/types/role";
import type { User, UserCreateForm, UserEditForm } from '@/types/user';
import { type AxiosPromise } from "axios";

export const createUser = async (user: UserCreateForm): Promise<string> => {
  try {
    const response = await api.post('/users/', {
      name: user.name,
      email: user.email,
      password: user.password,
    });
    
    return response.data.message;
  } catch (error) {
    throw error;
  }
};

export const showUser = async (id: number): Promise<User> => {
  try {
    const response = await api.get(`/users/${id}`);
    return response.data;
  } catch (error) {
    throw error;
  }
};

export const editUser = async (user: UserEditForm): Promise<AxiosPromise> => {
  try {
    const response = await api.put(`/users/${user.id}`, { name: user.name, email: user.email });
    return response;
  } catch (error) {
    throw error;
  }
};

export const deleteUser = async (id: number): Promise<AxiosPromise> => {
  try {
    const response = await api.delete(`/users/${id}`);
    return response;
  } catch (error) {
    throw error;
  }
};

export const getUserRoles = async (id: number): Promise<Role[]> => {
  try {
    const res = await api(`/users/${id}/roles`);
    return res.data.roles;
  } catch (e) {
    throw e;
  }
}

export const editUserRoles = async (user: UserEditForm): Promise<string> => {
  try {
    let roles: number[] | null = [];
    
    if (user.roles) {
      roles = user.roles.map((role) => role.id); // Get the provided roles by id.
    }    

    const response = await api.put(`/users/${user.id}/roles`, {
      roles: roles,
    });
    return response.data.message;
  } catch (error) {
    throw error;
  }
};
