<?php

namespace App\Http\Controllers;

use App\Catalog;
use App\Episode;
use App\Category;
use Illuminate\Http\Request;

use App\Http\Requests;

class CatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome')->with('episodes', Episode::all());
    }

    public function admin()
    {
        $catalogs = Catalog::all();
        $categories = Category::pluck('name', 'id');

        return view('admin.catalogs')->with('catalogs', $catalogs)
            ->with('categories', $categories->all());
    }

    public function admin_save(Request $request)
    {
        $catalog_id = $request->input('catalog');
        $categories = $request->input('categories');
        //dd($catalog);

        $catalog = Catalog::where('id', $catalog_id)->first();

        $catalog->categories()->detach();
        foreach ((array)$categories as $category) {
            $catalog->categories()->attach($category);
        }

        return $this->admin();
    }

}
