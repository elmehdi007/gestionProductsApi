<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{

    /**
     * Create a new ProductController instance.
     *
     * @return void
     */
    public function __construct(
        private ProductRepository $productRepository,
        private Request $request
    ) {}

    public function index()
    {
        $response = null;
        $name = $this->request->get("name");
        $category = $this->request->get("category");

        try {

            $response = response()->json($this->productRepository->searching($name, $category)->toArray(), 200);
        } catch (\Throwable $th) {
            $response = response()->json(['errors' => ["internal_error" => [($this->env === "prod") ? __('internal error') : $th->__toString()]]], 500);;
        } finally {
            return $response;
        }
    }


    public function getAllCategories()
    {
        $response = null;

        try {

            $response = response()->json($this->productRepository->getAllCategories()->toArray(), 200);
        } catch (\Throwable $th) {
            $response = response()->json(['errors' => ["internal_error" => [($this->env === "prod") ? __('internal error') : $th->__toString()]]], 500);;
        } finally {
            return $response;
        }
    }

    
    public function getProductById(string $id)
    {
        $response = null;

        try {

            if ($this->productRepository->exists($id) === false) $response = response()->json(null, 404);

            else  $response = response()->json($this->productRepository->find($id)->toArray(), 200);
        } catch (\Throwable $th) {
            $response = response()->json(['errors' => ["internal_error" => [($this->env === "prod") ? __('internal error') : $th->__toString()]]], 500);;
        } finally {
            return $response;
        }
    }

    public function update(string $id)
    {
        $response = null;
        $name = $this->request->get("name");
        $category = $this->request->get("category");
        $sku = $this->request->get("sku");
        $price = $this->request->get("price");
        $quantity = $this->request->get("quantity");
        $paramsSearch = $this->request->get("search_for_maj", []);

        try {

            $validator = Validator::make($this->request->all(), [
                'name' => 'required',
                'category' => 'required',
                'sku' => 'required',
                'price' => 'required',
                'quantity' => 'required'
            ]);

            if ($validator->fails()) $response = response()->json(['errors' => $validator->errors()], 400);

            else if ($this->productRepository->exists($id) === false) $response = response()->json(null, 404);

            else  $response = response()->json($this->productRepository->update(
                $id,
                $name,
                $category,
                $sku,
                $price,
                $quantity,
                $paramsSearch
            )->toArray(), 200);
        } catch (\Throwable $th) {
            $response = response()->json(['errors' => ["internal_error" => [($this->env === "prod") ? __('internal error') : $th->__toString()]]], 500);;
        } finally {
            return $response;
        }
    }

    public function delete(string $id)
    {
        $response = null;
        $paramsSearch = $this->request->get("search_for_maj", []);

        try {

            if ($this->productRepository->exists($id) === false) $response = response()->json(null, 404);

            else  $response = response()->json($this->productRepository->delete($id, $paramsSearch)->toArray(), 200);
        } catch (\Throwable $th) {
            $response = response()->json(['errors' => ["internal_error" => [($this->env === "prod") ? __('internal error') : $th->__toString()]]], 500);;
        } finally {
            return $response;
        }
    }

    public function create()
    {
        $response = null;
        $name = $this->request->get("name");
        $category = $this->request->get("category");
        $sku = $this->request->get("sku");
        $price = $this->request->get("price");
        $quantity = $this->request->get("quantity");
        $paramsSearch = $this->request->get("search_for_maj", []);

        try {

            $validator = Validator::make($this->request->all(), [
                'name' => 'required',
                'category' => 'required',
                'sku' => 'required',
                'price' => 'required',
                'quantity' => 'required'
            ]);

            if ($validator->fails()) $response = response()->json(['errors' => $validator->errors()], 400);

            else $response = response()->json($this->productRepository->create(
                $name,
                $category,
                $sku,
                $price,
                $quantity,
                $paramsSearch
            )->toArray(), 200);
        } catch (\Throwable $th) {
            $response = response()->json(['errors' => ["internal_error" => [($this->env === "prod") ? __('internal error') : $th->__toString()]]], 500);;
        } finally {
            return $response;
        }
    }
}
