import { NavItem } from "@/types";
import { DollarSign, LayoutGrid, User, Utensils } from 'lucide-vue-next';

export const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Usuarios',
        href: '/employees',
        icon: User,
    },
    {
        title: 'Platillos',
        href: '/dashboard',
        icon: Utensils,
    },
    {
        title: 'Ventas',
        href: '/dashboard',
        icon: DollarSign,
    },
];