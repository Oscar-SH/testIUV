import { DishInterface, RowDishInterface } from "./DishInterface";
import { RowEmployeeInterface } from "./EmployeeInterface";

export interface SaleInterface {
    id?: number;
    table_number: number;
    tip: number;
    id_employee: number;
    dishes: DishInterface[];
}

export interface RowSaleInterface extends SaleInterface {
    id: number;
    created_at: string;
    deleted_at: string;
    updated_at: string;
    dishes: RowDishInterface[];
    employee: RowEmployeeInterface;
}

export const initSaleInterface: SaleInterface = {
    table_number: 0,
    tip: 0,
    id_employee: -1,
    dishes: []
};


export interface SalesExcelInterface {
    employee: string;
    hour: number;
    table_number: number;
    total_tip: number;
    total_sale: number;
    total: number;
}