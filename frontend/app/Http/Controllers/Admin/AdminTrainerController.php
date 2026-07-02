<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trainer;

class AdminTrainerController extends Controller
{
    public function index(Request $request)
    {
        $token = $this->getGoJwtToken();

        $queryParams = $request->all();
        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->get('http://localhost:8080/api/trainers', $queryParams); // trainers is public or protected? it's protected in Go but doesn't require admin role. We can just use it.

        if ($response->successful()) {
            $trainersData = $response->json() ?? [];
            
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $perPage = 10;
            $currentItems = array_slice($trainersData, ($currentPage - 1) * $perPage, $perPage);
            $trainers = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItems, count($trainersData), $perPage, $currentPage,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );

            return view('admin.trainers', compact('trainers'));
        }
        return redirect()->route('admin.login')->with('error', 'Gagal memuat data trainer.');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        $data = $request->all();
        if ($request->hasFile('photo')) {
            $data['photo_url'] = '/storage/' . $request->file('photo')->store('trainers', 'public');
        }

        $token = $this->getGoJwtToken();
        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->post('http://localhost:8080/api/admin/trainers', [
                'name' => $data['name'],
                'specialty' => $data['specialty'] ?? '',
                'bio' => $data['bio'] ?? '',
                'price_per_session' => isset($data['price_per_session']) ? (int)$data['price_per_session'] : 0,
                'photo_url' => $data['photo_url'] ?? ''
            ]);

        if ($response->successful()) return back()->with('success', 'Trainer berhasil ditambahkan.');
        return back()->with('error', 'Gagal menambahkan trainer.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);
        $data = $request->all();
        if ($request->hasFile('photo')) {
            $data['photo_url'] = '/storage/' . $request->file('photo')->store('trainers', 'public');
        }

        $token = $this->getGoJwtToken();
        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->put("http://localhost:8080/api/admin/trainers/{$id}", [
                'name' => $data['name'],
                'specialty' => $data['specialty'] ?? '',
                'bio' => $data['bio'] ?? '',
                'price_per_session' => isset($data['price_per_session']) ? (int)$data['price_per_session'] : 0,
                'photo_url' => $data['photo_url'] ?? ''
            ]);

        if ($response->successful()) return back()->with('success', 'Trainer berhasil diperbarui.');
        return back()->with('error', 'Gagal memperbarui trainer.');
    }

    public function destroy($id)
    {
        $token = $this->getGoJwtToken();
        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->delete("http://localhost:8080/api/admin/trainers/{$id}");

        if ($response->successful()) return back()->with('success', 'Trainer berhasil dihapus.');
        return back()->with('error', 'Gagal menghapus trainer.');
    }
}
