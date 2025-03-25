<script setup lang="ts">
import { defineProps } from 'vue';
import { DishInterface } from '@/interfaces/DishInterface';

defineProps<{
    editing: boolean;
    dish: DishInterface;
}>();

const emit = defineEmits(["submit"]);
const handleSubmit = () => { emit("submit"); };
</script>

<template>
    <form @submit.prevent="handleSubmit" class="modal-container">
        <div class="modal-content">
            <label for="name" class="modal-label">Nombre</label>
            <input required v-model="dish.name" placeholder="Nombre" class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">Descripcion:</label>
            <textarea required v-model="dish.description" placeholder="Descripción" class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">Tiempo de Elaboracion:</label>
            <input required v-model.number="dish.make_time" placeholder="Tiempo de elaboración (minutos)" type="number"
                min="1" class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">Precio de Elaboracion:</label>
            <input required v-model="dish.make_price" placeholder="Precio de Elaboracion" type="number"
                class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">Precio de venta:</label>
            <input required v-model="dish.sale_price" placeholder="Precio de venta" type="number" class="modal-input" />
        </div>
        <button type="submit" class="modal-button-submit">{{ editing ? 'Actualizar' : 'Agregar' }}</button>
    </form>
</template>