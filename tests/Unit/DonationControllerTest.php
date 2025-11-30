<?php

namespace Tests\Unit;

use App\Models\Donation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DonationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat role dan user sesuai pola seeder yang kamu kirim
        $adminRole = Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        $this->admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role_id'  => $adminRole->id,
        ]);
    }

    /**
     * Data valid standar untuk Donation
     */
    protected function validData(array $overrides = []): array
    {
        return array_merge([
            'title'         => 'Donasi Korban Banjir',
            'description'   => 'Bantu korban banjir di Malang.',
            'target_amount' => 500000, // >= 100000
            'end_date'      => now()->addDays(7)->toDateString(), // lulus after:today
            'status'        => 'active',
        ], $overrides);
    }

    /** @test */
    public function index_menampilkan_daftar_donasi()
    {
        Donation::create($this->validData(['title' => 'Donasi A']));
        Donation::create($this->validData(['title' => 'Donasi B']));

        $response = $this->actingAs($this->admin)
            ->get(route('admin.donations.index'));

        $response->assertStatus(200)
            ->assertViewIs('admin.donations.index')
            ->assertViewHas('donations');

        $response->assertSee('Donasi A');
        $response->assertSee('Donasi B');
    }

    /** @test */
    public function index_bisa_filter_berdasarkan_status_dan_search()
    {
        Donation::create($this->validData([
            'title'  => 'Banjir Malang',
            'status' => 'active',
        ]));

        Donation::create($this->validData([
            'title'  => 'Gempa Lombok',
            'status' => 'inactive',
        ]));

        $response = $this->actingAs($this->admin)
            ->get(route('admin.donations.index', [
                'status' => 'active',
                'search' => 'Banjir',
            ]));

        $response->assertStatus(200);
        $response->assertSee('Banjir Malang');
        $response->assertDontSee('Gempa Lombok');
    }

    /** @test */
    public function create_menampilkan_form_donasi_baru()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.donations.create'));

        $response->assertStatus(200)
            ->assertViewIs('admin.donations.create');
    }

    /** @test */
    public function store_menyimpan_donasi_baru_tanpa_gambar()
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.donations.store'), $this->validData());

        $response->assertRedirect(route('admin.donations.index'))
            ->assertSessionHas('success', 'Donasi berhasil dibuat.');

        $this->assertDatabaseCount('donations', 1);

        $this->assertDatabaseHas('donations', [
            'title'         => 'Donasi Korban Banjir',
            'target_amount' => 500000,
            'status'        => 'active',
        ]);
    }

    /** @test */
    public function store_menyimpan_donasi_baru_dengan_gambar()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('gambar.jpg');

        $data = $this->validData([
            'image' => $file,
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.donations.store'), $data);

        $response->assertRedirect(route('admin.donations.index'));

        $donation = Donation::first();

        $this->assertNotNull($donation->image);
        $this->assertTrue(
            Storage::disk('public')->exists($donation->image),
            'File gambar donation seharusnya ada di disk public.'
        );
    }

    /** @test */
    public function store_validasi_gagal_jika_data_tidak_valid()
    {
        $response = $this->actingAs($this->admin)
            ->from(route('admin.donations.create'))
            ->post(route('admin.donations.store'), [
                'title'         => '', // required
                'description'   => '', // required
                'target_amount' => 50000, // < 100000
                'end_date'      => now()->subDay()->toDateString(), // before today (gagal after:today)
                'status'        => 'salah', // bukan active/inactive
            ]);

        $response->assertRedirect(route('admin.donations.create'));
        $response->assertSessionHasErrors([
            'title',
            'description',
            'target_amount',
            'end_date',
            'status',
        ]);

        $this->assertDatabaseCount('donations', 0);
    }

    /** @test */
    public function show_menampilkan_detail_donasi()
    {
        $donation = Donation::create($this->validData());

        $response = $this->actingAs($this->admin)
            ->get(route('admin.donations.show', $donation));

        $response->assertStatus(200)
            ->assertViewIs('admin.donations.show')
            ->assertViewHas('donation', function ($d) use ($donation) {
                return $d->id === $donation->id;
            })
            ->assertViewHas('transactions');
    }

    /** @test */
    public function edit_menampilkan_form_edit_donasi()
    {
        $donation = Donation::create($this->validData());

        $response = $this->actingAs($this->admin)
            ->get(route('admin.donations.edit', $donation));

        $response->assertStatus(200)
            ->assertViewIs('admin.donations.edit')
            ->assertViewHas('donation', function ($d) use ($donation) {
                return $d->id === $donation->id;
            });
    }

    /** @test */
    public function update_mengubah_data_donasi_tanpa_mengganti_gambar()
    {
        $donation = Donation::create($this->validData([
            'title' => 'Judul Lama',
        ]));

        $response = $this->actingAs($this->admin)
            ->put(route('admin.donations.update', $donation), $this->validData([
                'title'    => 'Judul Baru',
                'status'   => 'completed', // status baru yang diizinkan di update()
                // di update hanya 'date', boleh sebelum hari ini
                'end_date' => now()->subDay()->toDateString(),
            ]));

        $response->assertRedirect(route('admin.donations.index'))
            ->assertSessionHas('success', 'Donasi berhasil diupdate.');

        $donation->refresh();

        $this->assertEquals('Judul Baru', $donation->title);
        $this->assertEquals('completed', $donation->status);
    }

    /** @test */
    public function update_mengganti_gambar_dan_menghapus_gambar_lama()
    {
        Storage::fake('public');

        // Simulasi file lama
        Storage::disk('public')->put('donations/lama.jpg', 'dummy');

        $donation = Donation::create($this->validData([
            'image' => 'donations/lama.jpg',
        ]));

        $fileBaru = UploadedFile::fake()->image('baru.jpg');

        $response = $this->actingAs($this->admin)
            ->put(route('admin.donations.update', $donation), $this->validData([
                'image' => $fileBaru,
            ]));

        $response->assertRedirect(route('admin.donations.index'));

        $donation->refresh();

        $this->assertFalse(
            Storage::disk('public')->exists('donations/lama.jpg'),
            'File lama.jpg seharusnya sudah dihapus.'
        );

        $this->assertTrue(
            Storage::disk('public')->exists($donation->image),
            'File gambar baru seharusnya tersimpan di disk public.'
        );
    }

    /** @test */
    public function destroy_menghapus_donasi_dan_gambar_jika_ada()
    {
        Storage::fake('public');

        Storage::disk('public')->put('donations/hapus.jpg', 'dummy');

        $donation = Donation::create($this->validData([
            'image' => 'donations/hapus.jpg',
        ]));

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.donations.destroy', $donation));

        $response->assertRedirect(route('admin.donations.index'))
            ->assertSessionHas('success', 'Donasi berhasil dihapus.');

        $this->assertDatabaseMissing('donations', [
            'id' => $donation->id,
        ]);

        $this->assertFalse(
            Storage::disk('public')->exists('donations/hapus.jpg'),
            'File hapus.jpg seharusnya sudah terhapus setelah destroy.'
        );
    }
}
