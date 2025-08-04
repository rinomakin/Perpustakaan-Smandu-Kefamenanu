<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JenisBukuResource;
use App\Models\JenisBuku;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class JenisBukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = JenisBuku::query();

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_jenis', 'like', "%{$search}%")
                  ->orWhere('kode_jenis', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $jenis = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => JenisBukuResource::collection($jenis),
            'pagination' => [
                'current_page' => $jenis->currentPage(),
                'last_page' => $jenis->lastPage(),
                'per_page' => $jenis->perPage(),
                'total' => $jenis->total(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_buku,nama_jenis',
            'kode_jenis' => 'required|string|max:10|unique:jenis_buku,kode_jenis',
            'deskripsi' => 'nullable|string|max:500',
            'status' => 'required|boolean',
        ]);

        $jenis = JenisBuku::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data jenis buku berhasil ditambahkan.',
            'data' => new JenisBukuResource($jenis),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(JenisBuku $jenisBuku): JsonResponse
    {
        $jenisBuku->load('buku');

        return response()->json([
            'success' => true,
            'data' => new JenisBukuResource($jenisBuku),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JenisBuku $jenisBuku): JsonResponse
    {
        $request->validate([
            'nama_jenis' => 'required|string|max:255|unique:jenis_buku,nama_jenis,' . $jenisBuku->id,
            'kode_jenis' => 'required|string|max:10|unique:jenis_buku,kode_jenis,' . $jenisBuku->id,
            'deskripsi' => 'nullable|string|max:500',
            'status' => 'required|boolean',
        ]);

        $jenisBuku->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data jenis buku berhasil diperbarui.',
            'data' => new JenisBukuResource($jenisBuku),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JenisBuku $jenisBuku): JsonResponse
    {
        // Cek apakah jenis buku masih digunakan oleh buku
        if ($jenisBuku->buku()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Jenis buku tidak dapat dihapus karena masih digunakan oleh ' . $jenisBuku->buku()->count() . ' buku.',
            ], 422);
        }

        $jenisBuku->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data jenis buku berhasil dihapus.',
        ]);
    }

    /**
     * Get active jenis buku only.
     */
    public function active(): JsonResponse
    {
        $jenis = JenisBuku::where('status', true)->get();

        return response()->json([
            'success' => true,
            'data' => JenisBukuResource::collection($jenis),
        ]);
    }
} 