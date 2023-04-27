<?php

namespace App\Libraries\ElasticSearch;


use App\Models\Restaurant;
use App\Models\Product;
use Elastic\Elasticsearch\ClientBuilder;

class ElasticSearch
{
    use ElasticSearchTrait, RestaurantSearchTrait;

    public static $instance = null;
    private $elasticsearch;

    public function __construct()
    {
        //$this->elasticsearch = ClientBuilder::create()->setHosts(['130.120.110.250:9200'])->build();
        $this->elasticsearch = ClientBuilder::create()->setHosts(['localhost:9200'])->build();

//        $response =  $this->elasticsearch->info();
//        dd($response['version']['number']);
    }

    public static function instance()
    {
        self::$instance = new static;
        return self::$instance;
    }

    public function restaurant(int $id)
    {
        return $this->getDocument(['index' => 'restaurants', 'id' => $id]);
    }

    public function restaurantSearch(string $term, $page = 0, $location = [])
    {
        $params = $this->restaurantSearchParams($term, $page);

        //dd(json_encode($params['body']));
        try {
            $searchResult = $this->elasticsearch->search($params);
            $items = [];
            foreach ($searchResult['hits']['hits'] as $item) {

                $restaurantProductsHits = $item['inner_hits']['restaurantProducts']['hits']['hits'];

                $products = [];
                if (count($restaurantProductsHits) > 0) {
                    foreach ($restaurantProductsHits as $restaurantProduct) {
                        $products[] = [
                            'id' => $restaurantProduct['_source']['productId'],
                            'name' => $restaurantProduct['_source']['productName'],
                            'slug' => $restaurantProduct['_source']['productSlug'],
                            'price' => $restaurantProduct['_source']['productPrice'],
                            'description' => $restaurantProduct['_source']['productDescription'],
                            'image' => $restaurantProduct['_source']['image'],
                            'sort' => $restaurantProduct['_source']['sort'],
                            'status' => $restaurantProduct['_source']['productStatus'],
                            'restaurantId' => $item['_source']['restaurantId'],
                            'restaurantName' => $item['_source']['restaurantName']
                        ];
                    }
                } else {

                    if (count($item['_source']['restaurantProducts']) > 0) {
                        foreach ($item['_source']['restaurantProducts'] as $i => $restaurantProduct) {

                            $products[] = [
                                'id' => $restaurantProduct['foodId'],
                                'name' => $restaurantProduct['productName'],
                                'slug' => $restaurantProduct['_source']['productSlug'],
                                'description' => $restaurantProduct['productDescription'],
                                'price' => $restaurantProduct['productPrice'],
                                'image' => $restaurantProduct['image'],
                                'sort' => $restaurantProduct['sort'],
                                'status' => $restaurantProduct['productStatus'],
                                'restaurantId' => $item['_source']['restaurantId'],
                                'restaurantName' => $item['_source']['restaurantName']
                            ];
                        }
                    }
                }

                $items[] = [
                    'id' => $item['_source']['restaurantId'],
                    'name' => $item['_source']['restaurantName'],
                    'title' => 'Restorana gitmek için tıklayın >',
                    'logo' => $item['_source']['restaurantLogo'],
                    'products' => $products,
                ];
            }

            return [
                'total' => $searchResult['hits']['total'],
                'items' => $items,
            ];

        } catch (\Exception $e) {
            return json_decode($e->getMessage(), true);
        }
    }

    public function restaurantDelete(int $id)
    {
        return $this->deleteDocument([
            'index' => 'restaurants',
            'id' => $id,
        ]);
    }

    public function restaurantCreate(int $id)
    {
        if (isset(($this->restaurant($id))['found']) && ($this->restaurant($id))['found']) {
            $this->restaurantDelete($id);
        }

        $restaurant = Restaurant::select(
            'restaurants.id',
            'restaurants.name',
            'restaurants.logo',
            'restaurants.latitude',
            'restaurants.longitude'
        )
            ->where('status', 1)
            ->where('id', $id)
            ->first();

        if (empty($restaurant))
            return false;

        $products = Product::select(
            'products.*'
        )
            ->where('restaurant_id', $restaurant->id)
            ->where('status', 1)
            ->get();

        $restaurantProducts = $products->map(function ($item) {
            $item->productId = $item->id;
            $item->productStatus = $item->status;
            $item->productName = $item->name;
            $item->productSlug = $item->slug;
            $item->productPrice = $item->price;
            $item->productDescription = $item->description;
            $item->sort = $item->sort;
            $item->image = $item->image;

            return $item->only([
                'productId',
                'productStatus',
                'productName',
                'productSlug',
                'productPrice',
                'productDescription',
                'sort',
                'image'
            ]);
        })->toArray();

        $params = [
            'index' => 'restaurants',
            'id' => $restaurant->id,
            'body' => [
                'restaurantId' => $restaurant->id,
                'restaurantName' => $restaurant->name,
                'restaurantLogo' => $restaurant->logo,
                'pin' => [
                    'location' => [
                        'lat' => $restaurant->latitude,
                        'lon' => $restaurant->longitude
                    ]
                ],
                'restaurantProducts' => $restaurantProducts,
            ]
        ];

        return $this->createDocument($params);
    }

