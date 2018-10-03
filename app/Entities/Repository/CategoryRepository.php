<?php

namespace App\Entities\Repository;

use Illuminate\Database\Eloquent\Model;

use App\Entities\Models\Category;

use App\Entities\Resource\CategoryResource as CategoryResource;
use App\Entities\Resource\ProductResource as ProductResource;


class CategoryRepository
{
    protected $category;
    
    private $totalPage = 10;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }

    public function getResults($name = null)
    {
        if(!$name)
        {
            $category = CategoryResource::collection(Category::get());
            return $category;
        }

        $category = CategoryResource::collection(Category::where('name','LIKE', "%{$name}%")->get());
        return $category;

    }

    public function show($id)
    {
        $category = $this->category->find($id);
        if(!$category)
        {
           return 'None';
        }
        return $category;
    }

    public function store($name)
    {
        if ($name == null) {
            return 'None';
        }
        $category = New Category;
        $category->name = $name;
        $category->save();
        return $category;
    }

    public function updateCategory($name, $id)
    {
        $category = $this->category->find($id);
        if(!$category)
        {
           return 'None';
        }
        $category->name = $name;
        $category->save();
        return $category;
    }

    public function delete($id)
    {
        $category = $this->category->find($id);
        if(!$category)
        {
           return 'None';
        }
        $category->delete();
        return 'Deleted';
    }

    public function products($id)
    {
        /**
         * EVITANDO Muitas consultas ao DB a fim de 
         * 
         * $category = $this->category->with(['products'])->find($id);
         * if(!$category)
         * {
         *     return 'None';
         * }else {
         * $products = ProductResource::collection($category->products);
         * return $products;
         * }
         */
        $category = $this->category->find($id);
        if(!$category)
        {
            return 'None';
        }else {
            $products = ProductResource::collection($category->products()->paginate($this->totalPage));
            return $products;
        }
    }
}
