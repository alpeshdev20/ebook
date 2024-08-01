<?php

namespace App\Http\Controllers;

use App\DataTables\app_departmentDataTable;
use App\Http\Requests;
use App\Http\Requests\Createapp_departmentRequest;
use App\Http\Requests\Updateapp_departmentRequest;
use App\Repositories\app_departmentRepository;
use Flash;
use App\Http\Controllers\AppBaseController;
use Response;

class app_departmentController extends AppBaseController
{
    /** @var  app_departmentRepository */
    private $appDepartmentRepository;

    public function __construct(app_departmentRepository $appDepartmentRepo)
    {
        $this->appDepartmentRepository = $appDepartmentRepo;
    }

    /**
     * Display a listing of the app_department.
     *
     * @param app_departmentDataTable $appDepartmentDataTable
     * @return Response
     */
    public function index(app_departmentDataTable $appDepartmentDataTable)
    {
        return $appDepartmentDataTable->render('app_departments.index');
    }

    /**
     * Show the form for creating a new app_department.
     *
     * @return Response
     */
    public function create()
    {
        return view('app_departments.create');
    }

    /**
     * Store a newly created app_department in storage.
     *
     * @param Createapp_departmentRequest $request
     *
     * @return Response
     */
    public function store(Createapp_departmentRequest $request)
    {
        $input = $request->all();

        $appDepartment = $this->appDepartmentRepository->create($input);

        Flash::success('App Department saved successfully.');

        return redirect(route('appDepartments.index'));
    }

    /**
     * Display the specified app_department.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $appDepartment = $this->appDepartmentRepository->find($id);

        if (empty($appDepartment)) {
            Flash::error('App Department not found');

            return redirect(route('appDepartments.index'));
        }

        return view('app_departments.show')->with('appDepartment', $appDepartment);
    }

    /**
     * Show the form for editing the specified app_department.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $appDepartment = $this->appDepartmentRepository->find($id);

        if (empty($appDepartment)) {
            Flash::error('App Department not found');

            return redirect(route('appDepartments.index'));
        }

        return view('app_departments.edit')->with('appDepartment', $appDepartment);
    }

    /**
     * Update the specified app_department in storage.
     *
     * @param  int              $id
     * @param Updateapp_departmentRequest $request
     *
     * @return Response
     */
    public function update($id, Updateapp_departmentRequest $request)
    {
        $appDepartment = $this->appDepartmentRepository->find($id);

        if (empty($appDepartment)) {
            Flash::error('App Department not found');

            return redirect(route('appDepartments.index'));
        }

        $appDepartment = $this->appDepartmentRepository->update($request->all(), $id);

        Flash::success('App Department updated successfully.');

        return redirect(route('appDepartments.index'));
    }

    /**
     * Remove the specified app_department from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $appDepartment = $this->appDepartmentRepository->find($id);

        if (empty($appDepartment)) {
            Flash::error('App Department not found');

            return redirect(route('appDepartments.index'));
        }

        $this->appDepartmentRepository->delete($id);

        Flash::success('App Department deleted successfully.');

        return redirect(route('appDepartments.index'));
    }
}
