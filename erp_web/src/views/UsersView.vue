<template>
  <main class="flex justify-center m-2 md:m-4">
    <div class="custom-card">
      <div class="flex justify-between items-center">
        <span class="text-xl font-bold text-secondary">Users</span>
        <CreateBtn @click="openModal(myRefs.isRegisterVisible)">New</CreateBtn>
      </div>
      <Table>
        <template v-slot:t-head>
          <tr class="*:px-4 *:py-2 *:md:px-6 *:md:py-3 text-center">
            <th>
              <span>Name</span>
            </th>
            <th>
              <span>E-mail</span>
            </th>
            <th>
              <span>Created At</span>
            </th>
            <th>
              <span>Last Updated</span>
            </th>
            <th>
              <span>Actions</span>
            </th>
          </tr>
        </template>

        <template v-slot:t-body>
          <tr v-for="(user, index) in users" :key="user.id"
            class="bg-tr-primary even:bg-tr-secondary *:px-2 *:py-1 *:md:px-4 *:md:py-2">
            <td class="text-center md:text-start ">
              {{ user.name }}
            </td>
            <td class="text-start">
              {{ user.email }}
            </td>
            <td>
              <p class="text-start">{{ dateTimeConverter(user.created_at) }}</p>
            </td>
            <td>
              <p class="text-start">{{ dateTimeConverter(user.updated_at) }}</p>
            </td>
            <td v-if="user.id != 1 || user.name != 'Admin'" class="align-middle text-center">
              <div class="flex justify-center items-center">
                <EditBtn @click="openEditModal(user.id)" title="Edit user" />
                <EditUserRoleBtn @click="openEditUserRoleModal(user)" title="Edit user role" />
                <DeleteBtn v-if="loggedUser && (loggedUser.id !== user.id)" @click="confirmDelete(user.id)"
                  title="Delete user" />
              </div>
            </td>
            <td v-else class="align-middle text-center">
              <span>-</span>
            </td>
          </tr>
        </template>
      </Table>
      <!-- <div class="border w-full md:rounded md:p-2 overflow-auto">
        <table class="lg:min-w-[28rem] overflow-auto">
          <thead class="border-b-[1px]">
            <tr class="*:px-4 *:py-1">
              <th>
                <span>Name</span>
              </th>
              <th>
                <span>E-mail</span>
              </th>
              <th>
                <span>Created At</span>
              </th>
              <th>
                <span>Last Updated</span>
              </th>
              <th>
                <span>Actions</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <template v-for="(user, index) in users" :key="user.id">
              <tr class="*:px-4 *:py-1" :class="{ 'bg-[#f7f7f7]': index % 2 === 0, 'bg-white': index % 2 !== 0 }">
                <td class="text-center md:text-start ">
                  {{ user.name }}
                </td>
                <td class="text-start">
                  {{ user.email }}
                </td>
                <td>
                  <p class="text-start">{{ dateTimeConverter(user.created_at) }}</p>
                </td>
                <td>
                  <p class="text-start">{{ dateTimeConverter(user.updated_at) }}</p>
                </td>
                <td class="align-middle text-center" v-if="user.id != 1 || user.name != 'Admin'">
                  <div class="flex justify-center items-center">
                    <EditBtn @click="openEditModal(user.id)" title="Edit user" />
                    <EditUserRoleBtn @click="openEditUserRoleModal(user)" title="Edit user role" />
                    <DeleteBtn v-if="loggedUser && (loggedUser.id !== user.id)" @click="confirmDelete(user.id)"
                      title="Delete user" />
                  </div>
                </td>
                <td v-else class="align-middle text-center">
                  <span>-</span>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div> -->
    </div>

    <!-- user register modal -->
    <Modal :showModal="isRegisterVisible" @close="closeModal(myRefs.isRegisterVisible)">
      <template v-slot:form-title>Create User</template>
      <form @submit.prevent="triggerCreateUser(userCreateForm)">
        <div class="my-4 rounded bg-tertiary p-4 space-y-2">
          <div class="form-group">
            <label for="name">Name</label>
            <input required type="text" id="name" minlength="3" maxlength="50" v-model="userCreateForm.name" />
          </div>
          <div class="form-group">
            <label for="email">E-mail</label>
            <input required type="email" id="email" minlength="3" maxlength="50" v-model="userCreateForm.email" />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input required type="password" id="password" minlength="8" maxlength="24"
              v-model="userCreateForm.password" />
          </div>
        </div>
        <div class="flex justify-end mt-4">
          <button class="green-btn" :disabled="isLoadingModal" type="submit">
            Create
            <svg v-if="isLoadingModal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path fill="currentColor"
                d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
              <path fill="currentColor"
                d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z">
                <animateTransform attributeName="transform" dur="0.75s" repeatCount="indefinite" type="rotate"
                  values="0 12 12;360 12 12" />
              </path>
            </svg>
          </button>
        </div>
      </form>
    </Modal>

    <!-- user edit modal -->
    <Modal :showModal="isEditVisible" @close="closeModal(myRefs.isEditVisible)">

      <template v-slot:form-title>Edit User</template>
      <form @submit.prevent="triggerEditUser">
        <div class="my-4 rounded bg-tertiary p-4 space-y-2">
          <div class="form-group">
            <label for="name">Name</label>
            <input required type="text" id="name" minlength="3" maxlength="50" v-model="userEditForm.name" />
          </div>
          <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" minlength="3" maxlength="50" v-model="userEditForm.email" />
          </div>
        </div>
        <div class="flex justify-end mt-4">
          <button class="green-btn" :disabled="isLoadingModal" type="submit">
            Edit
            <svg v-if="isLoadingModal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path fill="currentColor"
                d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
              <path fill="currentColor"
                d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z">
                <animateTransform attributeName="transform" dur="0.75s" repeatCount="indefinite" type="rotate"
                  values="0 12 12;360 12 12" />
              </path>
            </svg>
          </button>
        </div>
      </form>
    </Modal>

    <!-- user delete confirmation modal -->
    <Modal :showModal="isDeleteVisible" @close="closeModal(myRefs.isDeleteVisible)" :hideCloseBtn="true">
      <div class="flex flex-col gap-6">
        <p class="text-lg text-center">Delete user?</p>
        <div class="flex justify-center gap-4">
          <button @click="deleteConfirmed" class="red-btn" type="button">
            <span class="px-2">Yes</span>
          </button>
          <button @click="cancelDelete" class="green-btn" type="button">
            <span class="px-2">No</span>
          </button>
        </div>
      </div>
    </Modal>

    <!-- User roles edit modal -->
    <Modal :showModal="isEditUserRoleVisible" @close="closeModal(myRefs.isEditUserRoleVisible), userEditForm = {}">

      <template v-slot:form-title>
        <span>
          Edit Roles
          <span class="text-secondary font-thin text-sm">- {{ userEditForm.name }}</span>
        </span>
      </template>
      <form class="mt-4" @submit.prevent="triggerEditUserRoles()">
        <div class="roles-card custom-scrollbar">
          <div class="side-list">
            <span>Available</span>
            <div v-if="availableRolesList.length > 0" class="role-list" v-for="permission in availableRolesList"
              key="permission.id">
              <div class="role">
                <span class="role-span">
                  {{ permission.name }}
                </span>
                <AddBtn @click="setUsingRole(permission)" type="button"/>
              </div>
            </div>
            <div v-else class="flex justify-center items-center w-full h-full">
              <!--If no roles in the available list -->
              <small class="text-gray-500">No roles available.</small>
            </div>
          </div>
          <div class="side-list">
            <span>In Use</span>
            <div v-if="inUseRolesList.length > 0" class="role-list" v-for="permission in inUseRolesList"
              key="permission.id">
              <div class="role">
                <span class="role-span">
                  {{ permission.name }}
                </span>
                <RemoveBtn @click="setAvailableRole(permission)" />
              </div>
            </div>
            <div v-else class="flex justify-center items-center w-full h-full">
              <!--If no roles in the available list -->
              <small class="text-gray-500">No roles added.</small>
            </div>
          </div>
        </div>
        <div class="flex justify-end mt-4">
          <button @click="triggerEditUserRoles()" class="green-btn" :disabled="isLoadingModal" type="submit">
            Update
            <svg v-if="isLoadingModal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <path fill="currentColor"
                d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25" />
              <path fill="currentColor"
                d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z">
                <animateTransform attributeName="transform" dur="0.75s" repeatCount="indefinite" type="rotate"
                  values="0 12 12;360 12 12" />
              </path>
            </svg>
          </button>
        </div>
      </form>
    </Modal>

    <!-- alerts -->
    <CenteredError :isVisible="isErrorVisible" :errorMessage="errorMessageRef" :closeError="closeError" />
    <CenteredSuccess :isVisible="isSuccessVisible" :successMessage="successMessageRef" :closeSuccess="closeSuccess" />
  </main>
