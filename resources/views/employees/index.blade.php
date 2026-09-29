<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-indigo-700 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <i class="fa-solid fa-users-gear text-2xl text-indigo-200"></i>
                    <span class="font-bold text-xl tracking-tight">Employee Management System</span>
                </div>
                <button onclick="openModal()" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium shadow transition flex items-center space-x-2">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Add New Employee</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex-1">

        <!-- Flash Message Banners -->
        @if(session('success'))
            <div id="flash-success" class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between transition-all">
                <div class="flex items-center space-x-3 text-emerald-800">
                    <i class="fa-solid fa-circle-check text-xl text-emerald-500"></i>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
                <button onclick="document.getElementById('flash-success').remove()" class="text-emerald-500 hover:text-emerald-800 text-lg font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div id="flash-error" class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-lg shadow-sm flex items-center justify-between">
                <div class="flex items-center space-x-3 text-rose-800">
                    <i class="fa-solid fa-triangle-exclamation text-xl text-rose-500"></i>
                    <span class="font-medium text-sm">{{ session('error') }}</span>
                </div>
                <button onclick="document.getElementById('flash-error').remove()" class="text-rose-500 hover:text-rose-800 text-lg font-bold">&times;</button>
            </div>
        @endif

        <!-- Stats Bar -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Employees</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $employees->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-50 rounded-lg flex items-center justify-center text-indigo-600 text-xl">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Active Employees</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $activeCount }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-600 text-xl">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Departments</p>
                    <p class="text-2xl font-bold text-slate-800 mt-1">{{ $departments->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center text-amber-600 text-xl">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Avg Salary</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1">${{ number_format($avgSalary, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600 text-xl">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form method="GET" action="{{ route('employees.index') }}" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-64">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-3 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="pl-9 pr-4 py-2 border border-slate-300 rounded-lg w-full text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <select name="department_id" class="px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>On Leave</option>
                </select>

                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'department_id', 'status']))
                    <a href="{{ route('employees.index') }}" class="text-slate-500 hover:text-slate-700 text-sm font-medium">Reset</a>
                @endif
            </form>
        </div>

        <!-- Employee Table -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 text-slate-600 text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3.5 text-left">Employee</th>
                        <th class="px-6 py-3.5 text-left">Department</th>
                        <th class="px-6 py-3.5 text-left">Designation</th>
                        <th class="px-6 py-3.5 text-left">Salary</th>
                        <th class="px-6 py-3.5 text-left">Status</th>
                        <th class="px-6 py-3.5 text-left">Joined Date</th>
                        <th class="px-6 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-sm">
                    @forelse($employees as $employee)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center mr-3">
                                        {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $employee->full_name }}</div>
                                        <div class="text-slate-500 text-xs">{{ $employee->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-800">
                                    {{ $employee->department->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-700">{{ $employee->designation }}</td>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-900">${{ number_format($employee->salary, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($employee->status === 'active')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5"></span> Active
                                    </span>
                                @elseif($employee->status === 'on_leave')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5"></span> On Leave
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 bg-slate-400 rounded-full mr-1.5"></span> Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-slate-500 text-xs">{{ $employee->joined_date?->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete employee {{ $employee->full_name }}?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:text-rose-900 p-1.5 rounded hover:bg-rose-50 transition" title="Delete">
                                        <i class="fa-regular fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                <i class="fa-regular fa-folder-open text-4xl text-slate-300 mb-2"></i>
                                <p class="text-base font-medium">No employees found.</p>
                                <p class="text-xs text-slate-400 mt-1">Click "Add New Employee" to save a new record.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $employees->withQueryString()->links() }}
            </div>
        </div>
    </main>

    <!-- Save Employee Modal -->
    <div id="employeeModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-xl w-full overflow-hidden transform transition-all">
            <div class="bg-indigo-700 px-6 py-4 flex items-center justify-between text-white">
                <h3 class="font-bold text-lg"><i class="fa-solid fa-user-plus mr-2"></i> Save New Employee</h3>
                <button onclick="closeModal()" class="text-indigo-200 hover:text-white text-xl">&times;</button>
            </div>
            
            <form action="{{ route('employees.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">First Name *</label>
                        <input type="text" name="first_name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Last Name *</label>
                        <input type="text" name="last_name" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Email Address *</label>
                        <input type="email" name="email" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Phone Number</label>
                        <input type="text" name="phone" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Department *</label>
                        <select name="department_id" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Designation *</label>
                        <input type="text" name="designation" placeholder="e.g. Software Engineer" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Salary ($) *</label>
                        <input type="number" step="0.01" min="0" name="salary" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Status *</label>
                        <select name="status" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="on_leave">On Leave</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Joined Date *</label>
                        <input type="date" name="joined_date" value="{{ date('Y-m-d') }}" required class="w-full px-3 py-2 border border-slate-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-slate-50">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium shadow">Save Employee</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('employeeModal').classList.remove('hidden');
            document.getElementById('employeeModal').classList.add('flex');
        }
        function closeModal() {
            document.getElementById('employeeModal').classList.add('hidden');
            document.getElementById('employeeModal').classList.remove('flex');
        }

        // Auto-hide success alert after 4 seconds
        setTimeout(() => {
            const el = document.getElementById('flash-success');
            if(el) el.remove();
        }, 4000);
    </script>
</body>
</html>
