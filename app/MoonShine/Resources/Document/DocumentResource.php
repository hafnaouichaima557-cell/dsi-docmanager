<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Document;

use Illuminate\Database\Eloquent\Model;
use App\Models\Document;
use App\MoonShine\Resources\Document\Pages\DocumentIndexPage;
use App\MoonShine\Resources\Document\Pages\DocumentFormPage;
use App\MoonShine\Resources\Document\Pages\DocumentDetailPage;

use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Support\Enums\Ability;

/**
 * @extends ModelResource<Document, DocumentIndexPage, DocumentFormPage, DocumentDetailPage>
 */
class DocumentResource extends ModelResource
{
    protected string $model = Document::class;

    protected string $title = 'Documents';

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            DocumentIndexPage::class,
            DocumentFormPage::class,
            DocumentDetailPage::class,
        ];
    }

    // Lecture seule : bloque create/update/delete, autorise seulement la vue
    protected function isCan(Ability $ability): bool
    {
        if (in_array($ability, [Ability::CREATE, Ability::UPDATE, Ability::DELETE, Ability::MASS_DELETE], true)) {
            return false;
        }

        return true;
    }
}
