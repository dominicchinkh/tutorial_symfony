<?php

namespace App\Twig\Components;

use App\Dto\SearchFilters;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Metadata\UrlMapping;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use Symfony\UX\TwigComponent\Attribute\PostMount;

#[AsLiveComponent]
class SearchModule
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    /*
     * You can use the as option in your LiveProp definition
     *   #[LiveProp(writable: true, url: new UrlMapping(as: 'q'))]
     *
     * The query value will appear in the URL like https://my.domain/search?q=my+query+string
     * 
     */

    /*
     * If you need to change the parameter name on a specific page, you can leverage the modifier option
     *   #[LiveProp(writable: true, url: true, modifier: 'modifyQueryProp')]
     * 
     * In Twig template:
     *   <twig:SearchModule alias="q" />
    */

    #[LiveProp(writable: true, url: true)]
    #[Assert\NotBlank]
    public string $query = '';

    /** @var string[] */
    #[LiveProp(writable: true, url: true)]
    public array $tags = [];

    #[LiveProp(writable: ['category', 'minPrice'], url: true)]
    public SearchFilters $filters;

    #[LiveProp]
    public ?string $alias = null;

    #[PostMount]
    public function postMount(): void
    {
        $this->filters ??= new SearchFilters();

        // Validate 'query' field without throwing an exception, so the component can
        // be mounted anyway and a validation error can be shown to the user

        $this->validateField('query', false);
    }

    /** @return string[] */
    public function getAvailableTags(): array
    {
        return ['fiction', 'science', 'history', 'biography'];
    }

    /** @return array<string, string> */
    public function getAvailableCategories(): array
    {
        return [
            'all' => 'All categories',
            'books' => 'Books',
            'electronics' => 'Electronics',
        ];
    }

    public function modifyQueryProp(LiveProp $liveProp): LiveProp
    {
        if ($this->alias) {
            $liveProp = $liveProp->withUrl(new UrlMapping(as: $this->alias));
        }
        return $liveProp;
    }
}
