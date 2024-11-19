<?php

namespace App\Livewire;

use App\Models\Todo;
use Illuminate\Auth\Events\Validated;
use Livewire\Component;
use Livewire\Atrributes\Rule;
use Livewire\Attributes\Rule as AttributesRule;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;
    #[AttributesRule('required|min:3|max:50')]
    public $name;

    public $search;



    public function create()
    {
        // dd('test');

        $validated=$this->validateOnly('name');
        Todo::create($validated);

        $this->reset('name');

        session()->flash('success','Created');



    } 
    public function render()
    {

        return view('livewire.todo-list',
    [
        'todos'=>Todo::latest()->where('name','like',"%{$this->search}%")->paginate(5)
    ]);
    }

}
