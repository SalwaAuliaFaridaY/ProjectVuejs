<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Siswa;
use App\Pelanggaran;
use App\Poin;
use App\User;
use DB;

class BerandaController extends Controller
{
    public function statistik()
    {
    	try {

    		$jmlSiswa = Siswa::count();
    		$jmlPetugas = User::count();
    		$jmlDataPelanggaran = Pelanggaran::count();
    		$jmlPelanggaranSiswaHariIni = Poin::where('tanggal', date('Y-m-d'))->count();

			$data['jml_siswa'] 				= $jmlSiswa;
			$data['jml_petugas'] 			= $jmlPetugas;
			$data['jml_data_pelanggaran'] 	= $jmlDataPelanggaran;
			$data['pelanggaran_hari_ini']	= $jmlPelanggaranSiswaHariIni;

			return response($data);

    	} catch (\Exceptions $e){
    		return response([
    			'status' => 0,
    			'message' => $e->getMessage(),
    		]);
    	}
    }

    public function poinTertinggi()
    {
    	try {

    		$dataPoinSiswa = DB::table('siswa')->join('poin_siswa','siswa.id','=','poin_siswa.id_siswa')
                                               ->join('pelanggaran','pelanggaran.id','=','poin_siswa.id_pelanggaran')
                                               ->select('siswa.nama_siswa',
                                               			'siswa.kelas',
                                               			DB::raw("(SELECT SUM(pelanggaran.poin) FROM poin_siswa JOIN pelanggaran ON pelanggaran.id = poin_siswa.id_pelanggaran WHERE poin_siswa.id_siswa = siswa.id) as total_poin")
                                               		)
                                               ->orderBy('total_poin', 'DESC')
                                               ->groupBy('siswa.id')
                                               ->take(5)
                                               ->skip(0);

            $data["count"] 	= $dataPoinSiswa->get()->count();
            $data["data"]  	= $dataPoinSiswa->get();
            $data["status"] = 1;
            return response($data);

    	} catch (\Exceptions $e){
    		return response([
    			'status' => 0,
    			'message' => $e->getMessage(),
    		]);
    	}
    }
}
