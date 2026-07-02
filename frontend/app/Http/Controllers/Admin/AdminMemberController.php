<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminMemberController extends Controller
{
    public function index(Request $request)
    {
        $token = $this->getGoJwtToken();

        $queryParams = $request->all();
        
        try {
            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->get('http://localhost:8080/api/admin/members', $queryParams);

            if ($response->successful()) {
                $membersData = $response->json('members') ?? [];
                
                // Manual pagination for simplicity since we fetched all
                $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
                $perPage = 10;
                $currentItems = array_slice($membersData, ($currentPage - 1) * $perPage, $perPage);
                $members = new \Illuminate\Pagination\LengthAwarePaginator(
                    $currentItems, count($membersData), $perPage, $currentPage,
                    ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
                );
                
                $currentTab = $request->tab ?? 'semua';
                return view('admin.members', compact('members', 'currentTab'));
            } else {
                return redirect()->route('admin.login')->with('error', 'Gagal memuat data member.');
            }
        } catch (\Exception $e) {
            return redirect()->route('admin.login')->with('error', 'Koneksi ke backend Golang terputus.');
        }
    }

    public function exportCsv(Request $request)
    {
        $members = User::where('role', 'member')->latest()->get();
        
        $filename = "members_" . date('Y-m-d') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Name', 'Email', 'Plan', 'Billing Cycle', 'Status', 'Registered At'];

        $callback = function() use($members, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($members as $m) {
                $row = [
                    $m->id,
                    $m->name,
                    $m->email,
                    ucfirst($m->plan),
                    ucfirst($m->billing_cycle),
                    ucfirst($m->status),
                    $m->created_at->format('Y-m-d H:i:s')
                ];
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show($id)
    {
        $member = User::findOrFail($id);
        return view('admin.member-detail', compact('member'));
    }

    public function destroy($id)
    {
        $token = $this->getGoJwtToken();

        $response = \Illuminate\Support\Facades\Http::withToken($token)
            ->delete("http://localhost:8080/api/admin/members/{$id}");

        if ($response->successful()) {
            return back()->with('success', 'Member berhasil dihapus dari sistem.');
        }
        return back()->with('error', 'Gagal menghapus member.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->ids;
        if (!empty($ids)) {
            $token = $this->getGoJwtToken();

            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->post("http://localhost:8080/api/admin/members/bulk-delete", [
                    'ids' => array_map('intval', $ids)
                ]);

            if ($response->successful()) {
                return back()->with('success', count($ids) . ' member berhasil dihapus.');
            }
            return back()->with('error', 'Gagal menghapus member yang dipilih.');
        }
        return back()->with('error', 'Tidak ada member yang dipilih.');
    }
}
