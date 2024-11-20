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

    public $EditingTodoID;

    public $EditingnewName;



    public function create()
    {
        // dd('test');

        $validated=$this->validateOnly('name');
        Todo::create($validated);

        $this->reset('name');

        session()->flash('success','Created');



    } 

    // public function delete($todoID)
    // {
    //     Todo::find($todoID)->delete();
    //     session()->flash('success','Deleted');
    // }

     public function delete(Todo $todo)
    {
       $todo->delete();
        session()->flash('danger','Deleted');
    }

    public function toggle($todoID)
    {
        $todo=Todo::find($todoID);
        $todo->completed=!$todo->completed;
        $todo->save();
    }

    public function edit($todoID)
    {
        $this->EditingTodoID=$todoID;
        $this->EditingnewName=Todo::find($todoID)->name;
    }

    public function cancelEdit()
    {
        $this->reset('EditingTodoID','EditingnewName');
    }

    public function update( )
    {
        $this->validateOnly('EditingnewName');
        Todo::find($this->EditingTodoID)->update(
            [
                'name'=>$this->EditingnewName,
            ]
            );
            $this->cancelEdit();
    }



    public function render()
    {

        return view('livewire.todo-list',
    [
        'todos'=>Todo::latest()->where('name','like',"%{$this->search}%")->paginate(5)
    ]);
    }

}
