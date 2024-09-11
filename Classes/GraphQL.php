<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once "../Classes/Dbh.php";
require_once "../Classes/Api.php";

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

// use GraphQL\GraphQL;
use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;


// $method = $_SERVER['REQUEST_METHOD'];
// $tableName = "products";

// $api = new Api($method, $tableName);

// $products = $api->getData();

class GraphQL
{
    static public function handle()
    {
        function products($product)
        {
            $object = (object) [
                ...$product,
                'gallery' => json_decode($product['gallery']),
                'attributes' => json_decode($product['attributes']),
                // 'price' => json_decode($product['price']),
                'price' => array_values(json_decode($product['price']))[0],
            ];
            return $object;
        }

        try {
            $method = $_SERVER['REQUEST_METHOD'];

            $productsApi = new Api($method, "products");

            $products = $productsApi->getData();

            $categoriesApi = new Api($method, "categories");

            $categories = $categoriesApi->getData();

            // echo json_encode(array_filter(array_map('products', $products), fn($product) => $product->id === "jacket-canada-goosee"));
            // Types
            $attributeType = new ObjectType([
                'name' => 'Attribute',
                'fields' => [
                    'displayValue' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'value' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'id' => [
                        'type' => Type::nonNull(Type::id())
                    ],
                    'typename' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                ],
            ]);

            $attributeSetType = new ObjectType([
                'name' => 'AttributeSet',
                'fields' => [
                    'id' => [
                        'type' => Type::nonNull(Type::id())
                    ],
                    'items' => [
                        'type' => Type::nonNull(Type::listOf(Type::nonNull($attributeType)))
                    ],
                    'name' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'type' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'typename' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                ],
            ]);

            $currencyType = new ObjectType([
                'name' => 'Currency',
                'fields' => [
                    'label' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'symbol' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'typename' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                ],
            ]);

            $priceType = new ObjectType([
                'name' => 'Price',
                'fields' => [
                    'amount' => [
                        'type' => Type::nonNull(Type::float())
                    ],
                    'currency' => [
                        'type' => Type::nonNull($currencyType)
                    ],
                    'typename' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                ],
            ]);

            $productType = new ObjectType([
                'name' => 'Product',
                'fields' => [
                    'id' => [
                        'type' => Type::nonNull(Type::id())
                    ],
                    'name' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'inStock' => [
                        'type' => Type::nonNull(Type::boolean())
                    ],
                    'gallery' => [
                        'type' => Type::nonNull(Type::listOf(Type::nonNull(Type::string())))
                    ],
                    'description' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'category' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'attributes' => [
                        'type' => Type::listOf(Type::nonNull($attributeSetType))
                    ],
                    'price' => [
                        'type' => Type::nonNull(Type::nonNull($priceType))
                    ],
                    'brand' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'typename' => [
                        'type' => Type::nonNull(Type::string())
                    ]
                ],
            ]);

            $categoryType = new ObjectType(([
                'name' => 'Category',
                'fields' => [
                    'name' => Type::nonNull(Type::string()),
                    'typename' => Type::nonNull(Type::string())
                ]
            ]));

            // Query (Entry points)
            $queryType = new ObjectType([
                'name' => 'Query',
                'fields' => [
                    'echo' => [
                        'type' => Type::string(),
                        'args' => [
                            'message' => ['type' => Type::string()],
                        ],
                        'resolve' => static fn($rootValue, array $args): string => $rootValue['prefix'] . $args['message'],
                    ],
                    'products' => [
                        'type' => Type::nonNull(Type::listOf($productType)),
                        // 'resolve' => static fn() => $products
                        'resolve' => static fn() => array_map('products', $products)
                    ],
                    'product' => [
                        'type' => Type::nonNull($productType),
                        // 'type' => Type::nonNull(Type::string()),
                        'args' => [
                            'productId' => ['type' => Type::id()]
                        ],
                        'resolve' => static fn($_, array $args) => array_values(array_filter(array_map('products', $products), fn($product) => $product->id === $args['productId']))[0]
                        // 'resolve' => static fn($_, array $args): string => $args['productId']
                    ],
                    'categories' => [
                        'type' => Type::nonNull(Type::listOf($categoryType)),
                        'resolve' => static fn() => $categories
                    ]
                ],
            ]);

            $mutationType = new ObjectType([
                'name' => 'Mutation',
                'fields' => [
                    'sum' => [
                        'type' => Type::int(),
                        'args' => [
                            'x' => ['type' => Type::int()],
                            'y' => ['type' => Type::int()],
                        ],
                        'resolve' => static fn($calc, array $args): int => $args['x'] + $args['y'],
                    ],
                ],
            ]);

            // See docs on schema options:
            // https://webonyx.github.io/graphql-php/schema-definition/#configuration-options
            $schema = new Schema(
                (new SchemaConfig())
                    ->setQuery($queryType)
                // ->setMutation($mutationType)
            );

            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }


            $input = json_decode($rawInput, true);
            $query = $input['query'];

            $variableValues = $input['variables'] ?? null;

            $rootValue = ['prefix' => 'You said: '];
            $result = GraphQLBase::executeQuery($schema, $query, $rootValue);
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: *');

        echo json_encode($output, JSON_THROW_ON_ERROR);
    }
}
