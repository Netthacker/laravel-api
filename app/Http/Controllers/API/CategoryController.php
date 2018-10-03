<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateCategoryFormRequest;

use App\Entities\Repository\CategoryRepository;

class CategoryController extends Controller
{
    private $category;

    public function __construct(CategoryRepository $category)
    {
        $this->category = $category;
    }

    public function index(Request $request)
    {
        $search = $request->input('name');
        $category = $this->category->getResults($search);
        return response()->json($category);
    }

    public function show($id)
    {
        $category = $this->category->show($id);
        if($category ==='None')
        {
            return response()->json(['error' => 'Not found'], 404);
        }
        else{
            return response()->json($category);
        }
    }

    public function store(StoreUpdateCategoryFormRequest $request)
    {
        $category_name = $request->input('name');
        $category = $this->category->store($category_name);
        if($category ==='None')
        {
            return response()->json(['error' => 'Not found'], 404);
        }
        
        return response()->json($category, 201);
    }

     public function update(StoreUpdateCategoryFormRequest $request, $id)
     {
        $category_name = $request->input('name');
        $category = $this->category->updateCategory($category_name,$id);
        if($category === 'None')
        {
            return response()->json(['error' => 'Not found'], 404);
        }
        else{
            return response()->json($category);
        }

     }

     public function destroy($id)
     {
        $category = $this->category->delete($id);
        if($category === 'None')
        {
            return response()->json(['error' => 'Not found'], 404);
        }
        else{
            return response()->json(['success'=> true], 204);
        }
        

     }

     public function products($id)
     {
         $category = $this->category->products($id);
         if($category === 'None')
         {
            return response()->json(['error' => 'Not found'], 404);
         }else {
            return response()->json([$category]);
         }
     }

}
