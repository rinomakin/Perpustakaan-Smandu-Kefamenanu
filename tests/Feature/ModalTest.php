<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\JenisBuku;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModalTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::factory()->create([
            'role' => 'admin'
        ]);
    }

    /** @test */
    public function modal_functions_exist_in_javascript()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/jenis-buku');

        $response->assertStatus(200);
        
        // Check if modal HTML exists
        $response->assertSee('crudModal');
        $response->assertSee('openCreateModal');
        $response->assertSee('editJenisBuku');
        $response->assertSee('closeModal');
    }

    /** @test */
    public function edit_endpoint_returns_json()
    {
        $jenisBuku = JenisBuku::factory()->create([
            'nama_jenis' => 'Test Jenis',
            'kode_jenis' => 'TEST',
            'deskripsi' => 'Test deskripsi',
            'status' => true
        ]);

        $response = $this->actingAs($this->admin)
            ->get("/admin/jenis-buku/{$jenisBuku->id}/edit");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'id' => $jenisBuku->id,
                'nama_jenis' => 'Test Jenis',
                'kode_jenis' => 'TEST',
                'deskripsi' => 'Test deskripsi',
                'status' => true
            ]
        ]);
    }

    /** @test */
    public function create_modal_form_has_correct_action()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/jenis-buku');

        $response->assertStatus(200);
        $response->assertSee('action="' . route('jenis-buku.store') . '"');
    }

    /** @test */
    public function modal_has_required_form_fields()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/jenis-buku');

        $response->assertStatus(200);
        
        // Check if form fields exist
        $response->assertSee('modal_nama_jenis');
        $response->assertSee('modal_kode_jenis');
        $response->assertSee('modal_deskripsi');
        $response->assertSee('modal_status');
    }
} 