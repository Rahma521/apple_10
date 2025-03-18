<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Http\Requests\PageRequest;
use App\Traits\ResponseTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\{JsonResponse, Request};
use Spatie\MediaLibrary\HasMedia;

class DashboardController extends Controller
{
    protected string $resourceClass;
    protected string $model;
    protected object $storeRequestClass;
    protected object $updateRequestClass;
    protected array $relations = [];
    protected bool $useFilter = false;
    use ResponseTrait;

    /**
     * Display a listing of the resource.
     */

    public function index(PageRequest $pageRequest): JsonResponse
    {
        $model = $this->useFilter
            ? $this->model::with($this->relations)
                ->filter(request(), (array)(new $this->model())->filterableColumns)
                ->orderBy('created_at', 'desc')
                ->paginate($pageRequest->page_count)
            : $this->model::with($this->relations)
                ->orderBy('created_at', 'desc')
                ->paginate($pageRequest->page_count);
        $data = $this->resourceClass::collection($model);
        return self::successResponsePaginate(data: $data->response()->getData(true));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $table = $this->model ? (new $this->model)->getTable() : null;
        $validatedData = $request->validate($this->storeRequestClass->rules($table));
        $model = $this->model::create($validatedData);
        $model->load($this->relations);
        if (isset($validatedData['files']) && $validatedData['files']) {
            handleMediaUploads($validatedData['files'], $model);
        }

        return self::successResponse(__('application.added'), data: $this->resourceClass::make($model));
    }

    public function show($id): JsonResponse
    {
        $model = $this->getModelById($id);
        return $this->successResponse(data: $this->resourceClass::make($model));

    }

    private function getModelById($value, $field = null): Model
    {
        $model = $this->model::where($field ?? 'id', $value)->firstOrFail();
        $model->load($this->relations);
        return $model;
    }

    public function update(Request $request, Model|string $id): JsonResponse
    {

        $model = $this->getModelById($id);
        $table = $this->model ? (new $this->model)->getTable() : null;
        $validatedData = $request->validate($this->updateRequestClass->rules($id, $table));

        if (isset($validatedData['files']) && $validatedData['files']) {
            handleMediaUploads($validatedData['files'], $model, true);
        }
        $model->update($validatedData);
        return $this->successResponse(
            __('application.updated'),
            data: $this->resourceClass::make($model)
        );

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {

        $model = $this->getModelById($id);
        $model->delete();

        if ($model instanceof HasMedia ) {
            clearMedia($model);
        }

        return $this->successResponse(__('application.deleted'));

    }
}
