import { NavFooter } from '@/components/nav-footer';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarGroup,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuAction,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { routeMatches } from '@/lib/utils';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { GitPullRequest, LayoutGrid, Link as LinkIcon, Plus } from 'lucide-react';
import AppLogo from './app-logo';

const footerNavItems: NavItem[] = [
    {
        title: 'Show me the code',
        href: 'https://github.com/bastien-phi/scoutly',
        icon: GitPullRequest,
    },
];

export function AppSidebar() {
    const page = usePage();

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={route('dashboard')} prefetch>
                                <AppLogo className="h-12! w-auto!" />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <SidebarGroup className="px-2 py-0">
                    <SidebarMenu>
                        <SidebarMenuItem key="Dashboard">
                            <SidebarMenuButton asChild isActive={routeMatches(page, 'dashboard')} tooltip={{ children: 'Dashboard' }}>
                                <Link href={route('dashboard')} prefetch>
                                    <LayoutGrid />
                                    <span>Dashboard</span>
                                </Link>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroup>
                <SidebarGroup className="px-2 py-0">
                    <SidebarGroupLabel>Links</SidebarGroupLabel>
                    <SidebarMenu>
                        <SidebarMenuItem key="My links">
                            <SidebarMenuButton asChild isActive={routeMatches(page, 'links.index')} tooltip={{ children: 'My links' }}>
                                <Link href={route('links.index')} prefetch>
                                    <LinkIcon />
                                    <span>My links</span>
                                </Link>
                            </SidebarMenuButton>
                            <SidebarMenuAction>
                                <Link href={route('links.create')} prefetch>
                                    <Plus size={18} />
                                </Link>
                            </SidebarMenuAction>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroup>
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
