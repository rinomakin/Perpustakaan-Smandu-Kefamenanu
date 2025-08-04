# API Dokumentasi - Jenis Buku

## Base URL

```
/api/jenis-buku
```

## Endpoints

### 1. Get All Jenis Buku

**GET** `/api/jenis-buku`

**Query Parameters:**

-   `search` (optional): Pencarian berdasarkan nama, kode, atau deskripsi
-   `status` (optional): Filter berdasarkan status (1 = aktif, 0 = tidak aktif)
-   `sort_by` (optional): Field untuk sorting (default: created_at)
-   `sort_order` (optional): Urutan sorting (asc/desc, default: desc)
-   `per_page` (optional): Jumlah data per halaman (default: 10)

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nama_jenis": "Buku Pelajaran",
            "kode_jenis": "BP",
            "deskripsi": "Buku-buku yang digunakan untuk pembelajaran di sekolah",
            "status": true,
            "status_text": "Aktif",
            "jumlah_buku": 5,
            "created_at": "01-01-2024 10:00:00",
            "updated_at": "01-01-2024 10:00:00"
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 1,
        "per_page": 10,
        "total": 1
    }
}
```

### 2. Get Active Jenis Buku Only

**GET** `/api/jenis-buku/active`

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "nama_jenis": "Buku Pelajaran",
            "kode_jenis": "BP",
            "deskripsi": "Buku-buku yang digunakan untuk pembelajaran di sekolah",
            "status": true,
            "status_text": "Aktif",
            "created_at": "01-01-2024 10:00:00",
            "updated_at": "01-01-2024 10:00:00"
        }
    ]
}
```

### 3. Get Single Jenis Buku

**GET** `/api/jenis-buku/{id}`

**Response:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "nama_jenis": "Buku Pelajaran",
        "kode_jenis": "BP",
        "deskripsi": "Buku-buku yang digunakan untuk pembelajaran di sekolah",
        "status": true,
        "status_text": "Aktif",
        "jumlah_buku": 5,
        "created_at": "01-01-2024 10:00:00",
        "updated_at": "01-01-2024 10:00:00",
        "buku": [
            {
                "id": 1,
                "judul": "Matematika Kelas 10",
                "isbn": "978-1234567890",
                "tahun_terbit": 2024,
                "jumlah_halaman": 200,
                "status": true,
                "status_text": "Tersedia",
                "created_at": "01-01-2024 10:00:00",
                "updated_at": "01-01-2024 10:00:00",
                "penulis": {
                    "id": 1,
                    "nama": "John Doe"
                },
                "penerbit": {
                    "id": 1,
                    "nama": "Penerbit ABC"
                }
            }
        ]
    }
}
```

### 4. Create Jenis Buku

**POST** `/api/jenis-buku`

**Request Body:**

```json
{
    "nama_jenis": "Buku Pelajaran",
    "kode_jenis": "BP",
    "deskripsi": "Buku-buku yang digunakan untuk pembelajaran di sekolah",
    "status": true
}
```

**Response:**

```json
{
    "success": true,
    "message": "Data jenis buku berhasil ditambahkan.",
    "data": {
        "id": 1,
        "nama_jenis": "Buku Pelajaran",
        "kode_jenis": "BP",
        "deskripsi": "Buku-buku yang digunakan untuk pembelajaran di sekolah",
        "status": true,
        "status_text": "Aktif",
        "created_at": "01-01-2024 10:00:00",
        "updated_at": "01-01-2024 10:00:00"
    }
}
```

### 5. Update Jenis Buku

**PUT** `/api/jenis-buku/{id}`

**Request Body:**

```json
{
    "nama_jenis": "Buku Pelajaran Updated",
    "kode_jenis": "BP",
    "deskripsi": "Buku-buku yang digunakan untuk pembelajaran di sekolah (updated)",
    "status": true
}
```

**Response:**

```json
{
    "success": true,
    "message": "Data jenis buku berhasil diperbarui.",
    "data": {
        "id": 1,
        "nama_jenis": "Buku Pelajaran Updated",
        "kode_jenis": "BP",
        "deskripsi": "Buku-buku yang digunakan untuk pembelajaran di sekolah (updated)",
        "status": true,
        "status_text": "Aktif",
        "created_at": "01-01-2024 10:00:00",
        "updated_at": "01-01-2024 10:00:00"
    }
}
```

### 6. Delete Jenis Buku

**DELETE** `/api/jenis-buku/{id}`

**Response Success:**

```json
{
    "success": true,
    "message": "Data jenis buku berhasil dihapus."
}
```

**Response Error (jika masih digunakan oleh buku):**

```json
{
    "success": false,
    "message": "Jenis buku tidak dapat dihapus karena masih digunakan oleh 5 buku."
}
```

## Error Responses

### Validation Error

```json
{
    "message": "The given data was invalid.",
    "errors": {
        "nama_jenis": ["Nama jenis buku wajib diisi."],
        "kode_jenis": ["Kode jenis sudah ada."]
    }
}
```

### Not Found Error

```json
{
    "message": "No query results for model [App\\Models\\JenisBuku] 999"
}
```

## Validation Rules

-   `nama_jenis`: required, string, max:255, unique
-   `kode_jenis`: required, string, max:10, unique
-   `deskripsi`: nullable, string, max:500
-   `status`: required, boolean

## Status Codes

-   `200`: Success
-   `201`: Created
-   `422`: Validation Error
-   `404`: Not Found
-   `500`: Server Error
