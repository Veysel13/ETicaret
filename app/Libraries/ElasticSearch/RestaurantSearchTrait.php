<?php


namespace App\Libraries\ElasticSearch;


trait RestaurantSearchTrait
{
    private $size = 20;
    private $distance = "5000km";

    private function restaurantSearchLocationParams(string $term, int $page, array $location): array
    {
        return [
            "index" => "restaurants",
            "body" => [
                "_source" => [
                    "restaurantId",
                    "restaurantName",
                    "restaurantTags",
                    "restaurantLogo"
                ],
                "size" => $this->size,
                "from" => $page,
                "sort" => [
                    [
                        "_geo_distance" => [
                            "pin.location" => $location,
                            "order" => "asc",
                            "unit" => "km"
                        ]
                    ]
                ],
                "query" => [
                    "bool" => [
                        "filter" => [
                            "geo_distance" => [
                                "distance" => $this->distance,
                                "pin.location" => $location
                            ]
                        ],
                        "must" => [
                            [
                                "nested" => [
                                    "path" => "restaurantProducts",
                                    "inner_hits" => [
                                        "_source" => [
                                            "restaurantProducts.*"
                                        ]
                                    ],
                                    "query" => [
                                        "multi_match" => [
                                            "query" => $term,
                                            "type" => "phrase_prefix"
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        "should" => [
                            [
                                "multi_match" => [
                                    "query" => $term,
                                    "type" => "phrase_prefix",
                                ],
                            ],
                        ]
                    ]
                ]
            ]
        ];
    }

    private function restaurantSearchParams(string $term, int $page): array
    {
        return [
            "index" => "restaurants",
            "body" => [
                "_source" => [
                    "restaurantId",
                    "restaurantName",
                    "restaurantLogo",
                    "restaurantProducts"
                ],
                "size" => $this->size,
                "from" => $page,
                "query" => [
                    "bool" => [
                        "should" => [
                            [
                                "nested" => [
                                    "path" => "restaurantProducts",
                                    "inner_hits" => [
                                        "_source" => [
                                            "restaurantProducts.*"
                                        ]
                                    ],
                                    "query" => [
                                        "multi_match" => [
                                            "query" => $term,
                                            "type" => "phrase_prefix",
                                            "fields" => [
                                                "restaurantProducts.productName",
                                                "restaurantProducts.productDescription"
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                            [
                                "multi_match" => [
                                    "query" => $term,
                                    "type" => "phrase_prefix",
                                    "fields" => [
                                        "restaurantName"
                                    ]
                                ],
                            ],
                        ]
                    ]
                ]
            ]
        ];
    }
}
