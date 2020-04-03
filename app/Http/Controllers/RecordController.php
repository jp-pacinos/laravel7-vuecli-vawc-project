<?php

namespace App\Http\Controllers;

use App\VawcUser;
use App\VawcRecord;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $complainantId = 1;
        $limit = $request->itemsPerPage ? $request->itemsPerPage : 10;
        $sortable = [
            'user' => 'vawc_users.lastname',
            'title' => 'vawc_records.title',
            'created_at' => 'vawc_records.created_at'
        ];

        $records = VawcRecord::select([
            'vawc_users.id as user_id',
            'vawc_records.id as record_id',
            'lastname',
            'firstname',
            'middlename',
            'suffix',
            'contact_number',
            'position',
            'position_status',
            'title',
            'vawc_records.created_at'
        ])
            ->join('vawc_user_records', 'vawc_records.id', '=', 'vawc_user_records.record_id')
            ->join('vawc_users', 'vawc_user_records.user_id', '=', 'vawc_users.id')
            ->when($request->lastname, function ($q) use ($request) {
                $q->where('lastname', 'like', "%$request->lastname%");
            })
            ->when(\array_key_exists($request->sortBy, $sortable), function($q) use ($request, $sortable) {
                $order = $request->sortDesc == 'desc' ? 'desc' : 'asc';
                $q->orderBy($sortable[$request->sortBy], $order);
            }, function ($q) use ($sortable) {
                $q->orderBy($sortable['created_at'], 'desc');
            })
            ->where('position_status', $complainantId);

        if ($limit > 1) {
            return $records->paginate($limit);
        }

        return response()->json(['data' => $records->get()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VawcRecord  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(VawcRecord $record)
    {
        return response()->json(VawcRecord::where('id', $record->id)->with('involved')->first(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validateForm($request);

        $attach = [];

        foreach ([0 => 'complainant', 1 => 'respondent'] as $position => $value) {
            $user = $this->insertPerson($request->{$value});

            $attach[$user->id] = [
                'position' => $value, 'position_status' => $position + 1
            ];
        }

        if ($request->otherInvolved) {
            foreach ($request->otherInvolved as $involved) {
                $user = $this->insertPerson($involved);

                $attach[$user->id] = [
                    'position' => $involved['type']['name'], 'position_status' => 3
                ];
            }
        }

        $record = VawcRecord::create([
            'title' => $request->statement['title'],
            'body' => $request->statement['text'],
            'datetime_happened' => $request->statement['dateHappened'] . ' ' . $request->statement['timeHappened']
        ]);

        $record->involved()->attach($attach);

        return response()->json(['message' => 'complain_created'], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VawcRecord  $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, VawcRecord $record)
    {
        $this->validateForm($request);

        $attach = [];

        foreach ([0 => 'complainant', 1 => 'respondent'] as $position => $value) {
            $user = $this->insertPerson($request->{$value});

            $attach[$user->id] = [
                'position' => $value, 'position_status' => $position + 1
            ];
        }

        if ($request->otherInvolved) {
            foreach ($request->otherInvolved as $involved) {
                $user = $this->insertPerson($involved);

                $attach[$user->id] = [
                    'position' => $involved['type']['name'], 'position_status' => 3
                ];
            }
        }

        $record->update([
            'title' => $request->statement['title'],
            'body' => $request->statement['text'],
            'datetime_happened' => $request->statement['dateHappened'] . ' ' . $request->statement['timeHappened']
        ]);

        // we make sure that we also delete its
        // $record->involved relation in db
        $record->involved()->each(function ($user) {
            $user->delete();
        });

        $record->involved()->attach($attach);

        return response()->json(['message' => 'complain_updated.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\VawcRecord $record
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(VawcRecord $record)
    {
        $record->delete();

        return response()->json(['message' => 'record_deleted.'], 200);
    }

    /**
     * validateForm function
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    private function validateForm(Request $request)
    {
        $request->validate([
            'statement.title' => 'required|max:80',
            'statement.text' => 'required',
            'statement.dateHappened' => 'required|date',
            'statement.timeHappened' => 'required',
            'complainant.lastname' => 'required',
            'complainant.firstname' => 'required',
            'complainant.sex' => 'required',
            'respondent.lastname' => 'required',
            'respondent.firstname' => 'required',
            'respondent.sex' => 'required',
            'otherInvolved.*.lastname' => 'required',
            'otherInvolved.*.firstname' => 'required',
            'otherInvolved.*.sex' => 'required',
            'otherInvolved.*.type.name' => 'required',
        ]);
    }

    /**
     * insertPerson function
     *
     * @param array $user
     *
     * @return \App\VawcUser
     */
    private function insertPerson($user)
    {
        return VawcUser::create([
            'lastname' => $user['lastname'],
            'firstname' => $user['firstname'],
            'middlename' => $user['middlename'] ?? null,
            'suffix' => $user['suffix'] ?? null,
            'birthdate' => $user['birthDate'] ?? null,
            'sex' => $user['sex'],
            'contact_number' => $user['contactNumber'] ?? null
        ]);
    }
}
