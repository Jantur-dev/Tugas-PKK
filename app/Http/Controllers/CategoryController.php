<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\CategoryFormRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryFormRequest $request, Category $category)
    {
        $validatedData = $request->validated();

        $category->name = $validatedData['name'];
        $category->slug = Str::slug($validatedData['slug']);
        $category->description = $validatedData['description'];

        // cek apakah name image ada filenya? jika ada maka...
        if ($request->hasFile('image')) {
            // ambil file seklaigus ekstensinya di request name image
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            // buat nama dari file imagenya
            $filename = time() . '.' . $ext;

            // pindahkan ke folder uploads/category
            $file->move('uploads/category/', $filename);
            $category->image = $filename;
        }

        $category->meta_title = $validatedData['meta_title'];
        $category->meta_description = $validatedData['meta_description'];
        $category->meta_keyword = $validatedData['meta_keyword'];

        $category->status = $request->status == true ? 1 : 0;

        $category->save();

        return redirect('/admin/category')->with('message', 'Category Added Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.category.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryFormRequest $request, $id)
    {
        $category = Category::findOrfail($id);

        $validatedData = $request->validated();

        $category->name = $validatedData['name'];
        $category->slug = Str::slug($validatedData['slug']);
        $category->description = $validatedData['description'];

        // cek apakah name image ada filenya? jika ada maka...
        if ($request->hasFile('image')) {
            // cek apakah file tersebut ada/tidak
            $path = 'uploads/category' . $category->image;
            if (File::exists($path)) {
                File::delete($path);
            }
            // ambil file seklaigus ekstensinya di request name image
            $file = $request->file('image');
            $ext = $file->getClientOriginalExtension();
            // buat nama dari file imagenya
            $filename = time() . '.' . $ext;

            // pindahkan ke folder uploads/category
            $file->move('uploads/category/', $filename);
            $category->image = $filename;
        }

        $category->meta_title = $validatedData['meta_title'];
        $category->meta_description = $validatedData['meta_description'];
        $category->meta_keyword = $validatedData['meta_keyword'];

        $category->status = $request->status == true ? 1 : 0;

        $category->update();

        return redirect('/admin/category')->with('message', 'Category Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
