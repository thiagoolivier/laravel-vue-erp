import type { Permission } from "./permission";

export type Role = {
  id: number;
  name: string;
  description: string;
  created_at: string;
  updated_at: string;
};

export type RoleCreateForm = {
  name: string;
  description: string;
};

export type RoleEditForm = {
  id?: number;
  name?: string;
  description?: string;
  permissions?: Array<Permission>;
};
