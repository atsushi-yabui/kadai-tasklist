<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;    // 追加

class TasksController extends Controller
{
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザの投稿も取得するように変更しますが、現時点ではこのユーザの投稿のみ取得します）
            $tasks = $user->tasklist()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        
        
        // Welcomeビューでそれらを表示
        return view('tasks.index', $data);
        }
        else{
        // メッセージ一覧ビューでそれを表示
        return view('welcome');
        }
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
            //'user_id' => 'required|max:255',   // 追加
        ]);
        
        // タスクを作成
        //$task= new Task;
        //$task->status = $request->status; //追加
        //$task->content = $request->content;
        //$task->user_id = $request->user_id; //追加
        //$task->save();
        
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasklist()->create([
            'content' => $request->content,
            'status' => $request->status,
            'user_id' => $request->user_id,
        ]);

        // トップページへリダイレクトさせる
        return redirect('/');
    }

    // getでtasks//idにアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        
        // idの値でメッセージを検索して取得
            $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) { // 認証済みの場合
        
            
    
            // タスク詳細ビューでそれを表示
            return view('tasks.show', [
                'task' => $task,
            ]);
        }else {
            // メッセージ一覧ビューでそれを表示
            return redirect('/');
        
        }
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
            //'user_id' => 'required|max:255',   // 追加
        ]);
        
        // idの値でタスクを検索して取得
        $task= Task::findOrFail($id);
        // タスクを更新
        $request->user()->tasklist()->create([
            'content' => $request->content,
            'status' => $request->status,
            'user_id' => $request->user_id,
        ]);

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
