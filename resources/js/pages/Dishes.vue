<script setup lang="ts">
import axios from 'axios';
import { ref, onMounted, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import CmpDishForm from '@/components/dishes/CmpDishForm.vue';
import CmpDishesTable from '@/components/dishes/CmpDishesTable.vue';
import { DishInterface, initDishInterface, RowDishInterface } from '@/interfaces/DishInterface';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter, DialogClose } from '@/components/ui/dialog';

const showAll = ref(false);
const editing = ref(false);
const isModalOpen = ref(false);
const dishes = ref<RowDishInterface[]>([]);
const dish = ref(structuredClone(initDishInterface));

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Administrar Platillos',
        href: '/dishes',
    }
];

const fetchDishes = async () => {
    try {
        const res = await axios.get("/dishes/all", { params: { deletes: showAll.value } });
        dishes.value = res.data;
    } catch (error) {
        console.error('Error fetching dishes:', error);
    }
};

const editDish = (emp: DishInterface) => {
    editing.value = true;
    dish.value = { ...emp };
    isModalOpen.value = true;
};

const resetForm = () => {
    editing.value = false;
    isModalOpen.value = false;
    dish.value = structuredClone(initDishInterface);
};

const handleSubmit = async () => {
    try {
        if (editing.value) {
            await axios.put(`/dishes/${dish.value.id}`, dish.value);
        } else {
            await axios.post("/dishes", dish.value);
        }
        resetForm();
        fetchDishes();
    } catch (error) {
        console.error('Error saving dish:', error);
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
    fetchDishes();
});
watch(showAll, () => {
    fetchDishes();
});
</script>

<template>

    <Head title="Platillos" />
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
                    <CmpDishForm :dish="dish" :editing="editing" @submit="handleSubmit" />
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
                <CmpDishesTable :dishes="dishes" :edit-dish="editDish" :fetch-dishes="fetchDishes"
                    @refetch="fetchDishes" />
            </div>
        </div>
    </AppLayout>
</template>