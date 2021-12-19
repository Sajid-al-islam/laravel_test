<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Variant;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public $price_from,$price_to;
    public function index()
    {
        
        $title = request()->query('title');
        $variant = request()->query('variant');
        $price_from = request()->query('price_from');
        $price_to = request()->query('price_to');
        $date = request()->query('date');
        $product = new Product();
        if($title) {
            $products = Product::where('title', 'LIKE' ,"%{$title}%")->paginate(3); 
        }
        if($variant) {
            $product->product_variant()->where('variant',"LIKE" ,"%{$variant}%")->paginate(3);

            // $products = Product::with('product_variant'=>function($query){
            //   $query->where('variant', $variant);  
            // });
        }
        if($price_from) {
            
            $product->product_variant_price()->whereBetween('price',[$price_from, $price_to])->paginate(3);
            
        }
        if($date) {
            $products = Product::where('created_at', "LIKE" ,"%{$date}%")->paginate(3); 
        }
        else {
            $products = Product::paginate(2);
        }
        $variants = Variant::all();
        // $product_variants = ProductVariant::all();
        // $product_variant_prices = ProductVariantPrice::all();
        return view('products.index', compact('products', 'variants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $title = $request->title;
        $description = $request->description;
        $sku = $request->sku;
        // dd($request);

        $product =  Product::create([
            'title' => $title,
            'description' => $description,
            'sku' => $sku
        ]);
        
        if($request->hasFile('product_image')) {
            $images = $request->file('product_image');
            $upload_path = public_path('assets/uploads/product');
            foreach ($images as $image){
                $name = time().'-'.$image->getClientOriginalName();
                $image->move($upload_path, $name);
                ProductImage::create([
                    'product_id'     => $product->id,
                    'product_id'     => $product->id,
                    'file_path'          => $name,
                    'created_at'         => Carbon::now(),
                ]);
            }
        }
        
        
        if ($request->exists("product_variant")) {
            foreach ($request->product_variant as $key => $variant) {
                // dd($variant);
                $variant_id = Variant::where('title',$variant['option'])->first();
                foreach($variant['tags'] as $tag) {
                        $product->product_variant()->create([
                        'variant' => $tag,
                        'variant_id' => $variant_id->id,
                        'product_id' => $product->id,
                        'created_at' => Carbon::now(),
                    ]);
                }
                dd($variant);
                
            }
        }
        return 'success';
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response()->json([
            'product' => $product
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        // dd($product);
        // return response()->json([
        //     'product' => $product
        // ], 200);
        return view('products.edit', compact('variants'))->with('product', json_encode($product));

    }

    public function get_product(Product $product)
    {
        
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $title = $request->title;
        $description = $request->description;
        $sku = $request->sku;
        
        $product->title = $title;
        $product->description = $description;
        $product->sku = $sku;
        

        $product->update();
        
        if($request->hasFile('product_image')) {
            $images = $request->file('product_image');
            $upload_path = public_path('assets/uploads/product');
            foreach ($images as $image){
                $name = time().'-'.$image->getClientOriginalName();
                $image->move($upload_path, $name);
                ProductImage::create([
                    'product_id'     => $product->id,
                    'product_id'     => $product->id,
                    'file_path'          => $name,
                    'created_at'         => Carbon::now(),
                ]);
            }
        }
        if ($request->exists("product_variant")) {
            foreach ($request->product_variant as $key => $variant) {
                // dd($variant);
                $variant_id = Variant::where('title',$variant['option'])->first();
                foreach($variant['tags'] as $tag) {
                        $product->product_variant()->create([
                        'variant' => $tag,
                        'variant_id' => $variant_id->id,
                        'product_id' => $product->id,
                        'created_at' => Carbon::now(),
                    ]);
                }
                dd($variant);
                
            }
        }
        return 'success';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}