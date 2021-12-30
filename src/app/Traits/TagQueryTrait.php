<?php

namespace VCComponent\Laravel\Tag\Traits;

trait TagQueryTrait
{
    /**
     * Scope a query to only include published tags.
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIsPublished($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Scope a query to sort tags by name column.
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param string $order
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortByName($query, $order = 'asc')
    {
        return $query->orderBy('name', $order);
    }

    /**
     * Scope a query to sort tags by usage time. From hight to low.
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param string $order
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMostUsed($query, $tagable_type = null) 
    {
        return $query->withCount(['tagables' => function ($q) use ($tagable_type) {
            if ($tagable_type) {
                $q->where('tagable_type', $tagable_type);
            }
        }])->orderBy('tagables_count', 'desc');
    }

    /**
     * Scope a query to sort tags by usage time. From low to hight.
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param string $order
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLeastUsed($query, $taggable_type = null) 
    {
        return $query->withCount(['taggables' => function ($q) use ($taggable_type) {
            if ($taggable_type) {
                $q->where('taggable_type', $taggable_type);
            }
        }])->orderBy('taggables_count', 'asc');
    }
}
