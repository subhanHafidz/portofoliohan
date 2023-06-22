<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Portopolio;

class PortopolioController extends Controller
{
    //
    function show(){
        $data['portopolio'] = Portopolio::all();
        $data['cari'] = "";
        return view('portopolio',$data);
   }

   function add(){
       $data =[
           'action'=> url('portopolio/create'),
           'tombol'=> 'simpan',
           'portopolio'=> (object)[
               'nama_portopolio' => '',
               'kategori' => '',
               'deskripsi' => '',
               'foto' => ''
           ]
           ];

           return view('form_portopolio',$data);
   }

   function create(Request $request){
       $validate= $this->validate($request,[
        //    Portopolio::create([

               'nama_portopolio' => 'required|string',
               'kategori' => 'required|string|max:20',
               'deskripsi' => 'required|string',
               'foto' => 'required|mimes:jpg,png'
           ]);
    //    ]);

       $namafile = $request->kategori.".".$request->file('foto')->getClientoriginalExtension();
       $validate['foto'] = $request->file('foto')->storeAs('foto',$namafile);

       Portopolio::create($validate);
       return redirect('portopolio');
   }
   function hapus($id){
       portopolio::where('id',$id)->delete();
       return redirect('portopolio');
   }
   function edit($id){
       $data['portopolio'] = portopolio::find($id);
       $data['action'] = url('portopolio/update').'/'.$data['portopolio']->id;
       $data['tombol'] = "Update";

       return view('form_portopolio',$data);
   }
   function update(Request $request){
       $namafile = $request->kategori.".".$request->file('foto')->getClientOriginalExtension();
       Portopolio::where('id',$request->id)->update([
           'nama_portopolio' => $request->nama_portopolio,
           'kategori' => $request->kategori,
           'deskripsi' => $request->deskripsi,
           'foto' => $request->file('foto')->storeAs('foto', $namafile),
       ]);

       return redirect('portopolio');
   }

   function search(Request $request){
       $data['portopolio'] = Portopolio::where('id,$request->cari')
       ->orWhere('nama_portopolio','like',$request->cari."%")
       ->orWhere('kategori',$request->cari)
       ->paginate();

       $data['cari'] = $request->cari;

       return view('portopolio',$data);
   }
}
