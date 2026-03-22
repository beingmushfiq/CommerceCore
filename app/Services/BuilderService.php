<?php

namespace App\Services;

use App\Models\BuilderPage;
use App\Models\BuilderSection;
use App\Models\BuilderContent;
use App\Models\Store;
use Illuminate\Support\Str;

class BuilderService
{
    public function createPage(Store $store, array $data): BuilderPage
    {
        $data['store_id'] = $store->id;
        $data['slug'] = $data['slug'] ?? Str::slug($data['page_name']);

        // If setting as homepage, unset other homepage
        if (!empty($data['is_homepage'])) {
            BuilderPage::where('store_id', $store->id)->update(['is_homepage' => false]);
        }

        return BuilderPage::create($data);
    }

    public function updatePage(BuilderPage $page, array $data): BuilderPage
    {
        if (!empty($data['is_homepage'])) {
            BuilderPage::where('store_id', $page->store_id)
                ->where('id', '!=', $page->id)
                ->update(['is_homepage' => false]);
        }

        $page->update($data);
        return $page->fresh();
    }

    public function addSection(BuilderPage $page, string $type): BuilderSection
    {
        $maxPosition = $page->sections()->max('position') ?? 0;

        $section = BuilderSection::create([
            'page_id' => $page->id,
            'type' => $type,
            'position' => $maxPosition + 1,
            'is_active' => true,
        ]);

        // Add default content
        $defaults = BuilderSection::defaultContent($type);
        foreach ($defaults as $key => $value) {
            BuilderContent::create([
                'section_id' => $section->id,
                'key' => $key,
                'value' => $value,
            ]);
        }

        return $section->load('contents');
    }

    public function updateSectionContent(BuilderSection $section, array $contents): BuilderSection
    {
        foreach ($contents as $key => $value) {
            BuilderContent::updateOrCreate(
                ['section_id' => $section->id, 'key' => $key],
                ['value' => $value]
            );
        }

        return $section->load('contents');
    }

    public function reorderSections(BuilderPage $page, array $order): void
    {
        foreach ($order as $position => $sectionId) {
            BuilderSection::where('id', $sectionId)
                ->where('page_id', $page->id)
                ->update(['position' => $position]);
        }
    }

    public function toggleSection(BuilderSection $section): BuilderSection
    {
        $section->update(['is_active' => !$section->is_active]);
        return $section->fresh();
    }

    public function deleteSection(BuilderSection $section): bool
    {
        return $section->delete();
    }

    public function getHomepage(Store $store): ?BuilderPage
    {
        return BuilderPage::where('store_id', $store->id)
            ->where('is_homepage', true)
            ->with('activeSections.contents')
            ->first();
    }
}
