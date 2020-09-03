<?php

namespace App\Http\Controllers\Api;

use App\Entry;
use App\Http\Controllers\Controller;
use App\Http\Resources\Entry as EntryResource;
use DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EntryController extends Controller {
    const PAGE_SIZE = 100;

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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if ($request->exists('limit')) {
            $limit = $request->input('limit');
            if ($limit == 1) {
                $page = (int)$request->input('page', 1);
                $skipped = ($page - 1) * self::PAGE_SIZE;
                return EntryResource::collection(Entry::skip($skipped)->take(1)->get());
            }
        }

        return EntryResource::collection($request->user()->entries()->paginate(self::PAGE_SIZE));
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
            abort(423, 'cannot add new entries while importing');
        }

        $attributes = $request->validate([
            'label' => 'required|max:255',
            'amount_cents' => 'required|integer|not_in:0',
            'date_time' => 'required|date'
        ]);
        $user = $request->user();
        $user->total_cents += $attributes['amount_cents'];
        $attributes['user_id'] = $user->id;

        DB::transaction(function () use (&$attributes, $user) {
            $entry = Entry::create($attributes);
            $user->save();
            $attributes['id'] = $entry->id;
        }, 5);

        return ['id' => $attributes['id'], 'total_cents' => $user->total_cents];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $attributes = $request->validate([
            'label' => 'sometimes|required|max:255',
            'amount_cents' => 'sometimes|required|integer|not_in:0',
            'date_time' => 'sometimes|required|date'
        ]);

        $entry = Entry::findOrFail($id);
        $user = $request->user();

        if (!$user->ownsEntry($entry)) {
            abort(401);
        }

        if (isset($attributes['amount_cents'])) {
            $delta_cents = $attributes['amount_cents'] - $entry->amount_cents;
            $user->total_cents += $delta_cents;
        }

        DB::transaction(function () use ($attributes, $user, $entry) {
            $entry->update($attributes);
            $user->save();
        }, 5);

        return ['total_cents' => $user->total_cents];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $entry = Entry::findOrFail($id);
        $user = $request->user();

        if (!$user->ownsEntry($entry)) {
            abort(401);
        }

        $user->total_cents -= $entry->amount_cents;

        DB::transaction(function () use ($entry, $user) {
            $entry->delete();
            $user->save();
        }, 5);

        return ['total_cents' => $user->total_cents];
    }
}
