<?php

namespace Pterodactyl\Http\Controllers\Admin\Nodes;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Pterodactyl\Models\Node;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Prologue\Alerts\AlertsMessageBag;
use Illuminate\Contracts\View\Factory;
use Pterodactyl\Http\Controllers\Controller;

class NodeDowntimeController extends Controller
{
    /**
     * @var \Prologue\Alerts\AlertsMessageBag
     */
    private $alert;

    /**
     * @var \Illuminate\Contracts\View\Factory
     */
    private $view;

    /**
     * NodeViewController constructor.
     */
    public function __construct(
        AlertsMessageBag $alert,
        Factory $view
    ) {
        $this->alert = $alert;
        $this->view = $view;
    }

    /**
     * Returns index view for a specific node on the system.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, Node $node): View
    {
        $node = DB::table('nodes')->where('id', '=', $node->id)->first();

        return $this->view->make('admin.nodes.view.downtime', [
            'node' => $node,
        ]);
    }

    public function update(Request $request, Node $node): RedirectResponse
    {
        $this->validate($request, [
            'downtime' => 'required',
        ]);

        $downtime = (int) $request->input('downtime');
        $start = $request->input('start', null);
        $end = $request->input('end', null);

        DB::table('nodes')
        ->where('id', '=', $node->id)
        ->update([
            'has_downtime' => $downtime,
            'downtime_start' => $start,
            'downtime_end' => $end,
        ]);

        $this->alert->success('You have successfully updated the downtime settings of this node.')->flash();

        return redirect()->route('admin.nodes.view.downtime', $node->id);
    }
}