</template>

<script setup lang="ts">
import { onMounted, reactive, ref, type Ref } from "vue";
import { dateTimeConverter } from "@/utils/converterUtils";
import { useAuthStore } from "@/stores/auth";
import api from "@/plugins/axios";
import type { User, UserCreateForm, UserEditForm } from '@/types/user';

import EditBtn from "@/components/buttons/EditBtn.vue";
import EditUserRoleBtn from "@/components/buttons/EditUserRoleBtn.vue";
import DeleteBtn from "@/components/buttons/DeleteBtn.vue";
import CreateBtn from "@/components/buttons/CreateBtn.vue";
import AddBtn from "@/components/buttons/AddBtn.vue";
import RemoveBtn from "@/components/buttons/RemoveBtn.vue";
import Modal from "@/components/Modal.vue";
import CenteredError from "@/components/alerts/CenteredError.vue";
import router from "@/router";
import CenteredSuccess from "@/components/alerts/CenteredSuccess.vue";
import { createUser, showUser, editUser, deleteUser, editUserRoles, getUserRoles } from "@/services/userService";
import { showError, showSuccess, closeError, closeSuccess } from "@/utils/alertUtils";
import axios from "axios";
import { getLocalStorageUser } from "@/utils/userUtils";
import type { Role } from "@/types/role";
import { getRoles } from "@/services/roleService";
import Table from "@/components/Table.vue";

