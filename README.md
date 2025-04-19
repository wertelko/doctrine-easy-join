# Doctrine ORM Package with Traits for Routine Task Simplification

This is a simple package for `doctrine/orm` that allows you to use JoinTrain to simplify certain joins.

## WithTrait

Enables fetching entities along with others.

### Repository:

```php
class CategoryRepository extends ServiceEntityRepository
{
    use WithTrait; // <-- add trait

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
        $result = $this->categoryRepository
            ->join(['products', 'products.image']) // <-- load eager with products and products images
            ->getQuery()
            ->getResult();
        
        return $this->json($result);
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
