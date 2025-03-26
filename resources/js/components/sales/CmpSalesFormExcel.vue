<script setup lang="ts">
import axios from 'axios';
import moment from 'moment';
import ExcelJS from "exceljs";
import { saveAs } from "file-saver";
import { onMounted, ref } from 'vue';
import { SalesExcelInterface } from '@/interfaces/SaleInterface';
import { RowEmployeeInterface } from '@/interfaces/EmployeeInterface';

const props = defineProps<{ resetForm: () => void; }>();

const catTables = ref<{ table_number: number }[]>([]);
const optionsEmployees = ref<RowEmployeeInterface[]>([]);
const params = ref({ id_employee: -1, table_number: -1, date: moment().format('YYYY-MM-DD') });

const fetchTables = async () => {
    try {
        const res = await axios.get("/sales/tables");
        catTables.value = res.data;
    } catch (error) {
        console.error('Error fetching sales:', error);
    }
};

const fetchEmployees = async () => {
    try {
        const res = await axios.get("/employees/all", { params: { deletes: false } });
        optionsEmployees.value = res.data;
    } catch (error) {
        console.error('Error fetching employees:', error);
    }
};

const exportToExcel = async () => {
    try {
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet("Reporte de Ventas");

        const data = ref<SalesExcelInterface[]>([]);

        const getdata = async () => {
            try {
                const res = await axios.get("/sales/excel", { params: { ...params.value } });
                data.value = res.data;
            } catch (error) {
                console.error('Error fetching employees:', error);
            }
        };

        await getdata();

        worksheet.columns = [
            { header: "Empleado", key: "employee" },
            { header: "Hora", key: "hour" },
            { header: "Mesa", key: "table_number" },
            { header: "Propinas", key: "total_tip" },
            { header: "Ventas", key: "total_sale" },
            { header: "Total", key: "total" }
        ];

        worksheet.addRows(data.value);
        worksheet.getRow(1).eachCell((cel) => [cel.font = { bold: true }, cel.alignment = { horizontal: 'center', vertical: 'middle' }]);
        workbook.xlsx.writeBuffer().then((buffer) => {
            saveAs(new Blob([buffer]), "Reporte de ventas.xlsx");
        });
        props.resetForm();
    } catch (error) {
        console.error(error, "Error at make excel");
    }
};

onMounted(() => {
    fetchTables();
    fetchEmployees();
});
</script>

<template>
    <form @submit.prevent="exportToExcel" class="modal-container">
        <div class="modal-content">
            <label class="modal-label">Fecha:</label>
            <input type="date" v-model="params.date" class="modal-input" />
        </div>
        <div class="modal-content">
            <label class="modal-label">No. Mesa:</label>
            <select v-model="params.table_number" class="form-select modal-input">
                <option disabled value="">Selecciona una opción</option>
                <option v-for="option in catTables" :key="option.table_number" :value="option.table_number">
                    {{ option.table_number }}
                </option>
            </select>
        </div>
        <div class="modal-content">
            <label class="modal-label">Empleado:</label>
            <select v-model="params.id_employee" class="form-select modal-input">
                <option disabled value="">Selecciona una opción</option>
                <option v-for="option in optionsEmployees" :key="option.id" :value="option.id">
                    {{ option.name }}
                </option>
            </select>
        </div>
        <button type="submit" class="modal-button-submit">Generar</button>
    </form>
</template>