import type { User } from "@/types/user";

export const getLocalStorageUser = (): User => {
  const user = localStorage.getItem('user');

  if (user) {
    return JSON.parse(user);
  } else {
    return {
      id: 0,
      name: '',
      email: '',
      created_at: '',
      updated_at: '',
      roles: []
    };
  }
};
