<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Poin;
use App\User;
use App\Siswa;
use App\Pelanggaran;
use DB;
use Illuminate\Support\Facades\Validator;

class PoinController extends Controller
{

    public function index()
    {
    	try{
	        $data["count"] = Poin::count();
	        $poin = array();
	        $dataPoin = DB::table('poin_siswa')->join('siswa','siswa.id','=','poin_siswa.id_siswa')
                                               ->join('pelanggaran','pelanggaran.id','=','poin_siswa.id_pelanggaran')
                                               ->select('poin_siswa.id', 'siswa.nama_siswa','siswa.kelas','siswa.nis','pelanggaran.nama_pelanggaran','pelanggaran.kategori', 'pelanggaran.poin', 'poin_siswa.tanggal','poin_siswa.id_siswa','pelanggaran.id as id_pelanggaran','poin_siswa.keterangan')
	                                           ->get();

	        foreach ($dataPoin as $p) {
	            $item = [
	                "id"          		 => $p->id,
                  "id_siswa"         => $p->id_siswa,
	                "nama_siswa"  		 => $p->nama_siswa,
	                "kelas"  			     => $p->kelas,
	                "nis"    	  		   => $p->nis,
                  "id_pelanggaran"    => $p->id_pelanggaran,
	                "nama_pelanggaran"  => $p->nama_pelanggaran,
	                "kategori"  		   => $p->kategori,
	                "poin"  			      => $p->poin,
                  "keterangan"        => $p->keterangan,
	                "tanggal" 			    => date('Y-m-d', strtotime($p->tanggal))
	            ];

	            array_push($poin, $item);
	        }
	        $data["poin"] = $poin;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function getAll($limit = 10, $offset = 0)
    {
    	try{
	        $data["count"] = Poin::count();
	        $poin = array();
	        $dataPoin = DB::table('poin_siswa')->join('siswa','siswa.id','=','poin_siswa.id_siswa')
                                               ->join('pelanggaran','pelanggaran.id','=','poin_siswa.id_pelanggaran')
                                               ->select('poin_siswa.id', 'siswa.nama_siswa','siswa.kelas','siswa.nis','pelanggaran.nama_pelanggaran','pelanggaran.kategori', 'pelanggaran.poin', 'poin_siswa.tanggal','poin_siswa.id_siswa','pelanggaran.id as id_pelanggaran','poin_siswa.keterangan')
                                               ->skip($offset)
                                               ->take($limit)
	                                           ->get();

	        foreach ($dataPoin as $p) {
	            $item = [
	                "id"             => $p->id,
                  "id_siswa"      => $p->id_siswa,
                  "nama_siswa"      => $p->nama_siswa,
                  "kelas"       => $p->kelas,
                  "nis"           => $p->nis,
                  "id_pelanggaran"  => $p->id_pelanggaran,
                  "nama_pelanggaran"  => $p->nama_pelanggaran,
                  "kategori"      => $p->kategori,
                  "poin"        => $p->poin,
                  "keterangan"        => $p->keterangan,
                  "tanggal"       => date('Y-m-d', strtotime($p->tanggal))
	            ];

	            array_push($poin, $item);
	        }
	        $data["poin"] = $poin;
	        $data["status"] = 1;
	        return response($data);

	    } catch(\Exception $e){
			return response()->json([
			  'status' => '0',
			  'message' => $e->getMessage()
			]);
      	}
    }

    public function store(Request $request)
    {
      try{
    		$validator = Validator::make($request->all(), [
    			'id_siswa'    		=> 'required|numeric',
				'id_pelanggaran'	=> 'required|numeric',
				'id_petugas'		=> 'required|numeric',
  				'tanggal'			=> 'required|date_format:Y-m-d',
  				'keterangan'		=> 'string',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

    		//cek apakah ada id user tersebut
    		if(Siswa::where('id', $request->input('id_siswa'))->count() > 0){
    			if(Pelanggaran::where('id', $request->input('id_pelanggaran'))->count() > 0){
    				$data = new Poin();
              		$data->id_petugas = $request->input('id_petugas');
			        $data->id_siswa = $request->input('id_siswa');
			        $data->id_pelanggaran = $request->input('id_pelanggaran');
			        $data->tanggal = $request->input('tanggal');
			        $data->keterangan = $request->input('keterangan');
			        $data->save();

		    		return response()->json([
		    			'status'	=> '1',
		    			'message'	=> 'Data poin pelanggaran berhasil ditambahkan!'
		    		], 201);
    			} else {
    				return response()->json([
		                'status' => '0',
		                'message' => 'Data poin pelanggaran tidak ditemukan.'
		            ]);
    			}
    		} else {
    			return response()->json([
	                'status' => '0',
	                'message' => 'Data siswa tidak ditemukan.'
	            ]);
    		}

    		

      } catch(\Exception $e){
            return response()->json([
                'status' => '0',
                'message' => $e->getMessage()
            ]);
        }
  	}


    public function update(Request $request, $id)
    {
      try {
      	$validator = Validator::make($request->all(), [
			'id_siswa'    		=> 'required|numeric',
			'id_pelanggaran'	=> 'required|numeric',
			'tanggal'			=> 'required|date_format:Y-m-d',
			'keterangan'		=> 'string',
		]);

      	if($validator->fails()){
      		return response()->json([
      			'status'	=> '0',
      			'message'	=> $validator->errors()
      		]);
      	}

      	//proses update data
      	$data = Poin::where('id', $id)->first();
        $data->id_petugas = $request->input('id_petugas');
        $data->id_siswa = $request->input('id_siswa');
        $data->id_pelanggaran = $request->input('id_pelanggaran');
        $data->tanggal = $request->input('tanggal');
        $data->keterangan = $request->input('keterangan');
        $data->save();

      	return response()->json([
      		'status'	=> '1',
      		'message'	=> 'Data poin pelanggaran berhasil diubah'
      	]);
        
      } catch(\Exception $e){
          return response()->json([
              'status' => '0',
              'message' => $e->getMessage()
          ]);
      }
    }

    public function delete($id)
    {
        try{

            $delete = Poin::where("id", $id)->delete();
            if($delete){
              return response([
                "status"  => 1,
                  "message"   => "Data poin pelanggaran berhasil dihapus."
              ]);
            } else {
              return response([
                "status"  => 0,
                  "message"   => "Data poin pelanggaran gagal dihapus."
              ]);
            }
            
        } catch(\Exception $e){
            return response([
            	"status"	=> 0,
                "message"   => $e->getMessage()
            ]);
        }
    }


    public function getPoinSiswa(){
    	try {

    		$dataPoinSiswa = DB::table('siswa')->join('poin_siswa','siswa.id','=','poin_siswa.id_siswa')
                                               ->join('pelanggaran','pelanggaran.id','=','poin_siswa.id_pelanggaran')
                                               ->select('siswa.id', 
                                               			'siswa.nama_siswa',
                                               			'siswa.kelas',
                                               			'siswa.nis')
                                               ->groupBy('siswa.id');

    		$data["count"] = $dataPoinSiswa->get()->count();
	        $poin = array();

	        foreach ($dataPoinSiswa->get() as $p) {

	        	//get total poin per user
	        	$total_poin = DB::table('poin_siswa')->join('pelanggaran','pelanggaran.id','=','poin_siswa.id_pelanggaran')
	        										 ->where('poin_siswa.id_siswa', '=', $p->id)
	        										 ->sum('poin');
	            $item = [
	                "id"          		=> $p->id,
	                "nama_siswa"  		=> $p->nama_siswa,
	                "kelas"  			=> $p->kelas,
	                "nis"    	  		=> $p->nis,
	                "total_poin"  		=> $total_poin
	            ];

	            array_push($poin, $item);
	        }
	        $data["poin"] = $poin;
	        $data["status"] = 1;
	        return response($data);

    	} catch(\Exception $e){
    		return response([
            	"status"	=> 0,
                "message"   => $e->getMessage()
            ]);
    	}
    }

    public function getPoinSiswaLimit($limit = 10, $offset = 0)
    {
      try{
          $data["count"] = Poin::count();
          $poin = array();
          $dataPoinSiswa = DB::table('siswa')->join('poin_siswa','siswa.id','=','poin_siswa.id_siswa')
                                               ->join('pelanggaran','pelanggaran.id','=','poin_siswa.id_pelanggaran')
                                               ->select('siswa.id', 
                                                    'siswa.nama_siswa',
                                                    'siswa.kelas',
                                                    'siswa.nis')
                                               ->skip($offset)
                                               ->take($limit)
                                               ->groupBy('siswa.id');

         $data["count"] = $dataPoinSiswa->get()->count();
          $poin = array();

          foreach ($dataPoinSiswa->get() as $p) {

            //get total poin per user
            $total_poin = DB::table('poin_siswa')->join('pelanggaran','pelanggaran.id','=','poin_siswa.id_pelanggaran')
                               ->where('poin_siswa.id_siswa', '=', $p->id)
                               ->sum('poin');
              $item = [
                  "id"              => $p->id,
                  "nama_siswa"      => $p->nama_siswa,
                  "kelas"       => $p->kelas,
                  "nis"           => $p->nis,
                  "total_poin"      => $total_poin
              ];

              array_push($poin, $item);
          }
          $data["poin"] = $poin;
          $data["status"] = 1;
          return response($data);

      } catch(\Exception $e){
      return response()->json([
        'status' => '0',
        'message' => $e->getMessage()
      ]);
        }
    }

    public function findPoinSiswa(Request $request, $limit = 10, $offset = 0)
    {
        $find = $request->find;
        $dataPoinSiswa = DB::table('siswa')->join('poin_siswa','siswa.id','=','poin_siswa.id_siswa')
                                               ->join('pelanggaran','pelanggaran.id','=','poin_siswa.id_pelanggaran')
                                               ->select('siswa.id', 
                                               			'siswa.nama_siswa',
                                               			'siswa.kelas',
                                               			'siswa.nis')
                                               ->where('nama_siswa', 'like', "%$find%")
                                               ->groupBy('siswa.id');

        $data["count"] = $dataPoinSiswa->get()->count();
        $poin = array();

        foreach ($dataPoinSiswa->get() as $p) {

        	//get total poin per user
        	$total_poin = DB::table('poin_siswa')->join('pelanggaran','pelanggaran.id','=','poin_siswa.id_pelanggaran')
        										 ->where('poin_siswa.id_siswa', '=', $p->id)
        										 ->sum('poin');
            $item = [
                "id"          		=> $p->id,
                "nama_siswa"  		=> $p->nama_siswa,
                "kelas"  			=> $p->kelas,
                "nis"    	  		=> $p->nis,
                "total_poin"  		=> $total_poin
            ];

            array_push($poin, $item);
        }
        $data["poin"] = $poin;
        $data["status"] = 1;
        return response($data);
    }

    public function getDetailPoinSiswa($id)
    {
    	try {

    		$dataSiswa = Siswa::where('id', $id)->first();
        if($dataSiswa != NULL){
      		$detailPoinSiswa = DB::table('poin_siswa')->join('siswa','poin_siswa.id_siswa','=','siswa.id')
      											 ->join('pelanggaran','pelanggaran.id','=','poin_siswa.id_pelanggaran')
      											 ->select('poin_siswa.tanggal', 'pelanggaran.nama_pelanggaran', 'pelanggaran.kategori', 'poin_siswa.keterangan', 'pelanggaran.poin')
      											 ->where('poin_siswa.id_siswa','=', $id);

      		
      		$data["nama_siswa"] = $dataSiswa->nama_siswa;
  	        $data["nis"] 		= $dataSiswa->nis;
  	        $data["kelas"] 		= $dataSiswa->kelas;

  	        $detailPoin = array();
  	        foreach ($detailPoinSiswa->get() as $p) {

  	            $item = [
  	                "tanggal"          	=> $p->tanggal,
  	                "nama_pelanggaran" 	=> $p->nama_pelanggaran,
  	                "kategori"  		=> $p->kategori,
  	                "keterangan"    	=> $p->keterangan,
  	                "poin"  			=> $p->poin
  	            ];

  	            array_push($detailPoin, $item);
  	        }

  	        $data["detail"] = $detailPoin;
  	        $data["count"] 	= $detailPoinSiswa->get()->count();
  	        $data["status"] = 1;
  	        return response($data);
          } else {
            return response([
              'status' => 0,
              'message' => 'Data siswa tidak ditemukan'
            ]);
          }

    	} catch(\Exception $e){
    		return response([
    			'status' => 0,
    			'message' => $e->getMessage()
    		]);
    	}
    }

}
