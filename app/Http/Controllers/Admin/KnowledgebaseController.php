<?php

namespace Pterodactyl\Http\Controllers\Admin;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prologue\Alerts\AlertsMessageBag;
use Pterodactyl\Http\Controllers\Controller;

class KnowledgebaseController extends Controller
{
    /**
     * @var \Prologue\Alerts\AlertsMessageBag
     */
    private $alert;

    /**
     * LocationController constructor.
     */
    public function __construct(AlertsMessageBag $alert)
    {
        $this->alert = $alert;
    }

    public function index(Request $request): View
    {
        $topics = DB::table('knowledgebase')->get();
        $categories = DB::table('knowledgebase_category')->get();

        return view('admin.knowledgebase.index', [
            'categories' => $categories,
            'topics' => $topics,
        ]);
    }

    public function newtopic(Request $request): View
    {
        $categories = DB::table('knowledgebase_category')->get();

        return view('admin.knowledgebase.topics.new', [
            'categories' => $categories,
        ]);
    }

    public function deletetopic(Request $request, $id)
    {
        $id = (int) $id;

        DB::table('knowledgebase')->where('id', '=', $id)->delete();
        $this->alert->success('You have successfully deleted the topic')->flash();

        return redirect()->route('admin.knowledgebase.index');
    }

    public function updatetopic(Request $request, $id)
    {
        $id = (int) $id;

        $this->validate($request, [
            'subject' => 'required|max:75',
            'author' => 'required|max:25',
            'category' => 'required',
            'answer' => 'required',
        ]);

        $subject = trim(strip_tags($request->input('subject')));
        $answer = trim($request->input('answer'));
        $author = trim($request->input('author'));
        $category = trim($request->input('category'));

        DB::table('knowledgebase')->where('id', '=', $id)->update([
            'subject' => $subject,
            'author' => $author,
            'information' => $answer,
            'category' => $category,
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        $this->alert->success('You have successfully edited this topic')->flash();

        return redirect()->route('admin.knowledgebase.index');
    }

    public function edittopic(Request $request, $id)
    {
        $id = (int) $id;

        $categories = DB::table('knowledgebase_category')->get();

        $topic = DB::table('knowledgebase')->where('id', '=', $id)->get();
        if (count($topic) < 1) {
            return redirect()->route('admin.knowledgebase.topic.new');
        }

        return view('admin.knowledgebase.topics.edit', [
            'topic' => $topic[0],
            'categories' => $categories,
        ]);
    }

    public function createtopic(Request $request)
    {
        $this->validate($request, [
            'subject' => 'required|max:75',
            'author' => 'required|max:50',
            'category' => 'required',
            'answer' => 'required',
        ]);

        $subject = trim(strip_tags($request->input('subject')));
        $answer = trim($request->input('answer'));
        $author = trim($request->input('author'));
        $category = trim($request->input('category'));

        DB::table('knowledgebase')->insert([
            'subject' => $subject,
            'author' => $author,
            'information' => $answer,
            'category' => $category,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        $this->alert->success('You have successfully creadted the topic')->flash();

        return redirect()->route('admin.knowledgebase.index');
    }

    public function newcategory(Request $request): View
    {
        return view('admin.knowledgebase.category.new');
    }

    public function deletecategory(Request $request, $id)
    {
        $id = (int) $id;

        DB::table('knowledgebase')->where('category', '=', $id)->delete();
        DB::table('knowledgebase_category')->where('id', '=', $id)->delete();
        $this->alert->success('You have successfully deleted the category')->flash();

        return redirect()->route('admin.knowledgebase.index');
    }

    public function updatecategory(Request $request, $id)
    {
        $id = (int) $id;

        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $name = trim(strip_tags($request->input('name')));
        $description = trim($request->input('description'));

        DB::table('knowledgebase_category')->where('id', '=', $id)->update([
            'name' => $name,
            'description' => $description,
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        $this->alert->success('You have successfully edited this category')->flash();

        return redirect()->route('admin.knowledgebase.index');
    }

    public function editcategory(Request $request, $id)
    {
        $id = (int) $id;

        $category = DB::table('knowledgebase_category')->where('id', '=', $id)->get();
        if (count($category) < 1) {
            return redirect()->route('knowledgebase.admin.edit');
        }

        return view('admin.knowledgebase.category.edit', [
            'category' => $category[0],
        ]);
    }

    public function createcategory(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
        ]);

        $name = trim(strip_tags($request->input('name')));
        $description = trim($request->input('description'));

        DB::table('knowledgebase_category')->insert([
            'name' => $name,
            'description' => $description,
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        $this->alert->success('You have successfully creadted the category')->flash();

        return redirect()->route('admin.knowledgebase.index');
    }
}
