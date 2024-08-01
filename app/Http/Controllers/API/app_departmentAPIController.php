<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\Createapp_departmentAPIRequest;
use App\Http\Requests\API\Updateapp_departmentAPIRequest;
use App\Models\app_department;
use App\Repositories\app_departmentRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class app_departmentController
 * @package App\Http\Controllers\API
 */

class app_departmentAPIController extends AppBaseController
{
    /** @var  app_departmentRepository */
    private $appDepartmentRepository;

    public function __construct(app_departmentRepository $appDepartmentRepo)
    {
        $this->appDepartmentRepository = $appDepartmentRepo;
    }

    /**
     * Display a listing of the app_department.
     * GET|HEAD /appDepartments
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $appDepartments = $this->appDepartmentRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($appDepartments->toArray(), 'App Departments retrieved successfully');
    }

    /**
     * Store a newly created app_department in storage.
     * POST /appDepartments
     *
     * @param Createapp_departmentAPIRequest $request
     *
     * @return Response
     */
    public function store(Createapp_departmentAPIRequest $request)
    {
        $input = $request->all();

        $appDepartment = $this->appDepartmentRepository->create($input);

        return $this->sendResponse($appDepartment->toArray(), 'App Department saved successfully');
    }

    /**
     * Display the specified app_department.
     * GET|HEAD /appDepartments/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var app_department $appDepartment */
        $appDepartment = $this->appDepartmentRepository->find($id);

        if (empty($appDepartment)) {
            return $this->sendError('App Department not found');
        }

        return $this->sendResponse($appDepartment->toArray(), 'App Department retrieved successfully');
    }

    /**
     * Update the specified app_department in storage.
     * PUT/PATCH /appDepartments/{id}
     *
     * @param int $id
     * @param Updateapp_departmentAPIRequest $request
     *
     * @return Response
     */
    public function update($id, Updateapp_departmentAPIRequest $request)
    {
        $input = $request->all();

        /** @var app_department $appDepartment */
        $appDepartment = $this->appDepartmentRepository->find($id);

        if (empty($appDepartment)) {
            return $this->sendError('App Department not found');
        }

        $appDepartment = $this->appDepartmentRepository->update($input, $id);

        return $this->sendResponse($appDepartment->toArray(), 'app_department updated successfully');
    }

    /**
     * Remove the specified app_department from storage.
     * DELETE /appDepartments/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var app_department $appDepartment */
        $appDepartment = $this->appDepartmentRepository->find($id);

        if (empty($appDepartment)) {
            return $this->sendError('App Department not found');
        }

        $appDepartment->delete();

        return $this->sendSuccess('App Department deleted successfully');
    }
}
