<script setup lang="ts">
import axios from "axios";
import { Canvg } from "canvg";
import moment from "moment";
import { ChartLine } from "lucide-vue-next";
import VueApexCharts from "vue3-apexcharts";
import { onMounted, ref, watch } from "vue";
import CmpSalesFormExcel from "./CmpSalesFormExcel.vue";
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter, DialogClose } from '@/components/ui/dialog';

const isModalOpen = ref(false);
const date = ref(moment().format('YYYY-MM-DD'));
const data = ref({ series: [], categories: [] });

const chartOptions = ref({
    chart: { id: "ventas-chart", zoom: { enabled: false } },
    colors: ['#77B6EA', '#545454'],
    dataLabels: { enabled: true },
    stroke: { curve: 'smooth' },
    title: { text: 'Ventas por hora', align: 'left' },
    grid: {
        borderColor: '#e7e7e7',
        row: { colors: ['#f3f3f3', 'transparent'], opacity: 0.5 },
    },
    xaxis: {
        categories: data.value.categories,
        title: { text: 'HORA' }
    }
});

const fetchData = async () => {
    try {
        const res = await axios.get("/sales/graph", { params: { date: date.value } });
        data.value = res.data;
        chartOptions.value.xaxis.categories = res.data.categories;
    } catch (error) {
        console.error('Error fetching sales:', error);
    }
};

const resetForm = () => {
    isModalOpen.value = false;
};

const openModal = () => {
    isModalOpen.value = true;
};

const handleOpenChange = (open: boolean) => {
    if (!open) resetForm();
    isModalOpen.value = open
};

onMounted(() => {
    fetchData();
});

watch(date, () => {
    fetchData();
});
</script>

<template>
    <div class="modal-container px-2">
        <div class="modal-content">
            <label for="dateInput" class="modal-label">Selecciona una fecha:</label>
            <input id="dateInput" type="date" v-model="date" class="input-date border p-2 rounded " />
            <Dialog v-model:open="isModalOpen" @update:open="handleOpenChange">
                <button @click="openModal" class="bg-blue-500 text-white px-4 py-2 rounded flex">
                    <ChartLine class="mr-2" />
                    <b>Generar Excel</b>
                </button>
                <DialogContent class="max-h-[90vh] overflow-y-auto">
                    <DialogHeader>
                        <DialogTitle>
                            Generar Excel
                        </DialogTitle>
                        <DialogDescription>
                            Completa todos los campos requeridos
                        </DialogDescription>
                    </DialogHeader>
                    <CmpSalesFormExcel :resetForm="resetForm"/>
                    <DialogFooter>
                        <DialogClose asChild>
                            <button @click="resetForm" class="modal-button">
                                Cerrar
                            </button>
                        </DialogClose>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </div>
    <VueApexCharts type="line" height="350" :options="chartOptions" :series="data.series"></VueApexCharts>
</template>