export interface EmployeeInterface{
    id: number;
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

export const initEmployeeInterface : EmployeeInterface = {
    id: -1,
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