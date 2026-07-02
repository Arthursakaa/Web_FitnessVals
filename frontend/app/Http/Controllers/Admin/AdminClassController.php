<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GymClass;
use App\Models\ClassSchedule;

class AdminClassController extends Controller
{
    public function index(Request $request)
    {
        $token = $this->getGoJwtToken();

        $queryParams = $request->all();
        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->get('http://localhost:8080/api/admin/classes', $queryParams);

        if ($response->successful()) {
            $data = $response->json();
            $classes = $data['classes'] ?? [];
            $schedules = $data['schedules'] ?? [];
            $trainers = $data['trainers'] ?? [];
            
            // Simple manual pagination for schedules
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $perPage = 15;
            $currentItems = array_slice($schedules, ($currentPage - 1) * $perPage, $perPage);
            $schedules = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItems, count($schedules), $perPage, $currentPage,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );

            return view('admin.classes', compact('classes', 'schedules', 'trainers'));
        }
        return redirect()->route('admin.login')->with('error', 'Gagal memuat data kelas.');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required', 'max_capacity' => 'required|integer']);
        
        $token = $this->getGoJwtToken();
        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->post('http://localhost:8080/api/admin/classes', [
                'name' => $request->name,
                'max_capacity' => (int)$request->max_capacity,
                'description' => $request->description,
                'type' => $request->type,
                'duration_minutes' => (int)$request->duration_minutes
            ]);

        if ($response->successful()) return back()->with('success', 'Kelas berhasil ditambahkan.');
        return back()->with('error', 'Gagal menambahkan kelas.');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required', 'max_capacity' => 'required|integer']);
        
        $token = $this->getGoJwtToken();
        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->put("http://localhost:8080/api/admin/classes/{$id}", [
                'name' => $request->name,
                'max_capacity' => (int)$request->max_capacity,
                'description' => $request->description,
                'type' => $request->type,
                'duration_minutes' => (int)$request->duration_minutes
            ]);

        if ($response->successful()) return back()->with('success', 'Kelas berhasil diperbarui.');
        return back()->with('error', 'Gagal memperbarui kelas.');
    }

    public function destroy($id)
    {
        $token = $this->getGoJwtToken();
        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->delete("http://localhost:8080/api/admin/classes/{$id}");

        if ($response->successful()) return back()->with('success', 'Kelas berhasil dihapus.');
        return back()->with('error', 'Gagal menghapus kelas.');
    }
}
