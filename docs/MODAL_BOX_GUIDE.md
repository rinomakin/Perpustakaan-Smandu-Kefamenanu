# Modal Box Implementation Guide - Jenis Buku

## Overview

Implementasi modal box untuk operasi Create dan Edit pada CRUD Jenis Buku menggunakan JavaScript vanilla dan Tailwind CSS.

## Fitur Modal Box

### ✅ Fitur yang Diimplementasi

1. **Create Modal**

    - Form untuk menambah jenis buku baru
    - Validasi client-side dan server-side
    - Loading state saat submit
    - Auto-focus pada input pertama

2. **Edit Modal**

    - Form untuk mengedit jenis buku existing
    - Pre-fill data dari server via AJAX
    - Loading state saat fetch data
    - Validasi yang sama dengan create

3. **UI/UX Features**

    - Responsive design
    - Smooth animations
    - Backdrop blur effect
    - Keyboard navigation (Escape to close)
    - Click outside to close
    - Prevent modal close when clicking inside

4. **Form Validation**
    - Real-time validation
    - Custom error messages
    - Visual feedback (red borders)
    - Required field indicators

## Struktur HTML

### Modal Container

```html
<div
    id="crudModal"
    class="fixed inset-0 z-50 hidden overflow-auto bg-black bg-opacity-50 flex items-center justify-center p-4"
>
    <div
        class="relative bg-white w-full max-w-md rounded-lg shadow-xl animate-fadeIn"
    >
        <!-- Modal Header -->
        <div
            class="flex justify-between items-center p-6 border-b border-gray-200"
        >
            <h2 id="modalTitle" class="text-xl font-semibold text-gray-800">
                Tambah Jenis Buku
            </h2>
            <button
                type="button"
                onclick="closeModal()"
                class="text-gray-400 hover:text-gray-600"
            >
                <!-- Close icon -->
            </button>
        </div>

        <!-- Modal Body -->
        <form
            id="crudForm"
            method="POST"
            action="{{ route('jenis-buku.store') }}"
            class="p-6"
        >
            <!-- Form fields -->
        </form>
    </div>
</div>
```

### Form Fields

```html
<div class="mb-4">
    <label
        for="nama_jenis"
        class="block text-sm font-medium text-gray-700 mb-2"
    >
        Nama Jenis <span class="text-red-500">*</span>
    </label>
    <input
        type="text"
        name="nama_jenis"
        id="nama_jenis"
        required
        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        placeholder="Masukkan nama jenis buku"
    />
    <p id="nama_jenis_error" class="mt-1 text-xs text-red-500 hidden">
        Nama jenis tidak boleh kosong
    </p>
</div>
```

## JavaScript Functions

### 1. openCreateModal()

```javascript
function openCreateModal() {
    // Reset form dan error messages
    resetFormErrors();
    resetSubmitButton();

    // Set modal untuk create
    document.getElementById("modalTitle").textContent = "Tambah Jenis Buku";
    document.getElementById("crudForm").action =
        '{{ route("jenis-buku.store") }}';
    document.getElementById("methodField").innerHTML = "";

    // Reset form
    document.getElementById("crudForm").reset();
    document.getElementById("status").value = "1"; // Default ke Aktif

    // Show modal
    document.getElementById("crudModal").classList.remove("hidden");

    // Focus pada input pertama
    setTimeout(() => document.getElementById("nama_jenis").focus(), 100);
}
```

### 2. editJenisBuku(id)

```javascript
function editJenisBuku(id) {
    // Reset form dan error messages
    resetFormErrors();
    showLoadingState();

    fetch(`/admin/jenis-buku/${id}/edit`)
        .then((response) => {
            if (!response.ok) {
                throw new Error(
                    response.status === 404
                        ? "Data tidak ditemukan"
                        : "Network response was not ok"
                );
            }
            return response.json();
        })
        .then((result) => {
            if (!result.success) {
                throw new Error(result.error || "Terjadi kesalahan");
            }

            const data = result.data;

            // Set modal untuk edit
            document.getElementById("modalTitle").textContent =
                "Edit Jenis Buku";
            document.getElementById(
                "crudForm"
            ).action = `/admin/jenis-buku/${id}`;
            document.getElementById("methodField").innerHTML = '@method("PUT")';

            // Fill form data
            document.getElementById("nama_jenis").value = data.nama_jenis;
            document.getElementById("kode_jenis").value = data.kode_jenis;
            document.getElementById("deskripsi").value = data.deskripsi || "";
            document.getElementById("status").value = data.status.toString();

            // Show modal
            document.getElementById("crudModal").classList.remove("hidden");

            // Focus pada input pertama
            setTimeout(
                () => document.getElementById("nama_jenis").focus(),
                100
            );

            // Reset submit button
            resetSubmitButton();
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Terjadi kesalahan: " + error.message);
            resetSubmitButton();
        });
}
```

