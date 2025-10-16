<?php

namespace App\Traits;

trait CommonQueryScopes
{
    /**
     * فلترة حسب الحالة (status)
     */
    public function scopeFilterByStatus($query, $status = null)
    {
        if (!empty($status)) {
            return $query->where('status', $status);
        }
        return $query;
    }

    /**
     * البحث حسب العنوان (title)
     */
    public function scopeSearchByTitle($query, $request)
    {
        if (!empty($request->search['value'])) {
            $search = '%' . $request->search['value'] . '%';
            return $query->where('title', 'LIKE', $search);
        }

        // أو في حالة استخدام query string مباشرة ?search=value
        if ($request->has('search') && !empty($request->search)) {
            $search = '%' . $request->search . '%';
            return $query->where('title', 'LIKE', $search);
        }

        return $query;
    }
}
