<script setup lang="ts">
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import CmpEmployeeForm from '@/components/employees/CmpEmployeeForm.vue';
import CmpEmployeeTable from '@/components/employees/CmpEmployeeTable.vue';
import { EmployeeInterface, initEmployeeInterface, RowEmployeeInterface } from '@/interfaces/EmployeeInterface';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter, DialogClose } from '@/components/ui/dialog';

const showAll = ref(false);
const editing = ref(false);
const isModalOpen = ref(false);
const employees = ref<RowEmployeeInterface[]>([]);
const employee = ref(structuredClone(initEmployeeInterface));

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Administrar Empleados',
        href: '/employee',
    }
];

const fetchEmployees = async () => {
    try {
        const res = await axios.get("/employees/all", { params: { deletes: showAll } });
        employees.value = res.data;
    } catch (error) {
        console.error('Error fetching employees:', error);
    }
};

const editEmployee = (emp: EmployeeInterface) => {
    editing.value = true;
    isModalOpen.value = true;
    employee.value = { ...emp };
};

const resetForm = () => {
    editing.value = false;
    isModalOpen.value = false;
    employee.value = structuredClone(initEmployeeInterface);
};

const handleSubmit = async () => {
    try {
        if (editing.value) {
            await axios.put(`/employees/${employee.value.id}`, employee.value);
        } else {
            await axios.post("/employees", employee.value);
        }
        resetForm();
        fetchEmployees();
    } catch (error) {
        console.error('Error saving employee:', error);
    }
};

const openModal = () => {
    isModalOpen.value = true;
};

const handleOpenChange = (open: boolean) => {
    if (!open) resetForm();
    isModalOpen.value = open
}

onMounted(() => {
    fetchEmployees();
});
</script>

<template>

    <Head title="Usuarios" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Dialog v-model:open="isModalOpen" @update:open="handleOpenChange">
                <div>
                    <button @click="openModal" class="bg-blue-500 text-white px-4 py-2 rounded flex">
                        <Plus /> Agregar
                    </button>
                </div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" v-model="showAll" class="sr-only" />
                    <div class="w-10 h-6 bg-gray-300 rounded-full p-1 duration-300 ease-in-out"
                        :class="{ 'bg-green-500': showAll }">
                        <div class="w-4 h-4 bg-white rounded-full shadow-md transform duration-300 ease-in-out"
                            :class="{ 'translate-x-4': showAll }"></div>
                    </div>
                    <span class="ml-2 text-sm text-gray-700">Mostrar inactivos</span>
                </label>
                <DialogContent class="max-h-[90vh] overflow-y-auto">
                    <DialogHeader>
                        <DialogTitle>
                            {{ editing ? 'Editar Empleado' : 'Nuevo Empleado' }}
                        </DialogTitle>
                        <DialogDescription>
                            Completa todos los campos requeridos
                        </DialogDescription>
                    </DialogHeader>
                    <CmpEmployeeForm :employee="employee" :editing="editing" @submit="handleSubmit" />
                    <DialogFooter>
                        <DialogClose asChild>
                            <button @click="resetForm" class="modal-button">
                                Cerrar
                            </button>
                        </DialogClose>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
            <div class="table-container">
                <CmpEmployeeTable :employees="employees" :editEmployee="editEmployee" :fetchEmployees="fetchEmployees"
                    @refetch="fetchEmployees" />
            </div>
        </div>
    </AppLayout>
</template>