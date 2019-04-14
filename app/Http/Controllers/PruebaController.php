<?php

namespace App\Http\Controllers;
use App\Post;
use App\Category;
use Illuminate\Http\Request;

class PruebaController extends Controller
{
    public function index(){
    	$titulo='Animales';
    	$animales=['Perro','Gato','Tigre'];

    	return view('pruebas.index',array(
    		'titulo' => $titulo,
    		'animales' =>$animales));
    }

    public function testOrm(){
    	$posts =Post::all();
    	foreach ($posts as $post) {
    		echo "<h1> ".$post->title." </h1>";
    		echo "<span style='color:gray;'>{$post->user->name} - {$post->category->name}</span>";
    		echo "<p>".$post->content."</p>";
    	}
    	die();
    }
}
