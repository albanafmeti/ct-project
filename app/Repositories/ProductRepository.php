<?php

namespace App\Repositories;

use App\Product;

class ProductRepository extends Repository
{
    public $products;
    public $nextId;

    public function __construct()
    {
        parent::__construct();

        $this->products = array_map(function ($object) {

            $product = new Product();
            $product->fill([
                'id'         => $object->id,
                'name'       => $object->name,
                'quantity'   => $object->quantity,
                'price'      => $object->price,
                'created_at' => $object->created_at,
            ]);
            return $product;

        }, $this->data->products);

        $this->nextId = $this->data->nextId;
        $this->products = collect($this->products);
    }

    /**
     * Get list of products
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll()
    {
        $products = $this->products->toArray();
        $products = array_map(function ($product) {

            // Add additional field `total_value`
            $product['total_value'] = $product['quantity'] * $product['price'];
            return (object) $product;

        }, $products);

        return collect($products);
    }

    /**
     * Add new product
     *
     * @param $data
     * @return Product
     */
    public function add($data)
    {
        $product = new Product();

        $data['id'] = $this->nextId;
        $data['created_at'] = date('Y-m-d H:i:s');
        $product->fill($data);

        $this->nextId++;
        $this->products->push($product);

        $this->persistData();

        return $product;
    }

    /**
     * Update existing product
     *
     * @param $id
     * @param $data
     */
    public function update($id, $data)
    {
        $this->products = array_map(function ($object) use ($id, $data) {

            if ($object->id == $id) {

                foreach ($data as $key => $value) {
                    $object->{$key} = $value;
                }
            }
            return $object;

        }, $this->data->products);

        $this->products = collect($this->products);
        return $this->persistData();
    }

    /**
     * Delete product
     *
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $this->products = $this->products->filter(function ($product, $key) use ($id) {
            return $product->id != $id;
        });

        return $this->persistData();
    }

    /**
     * Persist data to disk
     */
    private function persistData()
    {
        $data = [
            'products' => $this->products->toArray(),
            'nextId'   => $this->nextId,
        ];

        return $this->persist($data);
    }
}