### 3. closeModal()

```javascript
function closeModal() {
    document.getElementById("crudModal").classList.add("hidden");
    document.getElementById("crudForm").reset();
    resetFormErrors();
    resetSubmitButton();
}
```

### 4. Form Validation

```javascript
form.addEventListener("submit", function (event) {
    if (isSubmitting) {
        event.preventDefault();
        return false;
    }

    let isValid = true;
    resetFormErrors();

    const namaJenis = namaJenisInput.value.trim();
    const kodeJenis = kodeJenisInput.value.trim();

    // Client-side validation
    if (!namaJenis) {
        event.preventDefault();
        namaJenisInput.classList.add("border-red-500");
        namaJenisError.classList.remove("hidden");
        isValid = false;
    }

    if (!kodeJenis) {
        event.preventDefault();
        kodeJenisInput.classList.add("border-red-500");
        kodeJenisError.classList.remove("hidden");
        isValid = false;
    }

    if (!isValid) {
        event.preventDefault();
        return false;
    }

    // Show loading state
    isSubmitting = true;
    showLoadingState();

    // Allow form submission
    return true;
});
```

## CSS Styling

### Animations

```css
.animate-fadeIn {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}
```

### Modal Improvements

```css
#crudModal {
    backdrop-filter: blur(4px);
}

#crudModal .relative {
    max-height: 90vh;
    overflow-y: auto;
}
```

### Form Focus States

```css
#crudForm input:focus,
#crudForm textarea:focus,
#crudForm select:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
```

## Event Listeners

### Modal Event Listeners

```javascript
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("crudModal");

    // Tutup modal dengan klik di luar modal
    modal.addEventListener("click", function (event) {
        if (event.target === this) {
            closeModal();
        }
    });

    // Tutup modal dengan tombol Escape
    document.addEventListener("keydown", function (event) {
        if (event.key === "Escape" && !modal.classList.contains("hidden")) {
            closeModal();
        }
    });

    // Prevent modal close when clicking inside modal content
    const modalContent = modal.querySelector(".relative");
    modalContent.addEventListener("click", function (event) {
        event.stopPropagation();
    });
});
```

## API Endpoints

### Edit Endpoint

```php
public function edit($id)
{
    try {
        $jenis = JenisBuku::findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $jenis
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Data tidak ditemukan'
        ], 404);
    }
}
```

## Testing

### Manual Testing Checklist

-   [ ] Modal opens when clicking "Tambah Data" button
-   [ ] Modal opens when clicking edit icon
-   [ ] Form validation works (required fields)
-   [ ] Loading state shows during edit data fetch
-   [ ] Form submits successfully for create
-   [ ] Form submits successfully for edit
-   [ ] Modal closes with X button
-   [ ] Modal closes with Escape key
-   [ ] Modal closes when clicking outside
-   [ ] Modal doesn't close when clicking inside
-   [ ] Form resets when modal closes
-   [ ] Error messages display correctly
-   [ ] Success messages show after submit

### Automated Testing

```bash
# Run modal tests
php artisan test --filter=JenisBukuModalTest
```

## Troubleshooting

### Common Issues

1. **Modal tidak muncul**

    - Check if JavaScript is loaded
    - Verify button onclick event
    - Check console for errors

2. **Form tidak submit**

    - Check form action URL
    - Verify CSRF token
    - Check validation errors

3. **Edit data tidak load**

    - Check AJAX request
    - Verify API endpoint
    - Check network tab for errors

4. **Modal tidak close**
    - Check event listeners
    - Verify closeModal function
    - Check CSS classes

### Debug Commands

```javascript
// Check if modal exists
console.log(document.getElementById("crudModal"));

// Check if form exists
console.log(document.getElementById("crudForm"));

// Test modal open
openCreateModal();

// Test modal close
closeModal();
```

## Best Practices

1. **Accessibility**

    - Use proper ARIA labels
    - Ensure keyboard navigation
    - Provide focus management

2. **Performance**

    - Lazy load modal content
    - Debounce form validation
    - Optimize animations

3. **Security**

    - Validate all inputs
    - Use CSRF protection
    - Sanitize data

4. **User Experience**
    - Provide clear feedback
    - Handle errors gracefully
    - Maintain state consistency

## Future Enhancements

1. **Advanced Features**

    - [ ] Auto-save draft
    - [ ] Form history
    - [ ] Bulk operations
    - [ ] Drag and drop

2. **UI Improvements**

    - [ ] Custom animations
    - [ ] Theme support
    - [ ] Mobile optimization
    - [ ] Accessibility enhancements

3. **Functionality**
    - [ ] File uploads
    - [ ] Rich text editor
    - [ ] Image preview
    - [ ] Search suggestions
