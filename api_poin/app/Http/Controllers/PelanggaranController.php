<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pelanggaran;   
use Illuminate\Support\Facades\Validator;

class PelanggaranController extends Controller
{

    public function index()
    {
    	try{
	        $data["count"] = Pelanggaran::count();
	        $pelanggaran = array();

	        foreach (Pelanggaran::all() as $p) {
	            $item = [
	                "id"          		=> $p->id,
	                "nama_pelanggaran"  => $p->nama_pelanggaran,
	                "kategori"  		=> $p->kategori,
	                "poin"    	  		=> $p->poin
	            ];

	            array_push($pelanggaran, $item);
	        }
	        $data["pelanggaran"] = $pelanggaran;
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
	        $data["count"] = Pelanggaran::count();
	        $pelanggaran = array();

	        foreach (Pelanggaran::take($limit)->skip($offset)->get() as $p) {
	            $item = [
	                "id"          		=> $p->id,
	                "nama_pelanggaran"  => $p->nama_pelanggaran,
	                "kategori"  		=> $p->kategori,
	                "poin"    	  		=> $p->poin
	            ];

	            array_push($pelanggaran, $item);
	        }
	        $data["pelanggaran"] = $pelanggaran;
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
    			'nama_pelanggaran'      => 'required|string|max:255',
				'kategori'			  	=> 'required|string|max:255',
				'poin'			  		=> 'required|numeric|max:500',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

    		$data = new Pelanggaran();
	        $data->nama_pelanggaran = $request->input('nama_pelanggaran');
	        $data->kategori = $request->input('kategori');
	        $data->poin = $request->input('poin');
	        $data->save();

    		return response()->json([
    			'status'	=> '1',
    			'message'	=> 'Data Pelanggaran berhasil ditambahkan!'
    		], 201);

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
			'nama_pelanggaran'      => 'required|string|max:255',
			'kategori'			  	=> 'required|string|max:255',
			'poin'			  		=> 'required|numeric|max:500',
		]);

      	if($validator->fails()){
      		return response()->json([
      			'status'	=> '0',
      			'message'	=> $validator->errors()
      		]);
      	}

      	//proses update data
      	$data = Pelanggaran::where('id', $id)->first();
        $data->nama_pelanggaran = $request->input('nama_pelanggaran');
        $data->kategori = $request->input('kategori');
        $data->poin = $request->input('poin');
        $data->save();

      	return response()->json([
      		'status'	=> '1',
      		'message'	=> 'Data Pelanggaran berhasil diubah'
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

            $delete = Pelanggaran::where("id", $id)->delete();

            if($delete){
              return response([
                "status"  => 1,
                  "message"   => "Data Pelanggaran berhasil dihapus."
              ]);
            } else {
              return response([
                "status"  => 0,
                  "message"   => "Data Pelanggaran gagal dihapus."
              ]);
            }
            
        } catch(\Exception $e){
            return response([
            	"status"	=> 0,
                "message"   => $e->getMessage()
            ]);
        }
    }

}
