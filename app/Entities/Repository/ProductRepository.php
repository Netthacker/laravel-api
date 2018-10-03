<?php

namespace App\Entities\Repository;

use Illuminate\Database\Eloquent\Model;

use App\Entities\Models\Product;

use Illuminate\Support\Facades\Storage;

use App\Entities\Resource\ProductResource as ProductResource;


class ProductRepository
{
    //Variáveis Protegidas
    protected $product;
    //Variáveis Privadas
    private $totalPage = 10;
    private $path = 'products';

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getResults($search = null)
    {
        if(!$search)
        {
            $product = ProductResource::collection(Product::paginate($this->totalPage));
            return $product;
        }else
        {
            $product = ProductResource::collection(
                Product::where(function($query) use ($search){
                    $query->where('name', 'LIKE', "%{$search}%");
                    $query->orWhere('description', 'LIKE', "%{$search}%");
                })->paginate($this->totalPage));
            return $product;
        }
        

    }

    public function show($id)
    {
        $product = $this->product->find($id);
        if(!$product)
        {
            return 'None';
        }else {
            $product = ProductResource::collection(Product::where('id',$product->id)->get());
            $item_product = $product->map(function($values,$key){
                return $values;
            });
            return $item_product;

            /**
             *  Função de callback para poder manipular os arquivos gerindo resource
             * 
             * $item = $product->map(function($values,$key)
             * {return $values;});
             * return $item; 
             */
            
            
        }
    }

    public function store($request)
    {
        $nameFile = null; // Variável que vai ficar o nome do arquivo

        if ($request->category_id == null or $request->name == null) {
            return 'None';
        }else {

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $name = kebab_case($request->name); //tratamento do nome do arquivo
                $today = date("m_d_Y"); //recebe o dia do envio
                $extension = $request->image->extension(); // extensão do arquivo
                $nameFile = $name . '-' . $today . '.' . $extension; //Nome do Arquivo
                $upload = $request->image->storeAs($this->path, $nameFile); //Upando o arquivo
                if(!$upload){
                    return 'ImageFailure';
                }
            }
            $product = New Product;
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->description = $request->description;
            $product->image = $nameFile;
            $product->save();
            $product = ProductResource::collection(Product::where('id',$product->id)->get());
            return $product;
        }
        
    }

    public function update($request, $id)
    {
        $nameFile = null;
        $product = $this->product->find($id);
        if(!$product)
        {
            return 'None';
        }else {
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->description = $request->description;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                
                if($product->image){
                    if(Storage::exists("{$this->path}/{$product->image}"))
                    {
                        Storage::delete("{$this->path}/{$product->image}");
                    }
                }
                $name = kebab_case($request->name); //tratamento do nome do arquivo
                $today = date("m_d_Y"); //recebe o dia do envio
                $extension = $request->image->extension(); // extensão do arquivo
                $nameFile = $name . '-' . $today . '.' . $extension; //Nome do Arquivo
                $upload = $request->image->storeAs($this->path, $nameFile); //Upando o arquivo
                
                if(!$upload){
                    return 'ImageFailure';
                }else {
                    $product->image = $nameFile;
                }
            }

            $product->save();
            $product = ProductResource::collection(Product::where('id',$product->id)->get());
            return $product;
        }
    }

    public function destroy($id)
    {
        $product = $this->product->find($id);
        if(!$product)
        {
            return 'None';
        }else {
            if($product->image){
                if(Storage::exists("{$this->path}/{$product->image}"))
                {
                    Storage::delete("{$this->path}/{$product->image}");
                }
            }
            $product->delete();
            return 'Deleted';
        }
    }

    

}
