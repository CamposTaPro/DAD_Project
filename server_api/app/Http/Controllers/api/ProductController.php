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

    public function index($id)
    {
        $product = Product::find($id);
        return response()->json($product);
    }

    public function getProducts()
    {
        $products = Product::all();

        return response()->json($products);
    }

    public function getProductByType(string $type)
    {
        $products = Product::where('type', $type)->get();
        return response()->json($products);
    }

    public function getProductsByOrderItemStatus(Request $request)
    {

        $order_items = Order_Item::where('status', $request->order_status)->get();

        $products = array();
        foreach ($order_items as $order_item) {
            if (!$order_item->product()->where('type', $request->type)->get()->isEmpty()) {
                array_push($products, $order_item->product()->where('type', $request->type)->get());
            }
        }
        return response()->json($products);
    }
    public function destroy(int $id)
    {
        $product = Product::find($id);
        $product->delete();
        return response()->json($product);
    }

    public function store(Request $request)
    {
        //dd($request->all());
        //dd($request->file('file'));
        $product = new Product();

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->type = $request->type;

        $file_path = $request->file('file')->store('products', 'public');
        $product->photo_url = $request->file('file')->hashName();

        $product->save();
        return response()->json($product);
    }

    public function gravarFoto($id, $file)
    {
        $photoPath = 'public/fotos';

        $targetDir = storage_path('app/' . $photoPath);
        //$sourceDir = database_path('seeds/fotos');
        $newfilename = $id . "_" . uniqid() . '.jpg';
        File::copy($file, $targetDir . '/' . $newfilename);
        DB::table('products')->where('id', $id)->update(['photo_url' => $newfilename]);
        $this->command->info("Updated Photo of User $id. File $file copied as $newfilename");
    }

    public function update(Request $request, $id)
    {
        /*$product = Product::find($id);
        $product->update($request->all());

        return response()->json($product);*/


        $product = Product::find($id);
        if ($request->name != null) {
            $product->name = $request->name;
        }
        if ($request->description != null) {
            $product->description = $request->description;
        }
        if ($request->price != null) {
            $product->price = $request->price;
        }
        if ($request->type != null) {
            $product->type = $request->type;
        }

        if ($request->file('file') != null) {
            $request->file('file')->store('products', 'public');
            $product->photo_url = $request->file('file')->hashName();
        }

        $product->update();
    }
}
