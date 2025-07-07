<?php

namespace App\Http\Middleware;

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
        $bukti_infak = $request->bukti_transaksi->status;
        if ($bukti_infak !== 'Terverifikasi') {
            return redirect()->back()->with('error', 'Bukti infak belum terverifikasi.');
        }
        return $next($request);
    }
}
