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
        if ($id == null) {
            return response()->json(['message' => 'Id null'], 422);
        }

        $product = Product::find($id);

        if ($product == null) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

    public function getProducts()
    {
        $products = Product::all();

        if ($products->isEmpty()) {
            return response()->json();
        }

        return response()->json($products);
    }

    public function getProductByType(string $type)
    {
        if ($type == null) {
            return response()->json(['message' => 'Type null'], 422);
        }

        $products = Product::where('type', $type)->get();

        if ($products->isEmpty()) {
            return response()->json();
        }

        return response()->json($products);
    }

    public function destroy(int $id)
    {
        if ($id == null) {
            return response()->json(['message' => 'Id null'], 422);
        }

        $product = Product::find($id);

        if ($product == null) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $product->delete();
        return response()->json($product);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|max:250',
            'description' => 'required|max:250',
            'price' => 'required|min:0',
            'type' => 'required|string|in:drink,hot dish,dessert,cold dish',
            'file' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);



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

    /*public function gravarFoto($id, $file)
    {
        $photoPath = 'public/fotos';

        $targetDir = storage_path('app/' . $photoPath);
        //$sourceDir = database_path('seeds/fotos');
        $newfilename = $id . "_" . uniqid() . '.jpg';
        File::copy($file, $targetDir . '/' . $newfilename);
        DB::table('products')->where('id', $id)->update(['photo_url' => $newfilename]);
        $this->command->info("Updated Photo of User $id. File $file copied as $newfilename");
    }*/

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string|max:250',
            'description' => 'string|max:250',
            'price' => 'numeric|min:0',
            'type' => 'string|in:drink,hot dish,dessert,cold dish',
            'file' => 'image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($id == null) {
            return response()->json(['message' => 'Id null'], 422);
        }

        $product = Product::find($id);

        if ($product == null) {
            return response()->json(['message' => 'Product not found'], 404);
        }

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
