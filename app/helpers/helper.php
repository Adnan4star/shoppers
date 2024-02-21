<?php
use App\Models\category;
use App\Models\Product;

function getCategories()
{
    return category::orderBy('name','ASC')
        ->with('sub_category')
        ->orderBy('id','DESC')
        ->where('status',1)
        ->where('showHome','Yes')
        ->get();
}

// For order detail page
function getProductImage($product_Id)
{
    return Product::where('id',$product_Id)->first();
}

?>