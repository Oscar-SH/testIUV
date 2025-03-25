<script setup lang="ts">
import axios from 'axios';
import { defineProps } from 'vue';
import { Ban, Check, Pencil, Trash, RefreshCcw } from 'lucide-vue-next';
import { DishInterface, RowDishInterface } from '@/interfaces/DishInterface';

const props = defineProps<{
    dishes: RowDishInterface[];
    fetchDishes: () => Promise<void>;
    editDish: (dish: DishInterface) => void;
}>();

const emit = defineEmits(['refetch']);

const deleteDish = async (id: number) => {
    if (confirm("¿Seguro que quieres eliminar este platillo?")) {
        try {
            await axios.delete(`/dishes/${id}`);
            emit('refetch');
            await props.fetchDishes();
        } catch (error) {
            console.error('Error deleting dish:', error);
        }
    }
};

const restoreDish = async (id: number) => {
    if (confirm("¿Seguro que quieres recuperar este platillo?")) {
        try {
            await axios.patch(`/dishes/${id}`);
            emit('refetch');
            await props.fetchDishes();
        } catch (error) {
            console.error('Error restoring dish:', error);
        }
    }
};

const changeStatus = async (id: number, status: string) => {
    if (confirm(`¿Seguro que quieres ${status === 'ACTIVO' ? 'inactivar' : 'activar'} este platillo?`)) {
        try {
            await axios.put(`/dishes/status/${id}`, { status: status === 'ACTIVO' ? 'INACTIVO' : 'ACTIVO' });
            emit('refetch');
            await props.fetchDishes();
        } catch (error) {
            console.error('Error change status at dish:', error);
        }
    }
};
</script>

<template>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripcion</th>
                <th>Tiempo de Elaboracion</th>
                <th>Precio de Elaboracion</th>
                <th>Precio de venta</th>
                <th>Estatus</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="dish in dishes" :key="dish.id"
                :style="dish.deleted_at ? { 'backgroundColor': 'rgba(246, 211, 211, 0.5)' } : {}">
                <td>{{ dish.name }}</td>
                <td>{{ dish.description }}</td>
                <td>{{ dish.make_time }}</td>
                <td>${{ dish.make_price.toFixed(2) }}</td>
                <td>${{ dish.sale_price.toFixed(2) }}</td>
                <td>{{ dish.status }}</td>
                <td>
                    <button v-if="!dish.deleted_at" @click="editDish(dish)"
                        class="text-blue-600 hover:text-blue-800 mr-2">
                        <Pencil />
                    </button>
                    <button v-if="dish.status === 'INACTIVO'" @click="changeStatus(dish.id, dish.status)"
                        class="text-green-600 hover:text-green-800 mr-2">
                        <Check />
                    </button>
                    <button v-if="dish.status === 'ACTIVO'" @click="changeStatus(dish.id, dish.status)"
                        class="text-red-600 hover:text-red-800 mr-2">
                        <Ban />
                    </button>
                    <button v-if="!dish.deleted_at" @click="deleteDish(dish.id)"
                        class="text-red-600 hover:text-red-800">
                        <Trash />
                    </button>
                    <button v-if="dish.deleted_at" @click="restoreDish(dish.id)"
                        class="text-blue-600 hover:text-blue-800">
                        <RefreshCcw />
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</template>