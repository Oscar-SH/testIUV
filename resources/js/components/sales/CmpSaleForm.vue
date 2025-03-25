<script setup lang="ts">
import axios from 'axios';
import { defineProps, onMounted, ref } from 'vue';
import { SaleInterface } from '@/interfaces/SaleInterface';
import { RowDishInterface } from '@/interfaces/DishInterface';
import { RowEmployeeInterface } from '@/interfaces/EmployeeInterface';

defineProps<{
    editing: boolean;
    sale: SaleInterface;
}>();

const optionsDishes = ref<RowDishInterface[]>([]);
const optionsEmployees = ref<RowEmployeeInterface[]>([]);

const fetchEmployees = async () => {
    try {
        const res = await axios.get("/employees/all", { params: { deletes: false } });
        optionsEmployees.value = res.data;
    } catch (error) {
        console.error('Error fetching employees:', error);
    }
};

const fetchDishes = async () => {
    try {
        const res = await axios.get("/dishes/all", { params: { deletes: false } });
        optionsDishes.value = res.data;
    } catch (error) {
        console.error('Error fetching dishes:', error);
    }
};

const emit = defineEmits(["submit"]);
const handleSubmit = () => { emit("submit"); };

onMounted(() => { fetchEmployees(); fetchDishes(); });
</script>

<template>
    <form @submit.prevent="handleSubmit" class="modal-container">
        <div class="modal-content">
            <label class="modal-label">No. Mesa:</label>
            <input required v-model="sale.table_number" placeholder="No. Mesa" type="number" min="1"
                class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">Empleado:</label>
            <select v-model="sale.id_employee" class="form-select modal-input">
                <option disabled value="">Selecciona una opción</option>
                <option v-for="option in optionsEmployees" :key="option.id" :value="option.id">
                    {{ option.name }}
                </option>
            </select>
        </div>
        <div class="modal-content">
            <label class="modal-label">Platillo:</label>
            <select v-model="sale.id_dish" class="form-select modal-input">
                <option disabled value="">Selecciona una opción</option>
                <option v-for="option in optionsDishes" :key="option.id" :value="option.id">
                    {{ option.name }}
                </option>
            </select>
        </div>
        <div class="modal-content">
            <label class="modal-label">Propina:</label>
            <input required v-model="sale.tip" placeholder="Propina" type="number" min="1" class="modal-input" />
        </div>
        <button type="submit" class="modal-button-submit">{{ editing ? 'Actualizar' : 'Agregar' }}</button>
    </form>
</template>