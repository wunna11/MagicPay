<?php

namespace App\Http\Controllers\Backend;

use Carbon\Carbon;
use App\Models\AdminUser;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreAdminUser;
use App\Http\Requests\UpdateAdminUser;
use PDF;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = AdminUser::all();
        return view('backend.admin_user.index', compact('users'));
    }

    public function ssd()
    {
        $data = AdminUser::query();
        return Datatables::of($data)
            ->editColumn('user_agent', function ($each) {
                if ($each->user_agent) {
                    $agent = new Agent();
                    $agent->setUserAgent($each->user_agent);
                    $device = $agent->device();
                    $platform = $agent->platform();
                    $browser = $agent->browser();

                    return '<table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Device</td>
                                <td>' . $device . '</td>
                            </tr>
                            <tr>
                                <td>Platform</td>
                                <td>' . $platform . '</td>
                            </tr>
                            <tr>
                                <td>Browser</td>
                                <td>' . $browser . '</td>
                            </tr>
                        </tbody>
                    </table>';
                }

                return '-';
            })

            ->editColumn('created_at', function ($each) {
                return Carbon::parse($each->created_at)->format('Y-m-d H:i:s');
            })

            ->editColumn('updated_at', function ($each) {
                return Carbon::parse($each->updated_at)->format('Y-m-d H:i:s');
            })

            ->addColumn('action', function ($each) {
                $edit_icon = '<a href="' . route('admin.admin-user.edit', $each->id) . '" class="text-info"><i class="fas fa-user-edit"></i></a>';
                $delete_icon = '<a href="#" class="text-danger delete" data-id="' . $each->id . '"><i class="fas fa-user-minus"></i></a>';

                // $delete_icon = '<a href="' . route('admin.admin-user.destroy', $each->id) . '" class="text-danger delete"><i class="fas fa-user-minus"></i></a>';

                return '<div class="action-icon">' . $edit_icon . $delete_icon . '</div>';
            })
            ->rawColumns(['user_agent', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.admin_user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAdminUser $request)
    {
        $data = new AdminUser();
        $data->name = request('name');
        $data->email = request('email');
        $data->phone = request('phone');
        $data->password = Hash::make(request('password'));
        $data->save();
        return redirect()->route('admin.admin-user.index')->with('create', 'Successfully created!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin_user = AdminUser::findOrFail($id);
        return view('backend.admin_user.edit', compact('admin_user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAdminUser $request, $id)
    {
        $data = AdminUser::findOrFail($id);
        $data->name = request('name');
        $data->email = request('email');
        $data->phone = request('phone');
        $data->password = request('password') ? Hash::make(request('password')) : $data->password;
        $data->update();
        return redirect()->route('admin.admin-user.index')->with('update', 'Successfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = AdminUser::findOrFail($id);
        $data->delete();
        return 'success';
    }

    // PDF Download
    public function generate_pdf()
    {
        $admin_users = AdminUser::all();
        $pdf = PDF::loadView('backend.pdf.admin-user-list', compact('admin_users'));
        return $pdf->download('admin-user-list.pdf');
    }
}