    public function restaurantCreateIndex()
    {
        $version = 7;

        $properties = [
            'restaurantId' => [
                'type' => 'long'
            ],
            'restaurantName' => [
                'type' => 'text',
                'analyzer' => 'turkish_analyzer'
            ],
            'restaurantLogo' => [
                'type' => 'text'
            ],
            'pin' => [
                'properties' => [
                    'location' => [
                        'type' => 'geo_point'
                    ]
                ]
            ],
            'restaurantProducts' => [
                'type' => 'nested',
                'properties' => [
                    'productName' => [
                        'type' => 'text',
                        'analyzer' => 'turkish_analyzer'
                    ],
                ]
            ],
        ];

        if ($version == 6) {
            $mapping = [
                '_doc' => [
                    'properties' => $properties
                ]
            ];
        } else {
            $mapping = [
                'properties' => $properties
            ];
        }

        $settings = [
            "analysis" => [
                "filter" => [
                    "my_ascii_folding" => [
                        "type" => "asciifolding",
                        "preserve_original" => true
                    ]
                ],
                "analyzer" => [
                    "turkish_analyzer" => [
                        "tokenizer" => "standard",
                        "filter" => [
                            "lowercase",
                            "my_ascii_folding"
                        ]
                    ]
                ],
            ]
        ];

        $this->createIndex('restaurants', $settings, $mapping);
    }

    public function restaurantBulkCreate()
    {
        $this->deleteIndex('restaurants');
        $this->restaurantCreateIndex();

        $restaurants = Restaurant::select(
            'restaurants.id',
            'restaurants.name',
            'restaurants.logo',
            'restaurants.latitude',
            'restaurants.longitude'
        )
            ->where('status', 1)
            ->get();

        foreach ($restaurants as $restaurant) {

            $products = Product::select(
                'products.*'
            )
                ->where('restaurant_id', $restaurant->id)
                ->where('status', 1)
                ->get();

            $restaurantProducts = $products->map(function ($item) {
                $item->productId = $item->id;
                $item->productStatus = $item->status;
                $item->productName = $item->name;
                $item->productSlug = $item->slug;
                $item->productPrice = $item->price;
                $item->productDescription = $item->description;
                $item->sort = $item->sort;
                $item->image = $item->image;

                //return $item;
                return $item->only([
                    'productId',
                    'productStatus',
                    'productName',
                    'productSlug',
                    'productPrice',
                    'productDescription',
                    'sort',
                    'image'
                ]);
            })->toArray();

            $params = [
                'index' => 'restaurants',
                'id' => $restaurant->id,
                'body' => [
                    'restaurantId' => $restaurant->id,
                    'restaurantName' => $restaurant->name,
                    'restaurantLogo' => $restaurant->logo,
                    'pin' => [
                        'location' => [
                            'lat' => $restaurant->latitude,
                            'lon' => $restaurant->longitude
                        ]
                    ],
                    'restaurantProducts' => $restaurantProducts,
                ]
            ];

            $this->createDocument($params);
        }

        return true;
    }


//    ---------------

    public function product(int $id)
    {
        return $this->getDocument(['index' => 'products', 'id' => $id]);
    }

    public function productSearch(string $term, $page = 0, $location = [])
    {
        $params = $this->restaurantSearchParams($term, $page);

        //dd(json_encode($params['body']));
        try {
            $searchResult = $this->elasticsearch->search($params);
            $items = [];
            foreach ($searchResult['hits']['hits'] as $item) {

                $restaurantProductsHits = $item['inner_hits']['restaurantProducts']['hits']['hits'];

                $products = [];
                if (count($restaurantProductsHits) > 0) {
                    foreach ($restaurantProductsHits as $restaurantProduct) {
                        $products[] = [
                            'id' => $restaurantProduct['_source']['productId'],
                            'name' => $restaurantProduct['_source']['productName'],
                            'slug' => $restaurantProduct['_source']['productSlug'],
                            'price' => $restaurantProduct['_source']['productPrice'],
                            'description' => $restaurantProduct['_source']['productDescription'],
                            'image' => $restaurantProduct['_source']['image'],
                            'sort' => $restaurantProduct['_source']['sort'],
                            'status' => $restaurantProduct['_source']['productStatus'],
                            'restaurantId' => $item['_source']['restaurantId'],
                            'restaurantName' => $item['_source']['restaurantName']
                        ];
                    }
                } else {

                    if (count($item['_source']['restaurantProducts']) > 0) {
                        foreach ($item['_source']['restaurantProducts'] as $i => $restaurantProduct) {

                            $products[] = [
                                'id' => $restaurantProduct['foodId'],
                                'name' => $restaurantProduct['productName'],
                                'slug' => $restaurantProduct['_source']['productSlug'],
                                'description' => $restaurantProduct['productDescription'],
                                'price' => $restaurantProduct['productPrice'],
                                'image' => $restaurantProduct['image'],
                                'sort' => $restaurantProduct['sort'],
                                'status' => $restaurantProduct['productStatus'],
                                'restaurantId' => $item['_source']['restaurantId'],
                                'restaurantName' => $item['_source']['restaurantName']
                            ];
                        }
                    }
                }

                $items[] = [
                    'id' => $item['_source']['restaurantId'],
                    'name' => $item['_source']['restaurantName'],
                    'title' => 'Restorana gitmek için tıklayın >',
                    'logo' => $item['_source']['restaurantLogo'],
                    'products' => $products,
                ];
            }

            return [
                'total' => $searchResult['hits']['total'],
                'items' => $items,
            ];

        } catch (\Exception $e) {
            return json_decode($e->getMessage(), true);
        }
    }

