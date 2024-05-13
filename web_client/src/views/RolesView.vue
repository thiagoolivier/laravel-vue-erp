<template>
  <main class="flex justify-center m-2 md:m-4">
    <div class="custom-card">
      <div class="flex justify-between items-center">
        <span class="text-xl font-bold text-secondary">Roles</span>
        <CreateBtn @click="openModal(myRefs.isCreateVisible)">New</CreateBtn>
      </div>
      <Table>
        <template v-slot:t-head>
          <tr class="*:px-4 *:py-1 *:md:px-6 *:md:py-3 text-center">
            <th>
              <span>Role</span>
            </th>
            <th>
              <span>Description</span>
            </th>
            <th>
              <span>Actions</span>
            </th>
          </tr>
        </template>

        <template v-slot:t-body>
          <tr v-for="(role, index) in roles" :key="role.id"
            class="bg-tr-primary even:bg-tr-secondary *:px-2 *:py-1 *:md:px-4 *:md:py-2">
            <td>
              <span>{{ role.name }}</span>
            </td>
            <td>
              <p class="text-start">{{ role.description }}</p>
            </td>
            <td v-if="role.id != 1" class="align-middle">
              <div class="flex flex-row justify-center items-center">
                <EditBtn title="Edit role" @click="openEditModal(role)" />
                <EditPermissionBtn title="Edit role permissions" @click="openEditPermissionsModal(role)" />
                <DeleteBtn title="Delete role" @click="confirmDelete(role)" />
              </div>
            </td>
            <template v-else class="space-x-2 min-h-14 align-middle">
              <span>-</span>
            </template>
          </tr>
        </template>
      </Table>
    </div>

    <!-- Role create modal -->
    <Modal :showModal="isCreateVisible" @close="closeModal(myRefs.isCreateVisible)">

      <template v-slot:form-title>Create Role</template>
      <form @submit.prevent="triggerCreateRole(createForm)">
        <div class="my-4 rounded bg-gray-100 p-4 space-y-2">
          <div class="form-group">
            <label for="name">Name</label>
            <input required type="text" id="name" minlength="3" maxlength="50" v-model="createForm.name" />
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" v-model="createForm.description" />
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

    <!-- Role edit modal -->
    <Modal :showModal="isEditVisible" @close="closeModal(myRefs.isEditVisible)">

      <template v-slot:form-title>Edit Role</template>
      <form @submit.prevent="triggerEditRole()">
        <div class="my-4 rounded bg-tertiary p-4 space-y-2">
          <div class="form-group text-secondary">
            <label for="name">Name</label>
            <input required type="text" id="name" minlength="3" maxlength="50" v-model="editForm.name" />
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea maxlength="200" minlength="3" id="description" name="description" rows="4"
              v-model="editForm.description" />
          </div>
        </div>
        <div class="flex justify-end mt-4">
          <button class="green-btn" :disabled="isLoadingModal" type="submit">
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

    <!-- Role permissions edit modal -->
    <Modal :showModal="isEditPermissionsVisible" @close="closeModal(myRefs.isEditPermissionsVisible), editForm = {}">

      <template v-slot:form-title>
        <span>
          Edit Role Permissions
          <span class="text-secondary font-thin text-sm">- {{ editForm.name }}</span>
        </span>
      </template>
      <form class="mt-4" @submit.prevent="triggerEditRolePermissions()">
        <div class="permissions-card custom-scrollbar">
          <div class="flex flex-col gap-2 text-center">
            <span>Available</span>
            <div class="side-list">
              <div v-if="availablePermList.length > 0" class="permission-list custom-scrollbar"
                v-for="permission in availablePermList" key="permission.id">
                <div class="permission">
                  <span class="permission-span">
                    {{ permission.name }}
                  </span>
                  <AddBtn @click="setUsingPermission(permission)" />
                </div>
              </div>
              <div v-else class="flex justify-center items-center w-full h-full">
                <!--If no permissions in the available list -->
                <small class="text-gray-500">No permissions available.</small>
              </div>
            </div>
          </div>
          <div class="flex flex-col gap-2 text-center">
            <span>In Use</span>
            <div class="side-list">
              <div v-if="inUsePermiList.length > 0" class="permission-list" v-for="permission in inUsePermiList"
                key="permission.id">
                <div class="permission">
                  <span class="permission-span">
                    {{ permission.name }}
                  </span>
                  <RemoveBtn @click="setAvailablePermission(permission)" />
                </div>
              </div>
              <div v-else class="flex justify-center items-center w-full h-full">
                <!--If no permissions in the available list -->
                <small class="text-gray-500">No permissions added.</small>
              </div>
            </div>
          </div>
        </div>
        <div class="flex justify-end mt-4">
          <button @click="triggerEditRolePermissions()" class="green-btn" :disabled="isLoadingModal" type="submit">
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

    <!-- user delete confirmation modal -->
    <Modal :showModal="isDeleteVisible" @close="closeModal(myRefs.isDeleteVisible)" :hideCloseBtn="true">
      <div class="flex flex-col gap-6">
        <div class="flex flex-col gap-1">
          <span class="text-md text-center">Are you sure you want to delete the role "{{ roleToDelete?.name }}"?</span>
          <span class="text-md text-center">This action can't be undone.</span>
        </div>
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

    <!-- alerts -->
    <CenteredError :isVisible="isErrorVisible" :errorMessage="errorMessageRef" :closeError="closeError" />
    <CenteredSuccess :isVisible="isSuccessVisible" :successMessage="successMessageRef" :closeSuccess="closeSuccess" />
  </main>
