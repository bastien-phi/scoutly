import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';
import ToastData = App.Data.ToastData;

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    environment: string;
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    draftCount: number;
    toast: ToastData | null;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    username: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Paginated<T> {
    data: T[];
    current_page: number;
    last_page: number;
    from: number;
    to: number;
    total: number;
    per_page: number;
    first_page_url: string;
    last_page_url: string;
    prev_page_url: string | null;
    next_page_url: string | null;
    path: string;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}
