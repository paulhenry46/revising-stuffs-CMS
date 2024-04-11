<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Step;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Lazy;

#[Lazy(isolate: false)] 
class ProgressBar extends Component
{
    public function render()
    {
        return view('livewire.progress-bar');
    }
    public $value;
    public $isLearned = false;
    public function mount(Post $post){
        $LastStep = Step::where('user_id', Auth::id())->where('post_id', $post->id)->orderBy('created_at', 'DESC')->first();
        if($LastStep === null){
            $this->value = 0;
        }else{
            if($LastStep->mastery >= 1){
                $this->isLearned = true;
                $this->value = (10 * $LastStep->mastery);
            }else{
                $this->value = $LastStep->percent;
            }
        }
        
    }
}
