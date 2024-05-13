// Permission
export type Permission = {
  id: number;
  name: string;
  description: string;
  created_at?: string;
  updated_at?: string;
};

export type PermissionCreateForm = {
  name: string;
  description: string;
};

export type PermissionEditForm = {
  id?: number;
  name?: string;
  description?: string;
};
