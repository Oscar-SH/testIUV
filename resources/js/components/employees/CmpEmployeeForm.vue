<script setup lang="ts">
import { defineProps } from 'vue';
import { EmployeeInterface } from '@/interfaces/EmployeeInterface';

const props = defineProps<{
    editing: boolean;
    isModalOpen: boolean;
    resetForm: () => void;
    employee: EmployeeInterface;
    handleSubmit: () => Promise<void>;
}>();

const emit = defineEmits<{ (event: 'update:isModalOpen', value: boolean): void; }>();

const closeModal = () => {
    emit('update:isModalOpen', false);
    props.resetForm();
};
</script>

<template>
    <div class=" bg-white p-6 rounded-lg shadow-lg w-96 dark:bg-gray-800 dark:text-white">
        <h2 class="text-xl font-semibold">{{ editing ? 'Editar Empleado' : 'Agregar Empleado' }}</h2>
        <form @submit.prevent="handleSubmit" class="mb-4">
            <label class="modal-label">Nombre:</label>
            <input v-model="employee.name" placeholder="Nombre" class="modal-input" />

            <label class="modal-label">Dirección:</label>
            <input v-model="employee.address" placeholder="Dirección" class="modal-input" />

            <label class="modal-label">Teléfono:</label>
            <input v-model="employee.phone" placeholder="Teléfono" class="modal-input" />

            <label class="modal-label">Email:</label>
            <input v-model="employee.email" placeholder="Email" class="modal-input" />

            <label class="modal-label">Puesto:</label>
            <input v-model="employee.position" placeholder="Puesto" class="modal-input" />

            <label class="modal-label">NSS:</label>
            <input v-model="employee.nss" placeholder="NSS" type="number" class="modal-input" />

            <label class="modal-label">RFC:</label>
            <input v-model="employee.rfc" placeholder="RFC" type="number" class="modal-input" />

            <label class="modal-label">Salario:</label>
            <input v-model="employee.salary" placeholder="Salario" type="number" class="modal-input" />

            <label class="modal-label">Sucursal:</label>
            <input v-model="employee.sucursal" placeholder="Sucursal" class="modal-input" />

            <button type="submit" class="modal-button">{{ editing ? 'Actualizar' : 'Agregar' }}</button>
        </form>
        <button @click="closeModal"
            class="bg-gray-500 text-white px-4 py-2 rounded mt-2 w-full hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-500">
            Cerrar
        </button>
    </div>
</template>