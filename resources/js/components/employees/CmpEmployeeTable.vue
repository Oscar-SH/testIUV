<script setup lang="ts">
import axios from 'axios';
import { defineProps } from 'vue';
import { Pencil, Trash, RefreshCcw } from 'lucide-vue-next';
import { EmployeeInterface, RowEmployeeInterface } from '@/interfaces/EmployeeInterface';

const props = defineProps<{
    employees: RowEmployeeInterface[];
    fetchEmployees: () => Promise<void>;
    editEmployee: (emp: EmployeeInterface) => void;
}>();

const emit = defineEmits(['refetch']);

const deleteEmployee = async (id: number) => {
    if (confirm("¿Seguro que quieres eliminar este empleado?")) {
        try {
            await axios.delete(`/employees/${id}`);
            emit('refetch');
            await props.fetchEmployees();
        } catch (error) {
            console.error('Error deleting employee:', error);
        }
    }
};

const restoreEmployee = async (id: number) => {
    if (confirm("¿Seguro que quieres recuperar este empleado?")) {
        try {
            await axios.patch(`/employees/${id}`);
            emit('refetch');
            await props.fetchEmployees();
        } catch (error) {
            console.error('Error restoring employee:', error);
        }
    }
};
</script>

<template>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Puesto</th>
                <th>Sucursal</th>
                <th>Salario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="emp in employees" :key="emp.id"
                :style="emp.deleted_at ? { 'backgroundColor': 'rgba(246, 211, 211, 0.5)' } : {}">
                <td>{{ emp.name }}</td>
                <td>{{ emp.email }}</td>
                <td>{{ emp.position }}</td>
                <td>{{ emp.sucursal }}</td>
                <td>${{ emp.salary.toFixed(2) }}</td>
                <td>
                    <button v-if="!emp.deleted_at" @click="editEmployee(emp)"
                        class="text-blue-600 hover:text-blue-800 mr-2">
                        <Pencil />
                    </button>
                    <button v-if="!emp.deleted_at" @click="deleteEmployee(emp.id)"
                        class="text-red-600 hover:text-red-800">
                        <Trash />
                    </button>
                    <button v-if="emp.deleted_at" @click="restoreEmployee(emp.id)"
                        class="text-blue-600 hover:text-blue-800">
                        <RefreshCcw />
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</template>