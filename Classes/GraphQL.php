<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

require_once "../src/Model.php";
require_once "../src/Controller.php";
require_once "../Controllers/Api.php";
require_once "../Classes/Product.php";
require_once "../Classes/AttributeItem.php";
require_once "../Classes/AttributeSet.php";
require_once "../Classes/Category.php";
require_once "../Classes/Currency.php";
require_once "../Classes/Gallery.php";
require_once "../Classes/Price.php";

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

// use GraphQL\GraphQL;
use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;




class GraphQL
{
    static public function handle()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $products = new Api($method, $product = new Prodcut());
        $categories = new Api($method, $product = new Category());
        $attributes = new Api($method, $product = new AttributeItem());
        $attributeSets = new Api($method, $product = new AttributeSet());
        $prices = new Api($method, $product = new Price());
        $currencies = new Api($method, $product = new Currency());
        $gallery = new Api($method, $product = new Gallery());

        // echo json_encode($currencies->getData());
        try {
            $method = $_SERVER['REQUEST_METHOD'];

            // Types
            $attributeType = new ObjectType([
                'name' => 'Attribute',
                'fields' => [
                    'attributeSetId' => [
                        'type' => Type::nonNull(Type::string())
                    ],
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
                    'attributeSetId' => [
                        'type' => Type::nonNull(Type::id())
                    ],
                    'productId' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'id' => [
                        'type' => Type::nonNull(Type::string())
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
                    'items' => [
                        'type' => Type::nonNull(Type::listOf($attributeType)),
                        'resolve' => fn($attributeSet) => array_filter($attributes->getData(), fn($attribute) => $attribute['attributeSetId'] === $attributeSet['attributeSetId'])
                    ]
                ],
            ]);

            $currencyType = new ObjectType([
                'name' => 'Currency',
                'fields' => [
                    'currencyId' => [
                        'type' => Type::nonNull(Type::id())
                    ],
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
                    'id' => [
                        'type' => Type::nonNull(Type::id())
                    ],
                    'productId' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'amount' => [
                        'type' => Type::nonNull(Type::float())
                    ],
                    'currencyId' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'typename' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'currency' => [
                        'type' => Type::nonNull($currencyType),
                        'resolve' => fn($price) => array_filter($currencies->getData(), fn($currency) => $currency['currencyId'] === $price['currencyId'])[0]
                    ]
                ],
            ]);

            $galleryType = new ObjectType([
                'name' => 'Gallery',
                'fields' => [
                    'imageUrl' => [
                        'type' => Type::nonNull(Type::id())
                    ],
                    'productId' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                ]
            ]);

            $categoryType = new ObjectType(([
                'name' => 'Category',
                'fields' => [
                    'name' => Type::nonNull(Type::id()),
                    'typename' => Type::nonNull(Type::string())
                ]
            ]));

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
                    'description' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'category' => [
                        'type' => Type::nonNull($categoryType)
                    ],
                    'brand' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'typename' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'gallery' => [
                        'type' => Type::nonNull(Type::listOf($galleryType)),
                        'resolve' => fn($product, array $args) => array_filter($gallery->getData(), fn($image) => $image['productId'] === $product['id'])
                    ],
                    'price' => [
                        'type' => Type::nonNull(Type::listOf($priceType)),
                        'resolve' => fn($product, array $args) => array_filter($prices->getData(), fn($price) => $price['productId'] === $product['id'])
                    ],
                    'attributes' => [
                        'type' => Type::nonNull(Type::listOf($attributeSetType)),
                        'resolve' => fn($product, array $args) => array_filter($attributeSets->getData(), fn($attributeSet) => $attributeSet['productId'] === $product['id'])
                    ]
                ],
            ]);


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
                        'resolve' => static fn() => $products->getData()
                    ],
                    'product' => [
                        'type' => Type::nonNull($productType),
                        // 'type' => Type::nonNull(Type::string()),
                        'args' => [
                            'productId' => ['type' => Type::id()]
                        ],
                        'resolve' => static fn($_, array $args) => array_filter($products->getData(), fn($product) => $product['id'] === $args['productId'])
                        // 'resolve' => static fn($_, array $args): string => $args['productId']
                    ],
                    'categories' => [
                        'type' => Type::nonNull(Type::listOf($categoryType)),
                        'resolve' => static fn() => $categories->getData()
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