</template>

<script setup lang="ts">
import { type Role, type RoleCreateForm, type RoleEditForm } from "@/types/role";
import { onMounted, reactive, ref } from "vue";
import { closeModal, openModal } from "@/utils/modalUtils";
import { closeError, closeSuccess, showSuccess, showError } from "@/utils/alertUtils";
import DeleteBtn from "@/components/buttons/DeleteBtn.vue";
import EditBtn from "@/components/buttons/EditBtn.vue";
import CreateBtn from "@/components/buttons/CreateBtn.vue";
import router from "@/router";
import axios from "axios";
import Modal from "@/components/Modal.vue";
import { useAuthStore } from "@/stores/auth";
import { createRole, getRoles, deleteRole, editRole, getRolePermissions, editRolePermissions } from "@/services/roleService";
import CenteredError from "@/components/alerts/CenteredError.vue";
import CenteredSuccess from "@/components/alerts/CenteredSuccess.vue";
import EditPermissionBtn from "@/components/buttons/EditPermissionBtn.vue"
import type { Permission } from "@/types/permission";
import { getPermissions } from "@/services/permissionService";
import Table from "@/components/Table.vue";
import AddBtn from "@/components/buttons/AddBtn.vue";
import RemoveBtn from "@/components/buttons/RemoveBtn.vue";

const auth = useAuthStore();
const roles = reactive<Array<Role>>([]);
const isLoadingModal = ref<boolean>(false);

const triggerGetRoles = async () => {
  if (!await auth.verifyToken()) {
    router.push('/login');
  } else {
    const fetchedRoles = await getRoles();
    roles.length = 0;

    if (fetchedRoles) {
      roles.push(...fetchedRoles);
    }
  }
}

// Role creation
const isCreateVisible = ref<boolean>(false);

const createForm = reactive<RoleCreateForm>({
  name: '',
  description: '',
});

const triggerCreateRole = async (role: RoleCreateForm) => {
  isLoadingModal.value = true;

  if (!await auth.verifyToken()) {
    router.push('/login');
  } else {
    try {
      const msg = await createRole(role);
      showSuccess(msg, successMessageRef, isSuccessVisible);
      closeModal(isCreateVisible);
      isLoadingModal.value = false;

      createForm.name = '';
      createForm.description = '';

      triggerGetRoles();
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
        showError('Error during role delete.', errorMessageRef, isErrorVisible);
      }

      isLoadingModal.value = false;
    }
  }
}

// Role edit
let editForm = reactive<RoleEditForm>({});

const isEditVisible = ref<boolean>(false);

const openEditModal = (role: RoleEditForm) => {
  editForm.id = role.id;
  editForm.name = role.name;
  editForm.description = role.description;
  openModal(isEditVisible);
}

const triggerEditRole = async () => {
  isLoadingModal.value = true;

  if (!await auth.verifyToken()) {
    router.push('/login');
  } else {
    try {
      const msg = await editRole(editForm);
      showSuccess(msg, successMessageRef, isSuccessVisible);
    } catch (e) {
      let message = '';

      if (axios.isAxiosError(e)) {
        showError(message, errorMessageRef, isErrorVisible);
      } else {
        showError('Error during role edition.', errorMessageRef, isErrorVisible);
      }
    }
  }
  editForm = {};
  isLoadingModal.value = false;
  closeModal(isEditVisible);
  triggerGetRoles();
}

// Role delete
const roleToDelete = ref<Role | null>(null);
const isDeleteVisible = ref<boolean>(false);

