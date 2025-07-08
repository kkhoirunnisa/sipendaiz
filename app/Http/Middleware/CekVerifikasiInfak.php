<?php

namespace App\Http\Middleware;

use App\Models\BuktiTransaksiModel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekVerifikasiInfak
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->route('id'); // Ambil ID dari route parameter
        $bukti = BuktiTransaksiModel::find($id);

        if (!$bukti) {
            return redirect()->back()->with('error', 'Data bukti transaksi tidak ditemukan.');
        }

        if ($bukti->status === 'Terverifikasi') {
            return redirect()->route('bukti-transaksi.index')->with('error', 'Data sudah terverifikasi dan tidak bisa diubah.');
        }

        return $next($request);
    }
}
