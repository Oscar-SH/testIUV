export interface EmployeeInterface {
    id?: number;
    name: string;
    address: string;
    phone: string;
    email: string;
    position: string;
    nss: number;
    rfc: string;
    salary: number;
    sucursal: string;
}

export interface RowEmployeeInterface extends EmployeeInterface {
    id: number;
    created_at: string;
    deleted_at: string;
    updated_at: string;
}

export const initEmployeeInterface: EmployeeInterface = {
    name: '',
    address: '',
    phone: '',
    email: '',
    position: '',
    nss: 0,
    rfc: '',
    salary: 0,
    sucursal: ''
};