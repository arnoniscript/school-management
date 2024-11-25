<?php


namespace App\View\Components;

use Illuminate\View\Component;

class CourseList extends Component
{
    public $courses;

    public function __construct($courses)
    {
        $this->courses = $courses;
    }

    public function render()
    {
        return view('components.course-list');
    }
}