    public function productDelete(int $id)
    {
        return $this->deleteDocument([
            'index' => 'products',
            'id' => $id,
        ]);
    }

    public function productCreate(int $id)
    {
        if (isset(($this->product($id))['found']) && ($this->product($id))['found']) {
            $this->productDelete($id);
        }

        $product = Product::select(
            'products.*',
            'restaurants.name as restaurant_name',
            'restaurants.latitude as latitude',
            'restaurants.longitude as longitude',
        )->join('restaurants','restaurants.id','=','products.restaurant_id')
            ->where('id', $id)
            ->where('status', 1)
            ->first();

        $params = [
            'index' => 'products',
            'id' => $product->id,
            'body' => [
                'restaurantId' => $product->id,
                'restaurantName' => $product->restaurant_name,
                'pin' => [
                    'location' => [
                        'lat' => $product->latitude,
                        'lon' => $product->longitude
                    ]
                ],
                'productName' => $product->name,
                'productSlug' => $product->slug,
                'productPrice' => $product->price,
                'productSort' => $product->sort,
                'productImage' => $product->image,
            ]
        ];

        return $this->createDocument($params);
    }

    public function productCreateIndex()
    {
        $version = 7;

        $properties = [
            'restaurantId' => [
                'type' => 'long'
            ],
            'restaurantName' => [
                'type' => 'text',
                'analyzer' => 'turkish_analyzer'
            ],
            'pin' => [
                'properties' => [
                    'location' => [
                        'type' => 'geo_point'
                    ]
                ]
            ],
            'productName' => [
                'type' => 'text',
                'analyzer' => 'turkish_analyzer'
            ],
            'productSlug' => [
                'type' => 'text',
            ],
            'productPrice' => [
                'type' => 'double',
            ],
            'productSort' => [
                'type' => 'integer',
            ],
            'productImage' => [
                'type' => 'text'
            ]
        ];

        if ($version == 6) {
            $mapping = [
                '_doc' => [
                    'properties' => $properties
                ]
            ];
        } else {
            $mapping = [
                'properties' => $properties
            ];
        }

        $settings = [
            "analysis" => [
                "filter" => [
                    "my_ascii_folding" => [
                        "type" => "asciifolding",
                        "preserve_original" => true
                    ]
                ],
                "analyzer" => [
                    "turkish_analyzer" => [
                        "tokenizer" => "standard",
                        "filter" => [
                            "lowercase",
                            "my_ascii_folding"
                        ]
                    ]
                ],
            ]
        ];

        $this->createIndex('products', $settings, $mapping);
    }

    public function productBulkCreate()
    {
        $this->deleteIndex('products');
        $this->productCreateIndex();

        $products = Product::select(
            'products.*',
            'restaurants.name as restaurant_name',
            'restaurants.latitude as latitude',
            'restaurants.longitude as longitude',
        )->join('restaurants','restaurants.id','=','products.restaurant_id')
            ->where('status', 1)
            ->get();

        foreach ($products as $product) {

            $params = [
                'index' => 'products',
                'id' => $product->id,
                'body' => [
                    'restaurantId' => $product->id,
                    'restaurantName' => $product->restaurant_name,
                    'pin' => [
                        'location' => [
                            'lat' => $product->latitude,
                            'lon' => $product->longitude
                        ]
                    ],
                    'productName' => $product->name,
                    'productSlug' => $product->slug,
                    'productPrice' => $product->price,
                    'productSort' => $product->sort,
                    'productImage' => $product->image,
                ]
            ];

            $this->createDocument($params);
        }

        return true;
    }
}
