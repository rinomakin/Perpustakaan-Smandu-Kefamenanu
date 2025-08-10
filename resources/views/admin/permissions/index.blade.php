@extends('layouts.admin')

@section('title', 'Manajemen Hak Akses')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Hak Akses</h1>
                <p class="text-gray-600 mt-1">Kelola hak akses untuk setiap role dengan mudah</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="showBulkAssignModal()" 
                        class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-all duration-200">
                    <i class="fas fa-users-cog mr-2"></i>
                    Bulk Assign
                </button>
                <button onclick="showCopyPermissionsModal()" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all duration-200">
                    <i class="fas fa-copy mr-2"></i>
                    Copy Permissions
                </button>
            </div>
        </div>
    </div>

    <!-- Roles Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
        @foreach($roles as $role)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6">
                <!-- Role Header -->
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $role->nama_peran }}</h3>
                        <p class="text-sm text-gray-600">{{ $role->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $role->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($role->status) }}
                        </span>
                    </div>
                </div>

                <!-- Permission Count -->
                <div class="mb-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Total Hak Akses:</span>
                        <span class="font-medium text-blue-600">{{ $role->permissions->count() }} / {{ $groupedPermissions->flatten()->count() }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-blue-600 h-2 rounded-full" 
                             style="width: {{ $groupedPermissions->flatten()->count() > 0 ? ($role->permissions->count() / $groupedPermissions->flatten()->count() * 100) : 0 }}%"></div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-2">
                    <button onclick="showPermissionModal({{ $role->id }})" 
                            class="flex-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-all duration-200">
                        <i class="fas fa-edit mr-1"></i>
                        Edit Hak Akses
                    </button>
                    <button onclick="resetRolePermissions({{ $role->id }})" 
                            class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-200"
                            title="Reset Semua Hak Akses">
                        <i class="fas fa-undo"></i>
                    </button>
                </div>

                <!-- Quick Preview -->
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <p class="text-xs text-gray-500 mb-2">Preview Hak Akses:</p>
                    <div class="flex flex-wrap gap-1">
                        @php $displayPermissions = $role->permissions->take(3); @endphp
                        @foreach($displayPermissions as $permission)
                        <span class="inline-flex items-center px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded">
                            {{ $permission->name }}
                        </span>
                        @endforeach
                        @if($role->permissions->count() > 3)
                        <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded">
                            +{{ $role->permissions->count() - 3 }} lainnya
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Permission Modal -->
<div id="permissionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Edit Hak Akses</h3>
                    <button type="button" onclick="hidePermissionModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <form id="permissionForm" class="overflow-y-auto max-h-[calc(90vh-180px)]">
                @csrf
                <input type="hidden" id="roleId" name="role_id">
                
                <div class="p-6">
                    <!-- Select All Controls -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <h4 class="font-medium text-gray-900">Kontrol Cepat</h4>
                            <div class="flex gap-2">
                                <button type="button" onclick="selectAllPermissions()" 
                                        class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded transition-colors">
                                    <i class="fas fa-check-double mr-1"></i>
                                    Pilih Semua
                                </button>
                                <button type="button" onclick="deselectAllPermissions()" 
                                        class="px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors">
                                    <i class="fas fa-times mr-1"></i>
                                    Batal Pilih
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Permission Groups -->
                    @foreach($groupedPermissions as $groupName => $permissions)
                    <div class="mb-6">
                        <div class="flex items-center mb-3">
                            <input type="checkbox" id="group_{{ $loop->index }}" 
                                   class="group-checkbox w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                   onchange="toggleGroup('{{ $loop->index }}')">
                            <label for="group_{{ $loop->index }}" class="ml-2 text-sm font-medium text-gray-900">
                                {{ $groupName }}
                            </label>
                            <span class="ml-2 text-xs text-gray-500">({{ $permissions->count() }} hak akses)</span>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 ml-6" data-group="{{ $loop->index }}">
                            @foreach($permissions as $permission)
                            <label class="flex items-start space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-colors">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" 
                                       class="permission-checkbox group-{{ $loop->parent->index }} w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 mt-0.5"
                                       onchange="updateGroupCheckbox('{{ $loop->parent->index }}')">
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900">{{ $permission->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $permission->description }}</div>
                                    <div class="text-xs text-blue-600 mt-1">{{ $permission->slug }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <div class="flex justify-end gap-3 p-6 border-t border-gray-200 bg-gray-50">
                    <button type="button" onclick="hidePermissionModal()" 
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Hak Akses
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Copy Permissions Modal -->
<div id="copyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Copy Hak Akses</h3>
                    <button type="button" onclick="hideCopyModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="copyForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dari Role</label>
                        <select name="from_role_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih role sumber</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->nama_peran }} ({{ $role->permissions->count() }} hak akses)</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ke Role</label>
                        <select name="to_role_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih role tujuan</option>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->nama_peran }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="hideCopyModal()" 
                                class="flex-1 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-all duration-200">
                            <i class="fas fa-copy mr-2"></i>
                            Copy
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Assign Modal -->
<div id="bulkModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Bulk Assign Hak Akses</h3>
                    <button type="button" onclick="hideBulkModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="bulkForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Role</label>
                        <div class="space-y-2 max-h-32 overflow-y-auto border border-gray-200 rounded p-2">
                            @foreach($roles as $role)
                            <label class="flex items-center">
                                <input type="checkbox" name="role_ids[]" value="{{ $role->id }}" 
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <span class="ml-2 text-sm">{{ $role->nama_peran }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Aksi</label>
                        <select name="action" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Pilih aksi</option>
                            <option value="add">Tambah hak akses</option>
                            <option value="replace">Ganti semua hak akses</option>
                            <option value="remove">Hapus hak akses</option>
                        </select>
                    </div>
                    
                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="hideBulkModal()" 
                                class="flex-1 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all duration-200">
                            Batal
                        </button>
                        <button type="submit" 
                                class="flex-1 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition-all duration-200">
                            <i class="fas fa-users-cog mr-2"></i>
                            Proses
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
        <span class="text-gray-700">Memproses...</span>
    </div>
</div>

<script>
// Modal functions
function showPermissionModal(roleId) {
    document.getElementById('roleId').value = roleId;
    showLoading();
    
    fetch(`/admin/permissions/role/${roleId}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            document.getElementById('modalTitle').textContent = `Edit Hak Akses - ${data.role.nama_peran}`;
            
            // Reset all checkboxes
            document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
            document.querySelectorAll('.group-checkbox').forEach(cb => cb.checked = false);
            
            // Check permissions that role has
            data.permission_ids.forEach(permissionId => {
                const checkbox = document.querySelector(`input[name="permissions[]"][value="${permissionId}"]`);
                if (checkbox) {
                    checkbox.checked = true;
                }
            });
            
            // Update group checkboxes
            updateAllGroupCheckboxes();
            
            document.getElementById('permissionModal').classList.remove('hidden');
        } else {
            showErrorAlert(data.message || 'Gagal memuat data role');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showErrorAlert('Terjadi kesalahan saat memuat data');
    });
}

function hidePermissionModal() {
    document.getElementById('permissionModal').classList.add('hidden');
}

function showCopyPermissionsModal() {
    document.getElementById('copyModal').classList.remove('hidden');
}

function hideCopyModal() {
    document.getElementById('copyModal').classList.add('hidden');
}

function showBulkAssignModal() {
    document.getElementById('bulkModal').classList.remove('hidden');
}

function hideBulkModal() {
    document.getElementById('bulkModal').classList.add('hidden');
}

// Permission selection functions
function selectAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = true);
    document.querySelectorAll('.group-checkbox').forEach(cb => cb.checked = true);
}

function deselectAllPermissions() {
    document.querySelectorAll('.permission-checkbox').forEach(cb => cb.checked = false);
    document.querySelectorAll('.group-checkbox').forEach(cb => cb.checked = false);
}

function toggleGroup(groupIndex) {
    const groupCheckbox = document.getElementById(`group_${groupIndex}`);
    const permissionCheckboxes = document.querySelectorAll(`.group-${groupIndex}`);
    
    permissionCheckboxes.forEach(cb => {
        cb.checked = groupCheckbox.checked;
    });
}

function updateGroupCheckbox(groupIndex) {
    const permissionCheckboxes = document.querySelectorAll(`.group-${groupIndex}`);
    const checkedCheckboxes = document.querySelectorAll(`.group-${groupIndex}:checked`);
    const groupCheckbox = document.getElementById(`group_${groupIndex}`);
    
    if (checkedCheckboxes.length === permissionCheckboxes.length) {
        groupCheckbox.checked = true;
        groupCheckbox.indeterminate = false;
    } else if (checkedCheckboxes.length > 0) {
        groupCheckbox.checked = false;
        groupCheckbox.indeterminate = true;
    } else {
        groupCheckbox.checked = false;
        groupCheckbox.indeterminate = false;
    }
}

function updateAllGroupCheckboxes() {
    const groupCheckboxes = document.querySelectorAll('.group-checkbox');
    groupCheckboxes.forEach((checkbox, index) => {
        updateGroupCheckbox(index);
    });
}

// Form submissions
document.getElementById('permissionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const roleId = document.getElementById('roleId').value;
    const submitBtn = document.getElementById('submitBtn');
    const originalText = submitBtn.innerHTML;
    
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
    submitBtn.disabled = true;
    
    fetch(`/admin/permissions/role/${roleId}/update`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessAlert(data.message);
            hidePermissionModal();
            location.reload();
        } else {
            showErrorAlert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorAlert('Terjadi kesalahan saat menyimpan');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

document.getElementById('copyForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/permissions/copy', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessAlert(data.message);
            hideCopyModal();
            location.reload();
        } else {
            showErrorAlert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorAlert('Terjadi kesalahan saat copy permissions');
    });
});

document.getElementById('bulkForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/admin/permissions/bulk-assign', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessAlert(data.message);
            hideBulkModal();
            location.reload();
        } else {
            showErrorAlert(data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorAlert('Terjadi kesalahan saat bulk assign');
    });
});

// Reset role permissions
function resetRolePermissions(roleId) {
    showConfirmDialog(
        'Yakin ingin menghapus semua hak akses untuk role ini?',
        'Konfirmasi Reset Hak Akses',
        function() {
            showLoading();
            
            fetch(`/admin/permissions/role/${roleId}/reset`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showSuccessAlert(data.message);
                    location.reload();
                } else {
                    showErrorAlert(data.message || 'Terjadi kesalahan');
                }
            })
            .catch(error => {
                hideLoading();
                console.error('Error:', error);
                showErrorAlert('Terjadi kesalahan saat reset permissions');
            });
        }
    );
}

function showLoading() {
    document.getElementById('loadingOverlay').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingOverlay').classList.add('hidden');
}
</script>
@endsection
