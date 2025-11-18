<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Admin Panel')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --sidebar-width: 260px;
            --header-height: 70px;
        }
        
        body {
            font-family: "Montserrat", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            overflow-x: hidden;
        }
        
        /* Sidebar Styles */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
            transition: transform 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .admin-sidebar.collapsed {
            transform: translateX(-100%);
        }
        
        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .menu-section-title {
            padding: 1rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(255, 255, 255, 0.5);
        }
        
        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        
        .menu-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
            border-left-color: #3b82f6;
        }
        
        .menu-item.active {
            background: rgba(59, 130, 246, 0.1);
            color: #fff;
            border-left-color: #3b82f6;
        }
        
        .menu-item i {
            width: 24px;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        .menu-badge {
            margin-left: auto;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 10px;
        }
        
        /* Header Styles */
        .admin-header {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--header-height);
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            z-index: 999;
            transition: left 0.3s ease;
        }
        
        .admin-header.expanded {
            left: 0;
        }
        
        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 100%;
            padding: 0 2rem;
        }
        
        .header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        
        .sidebar-toggle:hover {
            background: #f3f4f6;
            color: #1f2937;
        }
        
        .header-search {
            position: relative;
            width: 300px;
        }
        
        .header-search input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            transition: all 0.2s;
        }
        
        .header-search input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .header-search i {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }
        
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .header-icon {
            position: relative;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.5rem;
            color: #6b7280;
            transition: all 0.2s;
            cursor: pointer;
        }
        
        .header-icon:hover {
            background: #f3f4f6;
            color: #1f2937;
        }
        
        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #fff;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .user-menu:hover {
            background: #f3f4f6;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
        }
        
        /* Main Content */
        .admin-main {
            margin-left: var(--sidebar-width);
            margin-top: var(--header-height);
            padding: 2rem;
            min-height: calc(100vh - var(--header-height));
            background: #f9fafb;
            transition: margin-left 0.3s ease;
        }
        
        .admin-main.expanded {
            margin-left: 0;
        }
        
        /* Stats Cards */
        .stat-card {
            background: #fff;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
            transition: all 0.2s;
        }
        
        .stat-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .stat-trend {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.875rem;
            margin-top: 0.5rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }
        
        .stat-trend.up {
            color: #059669;
            background: #d1fae5;
        }
        
        .stat-trend.down {
            color: #dc2626;
            background: #fee2e2;
        }
        
        /* Chart Card */
        .chart-card {
            background: #fff;
            border-radius: 0.75rem;
            padding: 1.5rem;
            border: 1px solid #e5e7eb;
        }
        
        .chart-header {
            display: flex;
            align-items: center;
            justify-content: between;
            margin-bottom: 1.5rem;
        }
        
        .chart-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        /* Table Styles */
        .data-table {
            background: #fff;
            border-radius: 0.75rem;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }
        
        .table-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .table thead th {
            background: #f9fafb;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .table tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
        }
        
        .table tbody tr {
            border-bottom: 1px solid #f3f4f6;
            transition: all 0.2s;
        }
        
        .table tbody tr:hover {
            background: #f9fafb;
        }
        
        /* Badges */
        .badge {
            padding: 0.375rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* Action Buttons */
        .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            transform: scale(1.1);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            
            .admin-sidebar.show {
                transform: translateX(0);
            }
            
            .admin-header {
                left: 0;
            }
            
            .admin-main {
                margin-left: 0;
            }
            
            .header-search {
                display: none;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <i class="bi bi-shop"></i>
                <span>Admin Panel</span>
            </a>
        </div>
        
        <nav class="sidebar-menu">
            <div class="menu-section-title">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="menu-item active">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
            
            <div class="menu-section-title">E-Commerce</div>
            <a href="{{ route('admin.products.index') }}" class="menu-item">
                <i class="bi bi-box-seam"></i>
                <span>Products</span>
                <span class="badge bg-primary menu-badge">{{ $productCount ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="menu-item">
                <i class="bi bi-tags"></i>
                <span>Categories</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="menu-item">
                <i class="bi bi-cart3"></i>
                <span>Orders</span>
                <span class="badge bg-warning menu-badge">{{ $pendingOrders ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.customers.index') }}" class="menu-item">
                <i class="bi bi-people"></i>
                <span>Customers</span>
            </a>
            
            <div class="menu-section-title">Content</div>
            <a href="{{ route('admin.blog.index') }}" class="menu-item">
                <i class="bi bi-file-text"></i>
                <span>Blog Posts</span>
            </a>
            <a href="{{ route('admin.pages.index') }}" class="menu-item">
                <i class="bi bi-file-earmark"></i>
                <span>Pages</span>
            </a>
            <a href="{{ route('admin.media.index') }}" class="menu-item">
                <i class="bi bi-image"></i>
                <span>Media Library</span>
            </a>
            
            <div class="menu-section-title">Settings</div>
            <a href="{{ route('admin.custom-designs.index') }}" class="menu-item">
                <i class="bi bi-palette"></i>
                <span>Custom Designs</span>
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="menu-item">
                <i class="bi bi-star"></i>
                <span>Reviews</span>
            </a>
            <a href="{{ route('admin.coupons.index') }}" class="menu-item">
                <i class="bi bi-ticket-perforated"></i>
                <span>Coupons</span>
            </a>
            <a href="{{ route('admin.notifications.create-discount') }}" class="menu-item">
                <i class="bi bi-bell"></i>
                <span>Send Notifications</span>
            </a>
            <a href="{{ route('admin.custom-tailoring.index') }}" class="menu-item">
                <i class="bi bi-scissors"></i>
                <span>Custom Tailoring</span>
            </a>
        </nav>
    </aside>

    <!-- Header -->
    <header class="admin-header" id="header">
        <div class="header-content">
            <div class="header-left">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="header-search">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
            </div>
            
            <div class="header-right">
                <div class="header-icon">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge"></span>
                </div>
                <div class="header-icon">
                    <i class="bi bi-envelope"></i>
                </div>
                <div class="dropdown">
                    <div class="user-menu" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->guard('admin')->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="d-none d-md-block">
                            <div style="font-size: 0.875rem; font-weight: 600;">{{ auth()->guard('admin')->user()->name ?? 'Admin' }}</div>
                            <div style="font-size: 0.75rem; color: #6b7280;">Administrator</div>
                        </div>
                        <i class="bi bi-chevron-down" style="font-size: 0.75rem; color: #9ca3af;"></i>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.custom-designs.index') }}"><i class="bi bi-palette me-2"></i>Custom Designs</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.reviews.index') }}"><i class="bi bi-star me-2"></i>Reviews</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('admin.logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="admin-main" id="mainContent">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const header = document.getElementById('header');
        const mainContent = document.getElementById('mainContent');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            header.classList.toggle('expanded');
            mainContent.classList.toggle('expanded');
            
            // For mobile
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
            }
        });
        
        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                }
            }
        });
        
        // Active menu item
        const currentPath = window.location.pathname;
        document.querySelectorAll('.menu-item').forEach(item => {
            if (item.getAttribute('href') === currentPath) {
                document.querySelectorAll('.menu-item').forEach(i => i.classList.remove('active'));
                item.classList.add('active');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>