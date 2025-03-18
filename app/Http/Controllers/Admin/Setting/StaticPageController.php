<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Controllers\Controller;
use App\Http\Requests\staticPageRequest;
use App\Http\Resources\staticPageKeyResource;
use App\Http\Resources\staticPageResource;
use App\Models\staticPage;
use App\Traits\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    use ResponseTrait;

    public function getPagesKeys()
    {
        $pages = staticPage::all();
        return $this->successResponse(data: staticPageKeyResource::collection($pages));
    }

    public function getPageByKey($key)
    {
        $page = staticPage::where('key', $key)->first();
        return $this->successResponse(data: staticPageResource::make($page));
    }

    public function store(staticPageRequest $request): JsonResponse
    {
        $page = staticPage::create(request()->all());
        return $this->successResponse(data: staticPageResource::make($page));
    }

    public function updatePageByKey(staticPageRequest $request, $key)
    {
        $page = staticPage::where('key', $key)->first();
        $page->update(request()->all());
        if ($request->has('images')) {
            $images = $request->allFiles()['images'] ?? null;
            if ($images) {
                handleMultiMediaUploads($images, $page, true);
            }
        }
        return $this->successResponse(data: staticPageResource::make($page));
    }

}
