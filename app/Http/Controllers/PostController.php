<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Session\Store;

class PostController extends Controller
{
    public function getIndex()
    {
        //explicitly asking Dependency Injector 
        //to give new instance of Post
        $post = resolve('App\Post');
        $posts = $post->getPosts();
        return view('blog.index', ['posts' => $posts]);
    }

    public function getAdminIndex()
    {
        $post = resolve('App\Post');
        $posts = $post->getPosts();
        return view('admin.index', ['posts' => $posts]);
    }

    public function getPost($id)
    {
        $post = resolve('App\Post');
        $post = $post->getPost($id);
        return view('blog.post', ['post' => $post]);
    }

    public function getAdminCreate()
    {
        return view('admin.create');
    }

    public function getAdminEdit($id)
    {
        $post = resolve('App\Post');
        $post = $post->getPost($id);
        return view('admin.edit', ['post' => $post, 'postId' => $id]);
    }

    public function postAdminCreate(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
        $post = resolve('App\Post');
        $post->addPost($request->input('title'), $request->input('content'));
        return redirect()->route('admin.index')->with('info', 'Post created, Title is: ' . $request->input('title'));
    }

    public function postAdminUpdate(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:5',
            'content' => 'required|min:10'
        ]);
        $post = resolve('App\Post');
        $post->editPost($request->input('id'), $request->input('title'), $request->input('content'));
        return redirect()->route('admin.index')->with('info', 'Post edited, new Title is: ' . $request->input('title'));
    }
}
