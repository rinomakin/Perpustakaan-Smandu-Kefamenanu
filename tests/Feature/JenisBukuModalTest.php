<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\JenisBuku;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JenisBukuModalTest extends TestCase
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
    public function admin_can_access_jenis_buku_index_page()
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/jenis-buku');

        $response->assertStatus(200);
        $response->assertViewIs('admin.jenis-buku.index');
    }

    /** @test */
    public function admin_can_get_edit_data_via_ajax()
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
    public function admin_can_create_jenis_buku_via_modal()
    {
        $data = [
            'nama_jenis' => 'Buku Pelajaran',
            'kode_jenis' => 'BP',
            'deskripsi' => 'Buku untuk pembelajaran',
            'status' => true
        ];

        $response = $this->actingAs($this->admin)
            ->post('/admin/jenis-buku', $data);

        $response->assertRedirect('/admin/jenis-buku');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('jenis_buku', $data);
    }

    /** @test */
    public function admin_can_update_jenis_buku_via_modal()
    {
        $jenisBuku = JenisBuku::factory()->create();

        $updateData = [
            'nama_jenis' => 'Updated Jenis',
            'kode_jenis' => 'UPD',
            'deskripsi' => 'Updated deskripsi',
            'status' => false
        ];

        $response = $this->actingAs($this->admin)
            ->put("/admin/jenis-buku/{$jenisBuku->id}", $updateData);

        $response->assertRedirect('/admin/jenis-buku');
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('jenis_buku', $updateData);
    }

    /** @test */
    public function validation_works_for_create_modal()
    {
        $response = $this->actingAs($this->admin)
            ->post('/admin/jenis-buku', []);

        $response->assertSessionHasErrors(['nama_jenis', 'kode_jenis', 'status']);
    }

    /** @test */
    public function validation_works_for_update_modal()
    {
        $jenisBuku = JenisBuku::factory()->create();

        $response = $this->actingAs($this->admin)
            ->put("/admin/jenis-buku/{$jenisBuku->id}", []);

        $response->assertSessionHasErrors(['nama_jenis', 'kode_jenis', 'status']);
    }

    /** @test */
    public function unique_validation_works_for_nama_jenis()
    {
        JenisBuku::factory()->create(['nama_jenis' => 'Test Jenis']);

        $response = $this->actingAs($this->admin)
            ->post('/admin/jenis-buku', [
                'nama_jenis' => 'Test Jenis',
                'kode_jenis' => 'TEST2',
                'status' => true
            ]);

        $response->assertSessionHasErrors(['nama_jenis']);
    }

    /** @test */
    public function unique_validation_works_for_kode_jenis()
    {
        JenisBuku::factory()->create(['kode_jenis' => 'TEST']);

        $response = $this->actingAs($this->admin)
            ->post('/admin/jenis-buku', [
                'nama_jenis' => 'Test Jenis 2',
                'kode_jenis' => 'TEST',
                'status' => true
            ]);

        $response->assertSessionHasErrors(['kode_jenis']);
    }
} 