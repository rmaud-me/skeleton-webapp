# Twig/Live Component Pagination with KnpPaginator

The example used to illustrate the pagination usage, use HttpClient as data provider, but this component can be used with array or query builder.

## Table of Contents

1. [Global Architecture](#1-global-architecture)
2. [Creating the Payload Transformer](#2-creating-the-payload-transformer)
3. [Creating the HTTP Repository](#3-creating-the-http-repository)
4. [Creating the LiveComponent](#4-creating-the-livecomponent)
5. [Using the Pagination Component](#5-using-the-pagination-component)

## 1. Global Architecture

The system works with 4 main components:

1. **PaginateHttpResponseSubscriber**: Intercepts KnpPaginator events and processes HTTP responses
2. **Repository**: Makes HTTP calls and returns a PaginateHttpResponseItemEventTarget object
3. **LiveComponent**: Manages pagination state and reloads data
4. **Pagination template**: Displays pagination controls in AJAX mode

```
             User Action
                  ┬
                  │
                  ▼
┌──────────────────────────────────────────────┐
│ LiveComponent (changePage)                   │
│ • Manages pagination state                   │
│ • Updates page number                        │
└─────────────────┬────────────────────────────┘
                  │
                  ▼
┌──────────────────────────────────────────────┐
│ Repository (HTTP Client)                     │
│ • Creates HTTP request closure               │
│ • Returns PaginateHttpResponseItemEventTarget│
└─────────────────┬────────────────────────────┘
                  │
                  ▼
┌──────────────────────────────────────────────┐
│ PaginateHttpResponseItemEventTarget          │
│ • Encapsulates HTTP response                 │
│ • Transform HTTP response into object        │
│ • Defines target DTO class                   │
└─────────────────┬────────────────────────────┘
                  │
                  ▼
┌──────────────────────────────────────────────┐
│ KnpPaginator Event (Subscriber)              │
│ • Intercepts pagination event                │
│ • Fetches and deserializes HTTP response     │
│ • Returns paginated DTOs                     │
└──────────────────────────────────────────────┘
```

> [!IMPORTANT]
> When using HTTP Request as data provider and you want to have all calculate fields on pagination you need to have the count of your total items

## 2. Creating the payload Transformer

> [!TIP]
> Skip this part if you want to use array or query builder as data provider.
> [Next Part](#4-creating-the-livecomponent)

Create your payload transformer who implements
[ApiPayloadTransformerInterface](../../../src/Pagination/Transformer/ApiPayloadTransformerInterface.php)
in order to map the HTTP response to the
[PaginationFromRequest](../../../src/Pagination/DTO/PaginationFromRequest.php) object.

```php
<?php

namespace App\Pagination\Transformer;

use App\Pagination\DTO\PaginationFromRequest;
use Symfony\Contracts\HttpClient\ResponseInterface;

class YourPayloadTransformer implements ApiPayloadTransformerInterface
{
    public function transform(ResponseInterface $response): PaginationFromRequest
    {
        $responseContent = $response->toArray(false);

        $pagination        = new PaginationFromRequest();
        $pagination->items = $responseContent['results'];
        $pagination->count = $responseContent['totalResults'];

        return $pagination;
    }
}
```

## 3. Creating the HTTP Repository

> [!TIP]
> Skip this part if you want to use array or query builder as data provider.
> [Next Part](#4-creating-the-livecomponent)

In your external repository, create your function that your Service with HttpClient to fetch data.

```php
<?php
declare(strict_types=1);

namespace App\Repository;

use App\DTO\KnpPaginator\PaginateHttpResponseItemEventTarget;
use App\DTO\YourDTO;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class YourApiRepository
{
    public function __construct(
        private ServiceApi         $serviceApi,
        private PaginatorInterface $paginator
    ){
    }

    /**
     * Fetches paginated data from the API
     */
    public function findAllPaginate(int $page = 1, int $itemPerPage = 10): PaginationInterface
    {
        // Use a closure to lazy-load the HTTP request
        $paginateHttpResponseEventTarget = new PaginateHttpResponseItemEventTarget(
            YourDTO::class.'[]',
            function () use ($page, $itemPerPage) {
                $response = $this->serviceApi->getCollection($page, $itemPerPage);

                if (Response::HTTP_OK !== $response->getStatusCode()) {
                    return null;
                }

                return $response;
            },
            YourPayloadTransformer::class
        );

        // Trigger pagination (the subscriber will intercept the event)
        return $this->paginator->paginate($target, $page, $itemPerPage);
    }
}
```

## 4. Creating the LiveComponent

1. Create the LiveComponent

```php
<?php
declare(strict_types=1);

namespace App\Twig\Components;

use App\Pagination\Twig\Components\LivePaginationInterface;use App\Repository\YourApiRepository;use Knp\Component\Pager\Pagination\PaginationInterface;use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;use Symfony\UX\LiveComponent\Attribute\LiveAction;use Symfony\UX\LiveComponent\Attribute\LiveArg;use Symfony\UX\LiveComponent\Attribute\LiveProp;use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent(template: 'components/YourList.html.twig')]
class YourList implements LivePaginationInterface
{
    use DefaultActionTrait;

    public PaginationInterface $itemsPagination;

    /**
     * Current page number (modifiable via AJAX)
     */
    #[LiveProp(writable: true)]
    public int $page = 1;

    /**
     * Additional data to persist (e.g., table headers)
     */
    #[LiveProp]
    public array $columnNames = [];

    public function __construct(
        private readonly YourApiRepository $repository
    ) {
    }

    /**
     * Called only on first render
     */
    public function mount(): void
    {
        // Initialize data that doesn't change
        $this->columnNames = ['ID', 'Name', 'Email', 'Status'];
        
        // Load initial data
        $this->loadItems();
    }

    /**
     * Action called when clicking on a pagination link
     */
    #[LiveAction]
    public function changePage(#[LiveArg] int $page): void
    {
        $this->page = $page;
        $this->loadItems();
    }

    /**
     * Load data from the API
     */
    private function loadItems(): void
    {
        $this->itemsPagination = $this->repository->findAllPaginated($this->page);
    }
}
```

2. Create the LiveComponent Template

```html
{# templates/components/YourList.html.twig #}
<div {{ attributes }}>
    <table class="min-w-full divide-y divide-gray-300">
        <thead>
            <tr>
                {% for columnName in columnNames %}
                  <th>{{ columnName }}</th>
                {% endfor %}
            </tr>
        </thead>
        <tbody>
            {% for item in itemsPagination %}
                <tr>
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.email }}</td>
                    <td>{{ item.status }}</td>
                </tr>
            {% endfor %}
        </tbody>
    </table>

    {# Pagination component in live mode #}
    <twig:thot:Pagination
        isLivePagination="{{ true }}"
        pagination="{{ itemsPagination }}"
    />
</div>
```

## 5. Using the Pagination Component

In your controller template

```html
{# templates/your/index.html.twig #}
{% extends 'base.html.twig' %}

{% block body %}
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Items List</h1>
    
        {# LiveComponent rendering #}
        <twig:YourList />
    </div>
{% endblock %}
```

Benefits of This Approach:

* Reusable: The subscriber works for any API following the JSON format
* Lazy loading: HTTP requests are only executed when needed
* Type-safe: Uses Symfony serializer to type DTOs
* Flexible: Works with any HTTP Client
