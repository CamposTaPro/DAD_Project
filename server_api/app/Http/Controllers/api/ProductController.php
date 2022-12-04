<?php

namespace App\Http\Controllers\api;

use App\Models\Order_Item;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function getProducts() {
        $products = Product::all();
        return response()->json($products);
    }

    public function getProductByType(string $type) {
        $products = Product::where('type', $type)->get();
        return response()->json($products);
    }

    public function getProductsByOrderItemStatus(Request $request){

        $order_items = Order_Item::where('status', $request->order_status)->get();

        $products = array();
        foreach($order_items as $order_item){
            $product = Product::where('id', $order_item->product_id)->first();
            array_push($products, $product);
        }
        return response()->json($products);
    }

    public function destroy(int $id) {
        $product = Product::find($id);
        $product->delete();
        return response()->json($product);
    }

    public function store(Request $request) {
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,csv,txt,xlx,xls,pdf|max:2048'
         ]);


        $product = new Product();

        $product->name = $request->name;
        $product->type = $request->type;
        $product->description = $request->description;
        //$product->photo_url = $request->photo_url->hashName();

        //$request->file('photo_url')->store('storage/products', 'public');
        //$photo_file = $request->file('photo_url');
        //$product->photo_url = $request->file('photo_url');
        $file_name = time().'_'.$request->file->getClientOriginalName();
        $file_path = $request->file('file')->storeAs('products', $file_name, 'public');
        $product->photo_url = '/storage/' . $file_path;


        $product->price = $request->price;

        $product->save();

        $imageName = time().'.'.$request->photo_url->getClientOriginalExtension();
        $request->photo_url->move(public_path('products'), $imageName);

        //$photoPath = 'public/fotos';
        //$targetDir = storage_path('app/' . $photoPath);

        /*$newfilename = $product->id . "_" . uniqid() . '.jpg';
        File::copy($photo_file, $targetDir . '/' . $newfilename);
        DB::table('users')->where('id', $product->id)->update(['photo_url' => $newfilename]);
        $this->command->info("Updated Photo of User $product->id. File $photo_file copied as $newfilename");*/
        return response()->json($product);

        /*$product = Product::create($request->all());
        return response()->json($product);*/
    }

    public function gravarFoto($id, $file)
    {
        $photoPath = 'public/fotos';

        $targetDir = storage_path('app/' . $photoPath);
        //$sourceDir = database_path('seeds/fotos');
        $newfilename = $id . "_" . uniqid() . '.jpg';
        File::copy($file, $targetDir . '/' . $newfilename);
        DB::table('users')->where('id', $id)->update(['photo_url' => $newfilename]);
        $this->command->info("Updated Photo of User $id. File $file copied as $newfilename");
    }
}