const auth = useAuthStore();
const loggedUser = getLocalStorageUser();

const users = reactive<Array<User>>([]);

const getUsers = async () => {
  try {
    const res = await api.get('/users');
    users.length = 0;
    users.push(...res.data.users);
  } catch (e) {
    showError('Server error', errorMessageRef, isErrorVisible);
  }
}

// Create user
const userCreateForm = reactive<UserCreateForm>({
  name: '',
  email: '',
  password: '',
});

const triggerCreateUser = async (user: UserCreateForm) => {
  isLoadingModal.value = true;

  if (!await auth.verifyToken()) {
    router.push('/login');
  } else {
    try {
      const res = await createUser(user); // This function call returns the server message for user creation.      
      showSuccess(res, successMessageRef, isSuccessVisible);
      closeModal(isRegisterVisible);

      userCreateForm.name = '';
      userCreateForm.email = '';
      userCreateForm.password = '';

      getUsers();
    } catch (e) {
      let message: string;

      if (axios.isAxiosError(e)) {
        if (e.response) {
          message = e.response?.data?.message ?? 'Server error.';
        } else if (e.request) {
          message = 'Server error.';
        } else {
          message = 'Unexpected error. Try again later.';
        }

        showError(message, errorMessageRef, isErrorVisible);
      } else {
        showError('Error during user delete.', errorMessageRef, isErrorVisible);
      }
    }
    isLoadingModal.value = false;
  }
}

// Edit user
let userEditForm = reactive<UserEditForm>({
  id: 0,
  name: '',
  email: '',
  roles: [],
});

const triggerEditUser = async () => {
  isLoadingModal.value = true;

  if (!await auth.verifyToken()) {
    router.push('/login');
  } else {
    try {
      const result = await editUser(userEditForm);
      showSuccess(result.data.message, successMessageRef, isSuccessVisible);
      isLoadingModal.value = false;
    } catch (error) {
      isLoadingModal.value = false;
    }
    closeModal(isEditVisible);
  }

  getUsers();
}

// Delete user
const userToDelete = ref<number | null>(null);

const triggerDeleteUser = async (id: number) => {
  isLoadingModal.value = true;

  if (!await auth.verifyToken()) {
    router.push('/login')
  } else {
    if (id) {
      try {
        await deleteUser(id);
        isLoadingModal.value = false;
        showSuccess('User successfully deleted.', successMessageRef, isSuccessVisible);
        getUsers();
      } catch (e) {
        if (axios.isAxiosError(e)) {
          showError(e.response?.data.message, errorMessageRef, isErrorVisible);
        } else {
          showError('Error during user delete.', errorMessageRef, isErrorVisible);
        }
        isLoadingModal.value = false;
      }

      closeModal(isDeleteVisible);
    }
  }
}

