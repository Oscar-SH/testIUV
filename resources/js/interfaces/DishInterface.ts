export interface DishInterface {
    id?: number;
    name: string;
    description: string;
    make_time: string;
    make_price: number;
    sale_price: number;
    status: string;
}

export interface RowDishInterface extends DishInterface {
    id: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;
}

export const initDishInterface: DishInterface = {
    name: '',
    description: '',
    make_time: '',
    make_price: 0,
    sale_price: 0,
    status: 'ACTIVO'
};