const confirmDelete = (role: Role): void => {
  roleToDelete.value = role;
  isDeleteVisible.value = true;
}

const deleteConfirmed = (): void => {
  if (roleToDelete.value && roleToDelete.value.id > 0) {
    triggerDeleteRole(roleToDelete.value.id);
    roleToDelete.value = null;
  }
  closeModal(isDeleteVisible);
}

const cancelDelete = (): void => {
  roleToDelete.value = null;
  isDeleteVisible.value = false;
}

const triggerDeleteRole = async (roleId: number) => {
  if (!await auth.verifyToken()) {
    router.push('/login');
  } else {
    try {
      await deleteRole(roleId);
      showSuccess('Role successfuly deleted', successMessageRef, isSuccessVisible);
      closeModal(isCreateVisible);
      isLoadingModal.value = false;

      roleToDelete.value = null;

      triggerGetRoles();
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
        showError('Error during role deletion.', errorMessageRef, isErrorVisible);
      }
    }
  }
}

// Role Permissions
const isEditPermissionsVisible = ref<boolean>(false);

let availablePermList = ref<Permission[]>([]);
let inUsePermiList = ref<Permission[]>([]);

const setAvailablePermission = (permission: Permission): void => {
  if (!availablePermList.value.includes(permission)) {
    availablePermList.value.push(permission);
    inUsePermiList.value = inUsePermiList.value.filter((item) => item.id != permission.id);
  }
}

const setUsingPermission = (permission: Permission): void => {
  if (!inUsePermiList.value.includes(permission)) {
    inUsePermiList.value.push(permission);
    availablePermList.value = availablePermList.value.filter((item) => item.id != permission.id);
  }
}

const openEditPermissionsModal = async (role: RoleEditForm) => {
  editForm = role;

  if (role.id && await auth.verifyToken()) {
    const rolePermissions = await getRolePermissions(role.id);
    const allPermissions = await getPermissions();
    const availablePermissions = checkAvailablePermissions(rolePermissions, allPermissions);

    availablePermList.value = [...availablePermissions];
    inUsePermiList.value = [...rolePermissions];
  }

  openModal(isEditPermissionsVisible);
}

const triggerEditRolePermissions = async () => {
  isLoadingModal.value = true;

  if (!auth.verifyToken()) {
    router.push('/login');
  } else {
    const rolePermissionIds: number[] = inUsePermiList.value.map((permission) => {
      return permission.id;
    });

    try {
      if (editForm.id) {
        const res = await editRolePermissions(editForm.id, rolePermissionIds);
        showSuccess(res, successMessageRef, isSuccessVisible);
      } else {
        throw Error("Can't find an role to update.");
      }
    } catch (e) {
      if (axios.isAxiosError(e)) {
        if (e.response?.data.errors) {
          const message = e.response?.data?.errors.permissions.reduce((acc: string, error: string) => {
            acc.concat(`${error} `);
          });
          showError(message, errorMessageRef, isErrorVisible);
        } else {
          showError(e.message, errorMessageRef, isErrorVisible);
        }
      } else {
        showError('Error updating role permissions.', errorMessageRef, isErrorVisible);
      }
    }
  }
  editForm = {};
  availablePermList.value = [];
  inUsePermiList.value = [];

  closeModal(isEditPermissionsVisible);
  isLoadingModal.value = false;
}

//Alerts
const isErrorVisible = ref<boolean>(false);
const isSuccessVisible = ref<boolean>(false);
const errorMessageRef = ref<boolean>(false);
const successMessageRef = ref<boolean>(false);

onMounted(() => {
  triggerGetRoles();
})

// Exposing refs
const myRefs = {
  isCreateVisible,
  isEditVisible,
  isEditPermissionsVisible,
  isDeleteVisible,
};

defineExpose({
  myRefs,
})

// Utils
const checkAvailablePermissions = (permissionsUsed: Permission[], allPermissions: Permission[]): Permission[] => {
  const idList = permissionsUsed.map((permission: Permission) => {
    return permission.id;
  });

  const result = allPermissions.filter((permission: Permission) => {
    return !idList.includes(permission.id);
  });

  return result;
}
</script>

<style scoped>
.permissions-card {
  @apply flex flex-col md:flex-row justify-center w-full gap-2 mt-4
}

.permission-title {
  @apply inline-block text-lg w-full text-center
}

.permission-list {
  @apply w-full py-1 px-2
}

.permission {
  @apply flex items-center gap-2 border border-gray-300 bg-primary rounded-xl p-2 w-full justify-between
}

.permission-span {
  @apply flex items-center
}
</style>