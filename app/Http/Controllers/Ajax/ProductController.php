<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    protected $repo;

    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * List of products
     */
    public function index()
    {
        $products = $this->repo->getAll();
        return ProductResource::collection($products);
    }

    /**
     * Store a new product
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
            'quantity' => 'required|numeric',
            'price'    => 'required|numeric',
        ]);

        $product = $this->repo->add([
            'name'     => $request->name,
            'quantity' => $request->quantity,
            'price'    => $request->price,
        ]);

        if ($product) {
            return $this->simple_json(true, $product, "Product has been added successfully.");
        }
        return $this->simple_json(true, null, "Product addition failed");
    }

    /**
     * Update the product with the specified id
     *
     * @param         $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'name'     => 'required',
            'quantity' => 'required|numeric',
            'price'    => 'required|numeric',
        ]);

        $done = $this->repo->update($id, [
            'name'     => $request->name,
            'quantity' => $request->quantity,
            'price'    => $request->price,
        ]);

        if ($done) {
            return $this->simple_json(true, $done, "Product has been updated successfully.");
        }
        return $this->simple_json(true, null, "Product update failed");
    }


    /**
     * Delete product
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $done = $this->repo->delete($id);

        if ($done) {
            return $this->simple_json(true, null, "Product has been deleted successfully.");
        }
        return $this->simple_json(true, null, "Product deletion failed");
    }

    /**
     * Build a predefined response for ajax requests.
     *
     * @param      $success
     * @param      $data
     * @param null $message
     * @return \Illuminate\Http\JsonResponse
     */

    private function simple_json($success, $data, $message = null)
    {
        return response()->json([
            'success' => $success,
            'data'    => $data,
            'message' => $message,
        ]);
    }
}
