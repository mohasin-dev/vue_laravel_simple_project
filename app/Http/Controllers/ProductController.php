<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Product;
use File;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ProductResource::collection(Product::latest()->paginate(6));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->image){
            $exploded = explode(',', $request->image);
            $decode = base64_decode($exploded[1]);

            if(str_contains($exploded[0], 'jpeg')){
             $extension = 'jpeg';
            }elseif(str_contains($exploded[0], 'jpg')){
             $extension = 'jpg';
            }elseif(str_contains($exploded[0], 'png')){
             $extension = 'png';
            }else{
             $extension = 'gif';
            }

            $fileName = str_random().'.'.$extension;
            $path = public_path().'/images/'.$fileName;
            file_put_contents($path, $decode);
        }else{
            $fileName = 'product.png';
        }

        $product = Product::create($request->except('image') + ['image' => $fileName]);
        return new ProductResource($product);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if($request->image){
            if(File::exists('images/'.$product->image)){
                File::delete('images/'.$product->image);
            }

            $exploded = explode(',', $request->image);
            $decode = base64_decode($exploded[1]);

            if(str_contains($exploded[0], 'jpeg')){
             $extension = 'jpeg';
            }elseif(str_contains($exploded[0], 'jpg')){
             $extension = 'jpg';
            }elseif(str_contains($exploded[0], 'png')){
             $extension = 'png';
            }else{
             $extension = 'gif';
            }

            $fileName = str_random().'.'.$extension;
            $path = public_path().'/images/'.$fileName;
            file_put_contents($path, $decode);
        }else{
            $fileName = 'product.jpeg';
        }
        $product->update($request->except('image') + ['image' => $fileName]);
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        $product->delete();
        return new ProductResource($product);
    }
}
