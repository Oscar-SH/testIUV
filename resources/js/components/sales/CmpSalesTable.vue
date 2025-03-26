<script setup lang="ts">
import axios from 'axios';
import moment from 'moment';
import { defineProps } from 'vue';
import { Pencil, Trash, RefreshCcw } from 'lucide-vue-next';
import { RowSaleInterface, SaleInterface } from '@/interfaces/SaleInterface';

const props = defineProps<{
    sales: RowSaleInterface[];
    fetchSales: () => Promise<void>;
    editSale: (emp: SaleInterface) => void;
}>();

const emit = defineEmits(['refetch']);

const deleteSale = async (id: number) => {
    if (confirm("¿Seguro que quieres eliminar esta venta?")) {
        try {
            await axios.delete(`/sales/${id}`);
            emit('refetch');
            await props.fetchSales();
        } catch (error) {
            console.error('Error deleting sale:', error);
        }
    }
};

const restoreSale = async (id: number) => {
    if (confirm("¿Seguro que quieres recuperar esta venta?")) {
        try {
            await axios.patch(`/sales/${id}`);
            emit('refetch');
            await props.fetchSales();
        } catch (error) {
            console.error('Error restoring sale:', error);
        }
    }
};

const calculateTotal = (dishes) => {
    return dishes.reduce((total, dish) => total + parseFloat(dish.sale_price), 0).toFixed(2) ?? 0;
}
</script>

<template>
    <table>
        <thead>
            <tr>
                <th>No. Mesa</th>
                <th>Empleado</th>
                <th>Platillo</th>
                <th>Propina</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="sale in sales" :key="sale.id"
                :style="sale.deleted_at ? { 'backgroundColor': 'rgba(246, 211, 211, 0.5)' } : {}">
                <td>{{ sale.table_number }}</td>
                <td>{{ sale.employee.name }}</td>
                <td>
                    <table class="subtable">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="dish in sale.dishes" :key="dish.id">
                                <td>{{ dish.name }}</td>
                                <td>{{ dish.sale_price }}</td>
                            </tr>
                            <tr class="text-right">
                                <td colspan="2">Total: ${{ calculateTotal(sale.dishes) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td>${{ sale.tip.toFixed(2) }}</td>
                <td>{{ moment(sale.created_at).format('LL').toUpperCase() }}</td>
                <td>{{ moment(sale.created_at).format('HH:ss') }}</td>
                <td>
                    <button v-if="!sale.deleted_at" @click="editSale(sale)"
                        class="text-blue-600 hover:text-blue-800 mr-2">
                        <Pencil />
                    </button>
                    <button v-if="!sale.deleted_at" @click="deleteSale(sale.id)"
                        class="text-red-600 hover:text-red-800">
                        <Trash />
                    </button>
                    <button v-if="sale.deleted_at" @click="restoreSale(sale.id)"
                        class="text-blue-600 hover:text-blue-800">
                        <RefreshCcw />
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</template>