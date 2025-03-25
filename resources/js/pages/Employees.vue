<script setup lang="ts">
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Pencil, Trash, Plus } from 'lucide-vue-next';
import CmpEmployeeForm from '@/components/employees/CmpEmployeeForm.vue';
import { EmployeeInterface, initEmployeeInterface } from '@/interfaces/EmployeeInterface';

const editing = ref(false);
const isModalOpen = ref(false);
const employee = ref(initEmployeeInterface);
const employees = ref<EmployeeInterface[]>([]);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Employees',
        href: '/employee',
    },
];

const fetchEmployees = async () => {
    try {
        const res = await axios.get("/employees/all");
        employees.value = res.data;
    } catch (error) {
        console.error('Error fetching employees:', error);
    }
};

const editEmployee = (emp: EmployeeInterface) => {
    employee.value = { ...emp };
    editing.value = true;
};

const deleteEmployee = async (id: number) => {
    if (confirm("¿Seguro que quieres eliminar este empleado?")) {
        try {
            await axios.delete(`/employees/${id}`);
            fetchEmployees();
        } catch (error) {
            console.error('Error deleting employee:', error);
        }
    }
};

const resetForm = () => {
    employee.value = initEmployeeInterface;
    editing.value = false;
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

onMounted(() => {
    fetchEmployees();
});
</script>

<template>

    <Head title="Usuarios" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-between items-center mb-4">
                <button @click="openModal" class="bg-blue-500 text-white px-4 py-2 rounded flex">
                    <Plus /> Agregar Empleado
                </button>
                <h1 class="text-2xl font-bold">Administrar Empleados</h1>
            </div>
            <div v-if="isModalOpen"
                class=" {modal: true} fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
                <CmpEmployeeForm :employee="employee" :editing="editing" v-model:isModalOpen="isModalOpen"
                    :handleSubmit="handleSubmit" :resetForm="resetForm" />
            </div>
            <div
                class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <table class="min-w-full border border-gray-200 rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="py-2 px-4 font-semibold text-sm text-gray-600">Nombre</th>
                            <th class="py-2 px-4 font-semibold text-sm text-gray-600">Email</th>
                            <th class="py-2 px-4 font-semibold text-sm text-gray-600">Puesto</th>
                            <th class="py-2 px-4 font-semibold text-sm text-gray-600">Sucursal</th>
                            <th class="py-2 px-4 font-semibold text-sm text-gray-600">Salario</th>
                            <th class="py-2 px-4 font-semibold text-sm text-gray-600">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="emp in employees" :key="emp.id" class="border-b hover:bg-gray-50">
                            <td class="py-2 px-4">{{ emp.name }}</td>
                            <td class="py-2 px-4">{{ emp.email }}</td>
                            <td class="py-2 px-4">{{ emp.position }}</td>
                            <td class="py-2 px-4">{{ emp.sucursal }}</td>
                            <td class="py-2 px-4">${{ emp.salary.toFixed(2) }}</td>
                            <td class="py-2 px-4">
                                <button @click="editEmployee(emp)" class="text-blue-600 hover:text-blue-800 mr-2">
                                    <Pencil />
                                </button>
                                <button @click="deleteEmployee(emp.id)" class="text-red-600 hover:text-red-800">
                                    <Trash />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>