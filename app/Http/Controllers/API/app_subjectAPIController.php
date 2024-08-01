<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\Createapp_subjectAPIRequest;
use App\Http\Requests\API\Updateapp_subjectAPIRequest;
use App\Models\app_subject;
use App\Repositories\app_subjectRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class app_subjectController
 * @package App\Http\Controllers\API
 */

class app_subjectAPIController extends AppBaseController
{
    /** @var  app_subjectRepository */
    private $appSubjectRepository;

    public function __construct(app_subjectRepository $appSubjectRepo)
    {
        $this->appSubjectRepository = $appSubjectRepo;
    }

    /**
     * Display a listing of the app_subject.
     * GET|HEAD /appSubjects
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $appSubjects = $this->appSubjectRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($appSubjects->toArray(), 'App Subjects retrieved successfully');
    }

    /**
     * Store a newly created app_subject in storage.
     * POST /appSubjects
     *
     * @param Createapp_subjectAPIRequest $request
     *
     * @return Response
     */
    public function store(Createapp_subjectAPIRequest $request)
    {
        $input = $request->all();

        $appSubject = $this->appSubjectRepository->create($input);

        return $this->sendResponse($appSubject->toArray(), 'App Subject saved successfully');
    }

    /**
     * Display the specified app_subject.
     * GET|HEAD /appSubjects/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var app_subject $appSubject */
        $appSubject = $this->appSubjectRepository->find($id);

        if (empty($appSubject)) {
            return $this->sendError('App Subject not found');
        }

        return $this->sendResponse($appSubject->toArray(), 'App Subject retrieved successfully');
    }

    /**
     * Update the specified app_subject in storage.
     * PUT/PATCH /appSubjects/{id}
     *
     * @param int $id
     * @param Updateapp_subjectAPIRequest $request
     *
     * @return Response
     */
    public function update($id, Updateapp_subjectAPIRequest $request)
    {
        $input = $request->all();

        /** @var app_subject $appSubject */
        $appSubject = $this->appSubjectRepository->find($id);

        if (empty($appSubject)) {
            return $this->sendError('App Subject not found');
        }

        $appSubject = $this->appSubjectRepository->update($input, $id);

        return $this->sendResponse($appSubject->toArray(), 'app_subject updated successfully');
    }

    /**
     * Remove the specified app_subject from storage.
     * DELETE /appSubjects/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var app_subject $appSubject */
        $appSubject = $this->appSubjectRepository->find($id);

        if (empty($appSubject)) {
            return $this->sendError('App Subject not found');
        }

        $appSubject->delete();

        return $this->sendSuccess('App Subject deleted successfully');
    }
}
