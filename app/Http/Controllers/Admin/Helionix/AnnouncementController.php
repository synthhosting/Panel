<?php

namespace Pterodactyl\Http\Controllers\Admin\Helionix;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\View\Factory as ViewFactory;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Admin\Helionix\AnnouncementSettingsRequest;
use Pterodactyl\Contracts\Repository\SettingsRepositoryInterface;

class AnnouncementController extends Controller
{
    protected $alert;
    
    /**
     * BaseController constructor.
     */
    public function __construct(AlertsMessageBag $alert,  private SettingsRepositoryInterface $helionix)
    {
        $this->alert = $alert;
    }

    /**
     * Return the admin index view.
     */
    public function index(): View
    {
        $announcements = DB::table('announcements')->orderBy('updated_at', 'DESC')->get();

        return view('admin.helionix.announcement.index', [
            'announcements_status' => $this->helionix->get('helionix::helionix:announcements_status', true),
            'announcements' => $announcements
        ]);
    }

    public function store(AnnouncementSettingsRequest $request)
    {
        foreach ($request->normalize() as $key => $value) {
            $this->helionix->set('helionix::' . $key, $value);
        }

        $this->alert->success('Theme settings have been updated successfully.')->flash();
        return redirect()->route('admin.helionix.announcement');
    }

    public function create()
    {
        return view('admin.helionix.announcement.new', [
            'announcements_status' => $this->helionix->get('helionix::helionix:announcements_status', true),
        ]);
    }

    public function new(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:100',
            'description' => 'required'
        ]);

        DB::table('announcements')->insert([
            'title' => $request->title,
            'description' => $request->description,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now()
        ]);

        $this->alert->success('Announcement has been created.')->flash();

        return redirect()->route('admin.helionix.announcement');
    }

    public function edit($id)
    {
        $announcement = DB::table('announcements')->where('id', '=', $id)->get();
        if (count($announcement) < 1) {
            $this->alert->danger('Announcement not found!.')->flash();
            return redirect()->route('admin.helionix.announcement');
        }

        return view('admin.helionix.announcement.edit', [
            'announcements_status' => $this->helionix->get('helionix::helionix:announcements_status', true),
            'announcement' => $announcement[0]
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:100',
            'description' => 'required'
        ]);

        $isset = DB::table('announcements')->where('id', '=', $id)->get();
        if (count($isset) < 1) {
            $this->alert->danger('Announcement not found!.')->flash();
            return redirect()->route('admin.helionix.announcement.edit', $id);
        }

        DB::table('announcements')->where('id', '=', $id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'updated_at' => \Carbon\Carbon::now()
        ]);

        $this->alert->success('Announcement has been updated.')->flash();
        return redirect()->route('admin.helionix.announcement');
    }

    public function delete(Request $request, $id)
    {
        $isset = DB::table('announcements')->where('id', '=', $id)->get();
        if (count($isset) < 1) {
            $this->alert->success('Announcement not found!.')->flash();
            return redirect()->route('admin.helionix.announcement');
        }

        DB::table('announcements')->where('id', '=', $id)->delete();

        $this->alert->success('Announcement has been deleted.')->flash();
        return redirect()->route('admin.helionix.announcement');
    }
}