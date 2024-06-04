<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Group;
use App\Models\School;
use App\Models\User;
use Mary\Traits\Toast;

class GroupEdit extends Component
{
    use Toast;
    public Group $group;
    
    public $headers = [
        ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
        ['key' => 'name', 'label' => 'Nice Name'],
    ];
    public $name;
    public bool $private;
    public $description;
    public $users;
    public $schools;
    public $school;
    public $selected_users;
    public function render()
    {
        return view('livewire.group-edit');
    }

    public function mount(Group $group){
        
        $this->group = $group;
        $this->selected_users = $group->users()->pluck('users.id');
        $this->users = User::All();
        $this->name = $group->name;
        if(!empty($group->school_id)){
            $this->school = $group->school_id;
        }else{
            $this->school = School::first()->id;
        }

        $this->description = $group->description;
        
        if(($group->public === NULL) or ($group->public === 1)){
           
            $this->private = false;
        }else{
            $this->private = true;
        }
    }
    public function save(){

        $this->group->name = $this->name;
        if($this->private == true){
            $this->group->public = false;
        }else{
            $this->group->public = true;
        }
        $this->group->school_id = $this->school;
        $this->group->description = $this->description;
        $this->group->save();
        $this->group->users()->sync($this->selected_users);
        $this->success(
            'Success',
            redirectTo: route('groups.index')
        );

    }
}
