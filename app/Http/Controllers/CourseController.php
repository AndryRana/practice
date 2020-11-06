<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Episode;
use App\Youtube\YoutubeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Support\Facades\Http;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('user')->select('courses.*', DB::raw(
            '(SELECT COUNT(DISTINCT(user_id)) FROM completions
            INNER JOIN episodes ON completions.episode_id = episodes.id
            WHERE episodes.course_id = courses.id) AS participants'
        ))->addSelect(DB::raw(
            '(
                SELECT SUM(duration)
                FROM episodes 
                WHERE episodes.course_id = courses.id
            ) AS total_duration'
        ))
        ->withCount('episodes')->latest()->get();
        // dd($courses);
        // echo "<pre>";print_r($courses);die;
        return Inertia::render('Courses/Index',['courses'=>$courses]);
    }

    public function show(int $id)
    {
        $course = Course::where('id', $id)->with('episodes')->first();
        $watched = auth()->user()->episodes;

        return Inertia::render('Courses/Show',[
            'course' =>$course,
            'watched' => $watched
            ]);
    }


    public function store(Request $request, YoutubeServices $ytb)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'episodes' => [ 'required', 'array'],
            'episodes.*.title' => 'required',
            'episodes.*.description' => 'required',
            'episodes.*.video_url' => 'required',
        ]);

        $course = Course::create($request->all());
        
        foreach($request->input('episodes') as $episode)
        {
            $episode['course_id'] = $course->id;
            $episode['duration'] = $ytb->handleYoutubeVideoDuration($episode['video_url']);

            Episode::create($episode) ;
        }

        return Redirect::route('courses.index')->with('success', 'Félicitation, la formation a bien été mise en ligne.');
    }


    public function edit(int $id)
    {
        $course = Course::where('id', $id)->with('episodes')->first();  
        $this->authorize('update', $course);
        return Inertia::render('Courses/Edit', [
            'course' => $course
        ]);
    }


    public function update(Request $request)
    {
        $course = Course::where('id', $request->id)->with('episodes')->first();
        
        $this->authorize('update', $course);
        
        $course->update($request->all());
        $course->episodes()->delete();

        foreach($request->episodes as $episode) {
            $episode['course_id']= $course->id;
            Episode::create($episode);
        }

        return Redirect::route('courses.index')->with('success', 'Félicitation, la formation a bien été modifiée.');
    }



    public function toggleProgress(Request $request)
    {

       $id = $request->input('episodeId');
       $user = auth()->user();

       $user->episodes()->toggle($id);

       return $user->episodes;

    }
}
