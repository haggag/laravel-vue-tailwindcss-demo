<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessCsvFile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FileController extends Controller {
    const DEFAULT_MESSAGE = "We're importing your balance entries. Sit tight.";

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        if ($request->user()->locked) {
            abort(423, 'a file is already being imported');
        }

        $response = null;
        $request->validate(['file' => 'required|file|max:1024|mimes:csv,txt']);
        $userId = $request->user()->id;
        $path = $request->file('file')->store("uploads/$userId");
        $originalName = $request->file('file')->getClientOriginalName();
        $fileSize = $request->file('file')->getSize();

        $request->user()->lock(self::DEFAULT_MESSAGE);
        try {
            ProcessCsvFile::dispatch($path, $originalName, $userId); //->onQueue('background-queue');
        } catch (Exception $ex) {
            // TODO handle unlocking failure
            $request->user()->unlock();
            return ['message' => "Failed to upload the file. Please try again later.", 'status' => 'error'];
        }

        return ['message' => self::DEFAULT_MESSAGE, 'status' => 'processing'];
    }

}
