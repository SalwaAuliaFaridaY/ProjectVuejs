<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Siswa;   
use Illuminate\Support\Facades\Validator;

class SiswaController extends Controller
{

    public function index()
    {
    	try{
	        $data["count"] = Siswa::count();
	        $siswa = array();

	        foreach (Siswa::all() as $p) {
	            $item = [
	                "id"          => $p->id,
	                "nis"         => $p->nis,
	                "nama_siswa"  => $p->nama_siswa,
	                "kelas"    	  => $p->kelas,
	                "created_at"  => $p->created_at,
	                "updated_at"  => $p->updated_at
	            ];

	            array_push($siswa, $item);
	        }
	        $data["siswa"] = $siswa;
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
	        $data["count"] = Siswa::count();
	        $siswa = array();

	        foreach (Siswa::take($limit)->skip($offset)->get() as $p) {
	            $item = [
	                "id"          => $p->id,
	                "nis"         => $p->nis,
	                "nama_siswa"  => $p->nama_siswa,
	                "kelas"    	  => $p->kelas,
	                "created_at"  => $p->created_at,
	                "updated_at"  => $p->updated_at
	            ];

	            array_push($siswa, $item);
	        }
	        $data["siswa"] = $siswa;
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
    			'nama_siswa'      => 'required|string|max:255',
				'nis'			  => 'required|string|max:255|unique:Siswa',
				'kelas'			  => 'required|string|max:255',
    		]);

    		if($validator->fails()){
    			return response()->json([
    				'status'	=> 0,
    				'message'	=> $validator->errors()
    			]);
    		}

    		$data = new Siswa();
	        $data->nama_siswa = $request->input('nama_siswa');
	        $data->kelas = $request->input('kelas');
	        $data->nis = $request->input('nis');
	        $data->save();

    		return response()->json([
    			'status'	=> '1',
    			'message'	=> 'Data siswa berhasil ditambahkan!'
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
			'nama_siswa'      => 'required|string|max:255',
			'nis'			  => 'required|string|max:255',
			'kelas'			  => 'required|string|max:255',
		]);

      	if($validator->fails()){
      		return response()->json([
      			'status'	=> '0',
      			'message'	=> $validator->errors()
      		]);
      	}

      	//proses update data
      	$data = Siswa::where('id', $id)->first();
        $data->nama_siswa = $request->input('nama_siswa');
        $data->kelas = $request->input('kelas');
        $data->nis = $request->input('nis');
        $data->save();

      	return response()->json([
      		'status'	=> '1',
      		'message'	=> 'Data siswa berhasil diubah'
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

            $delete = Siswa::where("id", $id)->delete();

            if($delete){
              return response([
              	"status"	=> 1,
                  "message"   => "Data siswa berhasil dihapus."
              ]);
            } else {
              return response([
                "status"  => 0,
                  "message"   => "Data siswa gagal dihapus."
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
