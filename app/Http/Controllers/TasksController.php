<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加

class TasksController extends Controller
{
    public function index()
    {
        // メッセージ一覧を取得
        $tasks = Task::all();

        // メッセージ一覧ビューでそれを表示
        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }

    // getでmtasks//createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        $task = new Task;

        // タスク作成ビューを表示
        return view('tasks.create', [
            'task' => $task,
        ]);
    }

    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',   // 追加
            'content' => 'required|max:255',
            'user_id' => 'required|max:255',   // 追加
        ]);
        
        // タスクを作成
        $task= new Task;
        $task->status = $request->status; //追加
        $task->user_id = $request->user_id; //追加
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // getでtasks//idにアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        // タスク詳細ビューでそれを表示
        return view('tasks.show', [
            'task' => $task,
        ]);
    }

    // getでtasks//id/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
        // idの値でメッセージを検索して取得
        $task = Task::findOrFail($id);

        // タスク編集ビューでそれを表示
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }

    // putまたはpatchでtasks//idにアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        
        // バリデーション
        $request->validate([
            'status' => 'required|max:10', //追加
            'content' => 'required|max:255',
            'user_id' => 'required|max:255',   // 追加
        ]);
        
        // idの値でタスクを検索して取得
        $task= Task::findOrFail($id);
        // タスクを更新
        $task->status = $request->status; //追加
        $task->user_id = $request->user_id; //追加
        $task->content = $request->content;
        $task->save();

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // deleteでtasks/idにアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        // idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        // タスクを削除
        $task->delete();

        // トップページへリダイレクトさせる
        return redirect('/');
    }
}
