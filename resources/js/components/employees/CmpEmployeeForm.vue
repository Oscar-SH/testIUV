<script setup lang="ts">
import { defineProps } from 'vue';
import { EmployeeInterface } from '@/interfaces/EmployeeInterface';

const props = defineProps<{
    editing: boolean;
    employee: EmployeeInterface;
}>();

const emit = defineEmits(["submit"]);
const handleSubmit = () => { emit("submit"); };

</script>

<template>
    <form @submit.prevent="handleSubmit" class="modal-container">
        <div class="modal-content">
            <label for="name" class="modal-label">Nombre</label>
            <input required v-model="employee.name" class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">Dirección:</label>
            <input required v-model="employee.address" placeholder="Dirección" class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">Teléfono:</label>
            <input required v-model="employee.phone" placeholder="Teléfono" class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">Email:</label>
            <input required v-model="employee.email" placeholder="Email" type="email" class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">Puesto:</label>
            <input required v-model="employee.position" placeholder="Puesto" class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">NSS:</label>
            <input required v-model="employee.nss" placeholder="NSS" type="number" class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">RFC:</label>
            <input required v-model="employee.rfc" placeholder="RFC" class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">Salario:</label>
            <input required v-model="employee.salary" placeholder="Salario" type="number" class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">Sucursal:</label>
            <input required v-model="employee.sucursal" placeholder="Sucursal" class="modal-input" />
        </div>
        <button type="submit" class="modal-button-submit">{{ editing ? 'Actualizar' : 'Agregar' }}</button>
    </form>
</template>