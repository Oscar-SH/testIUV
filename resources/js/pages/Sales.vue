<script setup lang="ts">
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { ref, onMounted, watch } from 'vue';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import CmpSaleForm from '@/components/sales/CmpSaleForm.vue';
import CmpSalesTable from '@/components/sales/CmpSalesTable.vue';
import { initSaleInterface, RowSaleInterface, SaleInterface } from '@/interfaces/SaleInterface';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter, DialogClose } from '@/components/ui/dialog';

const showAll = ref(false);
const editing = ref(false);
const isModalOpen = ref(false);
const sales = ref<RowSaleInterface[]>([]);
const sale = ref(structuredClone(initSaleInterface));

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Administrar Ventas',
        href: '/sales',
    }
];

const fetchSales = async () => {
    try {
        const res = await axios.get("/sales/all", { params: { deletes: showAll.value } });
        sales.value = res.data;
    } catch (error) {
        console.error('Error fetching sales:', error);
    }
};

const editSale = (emp: SaleInterface) => {
    editing.value = true;
    sale.value = { ...emp };
    isModalOpen.value = true;
};

const resetForm = () => {
    editing.value = false;
    isModalOpen.value = false;
    sale.value = structuredClone(initSaleInterface);
};

const handleSubmit = async () => {
    try {
        if (editing.value) {
            await axios.put(`/sales/${sale.value.id}`, sale.value);
        } else {
            await axios.post("/sales", sale.value);
        }
        resetForm();
        fetchSales();
    } catch (error) {
        console.error('Error saving sale:', error);
    }
};

const openModal = () => {
    isModalOpen.value = true;
};

const handleOpenChange = (open: boolean) => {
    if (!open) resetForm();
    isModalOpen.value = open
};

watch(showAll, fetchSales);

onMounted(() => { fetchSales(); });
</script>

<template>
    <Head title="Ventas" />
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
                            {{ editing ? 'Editar Platillo' : 'Nuevo Platillo' }}
                        </DialogTitle>
                        <DialogDescription>
                            Completa todos los campos requeridos
                        </DialogDescription>
                    </DialogHeader>
                    <CmpSaleForm :sale="sale" :editing="editing" @submit="handleSubmit" />
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
                <CmpSalesTable :sales="sales" :editSale="editSale" :fetchSales="fetchSales" @refetch="fetchSales" />
            </div>
        </div>
    </AppLayout>
</template>