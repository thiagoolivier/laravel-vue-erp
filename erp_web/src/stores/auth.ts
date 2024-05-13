import { defineStore } from 'pinia';
import { reactive, ref } from 'vue';
import api from '@/plugins/axios';
import type { User } from '@/types/user';

export const useAuthStore = defineStore('auth', () => {
  let user = reactive<User|any>({});

  const isAuthenticated = ref<boolean>(false);
  const lastVerifyTime = ref<number>(0);

  const setToken = (tokenValue: string): void => {
    localStorage.setItem('access_token', tokenValue);
  };

  const setUser = (userData: User): void => {
    localStorage.setItem('user', JSON.stringify(userData));    
    user = userData;
  };

  const getUser = (): User => {    
    return user;
  }; 

  const clearUserData = () => {
    localStorage.removeItem('access_token');
    localStorage.removeItem('user');
    user = { id: 1, name: '', email: '', created_at: '', updated_at: '' };
  };

  const verifyToken = async (): Promise<boolean> => {
    const tempToken = `Bearer ${localStorage.getItem('access_token')}`;

    // if the last time the token was verified is more than 3 minutes ago, we verify it again.
    if ((Date.now() - lastVerifyTime.value) > 180000) {
      try {
        await api('/auth/verify', {
          headers: {
            Authorization: tempToken,
          },
        });
        lastVerifyTime.value = Date.now();
        return true;
      } catch (e) {
        return false;
      }
    }

    return true;
  };

  return {
    isAuthenticated,
    setToken,
    setUser,
    getUser,
    clearUserData,
    verifyToken,
  };
});
