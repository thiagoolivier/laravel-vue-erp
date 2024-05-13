import type { Role } from "./role";

export type UserData = {
  name: string;
  email: string;
};

// User
export type User = {
  id: number;
  name: string;
  email: string;
  created_at: string;
  updated_at: string;
  roles: Role[];
};

export type UserCreateForm = {
  name: string;
  email: string;
  password: string;
};

export type UserEditForm = {
  id?: number;
  name?: string;
  email?: string;
  password?: string;
  roles?: Role[];
};
