<?php

namespace App\Repositories;

use App\Models\City;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Helpers\Service\RepositoryDataResponseStructure;
use App\Helpers\Service\RepositoryMajResponseStructure;
use Illuminate\Support\Str;

/**
 * Class ProductRepository
 *
 * @package \App\Repositories
 */
class ProductRepository extends BaseRepository
{

    /**
     * ProductRepository constructor.
     */
    public function __construct(Product $model, Request $request)
    {
        parent::__construct($model, $request);
    }

    /**
     */
    public function searching(?string $name = null, ?string $category = null): RepositoryDataResponseStructure
    {

        $query = $this->model;

        if ($name != null) $query->where([['name', 'like', "%$name%"]]);
        if ($category != null) $query->where([['category', 'like', "%$category%"]]);

        $totalRows = $query->count();
        $data = $query->orderBy($this->sortBy, $this->sortDir)->skip($this->skip)->take($this->take)->get();
        $count = $data->count();

        $repositoryDataResponseStructure = new RepositoryDataResponseStructure(__("ok"), $data, $count, $totalRows);

        return $repositoryDataResponseStructure;
    }

        /**
     */
    public function getAllCategories(): RepositoryDataResponseStructure
    {

        $query = $this->model->select(['category'])->distinct();
        $totalRows = $query->count();
        $data = $query->get();
        $count = $data->count();

        $repositoryDataResponseStructure = new RepositoryDataResponseStructure(__("ok"), $data, $count, $totalRows);

        return $repositoryDataResponseStructure;
    }
    
    public function delete(string $id, $paramsSearch): RepositoryDataResponseStructure
    {
        $product = $this->find($id);
        $Searchame = $paramsSearch["name"] ?? null;
        $SearchCategory = $paramsSearch["category"] ?? null;

        if ($product != null) $product->delete();

        $searching = $this->searching($Searchame, $SearchCategory);
        $searching->setMessage(__("deleted"));

        return $searching;
    }

    public function update(
        string $id,
        string $name,
        string $category,
        bool $sku,
        float $price,
        int $quantity,
        $paramsSearch
    ): RepositoryDataResponseStructure {


        $product = $this->find($id);
        $Searchame = $paramsSearch["name"] ?? null;
        $SearchCategory = $paramsSearch["category"] ?? null;

        if ($product != null) {
            $product->name = $name;
            $product->category = $category;
            $product->sku = $sku;
            $product->price = $price;
            $product->quantity = $quantity;
            $product->save();
        }

        $searching = $this->searching($Searchame, $SearchCategory);
        $searching->setMessage(__("updated"));

        return $searching;
    }

    public function create(
        string $name,
        string $category,
        bool $sku,
        float $price,
        int $quantity,
        $paramsSearch
    ): RepositoryDataResponseStructure {

        $product = new Product();
        $Searchame = $paramsSearch["name"] ?? null;
        $SearchCategory = $paramsSearch["category"] ?? null;

        $product->name = $name;
        $product->category = $category;
        $product->sku = $sku;
        $product->price = $price;
        $product->quantity = $quantity;
        $product->save();

        $searching = $this->searching($Searchame, $SearchCategory);
        $searching->setMessage(__("created"));

        return $searching;
    }
}