const confirmDelete = (id: number): void => {
  userToDelete.value = id;
  isDeleteVisible.value = true;
}

const deleteConfirmed = (): void => {
  if (userToDelete.value && userToDelete.value > 0) {
    triggerDeleteUser(userToDelete.value);
    userToDelete.value = null;
  }
  closeModal(isDeleteVisible);
}

const cancelDelete = (): void => {
  userToDelete.value = null;
  isDeleteVisible.value = false;
}

// User roles
const isEditUserRoleVisible = ref<boolean>(false);

let availableRolesList = ref<Role[]>([]);
let inUseRolesList = ref<Role[]>([]);

const setAvailableRole = (role: Role): void => {
  if (!availableRolesList.value.includes(role)) {
    availableRolesList.value.push(role);
    inUseRolesList.value = inUseRolesList.value.filter((item) => item.id != role.id);
  }
}

const setUsingRole = (role: Role): void => {
  if (!inUseRolesList.value.includes(role)) {
    inUseRolesList.value.push(role);
    availableRolesList.value = availableRolesList.value.filter((item) => item.id != role.id);
  }
}

const openEditUserRoleModal = async (user: UserEditForm) => {
  userEditForm = user; // User to edit

  if (user.id && await auth.verifyToken()) {
    const userRoles = await getUserRoles(user.id);
    const allRoles = await getRoles();
    const availableRoles = checkAvailableRoles(userRoles, allRoles);

    availableRolesList.value = [...availableRoles];
    inUseRolesList.value = [...userRoles];
  }

  openModal(isEditUserRoleVisible);
}

const triggerEditUserRoles = async () => {
  isLoadingModal.value = true;

  if (!auth.verifyToken()) {
    router.push('/login');
  } else {
    try {
      userEditForm.roles = inUseRolesList.value;
      const res = await editUserRoles(userEditForm);
      showSuccess(res, successMessageRef, isSuccessVisible);
    } catch (e) {
      const errorMessage = '';
    }
  }

  isLoadingModal.value = false;
  closeModal(isEditUserRoleVisible);
  userEditForm = {};
}

// Modals
const isRegisterVisible = ref<boolean>(false);
const isEditVisible = ref<boolean>(false);
const isDeleteVisible = ref<boolean>(false);
const isLoadingModal = ref<boolean>(false);

const openModal = (input: Ref): void => { input.value = true };
const closeModal = (input: Ref): void => { input.value = false };

const openEditModal = async (id: number) => {
  if (id) {
    try {
      const userData = await showUser(id);
      userEditForm.id = userData.id;
      userEditForm.email = userData.email;
      userEditForm.name = userData.name;
      openModal(isEditVisible);
    } catch (e) {
      showError('Server error.', errorMessageRef, isErrorVisible);
    }
  } else {
    userEditForm.email = '';
    userEditForm.name = '';
    userEditForm.password = '';
  }
}

// Alerts
const isErrorVisible = ref<boolean>(false);
const isSuccessVisible = ref<boolean>(false);

const errorMessageRef = ref<string>('');
const successMessageRef = ref<string>('');

onMounted(() => {
  getUsers();
})

const myRefs = {
  isRegisterVisible,
  isEditVisible,
  isEditUserRoleVisible,
  isDeleteVisible,
}
defineExpose(myRefs); // Exposes all refs wrapped in a objetc so that the template receives the full ref.

// Utils
const checkAvailableRoles = (rolesUsed: Role[], allPermissions: Role[]): Role[] => {
  const idList = rolesUsed.map((role) => {
    return role.id;
  });

  const result = allPermissions.filter((role) => {
    return !idList.includes(role.id);
  });

  return result;
}
</script>

<style scoped>
.login-btn {
  @apply bg-gray-600 rounded py-1 px-3 border max-w-max block mx-auto font-bold text-white text-lg
}

.user-list {
  @apply border max-w-max mx-auto mt-2 p-2
}

.roles-card {
  @apply flex flex-col md:flex-row justify-center w-full gap-2 mt-4
}

/* .side-list {
  @apply w-full min-w-[16rem] min-h-[20rem] border rounded-md flex flex-col items-center bg-tertiary
} */

.role-title {
  @apply p-2 text-lg
}

.role-list {
  @apply w-full py-1 px-2
}

.role {
  @apply flex items-center gap-2 border border-gray-300 bg-primary rounded-xl p-2 w-full justify-between
}

.role-span {
  @apply flex items-center
}
</style>