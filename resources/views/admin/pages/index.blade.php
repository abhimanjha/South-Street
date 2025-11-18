@extends('layouts.admin')

@section('title', 'Pages Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">Pages Management</h1>
                    <p class="text-muted">Manage static pages and content</p>
                </div>
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Add New Page
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-primary">
                        <i class="bi bi-file-earmark-text text-white"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ $totalPages ?? 0 }}</div>
                        <div class="stat-label">Total Pages</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-success">
                        <i class="bi bi-eye text-white"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ $publishedPages ?? 0 }}</div>
                        <div class="stat-label">Published</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-warning">
                        <i class="bi bi-pencil text-white"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ $draftPages ?? 0 }}</div>
                        <div class="stat-label">Drafts</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="stat-card">
                <div class="d-flex align-items-center">
                    <div class="stat-icon bg-info">
                        <i class="bi bi-clock text-white"></i>
                    </div>
                    <div class="ms-3">
                        <div class="stat-value">{{ $recentPages ?? 0 }}</div>
                        <div class="stat-label">This Month</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pages Table -->
    <div class="row">
        <div class="col-12">
            <div class="data-table">
                <div class="table-header">
                    <h5 class="mb-0">All Pages</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control form-control-sm" placeholder="Search pages..." style="width: 200px;">
                        <select class="form-select form-select-sm" style="width: 120px;">
                            <option>All Status</option>
                            <option>Published</option>
                            <option>Draft</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Status</th>
                                <th>Author</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pages ?? [] as $page)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-3">
                                            <h6 class="mb-0">{{ $page->title }}</h6>
                                            <small class="text-muted">{{ Str::limit($page->content ?? '', 50) }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <code class="small">{{ $page->slug }}</code>
                                </td>
                                <td>
                                    @if($page->status === 'published')
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-warning">Draft</span>
                                    @endif
                                </td>
                                <td>{{ $page->author->name ?? 'System' }}</td>
                                <td>{{ $page->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.pages.edit', $page) }}" class="action-btn btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <a href="{{ route('admin.pages.show', $page) }}" class="action-btn btn btn-sm btn-outline-info" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn btn btn-sm btn-outline-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-file-earmark-x display-4 mb-3"></i>
                                        <h5>No pages found</h5>
                                        <p>Get started by creating your first page.</p>
                                        <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                                            <i class="bi bi-plus-circle me-2"></i>Create Page
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($pages) && $pages->hasPages())
                <div class="d-flex justify-content-center p-3">
                    {{ $pages->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
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

.badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.75rem;
    font-weight: 600;
}

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

@media (max-width: 768px) {
    .table-header {
        flex-direction: column;
        gap: 1rem;
        align-items: stretch;
    }

    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endsection
