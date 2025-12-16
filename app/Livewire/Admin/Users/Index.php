<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Flux\Flux;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    
    // Modal state
    public $showModal = false;
    public $isEdit = false;
    public $userIdBeingEdited;

    // Form fields
    public $name = '';
    public $email = '';
    public $password = '';
    public $selectedRole = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->reset(['name', 'email', 'password', 'selectedRole', 'userIdBeingEdited']);
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        $this->userIdBeingEdited = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = ''; 
        
        // Ensure roles are loaded or access them safely
        $this->selectedRole = $user->roles->first()?->name ?? '';
        
        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|min:3',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->userIdBeingEdited)],
            'selectedRole' => 'required|exists:roles,name',
        ];

        if (!$this->isEdit) {
            $rules['password'] = 'required|min:8';
        } else {
            $rules['password'] = 'nullable|min:8';
        }

        $this->validate($rules);

        if ($this->isEdit) {
            $user = User::findOrFail($this->userIdBeingEdited);
            $updateData = [
                'name' => $this->name,
                'email' => $this->email,
            ];
            if (!empty($this->password)) {
                $updateData['password'] = Hash::make($this->password);
            }
            $user->update($updateData);
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
        }

        // Sync roles
        $user->syncRoles($this->selectedRole);

        $this->showModal = false;
        $this->dispatch('close-modal');
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
           return; 
        }
        $user->delete();
    }

    #[Layout('components.layouts.app')] 
    public function render()
    {
        $users = User::query()
            ->with('roles')
            ->where('name', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%")
            ->paginate(10);

        $roles = Role::all();

        return view('livewire.admin.users.index', [
            'users' => $users,
            'roles' => $roles
        ]);
    }
}
