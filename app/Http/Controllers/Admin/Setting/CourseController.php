<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Base\DashboardController;
use App\Http\Requests\Store\StoreCourseRequest;
use App\Http\Requests\Update\UpdateCourseRequest;
use App\Http\Resources\CourseResource;
use App\Models\Course;

class CourseController extends DashboardController
{
    public function __construct()
    {
        $this->resourceClass = CourseResource::class;
        $this->model = Course::class;
        $this->storeRequestClass = new StoreCourseRequest();
        $this->updateRequestClass = new UpdateCourseRequest();
        $this->useFilter = true;
    }


    public function storeCourse(StoreCourseRequest $request)
    {
        $course = Course::create($request->validated());

        if ($request->course) {
            $files = $request->allFiles()['course'];
            handleMultiMediaUploads($files, $course);
        }

        return self::successResponse(message: __('application.added'), data: CourseResource::make($course));
    }

    public function updateCourse(UpdateCourseRequest $request, Course $course)
    {
        $course->update($request->validated());
        if ($request->has('course')) {
            $files = $request->allFiles()['course'] ?? null;
            if ($files) {
                handleMultiMediaUploads($files, $course, true);
            }
        }
        return self::successResponse(data: CourseResource::make($course));
    }

}
