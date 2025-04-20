# Doctrine ORM Package with Traits for Routine Task Simplification

This is a simple package for `doctrine/orm` that allows you to use JoinTrain for repository to simplify certain joins.

## JoinTrait

Enables fetching entities along with others.

### Repository:

```php
class CategoryRepository extends ServiceEntityRepository
{
    use JoinTrait; // <-- add trait

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
    
    // Other methods
}
```

Our service or controller
```php
class CategoryController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
    ) {
    }

    public function index(): Response
    {
        $category = $this->categoryRepository
            ->join([ // load eager with products and products images
            'products', // <-- $category->getProducts() is initialized
            'products.image' // <-- $category->getProducts()->first()->getImage() is initialized too
            ])
            ->getQuery()
            ->getResult();
        
        return $this->json($category);
    }
}
```

Also you can set other parameters for `join()`
```php
//code
$this->categoryRepository
    ->join(
        $fields, // Array of fields
        $joinType, // String of join type ['left', 'inner'] 
        $qb, // QueryBuilder
    )
//code
```
