import { RowDishInterface } from "./DishInterface";
import { RowEmployeeInterface } from "./EmployeeInterface";

export interface SaleInterface {
    id?: number;
    table_number: number;
    tip: number;
    id_employee: number;
    id_dish: number;
}

export interface RowSaleInterface extends SaleInterface {
    id: number;
    created_at: string;
    deleted_at: string;
    updated_at: string;
    dish: RowDishInterface;
    employee: RowEmployeeInterface;
}

export const initSaleInterface: SaleInterface = {
    table_number: 0,
    tip: 0,
    id_employee: -1,
    id_dish: -1
};