<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Profile;
// 以下を追記
use App\ProfileHistory;
// 以下を追記
use Carbon\Carbon;

class ProfileController extends Controller
{
  public function add()
  {
      return view('admin.profile.create');
  }

  public function create(Request $request)
  {

      // Varidationを行う
      $this->validate($request, Profile::$rules);

      $profile = new Profile;
      $form = $request->all();


      unset($form['_token']);
    
      // データベースに保存する
      $profile->fill($form);
      $profile->save();

      return redirect('admin/profile/create');
  }

  public function index(Request $request)
  {
      $cond_title = $request->cond_title;
      if ($cond_title != '') {
          $posts = Profile::where('title', $cond_title)->get();
      } else {
          $posts = Profile::all();
      }
      return view('admin.profile.index', ['posts' => $posts, 'cond_title' => $cond_title]);
  }

  // 以下を追記

  public function edit(Request $request)
  {
      // Profile Modelからデータを取得する
      $profile = Profile::find($request->id);
      if (empty($profile)) {
        abort(404);    
      }
      return view('admin.profile.edit', ['profile_form' => $profile]);
  }


   public function update(Request $request)
  {
      // Validationをかける
      $this->validate($request, Profile::$rules);
      // Profile Modelからデータを取得する
      $profile = Profile::find($request->id);
      // 送信されてきたフォームデータを格納する
      $profile_form = $request->all();
      if (isset($profile_form['image'])) {
        $path = $request->file('image')->store('public/image');
        $profile->image_path = basename($path);
        unset($profile_form['image']);
      } elseif (isset($request->remove)) {
        $profile->image_path = null;
        unset($profile_form['remove']);
      }
      unset($profile_form['_token']);
      // 該当するデータを上書きして保存する
      $profile->fill($profile_form)->save();
      
      // 以下を追記
        $ProfileHistory = new ProfileHistory;
        $ProfileHistory->profile_id = $profile->id;
        $ProfileHistory->edited_at = Carbon::now();
        $ProfileHistory->save();

      return redirect('admin/profile');
  }
  
  // 以下を追記　　
  public function delete(Request $request)
  {
      // 該当するProfile Modelを取得
      $profile = Profile::find($request->id);
      // 削除する
      $profile->delete();
      return redirect('admin/profile/');
  }  


}